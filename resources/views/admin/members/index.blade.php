@extends('admin.layout')

@section('title', 'Quản lý thành viên')
@section('page-title', 'Thành viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex" method="GET">
        <input name="search" class="form-control me-2" placeholder="Tìm kiếm..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary">Tìm</button>
    </form>
    <a href="{{ route('admin.members.create') }}" class="btn btn-primary">Thêm thành viên</a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Loại</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $m)
            <tr>
                <td>{{ $m->id }}</td>
                <td><a href="{{ route('admin.members.show', $m->id) }}">{{ $m->name }}</a></td>
                <td>{{ $m->email }}</td>
                <td>{{ $m->phone }}</td>
                <td>{{ $m->membership_type }}</td>
                <td>{{ $m->status }}</td>
                <td>
                    <a href="{{ route('admin.members.edit', $m->id) }}" class="btn btn-sm btn-warning btn-action">Sửa</a>
                    <form action="{{ route('admin.members.destroy', $m->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger btn-action" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $members->withQueryString()->links() }}
</div>

@endsection
