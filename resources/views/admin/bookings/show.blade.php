@extends('admin.layout')

@section('title', 'Chi tiết booking')
@section('page-title', 'Chi tiết booking')

@section('content')
<div class="card p-3">
    <h5>Mã: {{ $booking->booking_code }}</h5>
    <p><strong>Thành viên:</strong> {{ $booking->member->name ?? '-' }}</p>
    <p><strong>Lớp:</strong> {{ $booking->gymClass->name ?? '-' }}</p>
    <p><strong>Trạng thái:</strong> {{ $booking->status }}</p>
</div>

@endsection
