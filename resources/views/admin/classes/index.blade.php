@extends('admin.layout')

@section('title', 'Quản lý lớp học')
@section('page-title', 'Lớp học')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex" method="GET">
        <input name="search" class="form-control me-2" placeholder="Tìm kiếm..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary">Tìm</button>
    </form>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">Thêm lớp</a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>HLV</th>
                <th>Thời lượng</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td><a href="{{ route('admin.classes.show', $c->id) }}">{{ $c->name }}</a></td>
                <td>{{ $c->trainer->name ?? '-' }}</td>
                <td>{{ $c->duration }} phút</td>
                <td>{{ $c->price }}</td>
                <td>{{ $c->status }}</td>
                <td>
                    <a href="{{ route('admin.classes.edit', $c->id) }}" class="btn btn-sm btn-warning btn-action">Sửa</a>
                    <form action="{{ route('admin.classes.destroy', $c->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger btn-action" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $classes->withQueryString()->links() }}
</div>

@endsection
