@extends('layouts.app')

@section('content')
    <div class="flex absolute right-0 dark:bg-gray-900 rounded-md p-2 gap-[20px]">
        <button id="header__sun" onclick="" title="Switch to system theme" class="text-gray-500 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024"><path fill="currentColor" fill-rule="evenodd" d="M548 818v126c0 8.837-7.163 16-16 16h-40c-8.837 0-16-7.163-16-16V818c15.845 1.643 27.845 2.464 36 2.464c8.155 0 20.155-.821 36-2.464m205.251-115.66l89.096 89.095c6.248 6.248 6.248 16.38 0 22.627l-28.285 28.285c-6.248 6.248-16.379 6.248-22.627 0L702.34 753.25c12.365-10.043 21.431-17.947 27.198-23.713c5.766-5.767 13.67-14.833 23.713-27.198m-482.502 0c10.043 12.365 17.947 21.431 23.713 27.198c5.767 5.766 14.833 13.67 27.198 23.713l-89.095 89.096c-6.248 6.248-16.38 6.248-22.627 0l-28.285-28.285c-6.248-6.248-6.248-16.379 0-22.627zM512 278c129.235 0 234 104.765 234 234S641.235 746 512 746S278 641.235 278 512s104.765-234 234-234m0 72c-89.47 0-162 72.53-162 162s72.53 162 162 162s162-72.53 162-162s-72.53-162-162-162M206 476c-1.643 15.845-2.464 27.845-2.464 36c0 8.155.821 20.155 2.464 36H80c-8.837 0-16-7.163-16-16v-40c0-8.837 7.163-16 16-16zm738 0c8.837 0 16 7.163 16 16v40c0 8.837-7.163 16-16 16H818c1.643-15.845 2.464-27.845 2.464-36c0-8.155-.821-20.155-2.464-36ZM814.062 180.653l28.285 28.285c6.248 6.248 6.248 16.379 0 22.627L753.25 320.66c-10.043-12.365-17.947-21.431-23.713-27.198c-5.767-5.766-14.833-13.67-27.198-23.713l89.095-89.096c6.248-6.248 16.38-6.248 22.627 0m-581.497 0l89.095 89.096c-12.365 10.043-21.431 17.947-27.198 23.713c-5.766 5.767-13.67 14.833-23.713 27.198l-89.096-89.095c-6.248-6.248-6.248-16.38 0-22.627l28.285-28.285c6.248-6.248 16.379-6.248 22.627 0M532 64c8.837 0 16 7.163 16 16v126c-15.845-1.643-27.845-2.464-36-2.464c-8.155 0-20.155.821-36 2.464V80c0-8.837 7.163-16 16-16z"/></svg>
        </button>
        <button id="header__moon" onclick="" title="Switch to light mode" class="text-gray-500 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024"><path fill="currentColor" fill-rule="evenodd" d="M489.493 111.658c30.658-1.792 45.991 36.44 22.59 56.329C457.831 214.095 426 281.423 426 354c0 134.757 109.243 244 244 244c72.577 0 139.905-31.832 186.014-86.084c19.868-23.377 58.064-8.102 56.332 22.53C900.4 745.823 725.141 912 512.5 912C291.31 912 112 732.69 112 511.5c0-211.39 164.287-386.024 374.198-399.649l.206-.013zm-81.143 79.75l-4.112 1.362C271.1 237.943 176 364.092 176 511.5C176 697.344 326.656 848 512.5 848c148.28 0 274.938-96.192 319.453-230.41l.625-1.934l-.11.071c-47.18 29.331-102.126 45.755-159.723 46.26L670 662c-170.104 0-308-137.896-308-308c0-58.595 16.476-114.54 46.273-162.467z"/></svg>        </button>
    </div>
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
            <div class="mb-[1.5rem] text-(--text)">
                <h1 class="text-center">Login</h1>
            </div>

            <form id="loginForm" class="space-y-4">
                <div class="mb-[1rem]">
                    <label for="email" class="label"><i class="fi fi-br-at"></i><span>Email</span></label>
                    <input type="email" id="email" name="email" required class="input">
                </div>

                <div class="mb-[1rem]">
                    <label for="password" class="label"><i class="fi fi-br-lock"></i><span>Password</span></label>
                    <input type="password" id="password" name="password" required class="input">
                </div>

                <div class="mb-[1rem]">
                    <label for="remember" class="label">
                        <i class="fi fi-br-bookmark"></i><span>Remember me</span>
                        <input class="ml-auto" type="checkbox" id="remember" name="remember">
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Login
                </button>
            </form>

            <div class="auth-footer">
                <p class="text-(--text-light)">Don't have an account?
                    <a href="/register" class="font-medium text-(--primary)">Register</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const response = await fetch('/api/login', {
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

            const data = await response.json();
            console.log(data);

            if (response.ok) {

                //Сохраняем токен
                localStorage.setItem('token', data.token);
                document.cookie = `token=${data.token}; path=/; max-age=${60 * 60 * 24}`;

                //Перенаправляем
                window.location.href = '/chat';
            } else {
                const alertToastMessage = {'type': 'error', 'message': 'Login failed'};
                callShowToast(alertToastMessage);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.documentElement;
            const moonBtn = document.getElementById('header__moon');
            const sunBtn = document.getElementById('header__sun');

            // Функция для установки темы
            function setTheme(theme) {
                // Удаляем все классы тем
                html.classList.remove('dark', 'light');

                // Добавляем нужный класс
                html.classList.add(theme);

                // Сохраняем в localStorage
                localStorage.setItem('theme', theme);

                // Обновляем title кнопок
                if (theme === 'dark') {
                    sunBtn.title = 'Switch to light mode';
                    sunBtn.classList.add('text-gray-500');
                    sunBtn.classList.remove('text-yellow-500');
                    moonBtn.title = 'Switch to dark theme';
                    moonBtn.classList.add('text-blue-500');
                    moonBtn.classList.remove('text-gray-500');
                } else {
                    sunBtn.title = 'Switch to light theme';
                    sunBtn.classList.add('text-yellow-500');
                    sunBtn.classList.remove('text-gray-500');
                    moonBtn.title = 'Switch to dark mode';
                    moonBtn.classList.add('text-gray-500');
                    moonBtn.classList.remove('text-blue-500');
                }
            }

            // Функция для получения системной темы
            function getSystemTheme() {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }
                return 'light';
            }

            // Обработчики кликов
            moonBtn.addEventListener('click', function() {
                setTheme('dark');
            });

            sunBtn.addEventListener('click', function() {
                setTheme('light');
            });

            // Проверяем сохраненную тему при загрузке
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                setTheme(savedTheme);
            } else {
                // Если нет сохраненной темы, используем системную
                setTheme(getSystemTheme());
            }

            // Слушаем изменения системной темы (опционально)
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                // Меняем тему только если пользователь не выбрал явно свою
                if (!localStorage.getItem('theme')) {
                    setTheme(e.matches ? 'dark' : 'light');
                }
            });
        });
    </script>
@endsection
