@extends('admin.layout')

@section('title', 'Chi tiết lớp')
@section('page-title', 'Chi tiết lớp')

@section('content')
<div class="card p-3">
    <h5>{{ $class->name }}</h5>
    <p>{{ $class->description }}</p>
    <p><strong>HLV:</strong> {{ $class->trainer->name ?? '-' }}</p>
    <p><strong>Thời lượng:</strong> {{ $class->duration }} phút</p>
    <p><strong>Giá:</strong> {{ $class->price }}</p>
</div>

@endsection
