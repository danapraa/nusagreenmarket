@extends('layouts.app')
@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .login-container {
        min-height: 100vh;
        display: flex;
        background: white;
    }
    
    .login-form-section {
        width: 45%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 80px;
        background: white;
    }
    
    .login-image-section {
    width: 55%;
    background-image: url('storage/images/background-pertanian.jpg');
    background-size: cover;
    background-position: center;
    position: relative;
    clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%);
}
    
    .login-title {
        font-size: 48px;
        font-weight: 700;
        color: #000;
        margin-bottom: 50px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .form-group-custom {
        margin-bottom: 30px;
    }
    
    .form-label-custom {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #000;
        margin-bottom: 8px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .form-input-wrapper {
        position: relative;
    }
    
    .form-control-custom {
        width: 100%;
        max-width: 450px;
        padding: 14px 16px;
        border: none;
        border-bottom: 2px solid #28a745;
        background: #f5f5f5;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        border-radius: 0;
    }
    
    .form-control-custom.has-icon {
        padding-right: 45px;
    }
    
    .form-control-custom:focus {
        outline: none;
        background: #f5f5f5;
        border-bottom: 2px solid #28a745;
    }
    
    .form-control-custom.is-invalid {
        border-bottom-color: #dc3545;
    }
    
    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #666;
        font-size: 18px;
        z-index: 10;
    }
    
    .form-input-wrapper {
        position: relative;
        max-width: 450px;
    }
    
    .btn-login {
        width: 100%;
        max-width: 450px;
        padding: 14px 24px;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 40px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .btn-login:hover {
        background: #218838;
    }
    
    .register-link {
        margin-top: 25px;
        font-size: 14px;
        color: #000;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .register-link a {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }
    
    .register-link a:hover {
        text-decoration: underline;
    }
    
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 13px;
        margin-top: 6px;
    }
    
    @media (max-width: 992px) {
        .login-container {
            flex-direction: column;
        }
        
        .login-form-section {
            width: 100%;
            padding: 50px 40px;
        }
        
        .login-image-section {
            width: 100%;
            min-height: 300px;
            clip-path: none;
        }
    }
</style>

<div class="login-container">
    <div class="login-form-section">
        <h1 class="login-title">Login</h1>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group-custom">
                <label for="email" class="form-label-custom">Alamat Email</label>
                <input id="email" 
                       type="email" 
                       class="form-control-custom @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="email" 
                       autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group-custom">
                <label for="password" class="form-label-custom">Kata Sandi</label>
                <div class="form-input-wrapper">
                    <input id="password" 
                           type="password" 
                           class="form-control-custom has-icon @error('password') is-invalid @enderror" 
                           name="password" 
                           required 
                           autocomplete="current-password">
                    <span class="password-toggle" onclick="togglePassword()">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </span>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <button type="submit" class="btn-login">
                <span>Login</span>
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </button>
            
            <div class="register-link">
                Belum memiliki akun? 
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Daftar</a>
                @else
                    <a href="#">Daftar</a>
                @endif
            </div>
        </form>
    </div>
    
    <div class="login-image-section"></div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
}
</script>
@endsection