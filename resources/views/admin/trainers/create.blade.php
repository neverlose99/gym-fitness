@extends('admin.layout')

@section('title', 'Thêm HLV')
@section('page-title', 'Thêm HLV')

@section('content')
<div class="card p-3">
    <form method="POST" action="{{ route('admin.trainers.store') }}" enctype="multipart/form-data">
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
            <label class="form-label">Chuyên môn</label>
            <input name="specialization" class="form-control" value="{{ old('specialization') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Kinh nghiệm (năm)</label>
            <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years',0) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Lương cơ bản</label>
            <input type="number" step="0.01" name="base_salary" class="form-control" value="{{ old('base_salary',0) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Avatar</label>
            <input type="file" name="avatar" class="form-control">
        </div>

        <button class="btn btn-primary">Lưu</button>
    </form>
</div>

@endsection
