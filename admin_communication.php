<?php
require 'admin_session.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messenger App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            display: flex;
            height: 90vh;
        }
        .users-list, .chat-box {
            border: 1px solid #ccc;
        }
        .users-list {
            width: 20%;
            height: 100%;
            overflow-y: scroll;
        }
        .chat-box {
            width: 80%;
            height: 100%;
            display: flex;
            flex-direction: column;
            border-left: 1px solid #ccc;
        }
        .chat-messages {
            flex-grow: 1;
            padding: 15px;
            overflow-y: scroll;
            background-color: #f7f7f7;
        }
        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
        }
        .chat-input input {
            flex-grow: 1;
            margin-right: 10px;
        }
        .call-options {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <?php include 'admin_bars.html'; // Nav and Side bar?>

    <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2>Messenger App</h2>
            </div>
        </div>
        <div class="row chat-container">
            <!-- Users list -->
            <div class="users-list p-3">
                <h5>Contacts</h5>
                <ul class="list-group" id="usersList">
                    <li class="list-group-item user-item" data-username="User 1">User 1</li>
                    <li class="list-group-item user-item" data-username="User 2">User 2</li>
                    <li class="list-group-item user-item" data-username="User 3">User 3</li>
                </ul>
            </div>

            <!-- Chat box -->
            <div class="chat-box">
                <div class="chat-messages" id="chatMessages">
                    <p>Select a user to start chatting...</p>
                </div>

                <!-- Input for chat -->
                <div class="chat-input">
                    <input type="text" class="form-control" id="messageInput" placeholder="Type a message" disabled>
                    <button class="btn btn-primary" id="sendBtn" disabled>Send</button>
                </div>

                <!-- Call Options -->
                <div class="call-options d-flex justify-content-around">
                    <button class="btn btn-success" id="voiceCallBtn" disabled>Voice Call</button>
                    <button class="btn btn-info" id="videoCallBtn" disabled>Video Call</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Bootstrap and JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentUser = null;

        // User selection functionality
        document.querySelectorAll('.user-item').forEach(function(userItem) {
            userItem.addEventListener('click', function() {
                // Get the username of the selected user
                currentUser = userItem.getAttribute('data-username');
                
                // Update chat messages
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.innerHTML = `<p>Chat with <strong>${currentUser}</strong></p>`;
                
                // Enable message input and call buttons
                document.getElementById('messageInput').disabled = false;
                document.getElementById('sendBtn').disabled = false;
                document.getElementById('voiceCallBtn').disabled = false;
                document.getElementById('videoCallBtn').disabled = false;
            });
        });

        // Sending a message
        document.getElementById('sendBtn').addEventListener('click', function() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value;
            
            if (message && currentUser) {
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.innerHTML += `<p><strong>You:</strong> ${message}</p>`;
                messageInput.value = ''; // Clear the input field
                chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the bottom
            }
        });

        // Placeholder for voice and video call (To be implemented with WebRTC)
        document.getElementById('voiceCallBtn').addEventListener('click', function() {
            if (currentUser) {
                alert('Voice call with ' + currentUser + ' will be implemented here.');
            }
        });

        document.getElementById('videoCallBtn').addEventListener('click', function() {
            if (currentUser) {
                alert('Video call with ' + currentUser + ' will be implemented here.');
            }
        });
    </script>
</body>
</html>
