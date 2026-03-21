import { initTelegramWebApp } from './telegram-init.js';

// Initialize Telegram Web App
document.addEventListener('DOMContentLoaded', () => {
    initTelegramWebApp();
});

/**
 * Show chart by type
 * @param {string} type - Chart type ('bp' or 'weight')
 */
function showChart(type) {
    document.getElementById('chartBP').classList.toggle('hidden', type !== 'bp');
    document.getElementById('chartWeight').classList.toggle('hidden', type !== 'weight');
    document.getElementById('tabBP').className = 'tab-btn pb-3 text-sm font-bold ' + (type === 'bp' ? 'tab-active' : 'text-muted-foreground');
    document.getElementById('tabWeight').className = 'tab-btn pb-3 text-sm font-bold ' + (type === 'weight' ? 'tab-active' : 'text-muted-foreground');
}

/**
 * Export chart as PDF
 * @param {HTMLElement} btn - Button element
 */
function exportPDF(btn) {
    const orig = btn.innerHTML;
    btn.innerHTML = '<iconify-icon icon="lucide:loader-2" width="22" height="22" class="text-primary animate-spin-slow"></iconify-icon><span class="text-[16px] font-semibold tracking-wide text-foreground">Generating PDF...</span>';
    setTimeout(() => {
        btn.innerHTML = '<iconify-icon icon="lucide:check-circle-2" width="22" height="22" class="text-accent-foreground"></iconify-icon><span class="text-[16px] font-semibold tracking-wide text-foreground">PDF Ready — Download</span>';
        setTimeout(() => {
            btn.innerHTML = orig;
        }, 3000);
    }, 2000);
}

// Make functions globally available for inline event handlers
window.showChart = showChart;
window.exportPDF = exportPDF;
