@extends('layouts.app')

@section('content')
@php
    $metaTitle       = 'Page Not Found | CEAC Hall Booking';
    $metaDescription = 'The page you are looking for could not be found.';
@endphp
<section class="page-hero">
    <div class="container text-center">
        <h1><i class="bi bi-exclamation-triangle me-2 text-ceac-orange"></i>404 — Page Not Found</h1>
        <p class="text-white-50">Sorry, the page you're looking for doesn't exist or may have moved.</p>
    </div>
</section>
<section class="py-5">
    <div class="container text-center">
        <p class="text-muted mb-4">Here are some helpful links instead:</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ base_url() }}/" class="btn btn-ceac-primary">Go Home</a>
            <a href="{{ base_url() }}/halls" class="btn btn-outline-secondary">Browse Halls</a>
            <a href="{{ base_url() }}/contact" class="btn btn-outline-secondary">Contact Us</a>
        </div>
    </div>
</section>
@endsection
