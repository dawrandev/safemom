import { initTelegramWebApp } from './telegram-init.js';

// Initialize Telegram Web App
document.addEventListener('DOMContentLoaded', () => {
    initTelegramWebApp();
    setupEventListeners();
});

/**
 * Update blood pressure display
 */
function updateBP() {
    const s = document.getElementById('systolic').value;
    const d = document.getElementById('diastolic').value;
    document.getElementById('bpDisplay').textContent = s + ' / ' + d;
}

/**
 * Analyze vitals and show results
 */
function analyzeVitals() {
    document.getElementById('inputView').classList.add('hidden');
    document.getElementById('loadingView').classList.remove('hidden');
    document.getElementById('bottomNav').style.display = 'none';

    const sys = parseInt(document.getElementById('systolic').value);
    const dia = parseInt(document.getElementById('diastolic').value);
    const hr = parseInt(document.getElementById('heartRate').value);
    const temp = parseFloat(document.getElementById('temp').value) / 10;

    setTimeout(() => {
        document.getElementById('loadingView').classList.add('hidden');
        document.getElementById('resultView').classList.remove('hidden');
        renderResult(sys, dia, hr, temp);
    }, 2200);
}

/**
 * Render analysis results
 */
function renderResult(sys, dia, hr, temp) {
    // Determine status
    let level = 'green',
        statusText = 'All Clear',
        color = 'accent';
    let title = 'Everything looks great! All vitals are within a healthy range.';
    let desc = 'Your readings are consistent with your personal baseline. Keep up the great routine!';
    let nutrition = 'Continue your balanced diet rich in leafy greens, lean proteins, and whole grains. Remember to include calcium-rich foods like yogurt and fortified cereals.';

    if (sys >= 140 || dia >= 90 || temp >= 100.4) {
        level = 'red';
        statusText = 'Alert';
        color = 'destructive';
        title = 'Some readings need attention. Please consult your doctor.';
        desc = 'Your blood pressure or temperature is elevated beyond safe pregnancy ranges. This may require immediate professional evaluation.';
        nutrition = 'Reduce sodium intake immediately. Focus on potassium-rich foods: bananas, sweet potatoes, spinach. Drink plenty of water and avoid caffeine.';
        document.getElementById('emergencyBtn').style.display = 'flex';
    } else if (sys >= 130 || dia >= 85 || hr > 100 || temp >= 99.5) {
        level = 'yellow';
        statusText = 'Monitor';
        color = 'secondary';
        title = 'Mostly fine, but let\'s keep an eye on a few readings.';
        desc = 'A slight upward trend was noticed. It\'s likely minor stress, but monitoring is recommended over the next 24 hours.';
        nutrition = 'Add more omega-3 rich foods like salmon and walnuts. Reduce processed foods and increase magnesium intake through dark chocolate and avocados.';
    }

    const colors = {
        green: {
            bg: 'bg-accent/40',
            iconBg: 'bg-accent',
            icon: 'lucide:check-circle-2',
            dot: 'bg-accent-foreground',
            badge: 'bg-accent/30 border-accent/20',
            label: 'text-accent-foreground'
        },
        yellow: {
            bg: 'bg-secondary/40',
            iconBg: 'bg-secondary',
            icon: 'lucide:alert-triangle',
            dot: 'bg-secondary-foreground',
            badge: 'bg-secondary/30 border-secondary/20',
            label: 'text-secondary-foreground'
        },
        red: {
            bg: 'bg-destructive/40',
            iconBg: 'bg-destructive',
            icon: 'lucide:alert-circle',
            dot: 'bg-destructive',
            badge: 'bg-destructive/20 border-destructive/20',
            label: 'text-destructive'
        }
    }[level];

    document.getElementById('statusPulse').className = 'absolute inset-0 rounded-full pulse-ring ' + colors.bg;
    document.getElementById('statusIconBg').className = 'relative w-20 h-20 rounded-[2rem] flex items-center justify-center shadow-sm ' + colors.iconBg;
    document.getElementById('statusIcon').setAttribute('icon', colors.icon);
    document.getElementById('statusIcon').className = level === 'green' ? 'text-accent-foreground' : level === 'yellow' ? 'text-secondary-foreground' : 'text-white';
    document.getElementById('statusTitle').innerHTML = title;
    document.getElementById('statusDesc').textContent = desc;
    document.getElementById('statusBadge').className = 'mt-6 inline-flex items-center gap-2 px-5 py-2 rounded-full border ' + colors.badge;
    document.getElementById('statusDot').className = 'w-2 h-2 rounded-full ' + colors.dot;
    document.getElementById('statusLabel').className = 'text-sm font-bold uppercase tracking-widest ' + colors.label;
    document.getElementById('statusLabel').textContent = 'Status: ' + statusText;
    document.getElementById('nutritionText').textContent = nutrition;

    // Vitals cards
    const bpFlagged = sys >= 130 || dia >= 85;
    const hrFlagged = hr > 100;
    const tempFlagged = temp >= 99.5;

    let html = '';
    html += vitalCard('lucide:heart-pulse', sys + ' / ' + dia, 'mmHg', 'Blood Pressure', bpFlagged);
    html += vitalCard('lucide:activity', hr, 'BPM', 'Heart Rate', hrFlagged);
    html += vitalCard('lucide:thermometer', temp.toFixed(1), '°F', 'Temperature', tempFlagged);
    document.getElementById('vitalsCards').innerHTML = html;
}

/**
 * Generate vital card HTML
 */
function vitalCard(icon, val, unit, label, flagged) {
    const bg = flagged ? 'bg-secondary/10 border-secondary/20' : 'bg-card border-border/40 shadow-[0_4px_12px_rgb(0,0,0,0.02)]';
    const iconBg = flagged ? 'bg-secondary text-secondary-foreground' : 'bg-accent/20 text-accent-foreground';
    const statusIcon = flagged ? '<iconify-icon icon="lucide:alert-circle" width="24" height="24" class="text-secondary-foreground"></iconify-icon>' : '<iconify-icon icon="lucide:check-circle-2" width="24" height="24" class="text-accent"></iconify-icon>';
    return `<div class="${bg} border rounded-[1.8rem] p-5 flex justify-between items-center">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-[1.2rem] ${iconBg} flex items-center justify-center">
        <iconify-icon icon="${icon}" width="24" height="24"></iconify-icon>
      </div>
      <div>
        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">${label}</p>
        <p class="text-xl font-bold font-heading text-foreground">${val} <span class="text-xs font-normal text-muted-foreground ml-1">${unit}</span></p>
      </div>
    </div>
    ${statusIcon}
  </div>`;
}

/**
 * Reset to input view
 */
function resetView() {
    document.getElementById('resultView').classList.add('hidden');
    document.getElementById('inputView').classList.remove('hidden');
    document.getElementById('bottomNav').style.display = '';
    document.getElementById('emergencyBtn').style.display = 'none';
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Blood pressure sliders
    const systolic = document.getElementById('systolic');
    const diastolic = document.getElementById('diastolic');

    if (systolic && diastolic) {
        systolic.addEventListener('input', updateBP);
        diastolic.addEventListener('input', updateBP);
    }
}

// Make functions globally available for inline event handlers
window.updateBP = updateBP;
window.analyzeVitals = analyzeVitals;
window.resetView = resetView;
