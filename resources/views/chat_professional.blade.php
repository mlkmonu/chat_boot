<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Support</title>
    <style>
        :root {
            --bg: #f6f7fb;
            --surface: #ffffff;
            --surface-soft: #ecfdf5;
            --primary: #0f766e;
            --primary-soft: #d1fae5;
            --text: #0f172a;
            --muted: #475569;
            --border: #dbeafe;
            --shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
            --shadow-soft: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Inter", system-ui, sans-serif;
            color: var(--text);
            background: radial-gradient(circle at top left, rgba(20, 184, 166, 0.14), transparent 24%),
                        radial-gradient(circle at 80% 10%, rgba(45, 212, 191, 0.1), transparent 18%),
                        linear-gradient(180deg, #f8fafc 0%, #f6f7fb 100%);
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }

        .layout {
            width: min(1200px, 100%);
            display: grid;
            grid-template-columns: minmax(0, 1fr) 420px;
            gap: 48px;
        }

        .hero {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-top: 12px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            background: rgba(59, 130, 246, 0.12);
            color: #1d4ed8;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .hero h1 {
            max-width: 600px;
            margin: 0 0 22px;
            font-size: clamp(46px, 6vw, 62px);
            line-height: 1.02;
            letter-spacing: -0.05em;
            font-weight: 800;
        }

        .hero p {
            max-width: 520px;
            margin: 0;
            font-size: 18px;
            line-height: 1.75;
            color: var(--muted);
        }

        .chat-shell {
            width: 100%;
            min-height: 82vh;
            max-height: 820px;
            border-radius: 32px;
            background: var(--surface);
            box-shadow: var(--shadow);
            border: 1px solid rgba(71, 93, 150, 0.12);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: #fff;
        }

        .chat-header strong {
            font-size: 16px;
            font-weight: 700;
        }

        .chat-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #34d399;
        }

        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 26px 24px 18px;
            background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        }

        .message {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        .message.user {
            align-items: flex-end;
        }

        .message.bot {
            align-items: flex-start;
        }

        .bubble {
            max-width: 84%;
            padding: 16px 18px;
            border-radius: 22px;
            font-size: 14px;
            line-height: 1.7;
            box-shadow: var(--shadow-soft);
        }

        .message.bot .bubble {
            background: var(--surface-soft);
            color: var(--text);
            border-top-left-radius: 8px;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .message.user .bubble {
            background: linear-gradient(135deg, var(--primary), #2dd4bf);
            color: #fff;
            border-top-right-radius: 8px;
        }

        .time {
            font-size: 11px;
            color: #8f9bb3;
            letter-spacing: 0.01em;
        }

        .composer {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 22px;
            border-top: 1px solid var(--border);
            background: #fbfdff;
        }

        .composer input {
            flex: 1;
            min-height: 50px;
            border-radius: 999px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 0 18px;
            font-size: 14px;
            color: var(--text);
            background: #fff;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .composer input:focus {
            border-color: rgba(59, 130, 246, 0.45);
        }

        .send-btn {
            width: 48px;
            height: 48px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(59, 130, 246, 0.22);
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .send-btn:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .typing {
            display: none;
        }

        .typing.visible {
            display: flex;
        }

        @media (max-width: 960px) {
            .page {
                padding: 20px;
            }

            .layout {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .hero h1 {
                font-size: clamp(40px, 10vw, 52px);
            }

            .chat-shell {
                min-height: 72vh;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="layout">
            <section class="hero">
                <div class="eyebrow">Customer Support</div>
                <h1>Modern support chat for your website</h1>
                <p>Fast, polished conversation experience built for customer questions, live replies, and better engagement.</p>
            </section>

            <section class="chat-shell">
                <div class="chat-header">
                    <strong>Live Chat</strong>
                    <div class="chat-status">
                        <span class="status-dot"></span>
                        Online now
                    </div>
                </div>

                <div id="messages" class="messages">
                    <div class="message bot">
                        <div class="bubble">Welcome to our support chat — how can I help you today?</div>
                        <div class="time">Just now</div>
                    </div>

                    <div class="message user">
                        <div class="bubble">Hi, I need help with my account.</div>
                        <div class="time">Just now</div>
                    </div>

                    <div class="message bot">
                        <div class="bubble">Sure! Please tell me the issue and I’ll assist you right away.</div>
                        <div class="time">Just now</div>
                    </div>

                    <div id="typing" class="message bot typing">
                        <div class="bubble">Typing...</div>
                        <div class="time">Just now</div>
                    </div>
                </div>

                <div class="composer">
                    <input type="text" id="message" placeholder="Type your message here...">
                    <button id="sendBtn" class="send-btn" type="button" aria-label="Send">➤</button>
                </div>
            </section>
        </div>
    </div>

    <script>
        const messagesBox = document.getElementById('messages');
        const messageInput = document.getElementById('message');
        const sendBtn = document.getElementById('sendBtn');
        const typing = document.getElementById('typing');

        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = value;
            return div.innerHTML;
        }

        function appendMessage(role, text) {
            const wrapper = document.createElement('div');
            wrapper.className = `message ${role}`;
            wrapper.innerHTML = `
                <div class="bubble">${escapeHtml(text)}</div>
                <div class="time">Just now</div>
            `;
            messagesBox.insertBefore(wrapper, typing);
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }

        async function sendMessage() {
            const message = messageInput.value.trim();

            if (!message) {
                messageInput.focus();
                return;
            }

            appendMessage('user', message);
            messageInput.value = '';
            typing.classList.add('visible');

            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                appendMessage('bot', data.reply || 'I could not generate a reply right now.');
            } catch (error) {
                appendMessage('bot', 'Message send karne me error aaya. Kripya thodi der baad try karein.');
            } finally {
                typing.classList.remove('visible');
            }
        }

        sendBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>
</html>

