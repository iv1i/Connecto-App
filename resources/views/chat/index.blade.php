@extends('layouts.app')
@push('styles')

@endpush

    @section('content')
    <div class="chat-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fi fi-br-cube"></i>Chat Rooms</h2>
                <button id="createRoomBtn" class="btn btn-primary w-full mt-2" style="font-weight: bold">
                    <i class="fi fi-br-magic-wand"></i> Create Room
                </button>
                <button id="joinRoomBtn" class="btn btn-secondary w-full mt-2" style="font-weight: bold">
                    <i class="fi fi-br-add"></i> Join Room
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
    <script id="v1.0.0">
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

                // Управление комнатой
                document.getElementById('inviteUsersBtn').addEventListener('click', showInviteUsersModal);
                document.getElementById('deleteRoomBtn').addEventListener('click', deleteCurrentRoom);
                document.getElementById('closeInviteModal').addEventListener('click', hideInviteUsersModal);
                document.getElementById('copyInviteLinkBtn').addEventListener('click', copyInviteLink);
                document.getElementById('inviteUserBtn').addEventListener('click', inviteUserByUsername);

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
                        document.getElementById('userAvatar').style.backgroundColor = userData.name_color; // Красный цвет
                        document.getElementById('userName').textContent = userData.name;
                        document.getElementById('userLink').textContent = userData.link_name;
                    } else {
                        throw new Error('Failed to load user data');
                    }
                } catch (error) {
                    console.error('Error loading user:', error);
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
                <h3 class="font-medium">${room.name} <span id="newMessagesSpan-${room.id}"></span> ${room.type === 'private' ? '<span class="private-chat">Private</span>' : '<span class="public-chat">Public</span>'}
</h3>
                <p class="room-messages-count-${room.id} text-sm text-gray-500">${room.messages_count} messages</p>
            `;

                    roomElement.addEventListener('click', () => joinRoom(room.id));
                    roomList.appendChild(roomElement);
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

                    updateRoomUI(room);
                    if (messages.data.length === 0){
                        showNopeMessages();
                    }
                    else {
                        renderMessages(messages.data);
                    }

                    messageInputContainer.classList.remove('hidden');
                    messageInput.focus();
                } catch (error) {
                    console.error('Error joining room:', error);
                    messagesContainer.innerHTML = `<div class="error-loading-room"><i class="fi fi-br-bug-slash"></i> Error loading room</div>`;
                }
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

            function updateRoomUI(room) {
                roomNameElement.textContent = room.name;
                roomDescriptionElement.textContent = room.description || 'No description';
                room.is_owner = room.created_by !== userData.id ? false : true;


                const inviteUsersBtn = document.getElementById('inviteUsersBtn');
                inviteUsersBtn.classList.remove('hidden');

                if (room.type === 'public'){
                    inviteUsersBtn.classList.add('hidden');
                }

                const inviteLink = document.getElementById('inviteLinkInput').value = `${room.invite_code}`;
                const roomActions = document.getElementById('roomActions');
                if (room.is_owner) {
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
                    return;
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
            // Добавьте новые функции
            async function showJoinRoomModal() {
                document.getElementById('joinRoomModal').classList.add('active');
                document.getElementById('inviteCodeInput').focus();
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
                        alert(error.message || 'Failed to join room');
                    }
                } catch (error) {
                    console.error('Error joining room:', error);
                    alert('An error occurred while joining room');
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
                        alert('User invited successfully');
                        document.getElementById('usernameInput').value = '';
                    } else {
                        const error = await response.json();
                        alert(error.message || 'Failed to invite user');
                    }
                } catch (error) {
                    console.error('Error inviting user:', error);
                    alert('An error occurred while inviting user');
                } finally {
                    inviteBtn.disabled = false;
                    inviteBtn.textContent = 'Invite';
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
                        alert('Room deleted successfully');
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
                        alert(error.message || 'Failed to delete room');
                    }
                } catch (error) {
                    console.error('Error deleting room:', error);
                    alert('An error occurred while deleting room');
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
