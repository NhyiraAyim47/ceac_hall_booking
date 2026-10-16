<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;

/** Admin > Manage Events. */
class EventController extends Controller
{
    public function index(Request $request)
    {
        $msg     = session('msg', '');
        $msgType = session('msgType', 'success');

        // ── Delete links (carry a CSRF token) ───────────────────
        if ($request->has('soft_delete') && $this->validGetToken($request)) {
            if ($denied = $this->denyViewer()) {
                return $denied;
            }
            $did = (int) $request->query('soft_delete');
            Sql::execute("UPDATE events SET deleted_at=NOW() WHERE id=?", [$did]);
            adminLog('soft_delete_event', "Soft deleted event ID $did");
            return redirect('/admin/events');
        }
        if ($request->has('delete') && $this->validGetToken($request)) {
            if ($denied = $this->denyViewer()) {
                return $denied;
            }
            Sql::execute("DELETE FROM events WHERE id=?", [(int) $request->query('delete')]);
            return redirect('/admin/events')->with('msg', 'Event deleted.')->with('msgType', 'success');
        }

        return view('admin.events', [
            'pageTitle'  => 'Manage Events',
            'msg'        => $msg,
            'msgType'    => $msgType,
            'events'     => Sql::fetchAll("SELECT e.*, h.name AS hall_name FROM events e LEFT JOIN halls h ON e.hall_id=h.id ORDER BY e.start_datetime DESC"),
            'halls'      => Sql::fetchAll("SELECT id, name FROM halls WHERE status='active'"),
            'eventTypes' => ['meetings' => 'Meetings'],
            // Auto-open the edit modal when ?edit=ID is present
            'autoEditId' => $request->has('edit') ? (int) $request->query('edit') : 0,
        ]);
    }

    /** POST /admin/events - create or update an event. */
    public function save(Request $request)
    {
        if ($denied = $this->denyViewer()) {
            return $denied;
        }

        $id        = (int) $request->input('event_id', 0);
        $hallId    = (int) $request->input('hall_id', 1);
        $title     = Security::sanitize($request->input('title'));
        $desc      = Security::sanitize($request->input('description'));
        $type      = 'meetings';
        $startDt   = Security::sanitize($request->input('start_datetime'));
        $endDt     = Security::sanitize($request->input('end_datetime'));
        $organizer = Security::sanitize($request->input('organizer'));
        $maxAtt    = (int) $request->input('max_attendees', 50);
        $status    = 'upcoming';
        if ($id) {
            $existing = Sql::fetch("SELECT status FROM events WHERE id = ?", [$id]);
            if ($existing) {
                $status = $existing['status'];
            }
        }
        $isRecurring = 0;
        $recPattern  = null;
        $slug        = strtolower(preg_replace('/[^a-z0-9]+/', '-', $title)) . '-' . time();

        if (!$title) {
            return redirect('/admin/events')->with('msg', 'Title is required.')->with('msgType', 'danger');
        }

        // Dates are optional in the form; the columns are NOT NULL, so an empty
        // value is stored as MySQL's "zero date" exactly like the original app
        // (the public pages already treat those as "no date set").

        if ($id) {
            Sql::execute(
                "UPDATE events SET hall_id=?,title=?,description=?,event_type=?,start_datetime=?,end_datetime=?,organizer=?,max_attendees=?,status=?,is_recurring=?,recurrence_pattern=?,updated_at=NOW() WHERE id=?",
                [$hallId, $title, $desc, $type, $startDt, $endDt, $organizer, $maxAtt, $status, $isRecurring, $recPattern, $id]
            );
            $msg = 'Event updated successfully.';
        } else {
            Sql::execute(
                "INSERT INTO events (hall_id,title,slug,description,event_type,start_datetime,end_datetime,organizer,max_attendees,status,is_recurring,recurrence_pattern) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
                [$hallId, $title, $slug, $desc, $type, $startDt, $endDt, $organizer, $maxAtt, $status, $isRecurring, $recPattern]
            );
            $msg = 'Event created successfully.';
        }
        adminLog($id ? 'update_event' : 'create_event', "Event: $title");

        return redirect('/admin/events')->with('msg', $msg)->with('msgType', 'success');
    }
}
