@extends('layouts.admin')

@section('content')
<div class="alert alert-danger">{{ $message ?? 'Access denied.' }}</div>
@endsection
