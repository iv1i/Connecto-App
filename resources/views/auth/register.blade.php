@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="card-header">
                <h1 class="text-center">Register</h1>
            </div>

            <form id="registerForm" class="space-y-4">
                <div class="form-group">
                    <label for="name" class="label"><i class="fi fi-br-pen-clip"></i>Name</label>
                    <input type="text" id="name" name="name" required class="input">
                </div>

                <div class="form-group">
                    <label for="link_name" class="label"><i class="fi fi-br-link-alt"></i>Link-Name</label>
                    <input type="text" id="link_name" name="link_name" placeholder="@link_name" required class="input">
                </div>

                <div class="form-group">
                    <label for="email" class="label"><i class="fi fi-br-at"></i>Email</label>
                    <input type="email" id="email" name="email" required class="input">
                </div>

                <div class="form-group">
                    <label for="password" class="label"><i class="fi fi-br-lock"></i>Password</label>
                    <input type="password" id="password" name="password" required class="input">
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="label"><i class="fi fi-br-lock"></i>Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="input">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Register
                </button>
            </form>

            <div class="auth-footer">
                <p class="text-light">Already have an account?
                    <a href="/login" class="text-primary">Login</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const link_name = document.getElementById('link_name').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }
            try {
                const encodedToken = getCookie('XSRF-TOKEN');
                const decodedToken = decodeURIComponent(encodedToken);
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': decodedToken
                    },
                    body: JSON.stringify({ name, link_name, email, password, password_confirmation })
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('token', data.token);
                    window.location.href = '/chat';
                } else {
                    alert(data.message || 'Registration failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
            }
        });
    </script>
@endsection
