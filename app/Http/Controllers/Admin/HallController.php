<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

/** Admin > Manage Halls (add / edit / delete, up to 4 photos, pricing). */
class HallController extends Controller
{
    private const ALLOWED_MIME = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    private const MAX_BYTES    = 5 * 1024 * 1024;

    public function index(Request $request)
    {
        // ── Soft delete ─────────────────────────────────────────
        if ($request->has('soft_delete') && $this->validGetToken($request)) {
            if ($denied = $this->denyViewer()) {
                return $denied;
            }
            $did  = (int) $request->query('soft_delete');
            $hall = Sql::fetch("SELECT name FROM halls WHERE id=?", [$did]);
            if ($hall) {
                Sql::execute("UPDATE halls SET deleted_at=NOW() WHERE id=?", [$did]);
                adminLog('soft_delete_hall', "Soft deleted hall: {$hall['name']}");
            }
            return redirect('/admin/halls');
        }

        // ── Permanent delete ────────────────────────────────────
        if ($request->has('delete') && $this->validGetToken($request)) {
            if ($denied = $this->denyViewer()) {
                return $denied;
            }
            $did         = (int) $request->query('delete');
            $hall        = Sql::fetch("SELECT name,image FROM halls WHERE id=?", [$did]);
            $hasBookings = Sql::fetch("SELECT COUNT(*) AS c FROM bookings WHERE hall_id=? AND status IN ('pending','approved')", [$did])['c'];

            if ($hasBookings > 0) {
                return redirect('/admin/halls')
                    ->with('hall_msg', 'Cannot delete a hall with active or pending bookings.')
                    ->with('hall_msg_type', 'danger');
            }

            if ($hall && $hall['image'] && strpos($hall['image'], 'halls/') !== false) {
                $p = public_path('assets/images/' . $hall['image']);
                if (file_exists($p)) {
                    @unlink($p);
                }
            }
            Sql::execute("DELETE FROM halls WHERE id=?", [$did]);
            adminLog('delete_hall', "Deleted hall: " . ($hall['name'] ?? ''));

            return redirect('/admin/halls')
                ->with('hall_msg', "Hall \"" . ($hall['name'] ?? '') . "\" permanently deleted.")
                ->with('hall_msg_type', 'success');
        }

        $halls = Sql::fetchAll(
            "SELECT h.*,
                (SELECT COUNT(*) FROM bookings b WHERE b.hall_id=h.id AND b.status='approved') AS approved_count,
                (SELECT COUNT(*) FROM bookings b WHERE b.hall_id=h.id AND b.status='pending')  AS pending_count,
                (SELECT COUNT(*) FROM bookings b WHERE b.hall_id=h.id) AS total_count
             FROM halls h WHERE h.deleted_at IS NULL ORDER BY h.id ASC"
        );

        // Hall to pre-fill the edit modal (?edit=ID)
        $editHall = null;
        if ($request->has('edit')) {
            $editHall = Sql::fetch("SELECT * FROM halls WHERE id=?", [(int) $request->query('edit')]);
        }

        return view('admin.halls', [
            'pageTitle' => 'Manage Halls',
            'msg'       => '',
            'msgType'   => 'success',
            'halls'     => $halls,
            'editHall'  => $editHall,
            'amenityFields' => [
                'has_projector' => 'Display Screen',
                'has_ac'        => 'Air Conditioning',
                'has_pa_system' => 'PA System',
                'has_wifi'      => 'WiFi',
            ],
        ]);
    }

