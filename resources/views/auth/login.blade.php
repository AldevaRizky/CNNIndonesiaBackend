<!doctype html>
<html
    lang="en"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('') }}assets/"
    data-template="vertical-menu-template-free"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login - {{ config('app.name') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="/" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('assets/img/CNN_International_logo.svg') }}" alt="Logo" width="50"/>
                                </span>
                                <span class="app-brand-text demo text-heading fw-bold">{{ config('app.name') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->

                        <h4 class="mb-1">Welcome Back! 👋</h4>
                        <p class="mb-4">Please sign in to your account</p>

                        @if (session('status'))
                            <div class="alert alert-success mb-3" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(isset($isLocked) && $isLocked)
                            <div class="alert alert-danger mb-3" role="alert" id="lockoutAlert">
                                <strong>Akun Dikunci!</strong><br>
                                Terlalu banyak percobaan login gagal. Silakan coba lagi dalam <strong id="remainingTime">{{ $remainingMinutes }}</strong> menit.
                            </div>
                        @endif

                        @if(isset($attempts) && $attempts > 0 && $attempts < 5)
                            <div class="alert alert-warning mb-3" role="alert">
                                <strong>Peringatan!</strong> Anda telah gagal login {{ $attempts }} kali. Tersisa {{ 5 - $attempts }} percobaan lagi sebelum akun dikunci selama 30 menit.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" id="formAuthentication">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus {{ isset($isLocked) && $isLocked ? 'disabled' : '' }} />
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required {{ isset($isLocked) && $isLocked ? 'disabled' : '' }} />
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} {{ isset($isLocked) && $isLocked ? 'disabled' : '' }}>
                                <label class="form-check-label" for="remember_me">Remember Me</label>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary d-grid w-100" type="submit" id="loginButton" {{ isset($isLocked) && $isLocked ? 'disabled' : '' }}>
                                    Log In
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

 <!-- Core JS -->
 <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
 <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
 <script src="{{ asset('assets/js/main.js') }}"></script>
 
 <script>
    // Check for lockout on page load
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
    
    function getIdentifier() {
        // Create identifier similar to server-side
        const email = document.getElementById('email') ? document.getElementById('email').value : '';
        // We can't perfectly replicate server-side hash, but we can check cookies
        return true;
    }
    
    function checkLockout() {
        // Check all cookies for lockout keys
        const cookies = document.cookie.split(';');
        let isLocked = false;
        let lockoutTime = 0;
        
        for (let cookie of cookies) {
            cookie = cookie.trim();
            if (cookie.startsWith('login_lockout_')) {
                const value = parseInt(cookie.split('=')[1]);
                if (value && value > Math.floor(Date.now() / 1000)) {
                    isLocked = true;
                    lockoutTime = value;
                    break;
                }
            }
        }
        
        if (isLocked) {
            disableForm(lockoutTime);
        }
    }
    
    function disableForm(lockoutTime) {
        const form = document.getElementById('formAuthentication');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const remember = document.getElementById('remember_me');
        const button = document.getElementById('loginButton');
        
        if (email) email.disabled = true;
        if (password) password.disabled = true;
        if (remember) remember.disabled = true;
        if (button) button.disabled = true;
        
        // Show or update alert
        let alert = document.getElementById('lockoutAlert');
        if (!alert) {
            alert = document.createElement('div');
            alert.id = 'lockoutAlert';
            alert.className = 'alert alert-danger mb-3';
            form.parentNode.insertBefore(alert, form);
        }
        
        const currentTime = Math.floor(Date.now() / 1000);
        const remainingMinutes = Math.ceil((lockoutTime - currentTime) / 60);
        
        alert.innerHTML = `<strong>Akun Dikunci!</strong><br>Terlalu banyak percobaan login gagal. Silakan coba lagi dalam <strong id="remainingTime">${remainingMinutes}</strong> menit.`;
        
        // Update countdown every minute
        const interval = setInterval(() => {
            const now = Math.floor(Date.now() / 1000);
            const remaining = Math.ceil((lockoutTime - now) / 60);
            
            if (remaining <= 0) {
                clearInterval(interval);
                location.reload();
            } else {
                const timeElement = document.getElementById('remainingTime');
                if (timeElement) {
                    timeElement.textContent = remaining;
                }
            }
        }, 60000); // Update every minute
    }
    
    // Check on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkLockout();
        
        // Also check on form submit
        const form = document.getElementById('formAuthentication');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Check lockout again before submit
                const cookies = document.cookie.split(';');
                for (let cookie of cookies) {
                    cookie = cookie.trim();
                    if (cookie.startsWith('login_lockout_')) {
                        const value = parseInt(cookie.split('=')[1]);
                        if (value && value > Math.floor(Date.now() / 1000)) {
                            e.preventDefault();
                            alert('Akun Anda masih dalam status terkunci. Silakan tunggu beberapa saat.');
                            return false;
                        }
                    }
                }
            });
        }
    });
    
    // Re-check periodically
    setInterval(checkLockout, 5000); // Check every 5 seconds
 </script>
</body>

</html>
