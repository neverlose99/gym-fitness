@extends('admin.layout')

@section('title', 'Sửa thành viên')
@section('page-title', 'Sửa thành viên')

@section('content')
<div class="card p-3">
    <form method="POST" action="{{ route('admin.members.update', $member->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input name="name" class="form-control" value="{{ old('name', $member->name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" class="form-control" value="{{ old('email', $member->email) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Loại thành viên</label>
            <select name="membership_type" class="form-select">
                <option value="basic" {{ $member->membership_type=='basic' ? 'selected' : '' }}>Basic</option>
                <option value="premium" {{ $member->membership_type=='premium' ? 'selected' : '' }}>Premium</option>
                <option value="vip" {{ $member->membership_type=='vip' ? 'selected' : '' }}>VIP</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="date" name="membership_start" class="form-control" value="{{ old('membership_start', $member->membership_start->format('Y-m-d')) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày kết thúc</label>
            <input type="date" name="membership_end" class="form-control" value="{{ old('membership_end', $member->membership_end->format('Y-m-d')) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" step="0.01" name="membership_price" class="form-control" value="{{ old('membership_price', $member->membership_price) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="active" {{ $member->status=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $member->status=='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button class="btn btn-primary">Cập nhật</button>
    </form>
</div>

@endsection
