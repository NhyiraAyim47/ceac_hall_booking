@extends('layouts.admin')

@section('content')
<div class="container py-4" style="max-width:700px;">
    <h5 class="fw-bold mb-3">Edit Booking — {{ $booking['booking_ref'] }}</h5>

    @if ($formErrors)
    <div class="alert alert-danger">{!! implode('<br>', array_map('htmlspecialchars', $formErrors)) !!}</div>
    @endif

    <form method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="{{ $booking['full_name'] }}" required>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $booking['email'] }}" required>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $booking['phone'] }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Organization</label>
            <input type="text" name="organization" class="form-control" value="{{ $booking['organization'] ?? '' }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Event Title</label>
            <input type="text" name="event_title" class="form-control" value="{{ $booking['event_title'] }}" required>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label">Start</label>
                <input type="datetime-local" name="start_datetime" class="form-control"
                       value="{{ !empty($booking['event_date']) && !empty($booking['start_time']) ? date('Y-m-d\TH:i', strtotime($booking['event_date'].' '.$booking['start_time'])) : '' }}" required>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">End</label>
                <input type="datetime-local" name="end_datetime" class="form-control"
                       value="{{ !empty($booking['event_date']) && !empty($booking['end_time']) ? date('Y-m-d\TH:i', strtotime($booking['event_date'].' '.$booking['end_time'])) : '' }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Expected Attendees</label>
            <input type="number" name="expected_attendees" class="form-control" value="{{ (int)$booking['expected_attendees'] }}" min="1">
        </div>
        <div class="mb-3">
            <label class="form-label">Special Requirements</label>
            <textarea name="special_requirements" class="form-control" rows="3">{{ $booking['special_requirements'] ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-ceac-primary">Save Changes</button>
        <a href="booking_detail?id={{ $id }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
