<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Messages</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="app-main-content">
        <div class="shape-blue"></div>
        <div class="shape-yellow"></div>

        <div class="chat-container">
            <aside class="sidebar">
                <div class="sidebar-nav">
                    <div class="sidebar-icon" title="Profile"><i class="fas fa-user"></i></div>
                    <div class="sidebar-icon" title="Home"><i class="fas fa-home"></i></div>
                    <div class="sidebar-icon" title="New Post"><i class="fas fa-plus-circle"></i></div>
                    <div class="sidebar-icon active" title="Messages"><i class="fas fa-paper-plane"></i></div>
                </div>
                <div class="profile-avatar" title="My Profile"></div>
            </aside>

            <section class="messages-list">
                <h2>Messages</h2>
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

    <script src="\assets\js\chat.js"></script>
</body>
</html>