@extends('layouts.admin')

@section('title', 'Manajemen Customer')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Customer</h5>
    </div>
    <div class="card-body">
        <!-- Search -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari customer..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Cari</button>
            </div>
        </form>

        <!-- Customers Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Total Pesanan</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $customer->orders_count }} pesanan</span>
                        </td>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada customer</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection