@extends('admin.layout')

@section('title', 'Thêm thành viên')
@section('page-title', 'Thêm thành viên')

@section('content')
<div class="card p-3">
    <form method="POST" action="{{ route('admin.members.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input name="name" class="form-control" value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Loại thành viên</label>
            <select name="membership_type" class="form-select">
                <option value="basic">Basic</option>
                <option value="premium">Premium</option>
                <option value="vip">VIP</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày bắt đầu</label>
            <input type="date" name="membership_start" class="form-control" value="{{ old('membership_start') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày kết thúc</label>
            <input type="date" name="membership_end" class="form-control" value="{{ old('membership_end') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" step="0.01" name="membership_price" class="form-control" value="{{ old('membership_price') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button class="btn btn-primary">Lưu</button>
    </form>
</div>

@endsection
