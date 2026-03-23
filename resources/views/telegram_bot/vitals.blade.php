@extends('telegram_bot.layouts.webapp')

@section('title', 'Vitals & AI Analysis - SafeMom')

@push('styles')
@vite('resources/css/telegram_bot/vitals.css')
@endpush

@push('scripts')
<script>
    window.translations = {
        currentLocale: "{{ app()->getLocale() }}",
        analyzing: "{{ __('common.analyzing') }}",
        allClearTitle: "{{ __('vitals.all_clear_title') }}",
        allClearDesc: "{{ __('vitals.all_clear_desc') }}",
        monitorTitle: "{{ __('vitals.monitor_title') }}",
        monitorDesc: "{{ __('vitals.monitor_desc') }}",
        alertTitle: "{{ __('vitals.alert_title') }}",
        alertDesc: "{{ __('vitals.alert_desc') }}",
        bloodPressure: "{{ __('vitals.blood_pressure') }}",
        systolic: "{{ __('vitals.systolic') }}",
        diastolic: "{{ __('vitals.diastolic') }}",
        restingHeartRate: "{{ __('vitals.resting_heart_rate') }}",
        temperature: "{{ __('vitals.temperature') }}",
        errorAnalyzing: "{{ __('vitals.error_analyzing') }}"
    };

    // Inline fallback for Telegram WebApp compatibility
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Vitals inline script loaded');

        // Update BP display
        function updateBP() {
            var s = document.getElementById('systolic').value;
            var d = document.getElementById('diastolic').value;
            document.getElementById('bpDisplay').textContent = s + ' / ' + d;
        }

        // Sliders
        var systolic = document.getElementById('systolic');
        var diastolic = document.getElementById('diastolic');
        var heartRate = document.getElementById('heartRate');
        var temp = document.getElementById('temp');

        if (systolic) systolic.addEventListener('input', updateBP);
        if (diastolic) diastolic.addEventListener('input', updateBP);
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

        // Analyze button
        var analyzeBtn = document.getElementById('analyzeButton');
        if (analyzeBtn) {
            analyzeBtn.addEventListener('click', function() {
                console.log('Analyze button clicked');
                if (typeof window.analyzeVitals === 'function') {
                    window.analyzeVitals();
                } else {
                    // Fallback - direct implementation
                    document.getElementById('inputView').classList.add('hidden');
                    document.getElementById('loadingView').classList.remove('hidden');
                    document.getElementById('bottomNav').style.display = 'none';

                    var sys = parseInt(document.getElementById('systolic').value);
                    var dia = parseInt(document.getElementById('diastolic').value);
                    var hr = parseInt(document.getElementById('heartRate').value);
                    var tempVal = parseFloat(document.getElementById('temp').value) / 10;

                    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch('/api/vitals', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            locale: window.translations?.currentLocale || 'en',
                            systolic_bp: sys,
                            diastolic_bp: dia,
                            heart_rate: hr,
                            temperature: tempVal
                        })
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        document.getElementById('loadingView').classList.add('hidden');
                        if (data.success) {
                            document.getElementById('resultView').classList.remove('hidden');
                            // Render results
                            var status = data.data.status || 'yellow';
                            document.getElementById('statusTitle').innerHTML = data.data.analysis_text || 'Analysis complete';
                            document.getElementById('nutritionText').textContent = data.data.nutrition_advice || '';
                        } else {
                            document.getElementById('inputView').classList.remove('hidden');
                            document.getElementById('bottomNav').style.display = '';
                            alert(data.message || 'Analysis failed');
                        }
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                        document.getElementById('loadingView').classList.add('hidden');
                        document.getElementById('inputView').classList.remove('hidden');
                        document.getElementById('bottomNav').style.display = '';
                        alert('Failed to analyze vitals');
                    });
                }
            });
            console.log('Analyze button listener attached');
        }

        // Reset buttons
        var closeBtn = document.getElementById('closeResultButton');
        var saveBtn = document.getElementById('saveReturnButton');

        function resetView() {
            document.getElementById('resultView').classList.add('hidden');
            document.getElementById('inputView').classList.remove('hidden');
            document.getElementById('bottomNav').style.display = '';
        }

        if (closeBtn) closeBtn.addEventListener('click', resetView);
        if (saveBtn) saveBtn.addEventListener('click', resetView);
    });
</script>
@vite('resources/js/telegram_bot/vitals.js')
@endpush

