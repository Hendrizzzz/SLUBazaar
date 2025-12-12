document.addEventListener('DOMContentLoaded', () => {
    const conversationList = document.getElementById('conversationList');
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const chatTitle = document.getElementById('chatTitle');
    const chatSubtitle = document.getElementById('chatSubtitle');

    // --- 1. Dummy Data for Conversations and Messages ---
    const conversations = [
        {
            id: 1,
            name: "Christian Kyle Ramirez",
            item: "Vintage Vinyl Player",
            avatar: "https://i.pravatar.cc/50?img=50",
            messages: [
                { text: "Hello, is this still available?", type: "incoming" },
                { text: "I can pick it up tomorrow.", type: "incoming" },
                { text: "Hi! Yes, it is. Tomorrow works for me.", type: "outgoing" }
            ]
        },
        {
            id: 2,
            name: "Jim Hendrix Bag-eo",
            item: "title of the item for sale",
            avatar: "https://i.pravatar.cc/50?img=15",
            messages: [
                { text: "Hey! Is this still available?", type: "incoming" },
                { text: "I'm interested in buying it.", type: "incoming" },
                { text: "Hi there! Yes, it's still available.", type: "outgoing" },
                { text: "Are you available to pick it up this week?", type: "outgoing" },
                { text: "Let me know.", type: "outgoing" }
            ]
        },
        {
            id: 3,
            name: "Joeffrey Edrian Carani",
            item: "Gaming Laptop",
            avatar: "https://i.pravatar.cc/50?img=42",
            messages: [
                { text: "Is the price negotiable?", type: "incoming" },
                { text: "The price is firm, sorry!", type: "outgoing" },
                { text: "Sounds good, thanks!", type: "incoming" }
            ]
        },
        {
            id: 4,
            name: "Gavrelle Garcia",
            item: "Handmade Ceramic Mug",
            avatar: "https://i.pravatar.cc/50?img=1",
            messages: [
                { text: "Do you have other colors?", type: "incoming" },
                { text: "Only the one shown in the picture.", type: "outgoing" },
                { text: "When can I see it?", type: "incoming" }
            ]
        },
        {
            id: 5,
            name: "Anya Smith",
            item: "Mountain Bike",
            avatar: "https://i.pravatar.cc/50?img=22",
            messages: [
                { text: "Is it good for trails?", type: "incoming" },
                { text: "Yes, excellent condition!", type: "outgoing" },
                { text: "I'll be there at 5.", type: "incoming" }
            ]
        }
    ];

    let activeConversationId = 2; // Default to the active conversation in the HTML

    // --- 2. Message Rendering Function ---
    const renderMessages = (messages) => {
        chatMessages.innerHTML = ''; // Clear existing messages
        messages.forEach(msg => {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', msg.type);
            messageElement.textContent = msg.text;
            chatMessages.appendChild(messageElement);
        });
        // Scroll to the latest message
        chatMessages.scrollTop = chatMessages.scrollHeight;
    };

    // --- 3. Conversation Switching Logic ---
    const switchConversation = (newId) => {
        const conversationData = conversations.find(c => c.id === newId);
        if (!conversationData) return;

        // Update the active conversation in the list
        document.querySelectorAll('.conversation').forEach(conv => {
            conv.classList.remove('active');
            if (parseInt(conv.dataset.userId) === newId) {
                conv.classList.add('active');
                // Remove the 'new' status dot when switching to it
                const statusDot = conv.querySelector('.conversation-status.new');
                if (statusDot) statusDot.remove();
            }
        });

        // Update the chat header
        chatTitle.innerHTML = `${conversationData.name} <i class="fas fa-external-link-alt" title="View Profile"></i>`;
        
        // FIX APPLIED HERE: Ensure correct property access (using 'item') and fallback
        chatSubtitle.textContent = conversationData.item || 'No Item Title Available';

        // Render the new messages
        renderMessages(conversationData.messages);

        activeConversationId = newId;
        messageInput.focus();
    };

    // --- 4. Send Message Functionality ---
    const sendMessage = () => {
        const text = messageInput.value.trim();
        if (text === '') return;

        // 1. Add message to dummy data
        const activeConv = conversations.find(c => c.id === activeConversationId);
        const newMessage = { text: text, type: 'outgoing' };
        activeConv.messages.push(newMessage);

        // 2. Add message to the DOM
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', 'outgoing');
        messageElement.textContent = text;
        chatMessages.appendChild(messageElement);

        // 3. Clear input and scroll
        messageInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // 4. Update conversation list preview (optional but good practice)
        const activeConvElement = document.querySelector(`.conversation.active .last-message`);
        if (activeConvElement) {
            activeConvElement.textContent = text;
        }
    };

    // --- 5. Event Listeners ---

    // Conversation list click listener
    conversationList.addEventListener('click', (event) => {
        const target = event.target.closest('.conversation');
        if (target && !target.classList.contains('active')) {
            const userId = parseInt(target.dataset.userId);
            switchConversation(userId);
        }
    });

    // Send button click listener
    sendButton.addEventListener('click', sendMessage);

    // Enter key press listener on input field
    messageInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevents a newline in case of a textarea
            sendMessage();
        }
    });

    // --- 6. Initialization ---
    // Load the initial active conversation (Jim Hendrix Bag-eo)
    switchConversation(activeConversationId);
});