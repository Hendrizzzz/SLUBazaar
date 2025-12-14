<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Market</title>

    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/user/chat.css">
    <link rel="stylesheet" href="/assets/css/global.css">
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
        <img src="https://ui-avatars.com/api/?name=User&background=random" alt="Me">
    </div>
</nav>

<div class="main-wrapper">
    <h1>Messages</h1>

    <div class="chat-container">
        <section class="messages-list">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search conversations...">
            </div>
            <div class="conversation-list" id="conversationList">
                <div class="conversation" data-user-id="1">
                    <div class="conversation-avatar" style="background-image: url('https://i.pravatar.cc/50?img=50');"></div>
                    <div class="conversation-details">
                        <div class="conversation-name">Christian Kyle Ramirez</div>
                        <div class="conversation-message">hi</div>
                    </div>
                    <div class="conversation-status new"></div>
                </div>
                <div class="conversation active" data-user-id="2">
                    <div class="conversation-avatar" style="background-image: url('https://i.pravatar.cc/50?img=15');"></div>
                    <div class="conversation-details">
                        <div class="conversation-name">Jim Hendrix Bag-eo</div>
                        <div class="conversation-message last-message">Let me know.</div>
                    </div>
                </div>
                <div class="conversation" data-user-id="3">
                    <div class="conversation-avatar" style="background-image: url('https://i.pravatar.cc/50?img=42');"></div>
                    <div class="conversation-details">
                        <div class="conversation-name">Joeffrey Edrian Carani</div>
                        <div class="conversation-message">Sounds good, thanks!</div>
                    </div>
                    <div class="conversation-status new"></div>
                </div>
                <div class="conversation" data-user-id="4">
                    <div class="conversation-avatar" style="background-image: url('https://i.pravatar.cc/50?img=1');"></div>
                    <div class="conversation-details">
                        <div class="conversation-name">Gavrelle Garcia</div>
                        <div class="conversation-message">When can I see it?</div>
                    </div>
                </div>
                <div class="conversation" data-user-id="5">
                    <div class="conversation-avatar" style="background-image: url('https://i.pravatar.cc/50?img=22');"></div>
                    <div class="conversation-details">
                        <div class="conversation-name">Anya Smith</div>
                        <div class="conversation-message">I'll be there at 5.</div>
                    </div>
                </div>
            </div>
        </section>

        <main class="chat-window">
            <header class="chat-header">
                <div class="chat-header-info">
                    <div class="chat-header-title" id="chatTitle">
                        Jim Hendrix Bag-eo
                        <i class="fas fa-external-link-alt" title="View Profile"></i>
                    </div>
                    <div class="chat-header-subtitle" id="chatSubtitle">
                        title of the item for sale
                    </div>
                </div>
            </header>

            <div class="chat-messages" id="chatMessages">
                <div class="message incoming">Hey! Is this still available?</div>
                <div class="message incoming">I'm interested in buying it.</div>
                <div class="message outgoing">Hi there! Yes, it's still available.</div>
                <div class="message outgoing">Are you available to pick it up this week?</div>
                <div class="message outgoing">Let me know.</div>
            </div>

            <footer class="chat-input">
                <input type="text" id="messageInput" placeholder="Send a message" autocomplete="off">
                <button class="send-btn" id="sendButton">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </footer>
        </main>
</div>
</div>

<script src="/assets/js/utils.js"></script>
<script src="/assets/js/chat.js?"></script>

</body>
</html>