@section('content')
<div class="flex flex-col h-screen bg-background overflow-hidden relative" id="app">

    <!-- INPUT VIEW -->
    <div id="inputView" class="flex flex-col flex-1 min-h-0">
        <!-- Header -->
        <header class="flex flex-col px-6 pt-6 pb-3 shrink-0 gap-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('telegram.webapp.dashboard') }}" class="w-12 h-12 flex items-center justify-center bg-card rounded-[1.2rem] shadow-[0_4px_16px_rgb(0,0,0,0.03)] border border-border/40">
                    <iconify-icon icon="lucide:chevron-left" width="24" height="24" class="text-foreground"></iconify-icon>
                </a>
                <div class="flex items-center gap-3">
                    @include('telegram_bot.components.language-switcher')
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs font-bold text-primary uppercase tracking-widest">{{ __('vitals.step', ['current' => 2, 'total' => 3]) }}</span>
                        <div class="w-24 h-1.5 bg-muted rounded-full overflow-hidden">
                            <div class="w-2/3 h-full bg-primary rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground leading-tight">{!! __('vitals.how_are_vitals') !!}</h1>
                <p class="text-[15px] text-muted-foreground">{{ __('vitals.slide_adjust') }}</p>
            </div>
        </header>

        <main class="flex-1 min-h-0 overflow-y-auto no-scrollbar px-6 pb-28">
            <!-- Blood Pressure -->
            <div class="mt-4 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col gap-6 anim-in">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">{{ __('vitals.blood_pressure') }}</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-extrabold text-foreground font-heading tracking-tighter" id="bpDisplay">120 / 80</span>
                            <span class="text-sm font-medium text-muted-foreground">mmHg</span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center">
                        <iconify-icon icon="lucide:heart-pulse" width="20" height="20" class="text-accent-foreground"></iconify-icon>
                    </div>
                </div>
                <div>
                    <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-2 block">{{ __('vitals.systolic') }}</label>
                    <input type="range" min="80" max="200" value="120" id="systolic">
                    <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-2 block mt-4">{{ __('vitals.diastolic') }}</label>
                    <input type="range" min="40" max="130" value="80" id="diastolic">
                    <div class="flex justify-between mt-3 text-[10px] font-bold text-muted-foreground uppercase tracking-widest">
                        <span>{{ __('vitals.low') }}</span><span>{{ __('vitals.normal') }}</span><span>{{ __('vitals.high') }}</span>
                    </div>
                </div>
            </div>

            <!-- Heart Rate -->
            <div class="mt-6 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col gap-6 anim-in" style="animation-delay:.1s">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">{{ __('vitals.resting_heart_rate') }}</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-extrabold text-foreground font-heading tracking-tighter" id="hrDisplay">72</span>
                            <span class="text-sm font-medium text-muted-foreground">BPM</span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center">
                        <iconify-icon icon="lucide:activity" width="20" height="20" class="text-secondary-foreground"></iconify-icon>
                    </div>
                </div>
                <div>
                    <input type="range" min="40" max="140" value="72" id="heartRate">
                    <div class="flex justify-between mt-3 text-[10px] font-bold text-muted-foreground uppercase tracking-widest">
                        <span>40</span><span>80</span><span>120+</span>
                    </div>
                </div>
            </div>

            <!-- Temperature -->
            <div class="mt-6 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col gap-6 anim-in" style="animation-delay:.2s">
                <div class="flex justify-between items-start">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">{{ __('vitals.temperature') }}</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-extrabold text-foreground font-heading tracking-tighter" id="tempDisplay">98.4</span>
                            <span class="text-sm font-medium text-muted-foreground">°F</span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                        <iconify-icon icon="lucide:thermometer" width="20" height="20" class="text-primary"></iconify-icon>
                    </div>
                </div>
                <div>
                    <input type="range" min="960" max="1040" value="984" id="temp">
                </div>
            </div>

            <!-- AI Insight -->
            <div class="mt-8 px-4 flex items-start gap-4 bg-primary/5 p-6 rounded-[2rem] border border-primary/10 anim-in" style="animation-delay:.3s">
                <div class="w-10 h-10 rounded-full bg-primary flex flex-shrink-0 items-center justify-center shadow-lg shadow-primary/20 text-white">
                    <iconify-icon icon="lucide:sparkles" width="20" height="20"></iconify-icon>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-primary mb-1 tracking-tight">{{ __('vitals.ai_insight') }}</h4>
                    <p class="text-[14px] leading-relaxed text-muted-foreground">{{ __('vitals.ai_insight_desc') }}</p>
                </div>
            </div>

            <!-- Analyze Button -->
            <div class="mt-8 pb-4">
                <button id="analyzeButton" class="w-full bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97]">
                <span class="text-[17px] font-semibold tracking-wide">{{ __('vitals.analyze_results') }}</span>
                <iconify-icon icon="lucide:arrow-right" width="22" height="22"></iconify-icon>
                </button>
            </div>
        </main>
    </div>

    <!-- LOADING VIEW -->
    <div id="loadingView" class="hidden absolute inset-0 bg-background z-50 flex flex-col items-center justify-center gap-8 px-8">
        <div class="relative w-24 h-24">
            <div class="absolute inset-0 rounded-full border-4 border-muted"></div>
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-primary spin-slow"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <iconify-icon icon="lucide:sparkles" width="32" height="32" class="text-primary"></iconify-icon>
            </div>
        </div>
        <div class="text-center">
            <h2 class="text-2xl font-bold font-heading text-foreground">{{ __('common.analyzing') }}</h2>
            <p class="text-muted-foreground mt-2 text-[15px]">{{ __('vitals.analyzing_message') }}</p>
        </div>
    </div>

    <!-- RESULT VIEW -->
    <div id="resultView" class="hidden absolute inset-0 bg-background z-50 flex flex-col overflow-hidden">
        <header class="flex justify-between items-end px-6 pt-6 pb-3 shrink-0">
            <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-muted-foreground tracking-wide uppercase">{{ __('vitals.analysis_complete') }}</span>
                <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground">{{ __('vitals.ai_summary') }}</h1>
            </div>
            <button id="closeResultButton" class="w-12 h-12 flex items-center justify-center bg-card rounded-[1.2rem] shadow-[0_4px_16px_rgb(0,0,0,0.03)] border border-border/40">
                <iconify-icon icon="lucide:x" width="24" height="24" class="text-muted-foreground"></iconify-icon>
            </button>
        </header>

        <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-24">
            <!-- Status Card -->
            <div class="mt-4 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col items-center text-center" id="statusCard">
                <div class="relative flex items-center justify-center w-[120px] h-[120px] mb-6">
                    <div class="absolute inset-0 rounded-full pulse-ring" id="statusPulse"></div>
                    <div class="relative w-20 h-20 rounded-[2rem] flex items-center justify-center shadow-sm" id="statusIconBg">
                        <iconify-icon id="statusIcon" width="40" height="40"></iconify-icon>
                    </div>
                </div>
                <h2 class="text-2xl font-bold font-heading leading-tight text-foreground px-2" id="statusTitle"></h2>
                <p class="mt-4 text-[15px] leading-relaxed text-muted-foreground px-2" id="statusDesc"></p>
                <div class="mt-6 inline-flex items-center gap-2 px-5 py-2 rounded-full border" id="statusBadge">
                    <div class="w-2 h-2 rounded-full" id="statusDot"></div>
                    <span class="text-sm font-bold uppercase tracking-widest" id="statusLabel"></span>
                </div>
            </div>

            <!-- Logged Vitals -->
            <div class="mt-10 mb-4">
                <h3 class="text-lg font-bold font-heading text-foreground">{{ __('vitals.logged_vitals') }}</h3>
            </div>
            <div class="space-y-3" id="vitalsCards"></div>

            <!-- AI Nutrition Advice Card -->
            <div class="mt-8 bg-primary/5 border border-primary/10 rounded-[2rem] p-6" id="nutritionCard">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white">
                        <iconify-icon icon="lucide:salad" width="20" height="20"></iconify-icon>
                    </div>
                    <h4 class="text-lg font-bold font-heading text-foreground">{{ __('vitals.nutrition_advice') }}</h4>
                </div>
                <p class="text-[14px] leading-relaxed text-muted-foreground" id="nutritionText"></p>
            </div>

            <!-- Actions -->
            <div class="mt-10 flex flex-col gap-4 pb-12">
                <button class="w-full bg-destructive text-white rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(253,164,175,0.6)]" id="emergencyBtn" style="display:none">
                    <iconify-icon icon="lucide:phone" width="22" height="22"></iconify-icon>
                    <span class="text-[17px] font-semibold tracking-wide">{{ __('vitals.call_emergency') }}</span>
                </button>
                <button id="saveReturnButton" class="w-full bg-muted text-foreground border border-border/60 rounded-full py-5 px-6 flex items-center justify-center gap-3">
                    <span class="text-[17px] font-semibold tracking-wide">{{ __('vitals.save_return') }}</span>
                </button>
            </div>
        </main>
    </div>

    @include('telegram_bot.components.bottom-nav')
</div>
@endsection
