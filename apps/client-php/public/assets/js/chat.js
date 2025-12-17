document.addEventListener('DOMContentLoaded', () => {
    const conversationListEl = document.getElementById('conversationList');
    const chatMessagesEl = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const chatHeader = document.getElementById('chatHeader');
    const chatTitle = document.getElementById('chatTitle');
    const chatSubtitle = document.getElementById('chatSubtitle');
    const activeConversationIdInput = document.getElementById('activeConversationId');
    const emptyState = document.getElementById('emptyState');
    const chatInputArea = document.getElementById('chatInputArea');
    const searchInput = document.getElementById('searchInput');

    let allConversations = window.initialConversations || [];
    let activeConversationId = null;
    let pollingInterval = null; // Store the interval ID

    // --- 1. Render Sidebar ---
    function renderConversationList(conversations) {
        conversationListEl.innerHTML = '';

        if (conversations.length === 0) {
            conversationListEl.innerHTML = '<div style="padding:20px; text-align:center; opacity:0.6">No conversations yet</div>';
            return;
        }

        conversations.forEach(conv => {
            const div = document.createElement('div');
            div.className = `conversation ${activeConversationId === conv.conversation_id ? 'active' : ''}`;
            div.dataset.id = conv.conversation_id;

            div.innerHTML = `
                <div class="conversation-avatar" style="background-image: url('${conv.user_avatar}');"></div>
                <div class="conversation-details">
                    <div class="conversation-name">${conv.user_name}</div>
                    <div class="conversation-message last-message">${conv.last_message}</div>
                </div>
            `;

            div.addEventListener('click', () => loadConversation(conv));
            conversationListEl.appendChild(div);
        });
    }

    // --- 2. Load Specific Conversation ---
    function loadConversation(conv) {
        if (activeConversationId === conv.conversation_id) return;

        activeConversationId = conv.conversation_id;
        activeConversationIdInput.value = conv.conversation_id;

        // Update UI State
        emptyState.style.display = 'none';
        chatHeader.style.display = 'flex';
        chatMessagesEl.style.display = 'flex';
        chatInputArea.style.display = 'flex';

        // Update Header
        chatTitle.textContent = conv.user_name;
        chatSubtitle.textContent = conv.item_title;

        // Highlight Active Item
        document.querySelectorAll('.conversation').forEach(el => {
            el.classList.toggle('active', parseInt(el.dataset.id) === activeConversationId);
        });

        // Fetch Messages immediately
        fetchMessages(activeConversationId, true);

        // Start Auto-Update
        startMessagePolling(activeConversationId);
    }

    // --- 3. Polling Logic (Auto Update) ---
    function startMessagePolling(convoId) {
        // Clear any existing timer first
        stopMessagePolling();

        // Set a new timer to fetch every 3 seconds
        pollingInterval = setInterval(() => {
            if (activeConversationId === convoId) {
                // Pass 'false' so we don't show the loading spinner every 3 seconds
                fetchMessages(convoId, false);
            }
        }, 3000);
    }

    function stopMessagePolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    async function fetchMessages(convoId, showLoading = true) {
        // Only show spinner on initial load, not during background polling
        if (showLoading) {
            chatMessagesEl.classList.add('loading');
            chatMessagesEl.innerHTML = '<div class="spinner-wrapper"><i class="fas fa-spinner fa-spin"></i></div>';
        }

        try {
            const response = await fetch(`index.php?action=get_messages&conversation_id=${convoId}`);

            if (!response.ok) {
                // If polling failed silently (e.g. network blip), just ignore it to avoid UI disruption
                if (!showLoading) return;
                throw new Error(`Server Error: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // If showing loading, clear it now
                if (showLoading) {
                    chatMessagesEl.classList.remove('loading');
                    chatMessagesEl.innerHTML = '';
                }

                // Render messages.
                // Optimization: In a real app, you'd only append NEW messages.
                // For now, we replace the list but keep scroll position if user is reading up.
                updateMessageList(data.messages);

            } else {
                console.error("Failed to load messages:", data.error);
                if (showLoading) {
                    chatMessagesEl.innerHTML = `<div class="error-state">Error: ${data.error}</div>`;
                }
            }
        } catch (error) {
            console.error(error);
            if (showLoading) {
                chatMessagesEl.classList.remove('loading');
                chatMessagesEl.innerHTML = '<div class="error-state">Failed to load messages.</div>';
            }
        }
    }

    function updateMessageList(messages) {
        // 1. Check if we need to scroll to bottom (if user was already at bottom)
        const isAtBottom = (chatMessagesEl.scrollHeight - chatMessagesEl.scrollTop) <= (chatMessagesEl.clientHeight + 100);

        // 2. Clear and Rebuild (Simplest approach for reliability)
        // Note: For a smoother experience, you could diff the lists, but this is sufficient for now.
        chatMessagesEl.innerHTML = '';

        if (messages.length === 0) {
            chatMessagesEl.innerHTML = '<div class="empty-chat-state">No messages yet. Say hi!</div>';
            return;
        }

        messages.forEach(msg => {
            const msgDiv = document.createElement('div');
            msgDiv.className = `message ${msg.type}`;
            msgDiv.textContent = msg.text;
            msgDiv.title = msg.created_at;
            chatMessagesEl.appendChild(msgDiv);
        });

        // 3. Scroll to bottom if this was a new load OR user was already at bottom
        if (isAtBottom) {
            scrollToBottom();
        }
    }

    // --- 4. Send Message ---
    async function sendMessage(e) {
        if (e) e.preventDefault();

        const text = messageInput.value.trim();
        if (!text || !activeConversationId) return;

        // Optimistic UI Update
        const tempMsg = document.createElement('div');
        tempMsg.className = 'message outgoing';
        tempMsg.textContent = text;
        tempMsg.style.opacity = '0.7';
        chatMessagesEl.appendChild(tempMsg);
        scrollToBottom();
        messageInput.value = '';

        try {
            const formData = new FormData();
            formData.append('conversation_id', activeConversationId);
            formData.append('message', text);

            const response = await fetch('index.php?action=send_message', {
                method: 'POST',
                body: formData
            });

            const result = await response.json().catch(() => { throw new Error("Invalid Server Response"); });

            if (result.success) {
                tempMsg.style.opacity = '1';
                // Trigger an immediate fetch to ensure sync and update sidebar
                fetchMessages(activeConversationId, false);

                // Update sidebar preview text manually for instant feedback
                const sidebarItem = document.querySelector(`.conversation[data-id="${activeConversationId}"] .last-message`);
                if (sidebarItem) sidebarItem.textContent = text;

            } else {
                tempMsg.classList.add('error');
                tempMsg.textContent += " (Failed)";
                showToast("Error sending message: " + (result.error || "Unknown error"), "error");
            }
        } catch (e) {
            console.error(e);
            tempMsg.classList.add('error');
            tempMsg.style.backgroundColor = '#ff4444';
            tempMsg.textContent += " (Network Error)";
        }
    }

    function scrollToBottom() {
        chatMessagesEl.scrollTop = chatMessagesEl.scrollHeight;
    }

    // --- 5. Search Filter ---
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        const filtered = allConversations.filter(c =>
            c.user_name.toLowerCase().includes(term) ||
            c.item_title.toLowerCase().includes(term)
        );
        renderConversationList(filtered);
    });

    // --- 6. Event Listeners ---
    sendButton.addEventListener('click', (e) => sendMessage(e));
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage(e);
        }
    });

    // Initialize
    renderConversationList(allConversations);
});