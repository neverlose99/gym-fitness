@extends('admin.layout')

@section('title', 'Thêm lớp')
@section('page-title', 'Thêm lớp')

@section('content')
<div class="card p-3">
    <form method="POST" action="{{ route('admin.classes.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input name="name" class="form-control" value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">HLV</label>
            <select name="trainer_id" class="form-select">
                @foreach($trainers as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Level</label>
            <select name="level" class="form-select">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Duration (phút)</label>
            <input type="number" name="duration" class="form-control" value="{{ old('duration',60) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price',0) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Lưu</button>
    </form>
</div>

@endsection
