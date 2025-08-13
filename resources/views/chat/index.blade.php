@extends('layouts.app')
@push('styles')

@endpush

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
    <div class="chat-layout">
        <!-- Sidebar-left -->
        <div id="sidebar-left" class="flex flex-col h-screen w-16 border-r-1 border-(--border) bg-white">

            <div id="sidebar-left-header" class="flex-none border-b-1 border-(--border)">
                <div id="menu" class="text-[25px] p-4">
                    <button id="openSidebarBtn" class="w-full !text-[25px] cursor-pointer">
                        <i class="fi fi-br-menu-burger"></i>
                    </button>
                </div>
            </div>

            <div id="sidebar-left-content" class="grow">
                <button id="createRoomBtn" class="btn btn-primary w-full mt-2 !font-bold" title="Create room">
                    <i class="fi fi-br-magic-wand"></i>
                </button>
                <button id="joinRoomBtn" class="btn btn-secondary w-full mt-2 !font-bold" title="Join private room">
                    <i class="fi fi-br-key"></i>
                </button>
                <button id="PublicRoomList" class="btn btn-secondary w-full mt-2 !font-bold" title="Join public room">
                    <i class="fi fi-br-search"></i>
                </button>
                <button id="friendsListBtn" class="btn btn-secondary w-full mt-2 !font-bold" title="Friends">
                    <i class="fi fi-br-users"></i>
                </button>
            </div>

            <div id="sidebar-left-footer" class="flex-none border-t-1 border-(--border)">
                <div class="flex flex-col items-center gap-2 p-3">
                    <button id="header__sun" onclick="" title="Switch to system theme" class="grow focus:text-yellow-500 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024"><path fill="currentColor" fill-rule="evenodd" d="M548 818v126c0 8.837-7.163 16-16 16h-40c-8.837 0-16-7.163-16-16V818c15.845 1.643 27.845 2.464 36 2.464c8.155 0 20.155-.821 36-2.464m205.251-115.66l89.096 89.095c6.248 6.248 6.248 16.38 0 22.627l-28.285 28.285c-6.248 6.248-16.379 6.248-22.627 0L702.34 753.25c12.365-10.043 21.431-17.947 27.198-23.713c5.766-5.767 13.67-14.833 23.713-27.198m-482.502 0c10.043 12.365 17.947 21.431 23.713 27.198c5.767 5.766 14.833 13.67 27.198 23.713l-89.095 89.096c-6.248 6.248-16.38 6.248-22.627 0l-28.285-28.285c-6.248-6.248-6.248-16.379 0-22.627zM512 278c129.235 0 234 104.765 234 234S641.235 746 512 746S278 641.235 278 512s104.765-234 234-234m0 72c-89.47 0-162 72.53-162 162s72.53 162 162 162s162-72.53 162-162s-72.53-162-162-162M206 476c-1.643 15.845-2.464 27.845-2.464 36c0 8.155.821 20.155 2.464 36H80c-8.837 0-16-7.163-16-16v-40c0-8.837 7.163-16 16-16zm738 0c8.837 0 16 7.163 16 16v40c0 8.837-7.163 16-16 16H818c1.643-15.845 2.464-27.845 2.464-36c0-8.155-.821-20.155-2.464-36ZM814.062 180.653l28.285 28.285c6.248 6.248 6.248 16.379 0 22.627L753.25 320.66c-10.043-12.365-17.947-21.431-23.713-27.198c-5.767-5.766-14.833-13.67-27.198-23.713l89.095-89.096c6.248-6.248 16.38-6.248 22.627 0m-581.497 0l89.095 89.096c-12.365 10.043-21.431 17.947-27.198 23.713c-5.766 5.767-13.67 14.833-23.713 27.198l-89.096-89.095c-6.248-6.248-6.248-16.38 0-22.627l28.285-28.285c6.248-6.248 16.379-6.248 22.627 0M532 64c8.837 0 16 7.163 16 16v126c-15.845-1.643-27.845-2.464-36-2.464c-8.155 0-20.155.821-36 2.464V80c0-8.837 7.163-16 16-16z"/></svg>
                    </button>
                    <button id="header__moon" onclick="" title="Switch to light mode" class="grow focus:text-blue-500 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1024 1024"><path fill="currentColor" fill-rule="evenodd" d="M489.493 111.658c30.658-1.792 45.991 36.44 22.59 56.329C457.831 214.095 426 281.423 426 354c0 134.757 109.243 244 244 244c72.577 0 139.905-31.832 186.014-86.084c19.868-23.377 58.064-8.102 56.332 22.53C900.4 745.823 725.141 912 512.5 912C291.31 912 112 732.69 112 511.5c0-211.39 164.287-386.024 374.198-399.649l.206-.013zm-81.143 79.75l-4.112 1.362C271.1 237.943 176 364.092 176 511.5C176 697.344 326.656 848 512.5 848c148.28 0 274.938-96.192 319.453-230.41l.625-1.934l-.11.071c-47.18 29.331-102.126 45.755-159.723 46.26L670 662c-170.104 0-308-137.896-308-308c0-58.595 16.476-114.54 46.273-162.467z"/></svg>        </button>
                </div>
            </div>

        </div>
        <!-- Sidebar -->
        <div id="sidebar" class="flex flex-col h-screen border-r-1 border-(--border) bg-white">

            <div id="sidebar-header" class="border-b-1 border-(--border) flex-none p-3">
                <input type="text" id="roomSearch" placeholder="Search rooms..." class="input">
            </div>

            <div id="sidebar-content" class=" p-3 grow overflow-auto">
                <div id="roomList" class="space-y-2">
                    <!-- Rooms will be loaded here -->
                </div>
            </div>

            <div id="sidebar-footer" class="border-t-1 border-(--border) flex-none p-3 ">
                <div class="flex items-center gap-2">
                    <div id="userAvatar" class="message-avatar">
                    </div>
                    <div class="UserData">
                        <span id="userName"></span>
                        <span id="userLink"></span>
                    </div>
                    <button id="logoutBtn" class="logout-button">
                        <i class="fi fi-br-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-area">
            <!-- Room Header -->
            <div class="chat-header">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 id="roomName">Select a room</h2>
                        <p id="roomDescription" class="text-light"></p>
                    </div>
                    <div id="roomActions" class="hidden">
                        <button id="logoutRoomBtn" class="btn btn-secondary mr-2 !font-bold" title="Logout room">
                            <i class="fi fi-br-leave"></i>
                        </button>
                        <button id="inviteUsersBtn" class="btn btn-secondary mr-2 !font-bold" title="Invite users">
                            <i class="fi fi-br-person-circle-plus"></i>
                        </button>
                        <button id="deleteRoomBtn" class="btn btn-danger !font-bold" title="Delete room">
                            <i class="fi fi-br-trash-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="messages-container">
                <div id="messages" class="space-y-4">
                    <!-- Messages will be loaded here -->
                </div>
            </div>

            <!-- Message Input -->
            <div id="messageInputContainer" class="message-input-container hidden">
                <form id="messageForm" class="flex gap-2">
                    <input type="text" id="messageInput" placeholder="Type a message..." class="input flex-grow" autocomplete="off">
                    <button type="submit" class="btn btn-primary">
                        Send
                    </button>
                </form>
            </div>
        </div>

        <div id="joinRoomModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Join Private Room</h2>
                </div>
                <form id="joinRoomForm">
                    <div class="form-group">
                        <label for="inviteCodeInput" class="label">Invite Code</label>
                        <input type="text" id="inviteCodeInput" name="invite_code" required class="input w-full">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="cancelJoinRoom" class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Join
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div id="inviteUsersModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Invite Users to Room</h2>
                </div>
                <div class="p-4">
                    <div class="form-group">
                        <label class="label">Invite Link</label>
                        <div class="flex">
                            <input type="text" id="inviteLinkInput" readonly class="input flex-grow">
                            <button id="copyInviteLinkBtn" class="btn btn-secondary ml-2">
                                Copy
                            </button>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="label">Invite by Username</label>
                        <div class="flex">
                            <input type="text" id="usernameInput" placeholder="Enter username" class="input flex-grow">
                            <button id="inviteUserBtn" class="btn btn-primary ml-2">
                                Invite
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeInviteModal" class="btn btn-secondary">
                        Close
                    </button>
                </div>
            </div>
        </div>
        <!-- friendsModal -->
        <div id="friendsModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Friends</h2>
                </div>
                <div class="p-4">
                    <div id="pendingFriends" class="flex flex-col gap-2">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Friend request</h3>
                        </div>
                        <!-- Приглашения в друзья -->
                        <div id="pendingFriendsList" class="space-y-2 mb-4">
                            <!-- Pending friends will be loaded here -->
                        </div>
                    </div>
                    <div>
                        <!-- Кнопка добавления друга -->
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">My Friends</h3>
                            <button id="addFriendBtn" class="btn btn-primary !font-bold">
                                <i class="fi fi-br-user-add"></i> Add Friend
                            </button>
                        </div>
                        <!-- Список друзей -->
                        <div id="friendsList" class="space-y-2">
                            <!-- Friends will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeFriendsModal" class="btn btn-secondary">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Модальное окно для добавления друзей -->
        <div id="addFriendModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Add New Friend</h2>
                </div>
                <div class="p-4">
                    <div class="form-group mb-4">
                        <input type="text" id="friendSearchInput" placeholder="Search users..." class="input w-full">
                    </div>
                    <div id="searchResults" class="space-y-2">
                        <!-- Search results will appear here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeAddFriendModal" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        <!-- Create Room Modal -->
        <div id="createRoomModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Create New Room</h2>
                </div>
                <form id="createRoomForm">
                    <div class="form-group">
                        <label for="roomNameInput" class="label">Room Name</label>
                        <input type="text" id="roomNameInput" name="name" required class="input w-full">
                    </div>

                    <div class="form-group">
                        <label for="roomDescriptionInput" class="label">Description</label>
                        <textarea id="roomDescriptionInput" name="description" rows="3" class="input w-full"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="label">Room Type</label>
                        <div class="radio-group">
                            <label class="radio-item">
                                <input type="radio" name="type" value="public" checked class="radio-input">
                                <span>Public</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="type" value="private" class="radio-input">
                                <span>Private</span>
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="cancelCreateRoom" class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div id="publicRoomsModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Public Rooms</h2>
                </div>
                <div class="p-4 overflow-hidden">
                    <div class="form-group mb-4">
                        <input type="text" id="publicRoomSearch" placeholder="Search public rooms..." class="input w-full">
                    </div>
                    <div id="publicRoomsList" class="space-y-2 overflow-auto h-[200px]">
                        <!-- Public rooms will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closePublicRoomsModal" class="btn btn-secondary">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script id="v1.1.1">
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            const encodedToken = getCookie('XSRF-TOKEN');
            const decodedToken = decodeURIComponent(encodedToken);
            const currentRoom = localStorage.getItem('roomId');

            // Инициализация
            let currentRoomId = null;
            let allMessages = [];
            let userData = null;
            const unreadRooms = {}

            // DOM элементы
            const messagesContainer = document.getElementById('messages');
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const roomNameElement = document.getElementById('roomName');
            const roomDescriptionElement = document.getElementById('roomDescription');
            const messageInputContainer = document.getElementById('messageInputContainer');

            // Инициализация приложения
            initApp();

            Echo.private(`room`).listen('MessageSentEvent', (e) => {
                console.log(e.message);
                if (e.message.user.id !== userData.id){
                    changeRoomMessagesCount(e.message.chat_room_id, 1);
                    if (String(e.message.chat_room_id) === localStorage.getItem('roomId')){
                        // Добавляем обработчик контекстного меню для новых сообщений
                        addMessageToUI(e.message);
                    }
                    if (String(e.message.chat_room_id) !== localStorage.getItem('roomId')) {
                        unreadRooms[e.message.chat_room_id] = true;
                        updateUnreadIndicators();
                    }
                    document.title = "Connecto-app (*)";
                    console.log('new message!')
                }
            });

            Echo.private(`deleted-message`).listen('MessageDellEvent', (e) => {
                if (e.message.user.id !== userData.id){
                    updateRoomMessageCount(e.message.chat_room_id, -1)
                    unreadRooms[e.message.chat_room_id] = false;
                    updateUnreadIndicators();
                    if (String(e.message.chat_room_id) === localStorage.getItem('roomId')){
                        document.getElementById(`message-${e.message.id}`).remove();
                    }
                    console.log('delete message!')
                }
            });

            Echo.private(`reaction-add`).listen('ReactionEvent', (e) => {
                if (String(e.message.chat_room_id) === localStorage.getItem('roomId')) {
                    updateMessageReactions(e.message.id, e.message.reactions);
                }
            });

            function updateUnreadIndicators() {
                Object.keys(unreadRooms).forEach(roomId => {
                    const span = document.getElementById(`newMessagesSpan-${roomId}`);
                    if (span && unreadRooms[roomId]) {
                        span.innerHTML = '<i id="" class="newMessages fi fi-br-envelope-dot"></i>';
                    }
                    if (span && !unreadRooms[roomId]) {
                        const roomElement = document.getElementById(`newMessagesSpan-${roomId}`);
                        const iconElement = roomElement.querySelector('i.newMessages');
                        if (iconElement) {
                            iconElement.remove();
                        }
                    }
                });
            }

            function changeRoomMessagesCount(roomId, change) {
                const countElement = document.querySelector(`.room-messages-count-${roomId}`);

                if (countElement) {
                    const currentText = countElement.textContent;
                    const currentMatch = currentText.match(/\d+/);

                    if (currentMatch) {
                        const currentCount = parseInt(currentMatch[0]);
                        const newCount = currentCount + change;
                        const suffix = currentText.replace(/^\d+\s*/, '');
                        countElement.textContent = `${newCount} ${suffix}`.trim();
                    }
                }
            }

            async function initApp() {
                await loadUser();
                await loadRooms();
                if (currentRoom) {
                    await joinRoom(currentRoom);
                }
                setupEventListeners();
            }

            function setupEventListeners() {
                // Отправка сообщения
                messageForm.addEventListener('submit', handleSendMessage);

                // Поиск комнат
                document.getElementById('roomSearch').addEventListener('input', debounce(searchRooms, 300));

                // Создание комнаты
                document.getElementById('createRoomBtn').addEventListener('click', showCreateRoomModal);
                document.getElementById('cancelCreateRoom').addEventListener('click', hideCreateRoomModal);
                document.getElementById('createRoomForm').addEventListener('submit', handleCreateRoom);

                // Присоединение к комнате по коду
                document.getElementById('joinRoomBtn').addEventListener('click', showJoinRoomModal);
                document.getElementById('cancelJoinRoom').addEventListener('click', hideJoinRoomModal);
                document.getElementById('joinRoomForm').addEventListener('submit', handleJoinRoom);

                // Присоединение к публичной комнате
                document.getElementById('PublicRoomList').addEventListener('click', showPublicRoomsModal);
                document.getElementById('closePublicRoomsModal').addEventListener('click', hidePublicRoomsModal);
                document.getElementById('publicRoomSearch').addEventListener('input', debounce(searchPublicRooms, 300));

                // Управление комнатой
                document.getElementById('inviteUsersBtn').addEventListener('click', showInviteUsersModal);
                document.getElementById('deleteRoomBtn').addEventListener('click', deleteCurrentRoom);
                document.getElementById('logoutRoomBtn').addEventListener('click', logoutCurrentRoom);
                document.getElementById('closeInviteModal').addEventListener('click', hideInviteUsersModal);
                document.getElementById('copyInviteLinkBtn').addEventListener('click', copyInviteLink);
                document.getElementById('inviteUserBtn').addEventListener('click', inviteUserByUsername);

                // Обработчик для кнопки друзей
                document.getElementById('friendsListBtn').addEventListener('click', showFriendsModal);
                document.getElementById('closeFriendsModal').addEventListener('click', hideFriendsModal);

                // Обработчики модальных окон
                document.getElementById('addFriendBtn').addEventListener('click', showAddFriendModal);
                document.getElementById('closeAddFriendModal').addEventListener('click', hideAddFriendModal);

                // Поиск пользователей
                document.getElementById('friendSearchInput').addEventListener('input', debounce(searchUsers, 300));

                // Обработчик для кнопки добавления в друзья
                document.addEventListener('click', async function(e) {
                    if (e.target.closest('.add-friend-btn')) {
                        const userId = e.target.closest('.add-friend-btn').dataset.userId;
                        await sendFriendRequest(userId);
                    }
                });

                // Обработчик для создания личного чата
                document.addEventListener('click', async function(e) {
                    if (e.target.closest('.start-chat-btn')) {
                        const friendId = e.target.closest('.start-chat-btn').dataset.friendId;
                        await createPrivateChat(friendId);
                    }
                });

                // Обработчик кнопки принятия друга
                document.addEventListener('click', async function(e) {
                    if (e.target.closest('.accept-friend-btn')) {
                        const friendId = e.target.closest('.accept-friend-btn').dataset.friendId;
                        await acceptFriendRequest(friendId);
                    }
                });

                document.addEventListener('click', async function(e) {
                    if (e.target.closest('.delete-friend-btn')) {
                        const friendId = e.target.closest('.delete-friend-btn').dataset.friendId;
                        await deleteFriendRequest(friendId);
                    }
                });

                // Выход
                document.getElementById('logoutBtn').addEventListener('click', logout);
            }

            // Функция для дебаунса
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            function showPublicRoomsModal() {
                document.getElementById('publicRoomsModal').classList.add('active');
                loadPublicRooms();
                document.getElementById('publicRoomSearch').focus();
                document.getElementById('publicRoomSearch').value = '';
            }

            function hidePublicRoomsModal() {
                document.getElementById('publicRoomsModal').classList.remove('active');
            }

            function showAddFriendModal() {
                document.getElementById('addFriendModal').classList.add('active');
                document.getElementById('friendSearchInput').focus();
                document.getElementById('friendSearchInput').value = '';

                document.getElementById('searchResults').innerHTML = '';
            }

            function hideAddFriendModal() {
                document.getElementById('addFriendModal').classList.remove('active');
            }

            // Поиск пользователей
            async function searchUsers(e) {
                const query = e.target.value.trim();
                if (query.length < 2) {
                    document.getElementById('searchResults').innerHTML = '';
                    return;
                }

                try {
                    const response = await fetch(`/api/users/search?query=${encodeURIComponent(query)}`, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        renderSearchResults(data.data);
                    }
                } catch (error) {
                    console.error('Error searching users:', error);
                }
            }

            // Отображение результатов поиска
            function renderSearchResults(users) {
                const resultsContainer = document.getElementById('searchResults');
                resultsContainer.innerHTML = '';

                if (users.length === 0) {
                    resultsContainer.innerHTML = '<div class="text-gray-500">No users found</div>';
                    return;
                }

                users.forEach(user => {
                    const userElement = document.createElement('div');
                    userElement.className = 'flex items-center justify-between p-2 hover:bg-gray-100 rounded-md';
                    userElement.innerHTML = `
            <div class="flex items-center gap-2">
                <div class="message-avatar" style="background-color: ${user.name_color}">
                    ${user.name.charAt(0).toUpperCase()}
                </div>
                <span>${user.name}</span>
            </div>
            <button class="btn btn-secondary add-friend-btn" data-user-id="${user.id}">
                <i class="fi fi-br-user-add"></i>
            </button>
        `;
                    resultsContainer.appendChild(userElement);
                });
            }

            // Загрузка публичных комнат
            async function loadPublicRooms() {
                try {
                    const response = await fetch('/api/rooms', {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        renderPublicRoomsList(data.data);
                    }
                } catch (error) {
                    console.error('Error loading public rooms:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'Error loading public rooms'};
                    callShowToast(alertToastMessage);
                }
            }

            // Поиск публичных комнат
            async function searchPublicRooms(e) {
                const query = e.target.value.trim();

                try {
                    const url = query.length >= 2
                        ? `/api/rooms/search?query=${encodeURIComponent(query)}`
                        : '/api/rooms/search';

                    const response = await fetch(url, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        renderPublicRoomsList(data.data);
                    }
                } catch (error) {
                    console.error('Error searching public rooms:', error);
                }
            }

            // Отправка запроса на дружбу
            async function sendFriendRequest(userId) {
                try {
                    const response = await fetch(`api/friends/${userId}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                        body: JSON.stringify({ friend_id: userId })
                    });

                    if (response.ok) {
                        const alertToastMessage = {'type': 'success', 'message': 'Friend added'};
                        callShowToast(alertToastMessage);
                        hideAddFriendModal();
                        await loadFriends(); // Обновляем список друзей
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to send request'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error sending friend request:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred'};
                    callShowToast(alertToastMessage);
                }
            }

            async function acceptFriendRequest(userId) {
                try {
                    const response = await fetch(`api/friends/${userId}`, {
                        method: 'PUT',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                        body: JSON.stringify({ friend_id: userId })
                    });

                    if (response.ok) {
                        const alertToastMessage = {'type': 'success', 'message': 'Friend accepted'};
                        callShowToast(alertToastMessage);
                        hideAddFriendModal();
                        await loadFriends(); // Обновляем список друзей
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to accepted friend'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error sending friend request:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred'};
                    callShowToast(alertToastMessage);
                }
            }

            async function deleteFriendRequest(userId) {
                try {
                    const response = await fetch(`api/friends/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                        body: JSON.stringify({ friend_id: userId })
                    });

                    if (response.ok) {
                        const alertToastMessage = {'type': 'success', 'message': 'Friend deleted'};
                        callShowToast(alertToastMessage);
                        hideAddFriendModal();
                        await loadFriends(); // Обновляем список друзей
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to delete friend'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error sending friend request:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred'};
                    callShowToast(alertToastMessage);
                }
            }

            // Функция присоединения к публичной комнате
            async function joinPublicRoom(roomId) {
                try {
                    hidePublicRoomsModal();
                    showLoadingMessages();
                    document.title = "Connecto-app";
                    const [roomResponse, messagesResponse] = await Promise.all([
                        fetch(`/api/rooms/${roomId}/join`, {
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Accept': 'application/json',
                                'X-XSRF-TOKEN': decodedToken
                            }
                        }),
                        fetch(`/api/rooms/${roomId}/messages`, {
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Accept': 'application/json',
                                'X-XSRF-TOKEN': decodedToken
                            }
                        })
                    ]);

                    if (!roomResponse.ok || !messagesResponse.ok) {
                        throw new Error('Failed to load room data');
                    }

                    const room = await roomResponse.json();
                    const messages = await messagesResponse.json();

                    currentRoomId = roomId;
                    unreadRooms[currentRoomId] = false;
                    updateUnreadIndicators();
                    localStorage.setItem('roomId', roomId);

                    updateRoomUI(room.room);
                    await loadRooms();
                    if (messages.data.length === 0){
                        showNopeMessages();
                    }
                    else {
                        renderMessages(messages.data);
                    }

                    messageInputContainer.classList.remove('hidden');
                    messageInput.focus();
                } catch (error) {
                    messagesContainer.innerHTML = `<div class="error-loading-room"><i class="fi fi-br-bug-slash"></i> Error loading room</div>`;
                }
            }

            // Загрузка пользователя
            async function loadUser() {
                try {
                    const response = await fetch('/api/profile', {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        userData = await response.json();
                        document.getElementById('userAvatar').textContent = userData.name.charAt(0).toUpperCase();
                        document.getElementById('userAvatar').style.backgroundColor = userData.name_color; // Красный цвет
                        document.getElementById('userName').textContent = userData.name;
                        document.getElementById('userLink').textContent = userData.link_name;
                    } else {
                        throw new Error('Failed to load user data');
                    }
                } catch (error) {
                    const alertToastMessage = {'type': 'error', 'message': error};
                    callShowToast(alertToastMessage);
                }
            }

            // Загрузка комнат
            async function loadRooms() {
                try {
                    const response = await fetch('/api/rooms/joined', {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        renderRoomList(data);
                        //renderPrivateChatsList(data.data.private_chats);
                    }
                } catch (error) {
                    console.error(error);
                    const alertToastMessage = {'type': 'error', 'message': 'Error loading rooms'};
                    callShowToast(alertToastMessage);
                }
            }

            // Поиск комнат
            async function searchRooms(e) {
                const query = e.target.value.trim();
                if (query.length < 2) {
                    await loadRooms();
                    return;
                }

                try {
                    const response = await fetch(`/api/rooms/search?query=${encodeURIComponent(query)}`, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        renderRoomList(data.data);
                    }
                } catch (error) {
                    const alertToastMessage = {'type': 'error', 'message': 'Error searching rooms'};
                    callShowToast(alertToastMessage);
                }
            }

            // Отображение списка комнат
            function renderRoomList(rooms) {
                const roomList = document.getElementById('roomList');
                const publicRoomList = document.getElementById('publicRoomsList');
                roomList.innerHTML = '';
                publicRoomList.innerHTML = '';
                //renderPublicRoomsList(rooms);
                rooms.forEach(room => {
                    const roomElement = document.createElement('div');
                    const publicRoomElement = document.createElement('div');

                    roomElement.className = 'p-2 hover:bg-gray-100 rounded-md cursor-pointer';
                    roomElement.dataset.roomId = room.id;
                    roomElement.innerHTML = `
            <h3 class="font-medium">${room.name} <span id="newMessagesSpan-${room.id}"></span> ${room.type === 'private' ? '<span class="private-chat">Private</span>' : '<span class="public-chat">Public</span>'}
</h3>
            <p class="room-messages-count-${room.id} text-sm text-gray-500">${room.messages_count} messages</p>
        `;
                    roomElement.addEventListener('click', () => joinRoom(room.id));
                    roomList.appendChild(roomElement);
                });
            }

            // Функция для отображения приватных чатов
            function renderPrivateChatsList(chats) {
                const roomList = document.getElementById('roomList');

                chats.forEach(chat => {
                    const otherUser = chat.users.find(u => u.id !== userData.id);
                    const roomElement = document.createElement('div');
                    roomElement.className = 'p-2 hover:bg-gray-100 rounded-md cursor-pointer';
                    roomElement.dataset.roomId = `private_${chat.id}`;
                    roomElement.innerHTML = `
            <h3 class="font-medium">${otherUser.name} <span class="private-chat">Private</span></h3>
            <p class="room-messages-count-${chat.id} text-sm text-gray-500">${chat.messages_count} messages</p>
        `;

                    roomElement.addEventListener('click', () => joinRoom(`private_${chat.id}`));
                    roomList.appendChild(roomElement);
                });
            }

            function renderPublicRoomsList(rooms){
                const publicRoomList = document.getElementById('publicRoomsList');
                publicRoomList.innerHTML = '';

                if (rooms.length === 0) {
                    publicRoomList.innerHTML = '<div class="text-gray-500">No public rooms found</div>';
                    return;
                }
                //renderPublicRoomsList(rooms);
                rooms.forEach(room => {
                    const publicRoomElement = document.createElement('div');

                    if (room.type === 'public') {
                        publicRoomElement.className = 'flex items-center justify-between p-2 hover:bg-gray-100 rounded-md';
                        publicRoomElement.dataset.roomId = room.id;
                        publicRoomElement.innerHTML = `
                <h3 class="font-medium">${room.name} <span id="newMessagesSpan-${room.id}"></span> ${room.type === 'private' ? '<span class="private-chat">Private</span>' : '<span class="public-chat">Public</span>'}
</h3>
                <p class="room-messages-count-${room.id} text-sm text-gray-500">${room.messages_count} messages</p>`;
                        publicRoomElement.addEventListener('click', () => joinPublicRoom(room.id));
                        publicRoomList.appendChild(publicRoomElement);
                    }

                });
            }

            // Присоединение к комнате
            async function joinRoom(roomId) {
                try {
                    showLoadingMessages();
                    document.title = "Connecto-app";
                    const [roomResponse, messagesResponse] = await Promise.all([
                        fetch(`/api/rooms/${roomId}`, {
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Accept': 'application/json',
                                'X-XSRF-TOKEN': decodedToken
                            }
                        }),
                        fetch(`/api/rooms/${roomId}/messages`, {
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Accept': 'application/json',
                                'X-XSRF-TOKEN': decodedToken
                            }
                        })
                    ]);

                    if (!roomResponse.ok || !messagesResponse.ok) {
                        throw new Error('Failed to load room data');
                    }

                    const room = await roomResponse.json();
                    const messages = await messagesResponse.json();

                    currentRoomId = roomId;
                    unreadRooms[currentRoomId] = false;
                    updateUnreadIndicators();
                    localStorage.setItem('roomId', roomId);

                    updateRoomUI(room.room)
                    if (messages.data.length === 0){
                        showNopeMessages();
                    }
                    else {
                        renderMessages(messages.data);
                    }

                    messageInputContainer.classList.remove('hidden');
                    messageInput.focus();
                } catch (error) {
                    messagesContainer.innerHTML = `<div class="error-loading-room"><i class="fi fi-br-bug-slash"></i> Error loading room</div>`;
                }
            }

            // Создание личного чата
            async function createPrivateChat(friendId) {
                try {
                    const response = await fetch(`/api/rooms/friend/${friendId}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                    });

                    if (response.ok) {
                        const chat = await response.json();
                        hideFriendsModal();
                        await joinRoom(chat.id);
                        await loadRooms();
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to create chat'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error creating private chat:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred while creating chat'};
                    callShowToast(alertToastMessage);
                }
            }

            // Присоединение к личному чату
            async function joinPrivateChat(chatId) {
                try {
                    showLoadingMessages();

                    const response = await fetch(`/api/private-chats/${chatId}/messages`, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const messages = await response.json();
                        currentRoomId = `private_${chatId}`;
                        localStorage.setItem('roomId', currentRoomId);

                        roomNameElement.textContent = 'Private Chat';
                        roomDescriptionElement.textContent = '';

                        if (messages.data.length === 0) {
                            showNopeMessages();
                        } else {
                            renderMessages(messages.data);
                        }

                        messageInputContainer.classList.remove('hidden');
                        document.getElementById('roomActions').classList.add('hidden');
                        messageInput.focus();
                    }
                } catch (error) {
                    messagesContainer.innerHTML = `<div class="error-loading-room"><i class="fi fi-br-bug-slash"></i> Error loading chat</div>`;
                }
            }

            // Новая функция для загрузки друзей
            async function loadFriends() {
                try {
                    const [friendsResponse, pendingFriendsResponse] = await Promise.all([
                        fetch(`/api/friends`, {
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Accept': 'application/json',
                                'X-XSRF-TOKEN': decodedToken
                            }
                        }),
                        fetch(`/api/friends/pending`, {
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Accept': 'application/json',
                                'X-XSRF-TOKEN': decodedToken
                            }
                        })
                    ]);

                    if (friendsResponse.ok) {
                        const friends = await friendsResponse.json();
                        renderFriendsList(friends.data);
                    }
                    else {
                        const friendsList = document.getElementById('friendsList');
                        friendsList.innerHTML = '<div class="text-gray-500">No friends found</div>';
                    }
                    if (pendingFriendsResponse.ok){
                        const pendingFriends = await pendingFriendsResponse.json();
                        renderPendingFriendsList(pendingFriends.data);
                    }
                    else {
                        const friendsList = document.getElementById('pendingFriendsList');
                        friendsList.innerHTML = '<div class="text-gray-500">No friend request</div>';
                    }
                } catch (error) {
                    console.error('Error loading friends:', error);
                }
            }

            // Отображение списка друзей

            function renderFriendsList(friends) {
                const friendsList = document.getElementById('friendsList');
                friendsList.innerHTML = '';

                friends.forEach(friend => {
                    if (friend.pivot.status === 'accepted') {
                        const friendElement = document.createElement('div');
                        friendElement.className = 'flex items-center justify-between p-2 hover:bg-gray-100 rounded-md';
                        friendElement.innerHTML = `
            <div class="flex items-center gap-2">
                <div class="message-avatar" style="background-color: ${friend.name_color}">
                    ${friend.name.charAt(0).toUpperCase()}
                </div>
                <span>${friend.name}</span>
            </div>
<div>
            <button class="btn btn-secondary hover:bg-blue-200 start-chat-btn" data-friend-id="${friend.id}">
                <i class=" fi fi-br-comment-alt"></i>
            </button>
            <button class="btn btn-secondary hover:bg-red-200 delete-friend-btn" data-friend-id="${friend.id}">
                <i class=" fi fi-br-trash-xmark"></i>
            </button>
</div>
        `;
                        friendsList.appendChild(friendElement);
                    }
                });
            }

            function renderPendingFriendsList(friends) {
                const pendingFriendsList = document.getElementById('pendingFriendsList');
                const pendingFriends = document.getElementById('pendingFriends');
                pendingFriends.classList.add('hidden');
                pendingFriendsList.innerHTML = '';

                friends.forEach(friend => {
                    if (friend.pivot.status === 'pending'){
                        pendingFriends.classList.remove('hidden');
                        const pendingFriendElement = document.createElement('div');
                        pendingFriendElement.className = 'flex items-center justify-between p-2 hover:bg-gray-100 rounded-md';
                        pendingFriendElement.innerHTML = `
            <div class="flex items-center gap-2">
                <div class="message-avatar" style="background-color: ${friend.name_color}">
                    ${friend.name.charAt(0).toUpperCase()}
                </div>
                <span>${friend.name}</span>
            </div>
            <button class="accept-friend-btn" data-friend-id="${friend.id}">
                <i class="fi fi-br-check"></i>
            </button>
        `;
                        pendingFriendsList.appendChild(pendingFriendElement);
                    }
                });
            }

            function showLoadingMessages() {
                messagesContainer.innerHTML = '<div class="loading"><i class="fi fi-br-loading"></i></div>';
            }

            function showNopeMessages() {
                messagesContainer.innerHTML = '<div id="nope-messages" class="nope-messages"><i class="fi fi-br-message-slash"></i>Nope messages</div>';
            }
            function removeShowNopeMessages(){
                const nopeMessages = document.getElementById(`nope-messages`);
                if (nopeMessages){
                    nopeMessages.remove();
                }
            }

            function showFriendsModal() {
                document.getElementById('friendsModal').classList.add('active');
                loadFriends();
            }

            function hideFriendsModal() {
                document.getElementById('friendsModal').classList.remove('active');
            }

            function updateRoomUI(room) {
                roomNameElement.textContent = room.name;
                roomDescriptionElement.textContent = room.description || 'No description';
                room.is_owner = room.created_by === userData.id;

                // Всегда показываем кнопку выхода
                const logoutRoomBtn = document.getElementById('logoutRoomBtn');
                logoutRoomBtn.classList.remove('hidden');

                // Кнопка приглашения - только для владельца приватной комнаты
                const inviteUsersBtn = document.getElementById('inviteUsersBtn');
                if (room.type === 'private' && room.is_owner && room.actions.invite !== 'none') {
                    inviteUsersBtn.classList.remove('hidden');
                } else {
                    inviteUsersBtn.classList.add('hidden');
                }

                // Кнопка удаления - для владельца или если разрешено всем
                const deleteRoomBtn = document.getElementById('deleteRoomBtn');
                if (room.is_owner || room.actions.delete === 'all') {
                    deleteRoomBtn.classList.remove('hidden');
                } else {
                    deleteRoomBtn.classList.add('hidden');
                }

                // Показываем контейнер действий, если хотя бы одна кнопка видима
                const roomActions = document.getElementById('roomActions');
                if (!logoutRoomBtn.classList.contains('hidden') ||
                    !inviteUsersBtn.classList.contains('hidden') ||
                    !deleteRoomBtn.classList.contains('hidden')) {
                    roomActions.classList.remove('hidden');
                } else {
                    roomActions.classList.add('hidden');
                }
            }

            // Отображение сообщений
            function renderMessages(messages) {
                allMessages = messages.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                messagesContainer.innerHTML = '';

                allMessages.forEach(message => {
                    addMessageToUI(message);
                });

                scrollToBottom();
            }

            // Закрытие всех контекстных меню
            function closeAllContextMenus() {
                const menus = document.querySelectorAll('.context-menu-container');
                menus.forEach(menu => menu.remove());
                document.removeEventListener('click', handleOutsideClick);
            }

            // Обработка кликов вне меню
            function handleOutsideClick(e) {
                const reactionMenu = document.querySelector('.context-menu-container');

                if (!reactionMenu?.contains(e.target)) {
                    closeAllContextMenus();
                }
            }

            function showContextMenu(e, messageId) {
                closeAllContextMenus();
                e.preventDefault();

                // Ищем сообщение в allMessages или в DOM
                let message = allMessages.find(m => m.id == messageId);

                if (!message) {
                    // Если сообщение не найдено в allMessages, попробуем получить данные из DOM
                    const messageElement = document.getElementById(`message-${messageId}`);
                    if (messageElement) {
                        message = {
                            id: messageId,
                            user_id: messageElement.querySelector('.own-message') ? userData.id : null,
                            content: messageElement.querySelector('.message-text')?.textContent || '',
                            user: {
                                id: messageElement.querySelector('.own-message') ? userData.id : null,
                                name: messageElement.querySelector('.message-username')?.textContent || '',
                                name_color: messageElement.querySelector('.message-avatar')?.style.backgroundColor || '#ccc'
                            },
                            // Добавляем пустые реакции по умолчанию
                            reactions: {},
                            user_reactions: []
                        };
                    }
                }

                if (!message) {
                    console.error('Message not found:', messageId);
                    return;
                }

                const menuContainer = document.createElement('div');
                menuContainer.className = 'context-menu-container';

                // Меню реакций
                const reactionsMenu = document.createElement('div');
                reactionsMenu.className = 'reactions-menu';

                const reactions = ['like', 'love', 'laugh', 'wow', 'sad', 'angry', 'fire', 'star', 'clap', 'rocket'];
                reactions.forEach(reaction => {
                    const option = document.createElement('button');
                    option.className = 'reaction-option';
                    option.innerHTML = getReactionEmoji(reaction);
                    option.title = reaction;
                    option.addEventListener('click', () => {
                        addReaction(messageId, reaction);
                        closeAllContextMenus();
                    });
                    reactionsMenu.appendChild(option);
                });

                // Меню действий
                const actionsMenu = document.createElement('div');
                actionsMenu.className = 'actions-menu';

                const actions = [
                    { icon: 'fi fi-br-undo', text: 'Ответить', action: () => replyToMessage(messageId) },
                    { icon: 'fi fi-br-thumbtack', text: 'Закрепить', action: () => pinMessage(messageId) },
                    { icon: 'fi fi-br-copy', text: 'Копировать текст', action: () => copyMessageText(message.content) }
                ];

                if (message.user_id === userData.id) {
                    actions.push({
                        icon: 'fi fi-br-trash',
                        text: 'Удалить',
                        action: () => deleteMessage(messageId)
                    });
                }

                actions.forEach(action => {
                    const item = document.createElement('button');
                    item.className = 'action-item';
                    item.innerHTML = `<i class="${action.icon}"></i> ${action.text}`;
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        action.action();
                        closeAllContextMenus();
                    });
                    actionsMenu.appendChild(item);
                });

                menuContainer.appendChild(reactionsMenu);
                menuContainer.appendChild(actionsMenu);
                document.body.appendChild(menuContainer);

                positionMenuContainer(menuContainer, e.clientX, e.clientY);

                document.addEventListener('click', handleOutsideClick);
            }

            function positionMenuContainer(menu, clientX, clientY) {
                const menuWidth = menu.offsetWidth;
                const menuHeight = menu.offsetHeight;
                const windowWidth = window.innerWidth;
                const windowHeight = window.innerHeight;
                const scrollY = window.scrollY || window.pageYOffset;

                let adjustedX = clientX;
                let adjustedY = clientY;

                if (clientX + menuWidth > windowWidth) {
                    adjustedX = windowWidth - menuWidth - 10;
                } else if (clientX < 10) {
                    adjustedX = 10;
                }

                if (clientY + menuHeight > windowHeight + scrollY) {
                    adjustedY = windowHeight + scrollY - menuHeight - 80;
                } else if (clientY < scrollY + 10) {
                    adjustedY = scrollY + 30;
                }

                menu.style.left = `${adjustedX}px`;
                menu.style.top = `${adjustedY}px`;
            }

            function replyToMessage(messageId) {
                const message = allMessages.find(m => m.id == messageId);
                if (!message) return;

                messageInput.value = `@${message.user.name} `;
                messageInput.focus();
                // Можно добавить визуальное выделение сообщения, на которое отвечаем
            }

            function pinMessage(messageId) {
                // Реализуйте логику закрепления сообщения
                console.log(`Pinning message ${messageId}`);
                // Отправка запроса на сервер для закрепления
            }

            function copyMessageText(text) {
                navigator.clipboard.writeText(text).then(() => {
                    console.log('Text copied to clipboard');
                    // Можно показать уведомление об успешном копировании
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                });
            }

            // Добавление сообщения в UI
            function addMessageToUI(message, prepend = false) {
                if (message.error){
                    const alertToastMessage = {'type': 'error', 'message': message.error};
                    callShowToast(alertToastMessage);
                }
                const messageElement = document.createElement('div');
                messageElement.addEventListener('contextmenu', (e) => {
                    e.preventDefault();
                    showContextMenu(e, message.id);
                });

                messageElement.className = `MSG ${prepend ? 'prepend-message' : ''}`;
                messageElement.id = `message-${message.id}`;
                removeShowNopeMessages();
                // Формируем HTML для реакций
                const reactionsHTML = message.reactions && Object.keys(message.reactions).length > 0
                    ? Object.entries(message.reactions).map(([type, count]) => {
                        const isUserReaction = message.user_reactions.includes(type);
                        return `
                <span class="reaction-badge ${isUserReaction ? 'user-reaction' : ''}"
                      data-reaction="${type}"
                      data-message-id="${message.id}">
                    ${getReactionEmoji(type)} ${count > 1 ? count : ''}
                </span>
            `;
                    }).join('')
                    : '';

                messageElement.innerHTML = `
        <div class="message-avatar" style="background-color: ${message.user.name_color}">
            ${message.user.name.charAt(0).toUpperCase()}
        </div>
        <div class="message-content ${message.user_id === userData.id ? 'own-message' : 'other-message'}">
            <div class="message-header">
                <span class="message-username">${message.user.name}</span>
                <span class="message-time">${formatDate(message.created_at)}</span>
            </div>
            <p class="message-text">${message.content}</p>
            <div class="message-reactions" id="reactions-${message.id}">
                ${reactionsHTML}
            </div>
        </div>
    `;

                if (prepend) {
                    messagesContainer.prepend(messageElement);
                } else {
                    messagesContainer.appendChild(messageElement);
                    scrollToBottom();
                }

                // Обновляем обработчики кликов
                messageElement.querySelectorAll('.reaction-badge').forEach(badge => {
                    badge.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const messageId = badge.dataset.messageId;
                        const reaction = badge.dataset.reaction;
                        handleReactionClick(messageId, reaction);
                    });
                });
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }

            // Отправка сообщения
            async function handleSendMessage(e) {
                e.preventDefault();

                const content = messageInput.value.trim();
                if (!content || !currentRoomId) return;

                const submitBtn = messageForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending...';

                try {
                    const response = await fetch('/api/messages', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                        body: JSON.stringify({
                            content: content,
                            chat_room_id: currentRoomId
                        })
                    });

                    if (response.ok) {
                        const result = await response.json();
                        // Добавляем новое сообщение с обработчиком контекстного меню
                        addMessageToUI(result);
                        messageInput.value = '';
                        updateRoomMessageCount(currentRoomId, 1);
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to send message'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred while sending message'};
                    callShowToast(alertToastMessage);
                    console.error('Error sending message:', error);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send';
                }
            }
            // Удаление сообщения
            async function deleteMessage(messageId) {
                try {
                    const response = await fetch(`/api/messages/${messageId}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        document.getElementById(`message-${messageId}`).remove();
                        if (allMessages.length === 0){
                            showNopeMessages();
                        }
                        updateRoomMessageCount(currentRoomId, -1);
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to delete message'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error deleting message:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred while deleting message'};
                    callShowToast(alertToastMessage);
                }
            }

            // Обновление счетчика сообщений в комнате
            function updateRoomMessageCount(roomId, change = 0) {
                const roomElement = document.querySelector(`#roomList > div[data-room-id="${roomId}"]`);
                if (roomElement) {
                    const countElement = roomElement.querySelector('p');
                    if (countElement) {
                        const text = countElement.textContent;
                        const match = text.match(/(\d+)/);
                        if (match) {
                            const currentCount = parseInt(match[1]);
                            countElement.textContent = text.replace(/\d+/, currentCount + change);
                        }
                    }
                }
            }

            // Прокрутка вниз
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Создание комнаты
            async function handleCreateRoom(e) {
                e.preventDefault();

                const form = e.target;
                const formData = {
                    name: form.name.value,
                    description: form.description.value,
                    type: form.type.value
                };

                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Creating...';

                try {
                    const response = await fetch('/api/rooms', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                        body: JSON.stringify(formData)
                    });

                    if (response.ok) {
                        hideCreateRoomModal();
                        form.reset();
                        await loadRooms();
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to create room'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error creating room:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred while creating room'};
                    callShowToast(alertToastMessage);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Create';
                }
            }

            function showCreateRoomModal() {
                document.getElementById('createRoomModal').classList.add('active');
            }

            function hideCreateRoomModal() {
                document.getElementById('createRoomModal').classList.remove('active');
            }
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }
            // Добавьте новые функции
            async function showJoinRoomModal() {
                document.getElementById('joinRoomModal').classList.add('active');
                document.getElementById('inviteCodeInput').focus();
                document.getElementById('inviteCodeInput').value = '';

            }

            function hideJoinRoomModal() {
                document.getElementById('joinRoomModal').classList.remove('active');
            }

            async function handleJoinRoom(e) {
                e.preventDefault();

                const inviteCode = document.getElementById('inviteCodeInput').value.trim();
                console.log(inviteCode);
                if (!inviteCode) return;

                const submitBtn = e.target.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Joining...';

                try {
                    const response = await fetch('/api/rooms/join', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                        body: JSON.stringify({
                            code: inviteCode
                        })
                    });

                    if (response.ok) {
                        const room = await response.json();
                        hideJoinRoomModal();
                        await joinRoom(room.id);
                        await loadRooms();
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to join room'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error joining room:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred while joining room'};
                    callShowToast(alertToastMessage);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Join';
                }
            }

            function showInviteUsersModal() {
                if (!currentRoomId) return;
                document.getElementById('inviteUsersModal').classList.add('active');
            }

            function hideInviteUsersModal() {
                document.getElementById('inviteUsersModal').classList.remove('active');
            }

            function copyInviteLink() {
                const inviteLinkInput = document.getElementById('inviteLinkInput');
                inviteLinkInput.select();
                document.execCommand('copy');

                const copyBtn = document.getElementById('copyInviteLinkBtn');
                copyBtn.textContent = 'Copied!';
                setTimeout(() => {
                    copyBtn.textContent = 'Copy';
                }, 2000);
            }

            async function inviteUserByUsername() {
                const username = document.getElementById('usernameInput').value.trim();
                if (!username || !currentRoomId) return;

                const inviteBtn = document.getElementById('inviteUserBtn');
                inviteBtn.disabled = true;
                inviteBtn.textContent = 'Inviting...';

                try {
                    const response = await fetch('/api/rooms/invite', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                        body: JSON.stringify({
                            room_id: currentRoomId,
                            username: username
                        })
                    });

                    if (response.ok) {
                        const alertToastMessage = {'type': 'success', 'message': 'User invited successfully'};
                        callShowToast(alertToastMessage);
                        document.getElementById('usernameInput').value = '';
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'success', 'message': error.message || 'Failed to invite user'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error inviting user:', error);
                    const alertToastMessage = {'type': 'success', 'message': error.message || 'An error occurred while inviting user'};
                    callShowToast(alertToastMessage);
                } finally {
                    inviteBtn.disabled = false;
                    inviteBtn.textContent = 'Invite';
                }
            }

            async function logoutCurrentRoom() {
                if (!currentRoomId || !confirm('Are you sure you want to logout this room?')) {
                    return;
                }

                const logoutBtn = document.getElementById('logoutRoomBtn');
                logoutBtn.disabled = true;

                try {
                    const response = await fetch(`/api/rooms/${currentRoomId}/logout`, {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const alertToastMessage = {'type': 'success', 'message': 'Room logout successfully'};
                        callShowToast(alertToastMessage);
                        currentRoomId = null;
                        localStorage.removeItem('roomId');
                        document.getElementById('roomActions').classList.add('hidden');
                        document.getElementById('messageInputContainer').classList.add('hidden');
                        document.getElementById('messages').innerHTML = '';
                        document.getElementById('roomName').textContent = 'Select a room';
                        document.getElementById('roomDescription').textContent = '';
                        await loadRooms();
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to logout room'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error logout room:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred while logout room'};
                    callShowToast(alertToastMessage);
                } finally {
                    logoutBtn.disabled = false;
                }
            }

            async function deleteCurrentRoom() {
                if (!currentRoomId || !confirm('Are you sure you want to delete this room? All messages will be lost.')) {
                    return;
                }

                const deleteBtn = document.getElementById('deleteRoomBtn');
                deleteBtn.disabled = true;
                deleteBtn.textContent = 'Deleting...';

                try {
                    const response = await fetch(`/api/rooms/${currentRoomId}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const alertToastMessage = {'type': 'success', 'message': 'Room deleted successfully'};
                        callShowToast(alertToastMessage);
                        currentRoomId = null;
                        localStorage.removeItem('roomId');
                        document.getElementById('roomActions').classList.add('hidden');
                        document.getElementById('messageInputContainer').classList.add('hidden');
                        document.getElementById('messages').innerHTML = '';
                        document.getElementById('roomName').textContent = 'Select a room';
                        document.getElementById('roomDescription').textContent = '';
                        await loadRooms();
                    } else {
                        const error = await response.json();
                        const alertToastMessage = {'type': 'error', 'message': error.message || 'Failed to delete room'};
                        callShowToast(alertToastMessage);
                    }
                } catch (error) {
                    console.error('Error deleting room:', error);
                    const alertToastMessage = {'type': 'error', 'message': 'An error occurred while deleting room'};
                    callShowToast(alertToastMessage);
                } finally {
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = 'Delete Room';
                }
            }

            // Выход
            async function logout() {
                try {
                    const response = await fetch('/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        },
                    });

                    if (response.ok) {
                        localStorage.removeItem('token');
                        localStorage.removeItem('roomId');
                        window.location.href = '/login';
                    }
                } catch (error) {
                    console.error('Error logging out:', error);
                }
            }

            // Глобальная функция для реакций
            window.addReaction = async function(messageId, reaction) {
                try {
                    const response = await fetch(`/api/messages/${messageId}/react/${reaction}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateMessageReactions(messageId, data.reactions);
                    } else {
                        const error = await response.json();
                        console.error('Error adding reaction:', error);
                    }
                } catch (error) {
                    console.error('Error adding reaction:', error);
                }
            };

            async function removeReaction(messageId, reaction) {
                try {
                    const response = await fetch(`/api/messages/${messageId}/react/${reaction}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateMessageReactions(messageId, data.reactions);
                    } else {
                        const error = await response.json();
                        console.error('Error removing reaction:', error);
                    }
                } catch (error) {
                    console.error('Error removing reaction:', error);
                }
            }

            function updateMessageReactions(messageId, reactions, userReactions = []) {
                const messageElement = document.getElementById(`message-${messageId}`);
                if (!messageElement) return;

                const reactionsContainer = messageElement.querySelector('.message-reactions');
                if (!reactionsContainer) return;

                reactionsContainer.innerHTML = '';

                if (reactions && Object.keys(reactions).length > 0) {
                    Object.entries(reactions).forEach(([type, count]) => {
                        if (count > 0) {
                            const isUserReaction = userReactions.includes(type);
                            const badge = document.createElement('span');
                            badge.className = `reaction-badge ${isUserReaction ? 'user-reaction' : ''}`;
                            badge.dataset.reaction = type;
                            badge.dataset.messageId = messageId;
                            badge.innerHTML = `${getReactionEmoji(type)}${count > 1 ? ` ${count}` : ''}`; // Используем innerHTML

                            badge.addEventListener('click', async (e) => {
                                e.stopPropagation();
                                await handleReactionClick(messageId, type);
                            });

                            reactionsContainer.appendChild(badge);
                        }
                    });
                }
            }

            async function handleReactionClick(messageId, reaction) {
                try {
                    const response = await fetch(`/api/messages/${messageId}/react/${reaction}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-XSRF-TOKEN': decodedToken
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Локально обновляем данные сообщения
                        const messageIndex = allMessages.findIndex(m => m.id == messageId);
                        if (messageIndex !== -1) {
                            allMessages[messageIndex].reactions = data.reactions;
                            allMessages[messageIndex].user_reactions = data.user_reactions;
                        }

                        updateMessageReactions(messageId, data.reactions, data.user_reactions);
                    }
                } catch (error) {
                    console.error('Error handling reaction:', error);
                }
            }

            function getReactionEmoji(reaction) {
                const emojis = {
                    'like': `<i class="fi fi-br-social-network"></i>`,
                    'love': `<i class="fi fi-br-heart"></i>`,
                    'laugh': `<i class="fi fi-br-grin-squint-tears"></i>`,
                    'wow': '<i class="fi fi-br-surprise"></i>',
                    'sad': '<i class="fi fi-br-sad-tear"></i>',
                    'angry': '<i class="fi fi-br-face-swear"></i>',
                    'fire': '<i class="fi fi-br-flame"></i>',
                    'star': '<i class="fi fi-br-star"></i>',
                    'clap': '<i class="fi fi-br-hands-clapping"></i>',
                    'rocket': '<i class="fi fi-br-rocket-lunch"></i>'
                };
                return emojis[reaction] || '';
            }
        });
    </script>
@endsection
