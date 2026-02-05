@extends('admin.layout')

@section('title', 'Quản lý booking')
@section('page-title', 'Đặt lịch')

@section('content')
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Mã</th>
                <th>Thành viên</th>
                <th>Lớp</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td><a href="{{ route('admin.bookings.show', $b->id) }}">{{ $b->booking_code }}</a></td>
                <td>{{ $b->member->name ?? '-' }}</td>
                <td>{{ $b->gymClass->name ?? '-' }}</td>
                <td>{{ $b->status }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.bookings.confirm', $b->id) }}" style="display:inline">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-sm btn-success btn-action">Xác nhận</button>
                    </form>
                    <form method="POST" action="{{ route('admin.bookings.complete', $b->id) }}" style="display:inline">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-sm btn-secondary btn-action">Hoàn thành</button>
                    </form>
                    <form method="POST" action="{{ route('admin.bookings.destroy', $b->id) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger btn-action" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $bookings->withQueryString()->links() }}
</div>

@endsection
