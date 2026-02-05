@extends('admin.layout')

@section('title', 'Sửa HLV')
@section('page-title', 'Sửa HLV')

@section('content')
<div class="card p-3">
    <form method="POST" action="{{ route('admin.trainers.update', $trainer->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input name="name" class="form-control" value="{{ old('name', $trainer->name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" class="form-control" value="{{ old('email', $trainer->email) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" value="{{ old('phone', $trainer->phone) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Chuyên môn</label>
            <input name="specialization" class="form-control" value="{{ old('specialization', $trainer->specialization) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="active" {{ $trainer->status=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $trainer->status=='inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="on_leave" {{ $trainer->status=='on_leave' ? 'selected' : '' }}>On Leave</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Avatar</label>
            <input type="file" name="avatar" class="form-control">
        </div>

        <button class="btn btn-primary">Cập nhật</button>
    </form>
</div>

@endsection
