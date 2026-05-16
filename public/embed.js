(function() {
    /**
     * OpenChatbot Embed Widget
     * Pure Vanilla JS - No Dependencies
     */

    // Configuration
    const scriptTag = document.currentScript || (function() {
        const scripts = document.getElementsByTagName('script');
        for (let i = scripts.length - 1; i >= 0; i--) {
            if (scripts[i].src.includes('embed.js') && scripts[i].getAttribute('data-bot-id')) {
                return scripts[i];
            }
        }
        return scripts[scripts.length - 1];
    })();
    
    const BOT_ID = scriptTag.getAttribute('data-bot-id');
    const SCRIPT_URL = new URL(scriptTag.src);
    const API_URL = SCRIPT_URL.origin;
    
    if (!BOT_ID) {
        console.error('OpenChatbot: data-bot-id is missing from the script tag.');
        return;
    }

    // State
    let isOpen = false;
    let visitorId = localStorage.getItem('botforge_visitor_id');
    if (!visitorId) {
        visitorId = 'v_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        localStorage.setItem('botforge_visitor_id', visitorId);
    }

    // Styles (Scoped via Shadow DOM)
    const styles = `
        :host {
            --bf-primary: #6366f1;
            --bf-primary-dark: #4f46e5;
            --bf-secondary: #a855f7;
            --bf-bg: #ffffff;
            --bf-text: #1f2937;
            --bf-text-muted: #6b7280;
            --bf-bot-msg: #f3f4f6;
            --bf-user-msg: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --bf-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            --bf-font: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .botforge-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 2147483647;
            font-family: var(--bf-font);
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            pointer-events: none;
        }

        .botforge-container * {
            box-sizing: border-box;
            pointer-events: auto;
        }

        /* Bubble */
        .chat-bubble {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--bf-user-msg);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 16px;
            border: none;
            outline: none;
        }

        .chat-bubble:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .chat-bubble svg {
            width: 28px;
            height: 28px;
            fill: white;
            transition: transform 0.3s ease;
        }

        .chat-bubble.open svg {
            transform: rotate(90deg);
        }

        /* Chat Window */
        .chat-window {
            width: 400px;
            height: 600px;
            max-height: calc(100vh - 120px);
            background: var(--bf-bg);
            border-radius: 20px;
            box-shadow: var(--bf-shadow);
            display: none;
            flex-direction: column;
            overflow: hidden;
            transform-origin: bottom right;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.05);
        }

        @media (max-width: 480px) {
            .chat-window {
                width: calc(100vw - 48px);
                height: calc(100vh - 120px);
                bottom: 100px;
            }
        }

        .chat-window.active {
            display: flex;
            animation: bf-pop-in 0.3s ease-out;
        }

        @keyframes bf-pop-in {
            0% { opacity: 0; transform: scale(0.9) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Header */
        .chat-header {
            padding: 24px;
            background: #ffffff;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .bot-avatar {
            width: 44px;
            height: 44px;
            background: var(--bf-user-msg);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
        }

        .bot-status-container {
            flex: 1;
        }

        .bot-status-container h3 {
            margin: 0;
            font-size: 17px;
            font-weight: 600;
            color: var(--bf-text);
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            color: #10b981;
            margin-top: 2px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Messages */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            background: #fcfcfd;
            scroll-behavior: smooth;
        }

        .message {
            max-width: 85%;
            padding: 12px 16px;
            font-size: 14px;
            line-height: 1.6;
            position: relative;
            word-wrap: break-word;
        }

        .message.bot {
            align-self: flex-start;
            background: white;
            color: var(--bf-text);
            border-radius: 4px 16px 16px 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            border: 1px solid #f1f1f1;
        }

        .message.user {
            align-self: flex-end;
            background: var(--bf-user-msg);
            color: white;
            border-radius: 16px 16px 4px 16px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        /* Input area */
        .chat-input-wrapper {
            padding: 20px;
            background: white;
            border-top: 1px solid #f3f4f6;
        }

        .chat-input-container {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 6px 6px 6px 16px;
            transition: all 0.2s;
        }

        .chat-input-container:focus-within {
            border-color: var(--bf-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: white;
        }

        .chat-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 0;
            font-size: 14px;
            outline: none;
            color: var(--bf-text);
            font-family: inherit;
        }

        .send-btn {
            background: var(--bf-primary);
            color: white;
            border: none;
            border-radius: 10px;
            width: 38px;
            height: 38px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .send-btn:hover {
            background: var(--bf-primary-dark);
            transform: scale(1.05);
        }

        .send-btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }

        .footer-tag {
            text-align: center;
            padding: 8px;
            font-size: 11px;
            color: var(--bf-text-muted);
            background: #ffffff;
            border-top: 1px solid #f9fafb;
        }

        .footer-tag a {
            color: var(--bf-primary);
            text-decoration: none;
            font-weight: 600;
        }

        /* Typing indicator */
        .typing {
            display: flex;
            gap: 4px;
            padding: 12px 16px;
            background: white;
            border-radius: 4px 16px 16px 16px;
            width: fit-content;
            border: 1px solid #f1f1f1;
            align-self: flex-start;
        }

        .typing span {
            width: 6px;
            height: 6px;
            background: #cbd5e1;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing span:nth-child(2) { animation-delay: 0.2s; }
        .typing span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-4px); }
        }

        .chat-messages::-webkit-scrollbar { width: 5px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    `;

    // UI Creation
    const container = document.createElement('div');
    container.id = 'botforge-widget-root';
    document.body.appendChild(container);

    const shadow = container.attachShadow({ mode: 'open' });
    const styleTag = document.createElement('style');
    styleTag.textContent = styles;
    shadow.appendChild(styleTag);

    const widget = document.createElement('div');
    widget.className = 'botforge-container';
    widget.innerHTML = `
        <div class="chat-window" id="bf-window">
            <div class="chat-header">
                <div class="bot-avatar">B</div>
                <div class="bot-status-container">
                    <h3>AI Assistant</h3>
                    <div class="status-badge">
                        <div class="status-dot"></div>
                        Online
                    </div>
                </div>
            </div>
            <div class="chat-messages" id="bf-messages">
                <div class="message bot">Hi there! 👋 I'm your AI assistant. How can I help you today?</div>
            </div>
            <div class="chat-input-wrapper">
                <div class="chat-input-container">
                    <input type="text" class="chat-input" id="bf-input" placeholder="Write a message..." autocomplete="off">
                    <button class="send-btn" id="bf-send">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </div>
            <div class="footer-tag">Powered by <a href="${API_URL}" target="_blank">OpenChatbot</a></div>
        </div>
        <button class="chat-bubble" id="bf-bubble">
            <svg id="bf-icon-chat" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path></svg>
            <svg id="bf-icon-close" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    `;
    shadow.appendChild(widget);

    // Elements
    const bubble = shadow.getElementById('bf-bubble');
    const window = shadow.getElementById('bf-window');
    const messagesContainer = shadow.getElementById('bf-messages');
    const input = shadow.getElementById('bf-input');
    const sendBtn = shadow.getElementById('bf-send');
    const chatIcon = shadow.getElementById('bf-icon-chat');
    const closeIcon = shadow.getElementById('bf-icon-close');

    // Functions
    function toggleChat() {
        isOpen = !isOpen;
        window.classList.toggle('active', isOpen);
        bubble.classList.toggle('open', isOpen);
        
        if (isOpen) {
            chatIcon.style.display = 'none';
            closeIcon.style.display = 'block';
            input.focus();
            scrollToBottom();
        } else {
            chatIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        }
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function addMessage(text, role) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `message ${role}`;
        msgDiv.textContent = text;
        messagesContainer.appendChild(msgDiv);
        scrollToBottom();
    }

    function showLoading() {
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'typing';
        loadingDiv.id = 'bf-loading';
        loadingDiv.innerHTML = '<span></span><span></span><span></span>';
        messagesContainer.appendChild(loadingDiv);
        scrollToBottom();
    }

    function hideLoading() {
        const indicator = shadow.getElementById('bf-loading');
        if (indicator) indicator.remove();
    }

    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        input.disabled = true;
        sendBtn.disabled = true;

        addMessage(text, 'user');
        showLoading();

        try {
            const response = await fetch(`${API_URL}/api/chat`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    bot_id: BOT_ID,
                    visitor_id: visitorId,
                    visitor_name: 'Visitor',
                    message: text
                })
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            hideLoading();

            if (data.reply) {
                addMessage(data.reply, 'bot');
            } else {
                addMessage('Something went wrong. Please try again.', 'bot');
            }
        } catch (error) {
            hideLoading();
            addMessage('Error connecting to the server. Please check your connection.', 'bot');
            console.error('OpenChatbot Error:', error);
        } finally {
            input.disabled = false;
            sendBtn.disabled = false;
            input.focus();
        }
    }

    // Event Listeners
    bubble.addEventListener('click', toggleChat);
    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

})();
