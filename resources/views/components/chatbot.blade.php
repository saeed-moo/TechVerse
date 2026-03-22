{{-- AI Chatbot Widget --}}
<div id="chatbot-container" class="fixed bottom-6 right-6 z-50">
    {{-- Chat Button --}}
    <button
        id="chatbot-toggle"
        onclick="toggleChatbot()"
        class="bg-purple-600 dark:bg-purple-500 text-white rounded-full p-4 shadow-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-all duration-200 flex items-center gap-2"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
        <span class="text-sm font-semibold hidden md:inline">Chat with Us</span>
    </button>

    {{-- Chat Window --}}
    <div
        id="chatbot-window"
        class="hidden absolute bottom-20 right-0 w-96 h-[500px] bg-white dark:bg-gray-800 rounded-lg shadow-2xl flex flex-col overflow-hidden transition-all duration-300"
    >
        {{-- Chat Header --}}
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 dark:from-purple-700 dark:to-purple-800 text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-2xl">
                    🤖
                </div>
                <div>
                    <h3 class="font-bold">TechVerse Assistant</h3>
                    <p class="text-xs opacity-90">We're here to help!</p>
                </div>
            </div>
            <button onclick="toggleChatbot()" class="hover:bg-purple-800 rounded p-1 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Chat Messages --}}
        <div id="chatbot-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900">
            {{-- Welcome Message --}}
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                    🤖
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm max-w-[80%]">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        Hi! 👋 I'm your TechVerse AI assistant. How can I help you today?
                    </p>
                </div>
            </div>
        </div>

        {{-- Typing Indicator --}}
        <div id="typing-indicator" class="hidden px-4 py-2">
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                    🤖
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chat Input --}}
        <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <form id="chatbot-form" class="flex gap-2">
                <input
                    type="text"
                    id="chatbot-input"
                    placeholder="Type your message..."
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                    required
                >
                <button
                    type="submit"
                    class="bg-purple-600 dark:bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors duration-200"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            <button
                onclick="clearChat()"
                class="text-xs text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 mt-2 transition"
            >
                Clear conversation
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Load chat history from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    loadChatHistory();
});

// Toggle chatbot visibility
function toggleChatbot() {
    const window = document.getElementById('chatbot-window');
    window.classList.toggle('hidden');

    if (!window.classList.contains('hidden')) {
        document.getElementById('chatbot-input').focus();
    }
}

// Load chat history from localStorage
function loadChatHistory() {
    const savedMessages = localStorage.getItem('techverse_chat_history');
    if (savedMessages) {
        const messages = JSON.parse(savedMessages);
        const container = document.getElementById('chatbot-messages');

        // Clear welcome message
        container.innerHTML = '';

        // Add all saved messages
        messages.forEach(msg => {
            addMessageToDOM(msg.role, msg.content, false); // false = don't save again
        });
    }
}

// Save chat history to localStorage
function saveChatHistory(role, content) {
    let history = [];
    const saved = localStorage.getItem('techverse_chat_history');

    if (saved) {
        history = JSON.parse(saved);
    }

    // Add new message
    history.push({ role, content });

    // Keep only last 20 messages (10 exchanges)
    if (history.length > 20) {
        history = history.slice(-20);
    }

    localStorage.setItem('techverse_chat_history', JSON.stringify(history));
}

// Send message to chatbot
document.getElementById('chatbot-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const input = document.getElementById('chatbot-input');
    const message = input.value.trim();

    if (!message) return;

    // Add user message to chat and save
    addMessageToDOM('user', message, true);

    // Clear input
    input.value = '';

    // Show typing indicator
    document.getElementById('typing-indicator').classList.remove('hidden');
    scrollToBottom();

    try {
        // Send to backend
        const response = await fetch('{{ route("chatbot.message") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });

        const data = await response.json();

        // Hide typing indicator
        document.getElementById('typing-indicator').classList.add('hidden');

        if (data.success) {
            // Add bot response and save
            addMessageToDOM('bot', data.message, true);
        } else {
            addMessageToDOM('bot', 'Sorry, something went wrong. Please try again.', true);
        }

    } catch (error) {
        console.error('Chatbot error:', error);
        document.getElementById('typing-indicator').classList.add('hidden');
        addMessageToDOM('bot', 'Sorry, I\'m having trouble connecting. Please try again.', true);
    }
});

// Add message to DOM (with optional save to localStorage)
function addMessageToDOM(role, content, shouldSave = true) {
    const messagesContainer = document.getElementById('chatbot-messages');
    const messageDiv = document.createElement('div');

    if (role === 'user') {
        messageDiv.className = 'flex gap-3 justify-end';
        messageDiv.innerHTML = `
            <div class="bg-purple-600 text-white rounded-lg p-3 shadow-sm max-w-[80%]">
                <p class="text-sm">${escapeHtml(content)}</p>
            </div>
            <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-sm flex-shrink-0">
                👤
            </div>
        `;
    } else {
        messageDiv.className = 'flex gap-3';
        messageDiv.innerHTML = `
            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                🤖
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm max-w-[80%]">
                <p class="text-sm text-gray-800 dark:text-gray-200">${formatBotMessage(content)}</p>
            </div>
        `;
    }

    messagesContainer.appendChild(messageDiv);

    // Save to localStorage if requested
    if (shouldSave) {
        saveChatHistory(role, content);
    }

    scrollToBottom();
}

// Format bot message (preserve line breaks)
function formatBotMessage(text) {
    return escapeHtml(text).replace(/\n/g, '<br>');
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Scroll to bottom of chat
function scrollToBottom() {
    const container = document.getElementById('chatbot-messages');
    container.scrollTop = container.scrollHeight;
}

// Clear chat history
async function clearChat() {
    if (!confirm('Clear conversation history?')) return;

    try {
        await fetch('{{ route("chatbot.clear") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        // Clear localStorage
        localStorage.removeItem('techverse_chat_history');

        // Clear messages
        const messagesContainer = document.getElementById('chatbot-messages');
        messagesContainer.innerHTML = `
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                    🤖
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm max-w-[80%]">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        Hi! 👋 I'm your TechVerse assistant. How can I help you today?
                    </p>
                </div>
            </div>
        `;
    } catch (error) {
        console.error('Clear chat error:', error);
    }
}
</script>
@endpush