    /** POST /admin/halls - add or update a hall. */
    public function save(Request $request)
    {
        if ($denied = $this->denyViewer()) {
            return $denied;
        }

        $id            = (int) $request->input('hall_id', 0);
        $action        = Security::sanitize($request->input('form_action', 'edit'));
        $name          = Security::sanitize($request->input('name'));
        $desc          = Security::sanitize($request->input('description'));
        $aboutExtended = Security::sanitize($request->input('about_extended'));
        $seatingInfo   = Security::sanitize($request->input('seating_info'));
        $capacity      = max(1, (int) $request->input('capacity', 50));
        $style         = Security::sanitize($request->input('seating_style', 'Theatre Style'));
        $status        = Security::sanitize($request->input('status', 'active'));
        $hasProjector  = $request->has('has_projector') ? 1 : 0;
        $hasAC         = $request->has('has_ac') ? 1 : 0;
        $hasPA         = $request->has('has_pa_system') ? 1 : 0;
        $hasWifi       = $request->has('has_wifi') ? 1 : 0;
        $hasWB         = $request->has('has_whiteboard') ? 1 : 0;
        $hasParking    = $request->has('has_parking') ? 1 : 0;
        [$featuresJson, $idealJson, $pricingJson] = $this->parseDetailFields($request);

        if (!in_array($status, ['active', 'inactive', 'maintenance'], true)) {
            $status = 'active';
        }

        if (!$name) {
            return redirect('/admin/halls')->with('hall_msg', 'Hall name is required.')->with('hall_msg_type', 'danger');
        }

        if ($action === 'add') {
            $imagesJson = $this->handleImages($request, []);
            $decoded    = json_decode($imagesJson, true);
            $mainImage  = !empty($decoded) ? $decoded[0] : '';
            $slug       = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name)) . '-' . time();

