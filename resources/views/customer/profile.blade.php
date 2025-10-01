@extends('layouts.customer')

@section('title', 'Profile Saya')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h5 class="mt-3 mb-0">{{ $user->name }}</h5>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                    <hr>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('customer.profile.edit') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user me-2"></i> Profile Saya
                        </a>
                        <a href="{{ route('customer.orders.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-box me-2"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('customer.cart') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-cart me-2"></i> Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <h3 class="mb-4">Edit Profile</h3>

            <!-- Update Profile Form -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap*</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email*</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">No. Telepon/WhatsApp</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $user->phone) }}" placeholder="08123456789">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" 
                                          placeholder="Jl. Contoh No. 123, Kota">{{ old('address', $user->address) }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Alamat ini akan digunakan sebagai alamat pengiriman default</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Password Lama*</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru*</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru*</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection