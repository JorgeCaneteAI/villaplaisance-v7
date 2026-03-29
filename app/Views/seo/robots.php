<?php
declare(strict_types=1);
$base = APP_ENV === 'production' ? 'https://v1.villaplaisance.fr' : APP_URL;
?>
User-agent: *
Allow: /

User-agent: GPTBot
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: anthropic-ai
Allow: /

User-agent: Google-Extended
Allow: /

Disallow: /admin/
Disallow: /seeds/

Sitemap: <?= $base ?>/sitemap.xml
