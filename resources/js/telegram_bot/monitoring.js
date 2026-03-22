import { initTelegramWebApp } from './telegram-init.js';
import { formatTime } from './utils.js';

console.log('monitoring.js loaded');

// State
let kickCount = 0;
let timerInterval = null;
let timerSeconds = 0;
let timerRunning = false;

// Initialize Telegram Web App
document.addEventListener('DOMContentLoaded', () => {
    console.log('monitoring.js DOMContentLoaded');
    initTelegramWebApp();
    setupEventListeners();
});

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Kick button
    const kickBtn = document.getElementById('kickBtn');
    if (kickBtn) {
        kickBtn.addEventListener('click', addKick);
        console.log('kickBtn click listener added');
    }

    // Timer buttons
    const timerBtn = document.getElementById('timerBtn');
    if (timerBtn) {
        timerBtn.addEventListener('click', toggleTimer);
    }

    const resetTimerBtn = document.getElementById('resetTimerBtn');
    if (resetTimerBtn) {
        resetTimerBtn.addEventListener('click', resetTimer);
    }

    // Mood buttons
    const moodButtons = document.querySelectorAll('.mood-btn');
    moodButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            selectMood(this, this.dataset.mood);
        });
    });

    // Symptom buttons
    const symptomButtons = document.querySelectorAll('.symptom-tag');
    symptomButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            toggleSymptom(this);
        });
    });

    // Save symptoms button
    const saveSymptomsBtn = document.getElementById('saveSymptomsBtn');
    if (saveSymptomsBtn) {
        saveSymptomsBtn.addEventListener('click', function() {
            saveSymptoms(this);
        });
        console.log('saveSymptomsBtn click listener added');
    }
}

/**
 * Add a kick
 */
function addKick() {
    kickCount++;
    const el = document.getElementById('kickCount');
    el.textContent = kickCount;
    const btn = document.getElementById('kickBtn');
    btn.classList.add('animate-kick-pulse');
    setTimeout(() => btn.classList.remove('animate-kick-pulse'), 400);

    // Update dots
    const dots = document.getElementById('kickDots').children;
    if (kickCount <= 10) {
        dots[kickCount - 1].className = 'w-3 h-3 rounded-full bg-primary';
    }
}

/**
 * Toggle timer (start/pause)
 */
function toggleTimer() {
    if (!timerRunning) {
        timerRunning = true;
        document.getElementById('timerBtn').textContent = 'Pause';
        timerInterval = setInterval(() => {
            timerSeconds++;
            const m = String(Math.floor(timerSeconds / 60)).padStart(2, '0');
            const s = String(timerSeconds % 60).padStart(2, '0');
            document.getElementById('timerDisplay').textContent = m + ':' + s;
        }, 1000);
    } else {
        timerRunning = false;
        document.getElementById('timerBtn').textContent = 'Resume';
        clearInterval(timerInterval);
    }
}

/**
 * Reset timer and kick count
 */
function resetTimer() {
    clearInterval(timerInterval);
    timerRunning = false;
    timerSeconds = 0;
    kickCount = 0;
    document.getElementById('timerDisplay').textContent = '00:00';
    document.getElementById('timerBtn').textContent = 'Start';
    document.getElementById('kickCount').textContent = '0';
    const dots = document.getElementById('kickDots').children;
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = 'w-3 h-3 rounded-full bg-muted';
    }
}

/**
 * Select mood
 */
function selectMood(btn, mood) {
    document.querySelectorAll('.mood-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

/**
 * Toggle symptom selection
 */
function toggleSymptom(btn) {
    btn.classList.toggle('active');
}

/**
 * Save symptoms log
 */
function saveSymptoms(btn) {
    btn.innerHTML = '<iconify-icon icon="lucide:check" width="20" height="20"></iconify-icon><span class="text-[17px] font-semibold tracking-wide">Saved!</span>';
    btn.classList.remove('bg-primary');
    btn.classList.add('bg-accent');
    setTimeout(() => {
        btn.innerHTML = '<iconify-icon icon="lucide:save" width="20" height="20"></iconify-icon><span class="text-[17px] font-semibold tracking-wide">Save Today\'s Log</span>';
        btn.classList.remove('bg-accent');
        btn.classList.add('bg-primary');
    }, 2000);
}

// Make functions globally available for inline event handlers
window.addKick = addKick;
window.toggleTimer = toggleTimer;
window.resetTimer = resetTimer;
window.selectMood = selectMood;
window.toggleSymptom = toggleSymptom;
window.saveSymptoms = saveSymptoms;

console.log('monitoring.js: window functions assigned', {
    addKick: typeof window.addKick,
    saveSymptoms: typeof window.saveSymptoms
});
