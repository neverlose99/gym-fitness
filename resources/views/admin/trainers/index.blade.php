@extends('admin.layout')

@section('title', 'Quản lý HLV')
@section('page-title', 'Huấn luyện viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex" method="GET">
        <input name="search" class="form-control me-2" placeholder="Tìm kiếm..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary">Tìm</button>
    </form>
    <a href="{{ route('admin.trainers.create') }}" class="btn btn-primary">Thêm HLV</a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Chuyên môn</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainers as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td><a href="{{ route('admin.trainers.show', $t->id) }}">{{ $t->name }}</a></td>
                <td>{{ $t->email }}</td>
                <td>{{ $t->specialization }}</td>
                <td>{{ $t->status }}</td>
                <td>
                    <a href="{{ route('admin.trainers.edit', $t->id) }}" class="btn btn-sm btn-warning btn-action">Sửa</a>
                    <form action="{{ route('admin.trainers.destroy', $t->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger btn-action" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $trainers->withQueryString()->links() }}
</div>

@endsection
