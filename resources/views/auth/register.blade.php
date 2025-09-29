@extends('layouts.app')

@section('content')
<style>
    .register-container {
        display: flex;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    
    .register-left {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        background: white;
    }
    
    .register-right {
        flex: 1;
        background: linear-gradient(135deg, rgba(139, 195, 74, 0.3), rgba(104, 159, 56, 0.5)), 
                    url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><rect fill="%2388c057" width="1200" height="800"/></svg>');
        background-size: cover;
        background-position: center;
        position: relative;
        overflow: hidden;
    }
    
    .register-right::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100px;
        width: 200px;
        height: 100%;
        background: white;
        transform: skewX(-10deg);
    }
    
    .register-form-wrapper {
        width: 100%;
        max-width: 400px;
    }
    
    .register-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #000;
        margin-bottom: 40px;
    }
    
    .form-group-custom {
        margin-bottom: 25px;
    }
    
    .form-label-custom {
        display: block;
        font-size: 0.95rem;
        font-weight: 500;
        color: #000;
        margin-bottom: 8px;
    }
    
    .form-input-custom {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
        background: #f8f8f8;
    }
    
    .form-input-custom:focus {
        outline: none;
        border-color: #66bb6a;
        background: white;
    }
    
    .form-input-custom.is-invalid {
        border-color: #f44336;
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
    }
    
    .btn-register {
        width: 100%;
        padding: 14px;
        background: #4caf50;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 10px;
    }
    
    .btn-register:hover {
        background: #45a049;
    }
    
    .login-link {
        margin-top: 20px;
        font-size: 0.95rem;
        color: #666;
    }
    
    .login-link a {
        color: #2196f3;
        text-decoration: none;
        font-weight: 500;
    }
    
    .login-link a:hover {
        text-decoration: underline;
    }
    
    .invalid-feedback {
        color: #f44336;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }
    
    @media (max-width: 768px) {
        .register-container {
            flex-direction: column;
        }
        
        .register-right {
            min-height: 200px;
        }
        
        .register-right::after {
            display: none;
        }
    }
</style>

<div class="register-container">
    <div class="register-left">
        <div class="register-form-wrapper">
            <h1 class="register-title">Registrasi</h1>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group-custom">
                    <label for="name" class="form-label-custom">Nama Lengkap</label>
                    <input id="name" type="text" class="form-input-custom @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label for="email" class="form-label-custom">Alamat Email</label>
                    <input id="email" type="email" class="form-input-custom @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label for="password" class="form-label-custom">Kata Sandi</label>
                    <div class="password-wrapper">
                        <input id="password" type="password" class="form-input-custom @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        <span class="password-toggle" onclick="togglePassword('password')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label for="password-confirm" class="form-label-custom">Konfirmasi Kata Sandi</label>
                    <div class="password-wrapper">
                        <input id="password-confirm" type="password" class="form-input-custom" name="password_confirmation" required autocomplete="new-password">
                        <span class="password-toggle" onclick="togglePassword('password-confirm')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    Registrasi →
                </button>
            </form>
            
            <div class="login-link">
                Sudah punya akun? <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </div>
    
    <div class="register-right"></div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>
@endsection