<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Mailer;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;

/** Admin > Messages: inbox (contact form), sent items, reply and compose. */
class MessageController extends Controller
{
    public function index(Request $request)
    {
        // ── Quick actions that arrive as links ──────────────────
        if ($request->has('mark_all_read')) {
            Sql::execute("UPDATE contact_messages SET is_read=1 WHERE is_read=0");
            return redirect('/admin/messages');
        }
        if ($request->has('mark_replied') && $request->has('view')) {
            Sql::execute("UPDATE contact_messages SET replied=1 WHERE id=?", [(int) $request->query('view')]);
            return redirect('/admin/messages?view=' . (int) $request->query('view'));
        }

        // Delete links change data: they need the CSRF token and a non-viewer role
        $deletes = [
            'delete'           => ["UPDATE contact_messages SET deleted_at=NOW() WHERE id=?", '/admin/messages'],
            'hard_delete'      => ["DELETE FROM contact_messages WHERE id=?",                 '/admin/messages'],
            'delete_sent'      => ["UPDATE sent_messages SET deleted_at=NOW() WHERE id=?",    '/admin/messages?tab=sent'],
            'hard_delete_sent' => ["DELETE FROM sent_messages WHERE id=?",                    '/admin/messages?tab=sent'],
        ];
        foreach ($deletes as $param => [$sql, $back]) {
            if ($request->has($param) && $this->validGetToken($request)) {
                if ($denied = $this->denyViewer()) {
                    return $denied;
                }
                Sql::execute($sql, [(int) $request->query($param)]);
                return redirect($back);
            }
        }

        // ── Listing ─────────────────────────────────────────────
        $filter  = Security::sanitize($request->query('filter'));
        $search  = Security::sanitize($request->query('search'));
        $page    = max(1, (int) $request->query('page', 1));
        $perPage = 15;
        $offset  = ($page - 1) * $perPage;

        $activeTab    = Security::sanitize($request->query('tab', 'inbox'));
        $sentMessages = [];
        $sentTotal    = 0;
        if ($activeTab === 'sent') {
            $sentTotal    = Sql::fetch("SELECT COUNT(*) AS c FROM sent_messages WHERE deleted_at IS NULL")['c'] ?? 0;
            $sentMessages = Sql::fetchAll("SELECT * FROM sent_messages WHERE deleted_at IS NULL ORDER BY sent_at DESC LIMIT $perPage OFFSET $offset");
        }

        $where  = "deleted_at IS NULL AND ip_address != '127.0.0.1' AND ip_address NOT LIKE '192.168.%'";
        $params = [];
        if ($filter === 'unread') {
            $where .= " AND is_read = 0";
        }
        if ($filter === 'replied') {
            $where .= " AND replied = 1";
        }
        if ($search) {
            $where .= " AND (full_name LIKE ? OR email LIKE ? OR subject LIKE ?)";
            $s      = "%$search%";
            $params = array_merge($params, [$s, $s, $s]);
        }

        $total      = Sql::fetch("SELECT COUNT(*) AS c FROM contact_messages WHERE $where", $params)['c'] ?? 0;
        $messages   = Sql::fetchAll("SELECT * FROM contact_messages WHERE $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset", $params);
        $totalPages = ceil($total / $perPage);

        // Single message view (marks it as read)
        $viewMsg = null;
        if ($request->has('view')) {
            $viewId  = (int) $request->query('view');
            $viewMsg = Sql::fetch("SELECT * FROM contact_messages WHERE id=?", [$viewId]);
            if ($viewMsg && !$viewMsg['is_read']) {
                Sql::execute("UPDATE contact_messages SET is_read=1 WHERE id=?", [$viewId]);
                $viewMsg['is_read'] = 1;
            }
        }

        return view('admin.messages', [
            'pageTitle'    => 'Messages',
            'filter'       => $filter,
            'search'       => $search,
            'page'         => $page,
            'activeTab'    => $activeTab,
            'sentMessages' => $sentMessages,
            'sentTotal'    => $sentTotal,
            'total'        => $total,
            'messages'     => $messages,
            'totalPages'   => $totalPages,
            'viewMsg'      => $viewMsg,
            'unreadCount'  => Sql::fetch("SELECT COUNT(*) AS c FROM contact_messages WHERE is_read=0 AND deleted_at IS NULL AND ip_address != '127.0.0.1' AND ip_address NOT LIKE '192.168.%'")['c'] ?? 0,
            'sentCount'    => Sql::fetch("SELECT COUNT(*) AS c FROM sent_messages WHERE deleted_at IS NULL")['c'] ?? 0,
        ]);
    }

    /** POST /admin/messages - "send new message" or "reply to a contact message". */
    public function handle(Request $request)
    {
        if ($denied = $this->denyViewer()) {
            return $denied;
        }

        // ── Compose a new message ───────────────────────────────
        if ($request->has('send_message_submit')) {
            $smTo      = Security::sanitize($request->input('sm_to'));
            $smName    = Security::sanitize($request->input('sm_to_name'));
            $smSubject = Security::sanitize($request->input('sm_subject'));
            $smBody    = Security::sanitize($request->input('sm_body'));
            $smSent    = false;
            $smError   = '';

            if ($smTo && $smSubject && $smBody) {
                try {
                    Mailer::sendHtml($smTo, $smName ?: $smTo, $smSubject, Mailer::styledReply($smName ?: 'Valued Member', $smBody), $smBody);
                    $smSent = true;
                    Sql::execute(
                        "INSERT INTO sent_messages (sent_to_email, sent_to_name, subject, body, sent_by, sent_at) VALUES (?,?,?,?,?,NOW())",
                        [$smTo, $smName, $smSubject, $smBody, admin_id()]
                    );
                } catch (\Throwable $e) {
                    report($e);
                    $smError = $e->getMessage();
                }
            }

            return redirect('/admin/messages')
                ->with('send_msg_result', $smSent ? 'sent' : 'failed')
                ->with('send_msg_error_detail', $smError);
        }

        // ── Reply to a contact message ──────────────────────────
        if ($request->has('reply_email_send')) {
            $rTo      = Security::sanitize($request->input('reply_to'));
            $rName    = Security::sanitize($request->input('reply_to_name'));
            $rSubject = Security::sanitize($request->input('reply_subject'));
            $rBody    = Security::sanitize($request->input('reply_body'));
            $flash    = ['reply_result' => 'failed'];

            try {
                Mailer::sendHtml($rTo, $rName, $rSubject, Mailer::styledReply($rName, $rBody), $rBody);
                $flash = ['reply_result' => 'sent'];

                // Keep a copy for the "Sent" tab
                Sql::execute(
                    "INSERT INTO sent_messages (sent_to_email, sent_to_name, subject, body, sent_by, sent_at) VALUES (?,?,?,?,?,NOW())",
                    [$rTo, $rName, $rSubject, $rBody, admin_id()]
                );
            } catch (\Throwable $e) {
                report($e);
                $flash['reply_error'] = $e->getMessage();
            }

            $back = $request->query('view') !== null ? '/admin/messages?view=' . (int) $request->query('view') : '/admin/messages';
            return redirect($back)->with($flash);
        }

        return redirect('/admin/messages');
    }
}
