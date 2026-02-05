@extends('admin.layout')

@section('title', 'Sửa lớp')
@section('page-title', 'Sửa lớp')

@section('content')
<div class="card p-3">
    <form method="POST" action="{{ route('admin.classes.update', $class->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input name="name" class="form-control" value="{{ old('name', $class->name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $class->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">HLV</label>
            <select name="trainer_id" class="form-select">
                @foreach($trainers as $t)
                    <option value="{{ $t->id }}" {{ $class->trainer_id==$t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="active" {{ $class->status=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $class->status=='inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="full" {{ $class->status=='full' ? 'selected' : '' }}>Full</option>
                <option value="cancelled" {{ $class->status=='cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button class="btn btn-primary">Cập nhật</button>
    </form>
</div>

@endsection
