import { formatDate } from './utils.js';
import { initTelegramWebApp } from './telegram-init.js';

console.log('dashboard.js loaded');

// Initialize Telegram Web App
document.addEventListener('DOMContentLoaded', () => {
    console.log('dashboard.js DOMContentLoaded');
    initTelegramWebApp();
    updateDate();
    setupEventListeners();
});

/**
 * Update medication counter
 */
function updateMedCount() {
    const checks = document.querySelectorAll('.med-check');
    let count = 0;
    checks.forEach(c => {
        if (c.checked) count++;
    });
    document.getElementById('medCounter').textContent = count + ' / 4';
}

/**
 * Increment kick counter
 */
function incrementKickCount() {
    const kickNum = document.getElementById('quickKickNum');
    const currentCount = parseInt(kickNum.innerText);
    kickNum.innerText = currentCount + 1;
}

/**
 * Update date display
 */
function updateDate() {
    const d = new Date();
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const dateText = days[d.getDay()] + ', ' + months[d.getMonth()] + ' ' + d.getDate();
    document.getElementById('dateText').textContent = dateText;
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Med checkboxes
    document.querySelectorAll('.med-check').forEach(checkbox => {
        checkbox.addEventListener('change', updateMedCount);
    });

    // Kick counter button
    const kickButton = document.querySelector('[data-kick-button]');
    if (kickButton) {
        kickButton.addEventListener('click', incrementKickCount);
    }
}

// Make functions globally available for inline event handlers
window.updateMedCount = updateMedCount;
window.incrementKickCount = incrementKickCount;

console.log('dashboard.js: window functions assigned', {
    updateMedCount: typeof window.updateMedCount,
    incrementKickCount: typeof window.incrementKickCount
});
