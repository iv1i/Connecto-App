@extends('layouts.app')

@section('content')
    <div class="chat-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Chat Rooms</h2>
                <button id="createRoomBtn" class="btn btn-primary w-full mt-2">
                    Create Room
                </button>
                <button id="joinRoomBtn" class="btn btn-secondary w-full mt-2">
                    Join Room
                </button>
            </div>

            <div class="sidebar-content">
                <input type="text" id="roomSearch" placeholder="Search rooms..." class="input mb-4">

                <div id="roomList" class="space-y-2">
                    <!-- Rooms will be loaded here -->
                </div>
            </div>

            <div class="sidebar-footer">
                <div class="flex items-center gap-2">
                    <div id="userAvatar" class="message-avatar">
                    </div>
                    <span id="userName"></span>
                </div>
                <button id="logoutBtn" class="btn-link text-sm mt-2">Logout</button>
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
                        <button id="inviteUsersBtn" class="btn btn-secondary mr-2">
                            Invite Users
                        </button>
                        <button id="deleteRoomBtn" class="btn btn-danger">
                            Delete Room
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
                    <input type="text" id="messageInput" placeholder="Type a message..." class="input flex-grow">
                    <button type="submit" class="btn btn-primary">
                        Send
                    </button>
                </form>
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
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            const encodedToken = getCookie('XSRF-TOKEN');
            const decodedToken = decodeURIComponent(encodedToken);
            const currentRoom = localStorage.getItem('roomId');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Инициализация
            let currentRoomId = null;
            let allMessages = [];
            let userData = null;

            // DOM элементы
            const messagesContainer = document.getElementById('messages');
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const roomNameElement = document.getElementById('roomName');
            const roomDescriptionElement = document.getElementById('roomDescription');
            const messageInputContainer = document.getElementById('messageInputContainer');

            // Инициализация приложения
            initApp();

            Echo.channel(`room.${currentRoom}`).listen('MessageSent', (e) => {
                if (e.message.user.id !== userData.id){
                    addMessageToUI(e.message);
                    messageInput.value = '';
                    updateRoomMessageCount(currentRoomId, 1);
                    console.log('new message!')
                }
            });
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
                        document.getElementById('userName').textContent = userData.name;
                    } else {
                        throw new Error('Failed to load user data');
                    }
                } catch (error) {
                    console.error('Error loading user:', error);
                    logout();
                }
            }

            // Загрузка комнат
            async function loadRooms() {
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
                        renderRoomList(data.data);
                    }
                } catch (error) {
                    console.error('Error loading rooms:', error);
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
                    console.error('Error searching rooms:', error);
                }
            }

            // Отображение списка комнат
            function renderRoomList(rooms) {
                const roomList = document.getElementById('roomList');
                roomList.innerHTML = '';

                rooms.forEach(room => {
                    const roomElement = document.createElement('div');
                    roomElement.className = 'p-2 hover:bg-gray-100 rounded-md cursor-pointer';
                    roomElement.dataset.roomId = room.id;
                    roomElement.innerHTML = `
                <h3 class="font-medium">${room.name}</h3>
                <p class="text-sm text-gray-500">${room.messages_count} messages</p>
                ${room.is_private ? '<span class="text-xs text-purple-500">Private</span>' : ''}
            `;

                    roomElement.addEventListener('click', () => joinRoom(room.id));
                    roomList.appendChild(roomElement);
                });
            }

            // Присоединение к комнате
            async function joinRoom(roomId) {
                try {
                    showLoadingMessages();

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
                    localStorage.setItem('roomId', roomId);

                    updateRoomUI(room);
                    renderMessages(messages.data);

                    messageInputContainer.classList.remove('hidden');
                    messageInput.focus();
                } catch (error) {
                    console.error('Error joining room:', error);
                    messagesContainer.innerHTML = `<div class="error">Error loading room: ${error.message}</div>`;
                }
            }

            function showLoadingMessages() {
                messagesContainer.innerHTML = '<div class="loading">Loading messages...</div>';
            }

            function updateRoomUI(room) {
                roomNameElement.textContent = room.name;
                roomDescriptionElement.textContent = room.description || 'No description';
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

            // Добавление сообщения в UI
            function addMessageToUI(message, prepend = false) {
                const messageElement = document.createElement('div');
                messageElement.className = `flex space-x-3 mb-4 ${message.user_id === userData.id ? 'own-message' : ''}`;
                messageElement.id = `message-${message.id}`;

                messageElement.innerHTML = `
            <div class="message-avatar">${message.user.name.charAt(0).toUpperCase()}</div>
            <div class="message-content">
                <div class="message-header">
                    <span class="message-username">${message.user.name}</span>
                    <span class="message-time">${formatDate(message.created_at)}</span>
                    ${message.user_id === userData.id ?
                    `<button class="delete-message-btn" data-message-id="${message.id}">×</button>` : ''}
                </div>
                <p class="message-text">${message.content}</p>
                <div class="message-reactions">
                    ${renderReactions(message)}
                </div>
            </div>
        `;

                if (prepend) {
                    messagesContainer.prepend(messageElement);
                } else {
                    messagesContainer.appendChild(messageElement);
                    scrollToBottom();
                }

                // Добавляем обработчик удаления для своих сообщений
                if (message.user_id === userData.id) {
                    messageElement.querySelector('.delete-message-btn').addEventListener('click', () => {
                        deleteMessage(message.id);
                    });
                }
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }

            function renderReactions(message) {
                const reactions = {
                    like: '👍',
                    love: '❤️',
                    laugh: '😆'
                };

                return Object.entries(reactions).map(([type, emoji]) => {
                    const count = message.reactions?.[type] || 0;
                    return `
                <button class="reaction-btn"
                        onclick="addReaction(${message.id}, '${type}')"
                        data-reaction="${type}"
                        data-message-id="${message.id}">
                    ${count > 0 ? count : ''}${emoji}
                </button>
            `;
                }).join('');
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
                        addMessageToUI(result);
                        messageInput.value = '';
                        updateRoomMessageCount(currentRoomId, 1);
                    } else {
                        const error = await response.json();
                        alert(error.message || 'Failed to send message');
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('An error occurred while sending message');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send';
                }
            }

            // Удаление сообщения
            async function deleteMessage(messageId) {
                if (!confirm('Are you sure you want to delete this message?')) return;

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
                        updateRoomMessageCount(currentRoomId, -1);
                    } else {
                        const error = await response.json();
                        alert(error.message || 'Failed to delete message');
                    }
                } catch (error) {
                    console.error('Error deleting message:', error);
                    alert('An error occurred while deleting message');
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
                        alert(error.message || 'Failed to create room');
                    }
                } catch (error) {
                    console.error('Error creating room:', error);
                    alert('An error occurred while creating room');
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
            // Выход
            async function logout() {
                try {
                    const response = await fetch('/api/logout', {
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
                    const reactionBtn = document.querySelector(`.reaction-btn[data-message-id="${messageId}"][data-reaction="${reaction}"]`);
                    if (!reactionBtn) return;

                    const oldText = reactionBtn.textContent;
                    reactionBtn.textContent = '...';

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
                        const count = data.reactions?.[reaction] || 0;
                        reactionBtn.textContent = `${count > 0 ? count : ''}${getReactionEmoji(reaction)}`;
                    } else {
                        reactionBtn.textContent = oldText;
                        const error = await response.json();
                        console.error('Error adding reaction:', error);
                    }
                } catch (error) {
                    console.error('Error adding reaction:', error);
                }
            };

            function getReactionEmoji(reaction) {
                const emojis = {
                    'like': '👍',
                    'love': '❤️',
                    'laugh': '😆'
                };
                return emojis[reaction] || '';
            }
        });
    </script>
@endsection
