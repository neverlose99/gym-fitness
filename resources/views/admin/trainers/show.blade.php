@extends('admin.layout')

@section('title', 'Chi tiết HLV')
@section('page-title', 'Chi tiết HLV')

@section('content')
<div class="card p-3">
    <h5>{{ $trainer->name }}</h5>
    <p><strong>Email:</strong> {{ $trainer->email }}</p>
    <p><strong>Phone:</strong> {{ $trainer->phone }}</p>
    <p><strong>Chuyên môn:</strong> {{ $trainer->specialization }}</p>
    <p><strong>Kinh nghiệm:</strong> {{ $trainer->experience_years }} năm</p>
</div>

@endsection
