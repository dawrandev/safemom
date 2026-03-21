import { initTelegramWebApp } from './telegram-init.js';

// Initialize Telegram Web App
document.addEventListener('DOMContentLoaded', () => {
    initTelegramWebApp();
    setupEventListeners();
});

/**
 * Send a chat message
 */
function sendMessage() {
    const input = document.getElementById('chatInput');
    const text = input.value.trim();
    if (!text) return;

    const container = document.getElementById('chatMessages');
    const now = new Date();
    const time = now.getHours() + ':' + (now.getMinutes() < 10 ? '0' : '') + now.getMinutes();

    // User bubble
    const userDiv = document.createElement('div');
    userDiv.className = 'flex gap-3 items-end justify-end';
    userDiv.innerHTML = `<div class="bg-primary text-white rounded-[1.4rem] rounded-br-lg px-5 py-3 max-w-[80%]">
    <p class="text-[14px] leading-relaxed">${text}</p>
    <p class="text-[10px] opacity-70 mt-1">${time}</p>
  </div>`;
    container.appendChild(userDiv);
    input.value = '';
    container.scrollTop = container.scrollHeight;

    // Typing indicator
    setTimeout(() => {
        const typing = document.createElement('div');
        typing.className = 'flex gap-3 items-end';
        typing.id = 'typing';
        typing.innerHTML = `<div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center flex-shrink-0">
      <iconify-icon icon="lucide:stethoscope" width="14" height="14" class="text-primary"></iconify-icon>
    </div>
    <div class="bg-muted rounded-[1.4rem] rounded-bl-lg px-5 py-3">
      <p class="text-[14px] text-muted-foreground">Typing...</p>
    </div>`;
        container.appendChild(typing);
        container.scrollTop = container.scrollHeight;

        // Doctor auto-reply
        setTimeout(() => {
            container.removeChild(document.getElementById('typing'));
            const replies = [
                'I\'ll check that for you. Please continue monitoring and let me know if anything changes.',
                'That sounds normal for your stage. Keep up with your daily vitals!',
                'Great question! I\'d recommend discussing this at your next checkup. For now, stay hydrated and rest well.',
                'Thanks for the update. Everything seems on track. Keep taking your prenatal vitamins!'
            ];
            const reply = replies[Math.floor(Math.random() * replies.length)];
            const docDiv = document.createElement('div');
            docDiv.className = 'flex gap-3 items-end';
            docDiv.innerHTML = `<div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center flex-shrink-0">
        <iconify-icon icon="lucide:stethoscope" width="14" height="14" class="text-primary"></iconify-icon>
      </div>
      <div class="bg-muted rounded-[1.4rem] rounded-bl-lg px-5 py-3 max-w-[80%]">
        <p class="text-[14px] leading-relaxed text-foreground">${reply}</p>
        <p class="text-[10px] text-muted-foreground mt-1">${time}</p>
      </div>`;
            container.appendChild(docDiv);
            container.scrollTop = container.scrollHeight;
        }, 1500);
    }, 500);
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    const input = document.getElementById('chatInput');
    if (input) {
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
}

// Make functions globally available for inline event handlers
window.sendMessage = sendMessage;
