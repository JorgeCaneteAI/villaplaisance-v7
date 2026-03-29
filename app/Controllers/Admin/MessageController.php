<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

class MessageController extends AdminBaseController
{
    public function index(): void
    {
        $messages = [];
        try {
            $messages = \Database::fetchAll("SELECT * FROM vp_messages ORDER BY created_at DESC");
        } catch (\Throwable) {}

        $csrf = $this->csrf();
        $this->render('admin/messages/index', compact('messages', 'csrf'));
    }

    public function show(int $id): void
    {
        $message = \Database::fetchOne("SELECT * FROM vp_messages WHERE id = ?", [$id]);
        if (!$message) {
            $this->flash('error', 'Message introuvable.');
            $this->redirect('/admin/messages');
            return;
        }

        // Mark as read
        if (empty($message['read_at'])) {
            \Database::update('vp_messages', ['read_at' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
            $message['read_at'] = date('Y-m-d H:i:s');
        }

        $csrf = $this->csrf();
        $this->render('admin/messages/show', compact('message', 'csrf'));
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->flash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/messages');
            return;
        }

        \Database::delete('vp_messages', 'id = ?', [$id]);
        $this->flash('success', 'Message supprimé.');
        $this->redirect('/admin/messages');
    }
}
