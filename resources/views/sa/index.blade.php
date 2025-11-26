@extends('layout.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Daftar Pengguna</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Unit</th>
                    <th>Penanggung Jawab</th>
                    <th>No HP User</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->nohp_user }}</td>
                    <td>{{ $user->unit }}</td>
                    <td>{{ $user->penanggung_jawab }}</td>
                    <td>{{ $user->no_hp }}</td>
                    <td>{{ ucfirst($user->level) }}</td>
                    <td>
                        @if ($user->level !== 'admin')
                        <form action="{{ route('users.makeAdmin', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-primary btn-sm">Jadikan Admin</button>
                        </form>
                        @else
                        <span class="badge bg-success">Admin</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection