@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold">Chat Rooms</h2>
                <button id="createRoomBtn" class="mt-2 w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                    Create Room
                </button>
            </div>

            <div class="p-4">
                <input type="text" id="roomSearch" placeholder="Search rooms..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md mb-4">

                <div id="roomList" class="space-y-2">
                    <!-- Rooms will be loaded here -->
                </div>
            </div>

            <div class="p-4 border-t">
                <div class="flex items-center space-x-2">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}"
                         alt="User" class="w-8 h-8 rounded-full">
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <button id="logoutBtn" class="mt-2 text-sm text-red-600 hover:text-red-800">Logout</button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Room Header -->
            <div id="roomHeader" class="p-4 bg-white shadow-sm border-b">
                <h2 id="roomName" class="text-xl font-semibold">Select a room</h2>
                <p id="roomDescription" class="text-gray-600"></p>
            </div>

            <!-- Messages -->
            <div id="messagesContainer" class="flex-1 p-4 overflow-y-auto">
                <div id="messages" class="space-y-4">
                    <!-- Messages will be loaded here -->
                </div>
            </div>

            <!-- Message Input -->
            <div id="messageInputContainer" class="p-4 bg-white border-t" style="display: none;">
                <form id="messageForm" class="flex space-x-2">
                    <input type="text" id="messageInput" placeholder="Type a message..."
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Send
                    </button>
                </form>
            </div>
        </div>

        <!-- Create Room Modal -->
        <div id="createRoomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Create New Room</h2>
                <form id="createRoomForm">
                    <div class="mb-4">
                        <label for="roomNameInput" class="block text-sm font-medium text-gray-700">Room Name</label>
                        <input type="text" id="roomNameInput" name="name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="roomDescriptionInput" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="roomDescriptionInput" name="description" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Room Type</label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input id="publicRoom" name="type" type="radio" value="public" checked
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="publicRoom" class="ml-2 block text-sm text-gray-700">Public</label>
                            </div>
                            <div class="flex items-center">
                                <input id="privateRoom" name="type" type="radio" value="private"
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <label for="privateRoom" class="ml-2 block text-sm text-gray-700">Private</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" id="cancelCreateRoom"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700">
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
            if (!token) {
                window.location.href = '/login';
                return;
            }

            let currentRoomId = null;

            // Initialize Echo
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY') }}',
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                forceTLS: true,
                auth: {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                }
            });

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
                    const response = await fetch(`/api/rooms/${roomId}`, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    const room = await response.json();

                    if (response.ok) {
                        currentRoomId = roomId;

                        // Update UI
                        document.getElementById('roomName').textContent = room.name;
                        document.getElementById('roomDescription').textContent = room.description || 'No description';
                        document.getElementById('messageInputContainer').style.display = 'block';

                        // Load messages
                        loadMessages(roomId);

                        // Join Echo channel
                        window.Echo.leaveAll();
                        window.Echo.join(`room.${roomId}`)
                            .here(users => {
                                console.log('Users in room:', users);
                            })
                            .joining(user => {
                                console.log('User joined:', user);
                            })
                            .leaving(user => {
                                console.log('User left:', user);
                            })
                            .listen('MessageSent', (data) => {
                                addMessageToUI(data.message);
                            });
                    }
                } catch (error) {
                    console.error('Error joining room:', error);
                }
            }

            // Load messages
            async function loadMessages(roomId) {
                try {
                    const response = await fetch(`/api/rooms/${roomId}/messages`, {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const messagesContainer = document.getElementById('messages');
                        messagesContainer.innerHTML = '';

                        data.data.forEach(message => {
                            addMessageToUI(message);
                        });

                        // Scroll to bottom
                        setTimeout(() => {
                            const container = document.getElementById('messagesContainer');
                            container.scrollTop = container.scrollHeight;
                        }, 100);
                    }
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            // Add message to UI
            function addMessageToUI(message) {
                const messagesContainer = document.getElementById('messages');

                const messageElement = document.createElement('div');
                messageElement.className = 'flex space-x-3';
                messageElement.innerHTML = `
            <div class="flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name=${message.user.name}"
                     alt="${message.user.name}" class="w-8 h-8 rounded-full">
            </div>
            <div>
                <div class="flex items-center space-x-1">
                    <span class="font-medium">${message.user.name}</span>
                    <span class="text-xs text-gray-500">${new Date(message.created_at).toLocaleString()}</span>
                </div>
                <p class="text-gray-800">${message.content}</p>
                <div class="flex space-x-2 mt-1">
                    <button class="text-xs text-gray-500 hover:text-indigo-600"
                            onclick="addReaction(${message.id}, 'like')">👍 Like</button>
                    <button class="text-xs text-gray-500 hover:text-indigo-600"
                            onclick="addReaction(${message.id}, 'love')">❤️ Love</button>
                    <button class="text-xs text-gray-500 hover:text-indigo-600"
                            onclick="addReaction(${message.id}, 'laugh')">😆 Laugh</button>
                </div>
            </div>
        `;

                messagesContainer.appendChild(messageElement);

                // Scroll to bottom
                const container = document.getElementById('messagesContainer');
                container.scrollTop = container.scrollHeight;
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
                        document.getElementById('messageInput').value = '';
                    } else {
                        const error = await response.json();
                        alert(error.message || 'Failed to send message');
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('An error occurred');
                }
            });

            // Create room modal
            document.getElementById('createRoomBtn').addEventListener('click', function() {
                document.getElementById('createRoomModal').classList.remove('hidden');
            });

            document.getElementById('cancelCreateRoom').addEventListener('click', function() {
                document.getElementById('createRoomModal').classList.add('hidden');
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
                        loadRooms();
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
                    const response = await fetch(`/api/messages/${messageId}/react/${reaction}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        const error = await response.json();
                        console.error('Error adding reaction:', error);
                    }
                } catch (error) {
                    console.error('Error adding reaction:', error);
                }
            };

            // Initial load
            loadRooms();
        });
    </script>
@endsection
