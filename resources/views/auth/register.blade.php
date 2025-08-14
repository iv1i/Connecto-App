@extends('layouts.app')

@section('content')
    <div class="flex absolute right-0 bg-gray-100 rounded-md p-2 gap-[20px]">
        <button id="header__sun" onclick="" title="Switch to system theme" class="focus:text-yellow-500 text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024"><path fill="currentColor" fill-rule="evenodd" d="M548 818v126c0 8.837-7.163 16-16 16h-40c-8.837 0-16-7.163-16-16V818c15.845 1.643 27.845 2.464 36 2.464c8.155 0 20.155-.821 36-2.464m205.251-115.66l89.096 89.095c6.248 6.248 6.248 16.38 0 22.627l-28.285 28.285c-6.248 6.248-16.379 6.248-22.627 0L702.34 753.25c12.365-10.043 21.431-17.947 27.198-23.713c5.766-5.767 13.67-14.833 23.713-27.198m-482.502 0c10.043 12.365 17.947 21.431 23.713 27.198c5.767 5.766 14.833 13.67 27.198 23.713l-89.095 89.096c-6.248 6.248-16.38 6.248-22.627 0l-28.285-28.285c-6.248-6.248-6.248-16.379 0-22.627zM512 278c129.235 0 234 104.765 234 234S641.235 746 512 746S278 641.235 278 512s104.765-234 234-234m0 72c-89.47 0-162 72.53-162 162s72.53 162 162 162s162-72.53 162-162s-72.53-162-162-162M206 476c-1.643 15.845-2.464 27.845-2.464 36c0 8.155.821 20.155 2.464 36H80c-8.837 0-16-7.163-16-16v-40c0-8.837 7.163-16 16-16zm738 0c8.837 0 16 7.163 16 16v40c0 8.837-7.163 16-16 16H818c1.643-15.845 2.464-27.845 2.464-36c0-8.155-.821-20.155-2.464-36ZM814.062 180.653l28.285 28.285c6.248 6.248 6.248 16.379 0 22.627L753.25 320.66c-10.043-12.365-17.947-21.431-23.713-27.198c-5.767-5.766-14.833-13.67-27.198-23.713l89.095-89.096c6.248-6.248 16.38-6.248 22.627 0m-581.497 0l89.095 89.096c-12.365 10.043-21.431 17.947-27.198 23.713c-5.766 5.767-13.67 14.833-23.713 27.198l-89.096-89.095c-6.248-6.248-6.248-16.38 0-22.627l28.285-28.285c6.248-6.248 16.379-6.248 22.627 0M532 64c8.837 0 16 7.163 16 16v126c-15.845-1.643-27.845-2.464-36-2.464c-8.155 0-20.155.821-36 2.464V80c0-8.837 7.163-16 16-16z"/></svg>
        </button>
        <button id="header__moon" onclick="" title="Switch to light mode" class="focus:text-blue-500 text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024"><path fill="currentColor" fill-rule="evenodd" d="M489.493 111.658c30.658-1.792 45.991 36.44 22.59 56.329C457.831 214.095 426 281.423 426 354c0 134.757 109.243 244 244 244c72.577 0 139.905-31.832 186.014-86.084c19.868-23.377 58.064-8.102 56.332 22.53C900.4 745.823 725.141 912 512.5 912C291.31 912 112 732.69 112 511.5c0-211.39 164.287-386.024 374.198-399.649l.206-.013zm-81.143 79.75l-4.112 1.362C271.1 237.943 176 364.092 176 511.5C176 697.344 326.656 848 512.5 848c148.28 0 274.938-96.192 319.453-230.41l.625-1.934l-.11.071c-47.18 29.331-102.126 45.755-159.723 46.26L670 662c-170.104 0-308-137.896-308-308c0-58.595 16.476-114.54 46.273-162.467z"/></svg>        </button>
    </div>
    <div class="flex items-center justify-center min-h-screen p-[2rem]">
        <div class="bg-(--bg-panel) rounded-lg shadow-md p-[2rem] w-full max-w-md">
            <div class="mb-[1.5rem] text-(--text)">
                <h1 class="text-center">Register</h1>
            </div>

            <form id="registerForm" class="space-y-4">
                <div class="mb-[1rem]">
                    <label for="name" class="flex gap-1 mb-[0.5rem] font-medium"><i class="fi fi-br-pen-clip"></i><span>Name</span></label>
                    <input type="text" id="name" name="name" required class="input">
                </div>

                <div class="mb-[1rem]">
                    <label for="link_name" class="flex gap-1 mb-[0.5rem] font-medium"><i class="fi fi-br-link-alt"></i><span>Link-Name</span></label>
                    <input type="text" id="link_name" name="link_name" placeholder="@link_name" value="" required class="placeholder:text-gray-500 input">
                </div>

                <div class="mb-[1rem]">
                    <label for="email" class="flex gap-1 mb-[0.5rem] font-medium"><i class="fi fi-br-at"></i><span>Email</span></label>
                    <input type="email" id="email" name="email" required class="input">
                </div>

                <div class="mb-[1rem]">
                    <label for="password" class="flex gap-1 mb-[0.5rem] font-medium"><i class="fi fi-br-lock"></i><span>Password</span></label>
                    <input type="password" id="password" name="password" required class="input">
                </div>

                <div class="mb-[1rem]">
                    <label for="password_confirmation" class="flex gap-1 mb-[0.5rem] font-medium"><i class="fi fi-br-lock"></i><span>Confirm Password</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="input">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Register
                </button>
            </form>

            <div class="mt-[1.5rem] text-center text-sm">
                <p class="text-(--text-light)">Already have an account?
                    <a href="/login" class="font-medium text-(--primary)">Login</a>
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
