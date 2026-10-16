@extends('layouts.admin')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-graph-up me-2"></i>Payment Analytics</h5>

<form method="GET" class="row g-2 mb-4">
    <div class="col-auto">
        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}">
    </div>
    <div class="col-auto">
        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-ceac-primary">Filter</button>
        <a href="payment_logs" class="btn btn-sm btn-outline-secondary">Clear</a>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="bg-white rounded-3 border shadow-sm p-3">
            <div class="text-muted small">Total Collected</div>
            <div class="fs-4 fw-bold text-ceac-orange">₵{!! number_format($totalCollected, 2) !!}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white rounded-3 border shadow-sm p-3">
            <div class="text-muted small">Transactions Logged</div>
            <div class="fs-4 fw-bold">{!! count($payments) !!}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white rounded-3 border shadow-sm p-3">
            <div class="text-muted small mb-1">By Method</div>
            @foreach ($byMethod as $method => $amt)
                <div class="small d-flex justify-content-between">
                    <span>{!! ucfirst(str_replace('_',' ',$method)) !!}</span>
                    <span>₵{!! number_format($amt, 2) !!}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-white rounded-3 border shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Booking Ref</th>
                <th>Booker</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Reference Note</th>
                <th>Recorded By</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $p)
            <tr>
                <td class="small">{!! date('M j, Y g:i A', strtotime($p['created_at'])) !!}</td>
                <td><a href="booking_detail?id={!! $p['booking_id'] !!}">{{ $p['booking_ref'] }}</a></td>
                <td class="small">{{ $p['booker_name'] }}</td>
                <td class="small">₵{!! number_format($p['amount'], 2) !!}</td>
                <td class="small">{!! ucfirst(str_replace('_',' ',$p['method'])) !!}</td>
                <td class="small text-muted">{{ $p['reference_note'] ?? '—' }}</td>
                <td class="small">{{ $p['recorded_by_name'] ?? 'Unknown' }}</td>
            </tr>
            @endforeach
            @if (!$payments)
            <tr><td colspan="7" class="text-center text-muted py-4">No payments logged yet.</td></tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
