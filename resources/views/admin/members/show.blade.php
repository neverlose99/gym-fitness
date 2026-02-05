@extends('admin.layout')

@section('title', 'Thông tin thành viên')
@section('page-title', 'Chi tiết thành viên')

@section('content')
<div class="card p-3">
    <h5>{{ $member->name }}</h5>
    <p><strong>Email:</strong> {{ $member->email }}</p>
    <p><strong>Phone:</strong> {{ $member->phone }}</p>
    <p><strong>Loại:</strong> {{ $member->membership_type }}</p>
    <p><strong>Thời hạn:</strong> {{ $member->membership_start }} → {{ $member->membership_end }}</p>

    <hr>
    <h6>Thống kê</h6>
    <p>Tổng booking: {{ $stats['total_bookings'] }}</p>
    <p>Hoàn thành: {{ $stats['completed_bookings'] }}</p>
    <p>Đã trả: {{ $stats['total_spent'] }}</p>
</div>

@endsection
