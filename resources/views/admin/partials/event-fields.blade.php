<div class='row g-3'>
    <div class='col-12'>
        <label class='form-label fw-semibold small'>Event Title <span class='text-danger'>*</span></label>
        <input type='text' name='title' class='form-control' required maxlength='200'
               value='{{ $ev['title'] ?? '' }}'>
    </div>
    <div class='col-md-6'>
        <label class='form-label fw-semibold small'>Hall <span class='text-danger'>*</span></label>
        <select name='hall_id' class='form-select'>@foreach ($halls as $h)<option value="{{ $h['id'] }}" {{ ($ev['hall_id'] ?? 1) == $h['id'] ? 'selected' : '' }}>{{ $h['name'] }}</option>@endforeach</select>
    </div>
    <div class='col-md-6'>
        <label class='form-label fw-semibold small'>Event Type</label>
        <input type='text' class='form-control' value='Meetings' disabled>
        <input type='hidden' name='event_type' value='meetings'>
    </div>
    <div class='col-md-6'>
        <label class='form-label fw-semibold small'>Start Date &amp; Time <span class='text-muted fw-normal'>(optional)</span></label>
        <input type='datetime-local' name='start_datetime' class='form-control' value=''>
    </div>
    <div class='col-md-6'>
        <label class='form-label fw-semibold small'>End Date &amp; Time <span class='text-muted fw-normal'>(optional)</span></label>
        <input type='datetime-local' name='end_datetime' class='form-control' value=''>
    </div>
    <div class='col-md-6'>
        <label class='form-label fw-semibold small'>Organizer</label>
        <input type='text' name='organizer' class='form-control' maxlength='150'
               value='{{ $ev['organizer'] ?? '' }}'>
    </div>
    <div class='col-md-6'>
        <label class='form-label fw-semibold small'>Max Attendees</label>
        <input type='number' name='max_attendees' class='form-control' min='1' max='10000'
               value='{{ $ev['max_attendees'] ?? 50 }}'>
    </div>
    <div class='col-12'>
        <label class='form-label fw-semibold small'>Description</label>
        <textarea name='description' class='form-control' rows='4'>{{ $ev['description'] ?? '' }}</textarea>
    </div>
</div>
