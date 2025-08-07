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
                <h2 id="roomName">Select a room</h2>
                <p id="roomDescription" class="text-light"></p>
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
            </div>
        </div>
    </div>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            const currentRoom = localStorage.getItem('roomId');
            if (!token) {
                window.location.href = '/login';
                return;
            }
            if (currentRoom) {
                joinRoom(currentRoom);
            }

            let currentRoomId = null;
            // Load rooms
            async function loadRooms() {
                try {
                    const response = await fetch('/api/rooms', {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const roomList = document.getElementById('roomList');
                        roomList.innerHTML = '';

                        data.data.forEach(room => {
                            const roomElement = document.createElement('div');
                            roomElement.className = 'p-2 hover:bg-gray-100 rounded-md cursor-pointer';
                            roomElement.innerHTML = `
                        <h3 class="font-medium">${room.name}</h3>
                        <p class="text-sm text-gray-500">${room.messages_count} messages</p>
                    `;

                            roomElement.addEventListener('click', () => joinRoom(room.id));
                            roomList.appendChild(roomElement);
                        });
                    }
                } catch (error) {
                    console.error('Error loading rooms:', error);
                }
            }

            // Join room
            async function joinRoom(roomId) {
                try {
                    const messagesContainer = document.getElementById('messages');
                    messagesContainer.innerHTML = '<div class="loading">Loading messages...</div>';
                    const response = await fetch(`/api/rooms/${roomId}`, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    const room = await response.json();

                    if (response.ok) {
                        allMessages = [];
                        currentRoomId = roomId;
                        localStorage.setItem('roomId', roomId);
                        // Update UI
                        document.getElementById('roomName').textContent = room.name;
                        document.getElementById('roomDescription').textContent = room.description || 'No description';
                        document.getElementById('messageInputContainer').style.display = 'block';

                        // Load messages
                        await loadMessages(roomId);

                    }
                } catch (error) {
                    console.error('Error joining room:', error);
                }
            }

            // load User
            async function loadUser() {
                try {
                    const response = await fetch('/api/profile', {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if (response.ok) {
                        document.getElementById('userAvatar').textContent = data.name.charAt(0).toUpperCase();
                        document.getElementById('userName').textContent = data.name;
                    }
                } catch (error) {
                    console.error('Error loading user:', error);
                    this.logout();
                }
            }

            // Хранилище всех сообщений
            let allMessages = [];

            function addMessageToUI(message, prepend = false) {
                const messagesContainer = document.getElementById('messages');

                const messageElement = document.createElement('div');
                messageElement.className = 'flex space-x-3 mb-4';
                messageElement.id = `message-id-${message.id}`;
                messageElement.innerHTML = `
        <div class="message-avatar">${message.user.name.charAt(0).toUpperCase()}</div>
        <div class="message-content">
            <div class="message-header">
                <span class="message-username">${message.user.name}</span>
                <span class="message-time">${new Date(message.created_at).toLocaleString()}</span>
            </div>
            <p class="message-text">${message.content}</p>
            <div class="message-reactions">
                <button class="reaction-btn" onclick="addReaction(${message.id}, 'like')">${message.reactions?.like || ''}👍</button>
                <button class="reaction-btn" onclick="addReaction(${message.id}, 'love')">${message.reactions?.love || ''}❤️</button>
                <button class="reaction-btn" onclick="addReaction(${message.id}, 'laugh')">${message.reactions?.laugh || ''}😆</button>
            </div>
        </div>
    `;

                if (prepend) {
                    messagesContainer.prepend(messageElement);
                } else {
                    messagesContainer.appendChild(messageElement);
                }
            }

            // Search rooms
            document.getElementById('roomSearch').addEventListener('input', async function(e) {
                const query = e.target.value;

                if (query.length < 2) {
                    loadRooms();
                    return;
                }

                try {
                    const response = await fetch(`/api/rooms/search?query=${encodeURIComponent(query)}`, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const roomList = document.getElementById('roomList');
                        roomList.innerHTML = '';

                        data.data.forEach(room => {
                            const roomElement = document.createElement('div');
                            roomElement.className = 'p-2 hover:bg-gray-100 rounded-md cursor-pointer';
                            roomElement.innerHTML = `
                        <h3 class="font-medium">${room.name}</h3>
                        <p class="text-sm text-gray-500">${room.messages_count} messages</p>
                    `;

                            roomElement.addEventListener('click', () => joinRoom(room.id));
                            roomList.appendChild(roomElement);
                        });
                    }
                } catch (error) {
                    console.error('Error searching rooms:', error);
                }
            });

            // Send message
            document.getElementById('messageForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const content = document.getElementById('messageInput').value;
                if (!content.trim() || !currentRoomId) return;

                try {
                    const response = await fetch('/api/messages', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            content: content,
                            chat_room_id: currentRoomId
                        })
                    });

                    if (response.ok) {
                        const newMessage = await response.json();
                        document.getElementById('messageInput').value = '';

                        // Добавляем новое сообщение в UI
                        addMessageToUI(newMessage.data);

                        // Обновляем счетчик сообщений в списке комнат
                        updateRoomMessageCount(currentRoomId);
                    } else {
                        const error = await response.json();
                        alert(error.message || 'Failed to send message');
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('An error occurred');
                }
            });

            // Load messages
            async function loadMessages(roomId) {
                try {
                    const messagesContainer = document.getElementById('messages');
                    messagesContainer.innerHTML = '<div class="loading">Loading messages...</div>';

                    const response = await fetch(`/api/rooms/${roomId}/messages`, {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        messagesContainer.innerHTML = '';
                        allMessages = data.data;

                        // Сортируем сообщения по дате (новые внизу)
                        allMessages.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

                        // Добавляем сообщения
                        allMessages.forEach(message => {
                            addMessageToUI(message);
                        });

                    }
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            async function updateRoomMessageCount(roomId) {
                const roomElements = document.querySelectorAll('#roomList > div');
                roomElements.forEach(el => {
                    if (el.getAttribute('data-room-id') === roomId.toString()) {
                        const countEl = el.querySelector('p');
                        if (countEl) {
                            const currentCount = parseInt(countEl.textContent) || 0;
                            countEl.textContent = `${currentCount + 1} messages`;
                        }
                    }
                });
            }

            // Create room modal
            document.getElementById('createRoomBtn').addEventListener('click', function() {
                document.getElementById('createRoomModal').classList.add('active');
            });

            document.getElementById('cancelCreateRoom').addEventListener('click', function() {
                document.getElementById('createRoomModal').classList.remove('active');
            });

            // Create room form
            document.getElementById('createRoomForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = {
                    name: document.getElementById('roomNameInput').value,
                    description: document.getElementById('roomDescriptionInput').value,
                    type: document.querySelector('input[name="type"]:checked').value
                };

                try {
                    const response = await fetch('/api/rooms', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    if (response.ok) {
                        document.getElementById('createRoomModal').classList.add('hidden');
                        document.getElementById('createRoomForm').reset();
                        await loadRooms();
                    } else {
                        const error = await response.json();
                        alert(error.message || 'Failed to create room');
                    }
                } catch (error) {
                    console.error('Error creating room:', error);
                    alert('An error occurred');
                }
            });

            // Logout
            document.getElementById('logoutBtn').addEventListener('click', async function() {
                try {
                    const response = await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        localStorage.removeItem('token');
                        window.location.href = '/login';
                    }
                } catch (error) {
                    console.error('Error logging out:', error);
                }
            });

            // Global function for reactions
            window.addReaction = async function(messageId, reaction) {
                try {
                    // Находим элемент сообщения
                    const messageElement = document.getElementById(`message-id-${messageId}`);
                    if (!messageElement) return;

                    // Находим кнопку реакции
                    const reactionBtn = messageElement.querySelector(`.reaction-btn[onclick*="${reaction}"]`);
                    if (!reactionBtn) return;

                    // Визуально обновляем реакцию (временно)
                    const oldText = reactionBtn.textContent;
                    reactionBtn.textContent = '...'; // Показываем загрузку

                    // Отправляем запрос
                    const response = await fetch(`/api/messages/${messageId}/react/${reaction}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Обновляем только нужные реакции в UI
                        const reactions = data.reactions || {};
                        reactionBtn.textContent = `${reactions[reaction] || ''}${getReactionEmoji(reaction)}`;

                        // Обновляем другие реакции этого сообщения
                        Object.keys(reactions).forEach(r => {
                            const btn = messageElement.querySelector(`.reaction-btn[onclick*="${r}"]`);
                            if (btn) {
                                btn.textContent = `${reactions[r] || ''}${getReactionEmoji(r)}`;
                            }
                        });
                    } else {
                        reactionBtn.textContent = oldText; // Возвращаем старое значение при ошибке
                        const error = await response.json();
                        console.error('Error adding reaction:', error);
                    }
                } catch (error) {
                    console.error('Error adding reaction:', error);
                }
            };

// Вспомогательная функция для получения эмодзи по типу реакции
            function getReactionEmoji(reaction) {
                const emojis = {
                    'like': '👍',
                    'love': '❤️',
                    'laugh': '😆'
                };
                return emojis[reaction] || '';
            }

            // Initial load
            loadRooms();
            loadUser();
        });
    </script>
@endsection
