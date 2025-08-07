@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="card-header">
                <h1 class="text-center">Login</h1>
            </div>

            <form id="loginForm" class="space-y-4">
                <div class="form-group">
                    <label for="email" class="label">Email</label>
                    <input type="email" id="email" name="email" required class="input">
                </div>

                <div class="form-group">
                    <label for="password" class="label">Password</label>
                    <input type="password" id="password" name="password" required class="input">
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

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('token', data.token);
                    window.location.href = '/chat';
                } else {
                    alert(data.message || 'Login failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
            }
        });
    </script>
@endsection
