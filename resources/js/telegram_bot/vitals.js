import { getTelegramInitData, initTelegramWebApp } from './telegram-init.js';

console.log('vitals.js loaded');

// Initialize Telegram Web App
document.addEventListener('DOMContentLoaded', () => {
    console.log('vitals.js DOMContentLoaded');
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
async function analyzeVitals() {
    document.getElementById('inputView').classList.add('hidden');
    document.getElementById('loadingView').classList.remove('hidden');
    document.getElementById('bottomNav').style.display = 'none';

    const sys = parseInt(document.getElementById('systolic').value);
    const dia = parseInt(document.getElementById('diastolic').value);
    const hr = parseInt(document.getElementById('heartRate').value);
    const temp = parseFloat(document.getElementById('temp').value) / 10;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const telegramInitData = getTelegramInitData();
        const currentUrl = new URL(window.location.href);
        const apiUrl = new URL('/api/vitals', window.location.origin);

        // Keep local debug auth bypass when the page was opened with ?skip_auth=1.
        if (currentUrl.searchParams.get('skip_auth') === '1') {
            apiUrl.searchParams.set('skip_auth', '1');
        }

        const response = await fetch(apiUrl.toString(), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                ...(telegramInitData ? { 'X-Telegram-Init-Data': telegramInitData } : {}),
            },
            body: JSON.stringify({
                locale: window.translations?.currentLocale || document.documentElement.lang || 'en',
                systolic_bp: sys,
                diastolic_bp: dia,
                heart_rate: hr,
                temperature: temp,
            }),
        });

        const contentType = response.headers.get('content-type') || '';
        const payload = contentType.includes('application/json')
            ? await response.json()
            : { message: await response.text() };

        if (!response.ok) {
            throw new Error(payload.message || payload.error || 'Network response was not ok');
        }

        const data = payload;
        if (!data.success) throw new Error(data.message || 'Analysis failed');

        document.getElementById('loadingView').classList.add('hidden');
        document.getElementById('resultView').classList.remove('hidden');

        renderAIResult(data.data, sys, dia, hr, temp);

    } catch (error) {
        console.error('Error analyzing vitals:', error);
        document.getElementById('loadingView').classList.add('hidden');
        document.getElementById('inputView').classList.remove('hidden');
        document.getElementById('bottomNav').style.display = '';
        alert(error.message || window.translations?.errorAnalyzing || 'Failed to analyze vitals. Please try again.');
    }
}

/**
 * Render AI analysis results
 */
function renderAIResult(diagnosis, sys, dia, hr, temp) {
    const statusConfig = {
        green: {
            icon: 'lucide:check-circle-2',
            iconClass: 'text-accent-foreground',
            bg: 'bg-accent/40',
            iconBg: 'bg-accent',
            statusText: window.translations?.allClearTitle || 'All Clear',
            dot: 'bg-accent-foreground',
            badge: 'bg-accent/30 border-accent/20',
            label: 'text-accent-foreground'
        },
        yellow: {
            icon: 'lucide:alert-triangle',
            iconClass: 'text-secondary-foreground',
            bg: 'bg-secondary/40',
            iconBg: 'bg-secondary',
            statusText: window.translations?.monitorTitle || 'Monitor',
            dot: 'bg-secondary-foreground',
            badge: 'bg-secondary/30 border-secondary/20',
            label: 'text-secondary-foreground'
        },
        red: {
            icon: 'lucide:alert-circle',
            iconClass: 'text-white',
            bg: 'bg-destructive/40',
            iconBg: 'bg-destructive',
            statusText: window.translations?.alertTitle || 'Alert',
            dot: 'bg-destructive',
            badge: 'bg-destructive/20 border-destructive/20',
            label: 'text-destructive'
        }
    };

    const config = statusConfig[diagnosis.status] || statusConfig.yellow;

    // Update status card
    document.getElementById('statusPulse').className = 'absolute inset-0 rounded-full pulse-ring ' + config.bg;
    document.getElementById('statusIconBg').className = 'relative w-20 h-20 rounded-[2rem] flex items-center justify-center shadow-sm ' + config.iconBg;
    document.getElementById('statusIcon').setAttribute('icon', config.icon);
    document.getElementById('statusIcon').className = config.iconClass;
    document.getElementById('statusTitle').innerHTML = diagnosis.analysis_text;
    document.getElementById('statusDesc').textContent = '';
    document.getElementById('statusBadge').className = 'mt-6 inline-flex items-center gap-2 px-5 py-2 rounded-full border ' + config.badge;
    document.getElementById('statusDot').className = 'w-2 h-2 rounded-full ' + config.dot;
    document.getElementById('statusLabel').className = 'text-sm font-bold uppercase tracking-widest ' + config.label;
    document.getElementById('statusLabel').textContent = 'Status: ' + config.statusText;
    document.getElementById('nutritionText').textContent = diagnosis.nutrition_advice || '';

    // Show emergency button if critical
    if (diagnosis.is_critical) {
        document.getElementById('emergencyBtn').style.display = 'flex';
    }

    // Render vitals cards (use existing vitalCard function)
    const bpFlagged = diagnosis.status !== 'green';
    const hrFlagged = diagnosis.status !== 'green';
    const tempFlagged = diagnosis.status !== 'green';

    let html = '';
    html += vitalCard('lucide:heart-pulse', sys + ' / ' + dia, 'mmHg', window.translations?.bloodPressure || 'Blood Pressure', bpFlagged);
    html += vitalCard('lucide:activity', hr, 'BPM', window.translations?.restingHeartRate || 'Heart Rate', hrFlagged);
    html += vitalCard('lucide:thermometer', temp.toFixed(1), '°F', window.translations?.temperature || 'Temperature', tempFlagged);
    document.getElementById('vitalsCards').innerHTML = html;
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
    const heartRate = document.getElementById('heartRate');
    const temp = document.getElementById('temp');
    const analyzeButton = document.getElementById('analyzeButton');
    const closeResultButton = document.getElementById('closeResultButton');
    const saveReturnButton = document.getElementById('saveReturnButton');

    if (systolic && diastolic) {
        systolic.addEventListener('input', updateBP);
        diastolic.addEventListener('input', updateBP);
    }

    if (heartRate) {
        heartRate.addEventListener('input', function() {
            document.getElementById('hrDisplay').textContent = this.value;
        });
    }

    if (temp) {
        temp.addEventListener('input', function() {
            document.getElementById('tempDisplay').textContent = (this.value / 10).toFixed(1);
        });
    }

    if (analyzeButton) {
        analyzeButton.addEventListener('click', analyzeVitals);
        console.log('analyzeButton click listener added');
    }

    if (closeResultButton) {
        closeResultButton.addEventListener('click', resetView);
    }

    if (saveReturnButton) {
        saveReturnButton.addEventListener('click', resetView);
    }
}

// Make functions globally available for inline event handlers
window.updateBP = updateBP;
window.analyzeVitals = analyzeVitals;
window.resetView = resetView;

console.log('vitals.js: window functions assigned', {
    updateBP: typeof window.updateBP,
    analyzeVitals: typeof window.analyzeVitals,
    resetView: typeof window.resetView
});
