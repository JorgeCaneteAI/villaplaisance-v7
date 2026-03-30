<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class RedirectController extends AdminBaseController
{
    public function index(): void
    {
        $redirects = \Database::fetchAll("SELECT * FROM vp_redirects ORDER BY id ASC");
        $this->render('admin/redirects/index', compact('redirects'));
    }

    public function create(): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/redirects');
            return;
        }

        $urlFrom = trim($_POST['url_from'] ?? '');
        $urlTo = trim($_POST['url_to'] ?? '');
        $statusCode = (int)($_POST['status_code'] ?? 301);
        $note = trim($_POST['note'] ?? '');

        if ($urlFrom === '' || $urlTo === '') {
            $this->flash('error', 'Les champs URL source et destination sont obligatoires.');
            $this->redirect('/admin/redirects');
            return;
        }

        if (!in_array($statusCode, [301, 302, 307, 308], true)) {
            $statusCode = 301;
        }

        \Database::query(
            "INSERT INTO vp_redirects (url_from, url_to, status_code, note) VALUES (?, ?, ?, ?)",
            [$urlFrom, $urlTo, $statusCode, $note]
        );

        $this->syncHtaccess();
        $this->flash('success', 'Redirection ajoutée.');
        $this->redirect('/admin/redirects');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/redirects');
            return;
        }

        $urlFrom = trim($_POST['url_from'] ?? '');
        $urlTo = trim($_POST['url_to'] ?? '');
        $statusCode = (int)($_POST['status_code'] ?? 301);
        $note = trim($_POST['note'] ?? '');
        $active = isset($_POST['active']) ? 1 : 0;

        if ($urlFrom === '' || $urlTo === '') {
            $this->flash('error', 'Les champs URL source et destination sont obligatoires.');
            $this->redirect('/admin/redirects');
            return;
        }

        if (!in_array($statusCode, [301, 302, 307, 308], true)) {
            $statusCode = 301;
        }

        \Database::update('vp_redirects', [
            'url_from' => $urlFrom,
            'url_to' => $urlTo,
            'status_code' => $statusCode,
            'note' => $note,
            'active' => $active,
        ], 'id = ?', [$id]);

        $this->syncHtaccess();
        $this->flash('success', 'Redirection mise à jour.');
        $this->redirect('/admin/redirects');
    }

    public function toggle(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/redirects');
            return;
        }

        \Database::query("UPDATE vp_redirects SET active = NOT active WHERE id = ?", [$id]);
        $this->syncHtaccess();
        $this->flash('success', 'Statut modifié.');
        $this->redirect('/admin/redirects');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/redirects');
            return;
        }

        \Database::delete('vp_redirects', 'id = ?', [$id]);
        $this->syncHtaccess();
        $this->flash('success', 'Redirection supprimée.');
        $this->redirect('/admin/redirects');
    }

    /**
     * Regenerate the redirect block in .htaccess from database
     */
    private function syncHtaccess(): void
    {
        $htaccessPath = ROOT . '/public/.htaccess';
        if (!file_exists($htaccessPath)) {
            return;
        }

        $redirects = \Database::fetchAll("SELECT * FROM vp_redirects WHERE active = 1 ORDER BY id ASC");

        // Build redirect rules
        $rules = "# ═══ 301 Redirects (managed by admin) ═══\n";
        foreach ($redirects as $r) {
            $from = $r['url_from'];
            $to = $r['url_to'];
            $code = (int)$r['status_code'];

            // If source contains a domain, it's an external redirect
            if (str_contains($from, '.')) {
                // Extract domain and path
                $parts = explode('/', $from, 2);
                $domain = $parts[0];
                $path = $parts[1] ?? '';
                $domainRegex = str_replace('.', '\\.', $domain);
                $domainRegex = str_replace('www\\.', '(www\\.)?', $domainRegex);

                if ($path === '' || $path === '/') {
                    $rules .= "RewriteCond %{HTTP_HOST} ^{$domainRegex}$ [NC]\n";
                    $rules .= "RewriteRule ^$ https://v1.villaplaisance.fr{$to} [R={$code},L]\n";
                } else {
                    $rules .= "RewriteRule ^" . preg_quote($path, '#') . "/?$ {$to} [R={$code},L]\n";
                }
            } else {
                // Internal redirect: simple path rewrite
                $fromClean = ltrim($from, '/');
                $rules .= "RewriteRule ^" . preg_quote($fromClean, '#') . "/?$ {$to} [R={$code},L]\n";
            }
        }

        // Add catch-all for old domain
        $hasOldDomain = false;
        foreach ($redirects as $r) {
            if (str_contains($r['url_from'], 'villaplaisance.fr')) {
                $hasOldDomain = true;
                break;
            }
        }
        if ($hasOldDomain) {
            $rules .= "\n# Catch-all: old domain → new domain root\n";
            $rules .= "RewriteCond %{HTTP_HOST} ^(www\\.)?villaplaisance\\.fr$ [NC]\n";
            $rules .= "RewriteRule ^(.*)$ https://v1.villaplaisance.fr/\$1 [R=301,L]\n";
        }

        // Read current .htaccess
        $content = file_get_contents($htaccessPath);

        // Replace the redirect block (between markers)
        $startMarker = "# ═══ 301 Redirect";
        $endMarker = "# ═══ Force HTTPS";

        $startPos = strpos($content, $startMarker);
        $endPos = strpos($content, $endMarker);

        if ($startPos !== false && $endPos !== false) {
            $newContent = substr($content, 0, $startPos) . $rules . "\n" . substr($content, $endPos);
            file_put_contents($htaccessPath, $newContent);
        }
    }
}
