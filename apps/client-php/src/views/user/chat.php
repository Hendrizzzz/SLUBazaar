<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | SLU Bazaar</title>

    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/user/chat.css">
</head>
<body>

<div class="bg-layer-stripes"></div>
<div class="bg-layer-building"></div>

<nav class="sidebar">
    <div class="nav-icons">
        <a href="index.php?action=profile" title="Profile"><i class="fa-regular fa-user"></i></a>
        <a href="index.php?action=marketplace" title="Home"><i class="fa-solid fa-house"></i></a>
        <a href="index.php?action=create_listing" title="New Listing"><i class="fa-solid fa-circle-plus"></i></a>
        <a href="index.php?action=chat" class="active" title="Messages"><i class="fa-regular fa-paper-plane"></i></a>
        <a href="index.php?action=logout" title="Logout" style="margin-top: 20px; color: #ef4444;"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
    <div class="bottom-user">
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fname'] ?? 'User'); ?>&background=random" alt="Me">
    </div>
</nav>

<div class="main-wrapper">
    <h1>Messages</h1>

    <div class="chat-container">
        <section class="messages-list">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search conversations...">
            </div>

            <div class="conversation-list" id="conversationList">
                <div style="padding: 20px; text-align: center; color: #666;">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
            </div>
        </section>

        <main class="chat-window">
            <header class="chat-header" id="chatHeader" style="display:none;">
                <div class="chat-header-info">
                    <div class="chat-header-title">
                        <span id="chatTitle">Select a conversation</span></div>
                    <div class="chat-header-subtitle" id="chatSubtitle">
                        Item Name
                    </div>
                </div>
            </header>

            <div id="emptyState" style="height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #888;">
                <i class="fa-regular fa-comments" style="font-size: 3rem; margin-bottom: 10px;"></i>
                <p>Select a conversation to start chatting</p>
            </div>

            <div class="chat-messages" id="chatMessages" style="display: none;">
            </div>

            <footer class="chat-input" id="chatInputArea" style="display: none;">
                <input type="hidden" id="activeConversationId">
                <input type="text" id="messageInput" placeholder="Send a message" autocomplete="off">
                <button class="send-btn" id="sendButton">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </footer>
        </main>
    </div>
</div>

<script src="/assets/js/utils.js"></script>

<script>
    window.currentUserId = <?= json_encode($userId) ?>;
    window.initialConversations = <?= json_encode($conversations) ?>;
</script>

<script src="/assets/js/chat.js?v=<?= time() ?>"></script>

</body>
</html>