            Sql::execute(
                "INSERT INTO halls (name,slug,description,about_extended,seating_info,features_json,ideal_for_json,capacity,seating_style,status,has_projector,has_ac,has_pa_system,has_wifi,has_whiteboard,has_parking,image,images_json,pricing_json) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [$name, $slug, $desc, $aboutExtended, $seatingInfo, $featuresJson, $idealJson, $capacity, $style, $status, $hasProjector, $hasAC, $hasPA, $hasWifi, $hasWB, $hasParking, $mainImage, $imagesJson, $pricingJson]
            );
            $msg = "Hall \"$name\" added successfully.";
            adminLog('add_hall', "New hall: $name");
        } else {
            $existing     = Sql::fetch("SELECT image,images_json FROM halls WHERE id=?", [$id]);
            $existingImgs = json_decode($existing['images_json'] ?? '[]', true) ?: [];
            $imagesJson   = $this->handleImages($request, $existingImgs);
            $decoded      = json_decode($imagesJson, true);
            $mainImage    = !empty($decoded) ? $decoded[0] : ($existing['image'] ?? '');

            Sql::execute(
                "UPDATE halls SET name=?,description=?,about_extended=?,seating_info=?,features_json=?,ideal_for_json=?,capacity=?,seating_style=?,status=?,has_projector=?,has_ac=?,has_pa_system=?,has_wifi=?,has_whiteboard=?,has_parking=?,image=?,images_json=?,pricing_json=?,updated_at=NOW() WHERE id=?",
                [$name, $desc, $aboutExtended, $seatingInfo, $featuresJson, $idealJson, $capacity, $style, $status, $hasProjector, $hasAC, $hasPA, $hasWifi, $hasWB, $hasParking, $mainImage, $imagesJson, $pricingJson, $id]
            );
            $msg = "Hall \"$name\" updated successfully.";
            adminLog('update_hall', "Hall updated: $name");
        }

        return redirect('/admin/halls')->with('hall_msg', $msg)->with('hall_msg_type', 'success');
    }

    /**
     * Up to 4 photos. Removed slots are deleted, new uploads replace / fill slots.
     * File type is checked from the file's real content (not its name).
     */
    private function handleImages(Request $request, array $saved): string
    {
        File::ensureDirectoryExists(public_path('assets/images/halls'));

        // Slots the admin removed (slots are 1-based)
        foreach ((array) $request->input('remove_img_slot', []) as $removedSlot) {
            $idx = (int) $removedSlot - 1;
            if (isset($saved[$idx])) {
                $old = public_path('assets/images/' . $saved[$idx]);
                if (file_exists($old)) {
                    @unlink($old);
                }
                $saved[$idx] = null;
            }
        }

        // New uploads
        for ($i = 1; $i <= 4; $i++) {
            $file = $request->file('hall_image_' . $i);
            if (!$file instanceof UploadedFile || !$file->isValid()) {
                continue;
            }
            $mime = $file->getMimeType();
            if (!in_array($mime, self::ALLOWED_MIME, true) || $file->getSize() > self::MAX_BYTES) {
                continue;
            }
            $ext      = ($mime === 'image/png') ? 'png' : (($mime === 'image/webp') ? 'webp' : 'jpg');
            $filename = 'hall_' . time() . '_' . $i . '_' . bin2hex(random_bytes(3)) . '.' . $ext;

            $file->move(public_path('assets/images/halls'), $filename);

            if (!empty($saved[$i - 1])) {
                $old = public_path('assets/images/' . $saved[$i - 1]);
                if (file_exists($old)) {
                    @unlink($old);
                }
            }
            $saved[$i - 1] = 'halls/' . $filename;
        }

        $saved = array_values(array_filter($saved, fn ($v) => $v !== null));
        return json_encode(array_slice($saved, 0, 4));
    }

    /** Features list, "ideal for" list and pricing -> JSON strings. */
    private function parseDetailFields(Request $request): array
    {
        $featuresList = array_values(array_filter(array_map('trim', explode("\n", (string) $request->input('features_text', '')))));
        $featuresJson = !empty($featuresList) ? json_encode($featuresList) : null;

        $idealNames    = (array) $request->input('ideal_name', []);
        $idealDescs    = (array) $request->input('ideal_desc', []);
        $idealEnabled  = (array) $request->input('ideal_enabled', []);
        $iconDefaults  = ['people-fill', 'broadcast', 'briefcase-fill', 'moon-stars-fill', 'star-fill', 'camera-video', 'music-note-beamed', 'calendar-event'];
        $colorDefaults = ['#2563EB', '#059669', '#7C3AED', '#C2410C', '#D97706', '#0891B2', '#7C3AED', '#374151'];
        $idealList     = [];
        foreach ($idealNames as $idx => $iName) {
            if (empty($idealEnabled[$idx]) || $idealEnabled[$idx] !== '1') {
                continue; // only ticked rows
            }
            $iName = htmlspecialchars(strip_tags(trim((string) $iName)));
            $iDesc = htmlspecialchars(strip_tags(trim((string) ($idealDescs[$idx] ?? ''))));
            if (!empty($iName)) {
                $idealList[] = [
                    'icon'  => $iconDefaults[$idx % count($iconDefaults)],
                    'color' => $colorDefaults[$idx % count($colorDefaults)],
                    'name'  => $iName,
                    'desc'  => $iDesc,
                ];
            }
        }
        $idealJson = !empty($idealList) ? json_encode($idealList) : null;

        // Pricing
        $pricing = [
            'currency'            => (string) $request->input('pricing_currency', 'GHS'),
            'penalty_per_hour'    => (int) $request->input('pricing_penalty', 0),
            'facility_projector'  => (int) $request->input('pricing_projector', 0),
            'facility_pa_system'  => (int) $request->input('pricing_pa_system', 0),
            'facility_ac'         => (int) $request->input('pricing_ac', 0),
            'facility_whiteboard' => (int) $request->input('pricing_whiteboard', 0),
        ];
        foreach (['meetings'] as $evKey) {
            $pricing[$evKey . '_half_hours'] = (int) $request->input('pricing_' . $evKey . '_half_hours', 2);
            $pricing[$evKey . '_half_price'] = (int) $request->input('pricing_' . $evKey . '_half_price', 0);
            $pricing[$evKey . '_full_hours'] = (int) $request->input('pricing_' . $evKey . '_full_hours', 4);
            $pricing[$evKey . '_full_price'] = (int) $request->input('pricing_' . $evKey . '_full_price', 0);
        }
        $hasAnyPrice = array_sum(array_filter($pricing, 'is_int')) > 0;

        return [$featuresJson, $idealJson, $hasAnyPrice ? json_encode($pricing) : null];
    }
}
