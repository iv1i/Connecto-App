@extends('layouts.app')

@section('content')
    <div class="notifications">
        <div class="toast ">
            <div  class="toast-content">
                <i class="fas fa-solid fa-check check"></i>

                <div class="toast-message">
                    <span class="text text-1"></span>
                    <span class="text text-2"></span>
                    <span class="text text-3"></span>
                </div>
            </div>
            <i class="fi fi-br-cross-small close"></i>
            <!-- Remove 'active' class, this is just to show in Codepen thumbnail -->
            <div  class="progress"></div>
        </div>
    </div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="card-header">
                <h1 class="text-center">Login</h1>
            </div>

            <form id="loginForm" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label for="email" class="label"><i class="fi fi-br-at"></i>Email</label>
                    <input type="email" id="email" name="email" required class="input">
                </div>

                <div class="form-group">
                    <label for="password" class="label"><i class="fi fi-br-lock"></i>Password</label>
                    <input type="password" id="password" name="password" required class="input">
                </div>

                <div class="form-group">
                    <label for="remember" class="label">
                        <i class="fi fi-br-bookmark"></i>Remember me
                        <input type="checkbox" id="remember" name="remember">
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Login
                </button>
            </form>

            <div class="auth-footer">
                <p class="text-light">Don't have an account?
                    <a href="/register" class="text-primary">Register</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    remember: document.getElementById('remember').value,
                    _token: "{{ csrf_token() }}"
                })
            });

            if (response.ok) {

                // Сохраняем токен
                //localStorage.setItem('token', token);
                //document.cookie = `token=${token}; path=/; max-age=${60 * 60 * 24}`;

                // Перенаправляем
                window.location.href = '/chat';
            } else {
                const alertToastMessage = {'type': 'error', 'message': 'Login failed'};
                callShowToast(alertToastMessage);
            }
        });
    </script>
@endsection
