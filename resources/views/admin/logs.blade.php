@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-3 border shadow-sm">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-ceac-orange"></i>Activity Log (last 100)</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 admin-table">
            <thead><tr><th>Time</th><th>Admin</th><th>Action</th><th>Description</th><th>IP</th></tr></thead>
            <tbody>
            @if (empty($logs))
                <tr><td colspan="5" class="text-center py-4 text-muted">No activity logged yet.</td></tr>
            @else @foreach ($logs as $l)
            <tr>
                <td class="small text-muted">{!! date('M j, Y g:i A', strtotime($l['created_at'])) !!}</td>
                <td class="small fw-semibold">{{ $l['full_name']??'System' }}</td>
                <td><span class="badge bg-{!! str_contains($l['action'],'login')?'success':(str_contains($l['action'],'delete')?'danger':'secondary') !!}">{{ $l['action'] }}</span></td>
                <td class="small">{{ $l['description'] }}</td>
                <td class="small"><code>{{ $l['ip_address'] }}</code></td>
            </tr>
            @endforeach @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
