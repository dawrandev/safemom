@extends('telegram_bot.layouts.webapp')

@section('title', 'Monitoring Hub - SafeMom')

@push('styles')
@vite('resources/css/telegram_bot/monitoring.css')
@endpush

@push('scripts')
<script>
    window.translations = {
        timerStart: "{{ __('monitoring.timer_start') }}",
        timerReset: "{{ __('monitoring.timer_reset') }}",
        kicks: "{{ __('monitoring.kicks') }}",
        mood: "{{ __('monitoring.mood') }}",
        symptoms: "{{ __('monitoring.symptoms') }}"
    };

    // Inline script for Telegram WebApp compatibility
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Monitoring inline script loaded');

        var kickCount = 0;
        var timerInterval = null;
        var timerSeconds = 0;
        var timerRunning = false;

        // Kick button
        var kickBtn = document.getElementById('kickBtn');
        if (kickBtn) {
            kickBtn.addEventListener('click', function() {
                kickCount++;
                document.getElementById('kickCount').textContent = kickCount;
                this.classList.add('animate-kick-pulse');
                var btn = this;
                setTimeout(function() { btn.classList.remove('animate-kick-pulse'); }, 400);

                // Update dots
                var dots = document.getElementById('kickDots').children;
                if (kickCount <= 10) {
                    dots[kickCount - 1].className = 'w-3 h-3 rounded-full bg-primary';
                }
            });
            console.log('Kick button listener attached');
        }

        // Timer button
        var timerBtn = document.getElementById('timerBtn');
        if (timerBtn) {
            timerBtn.addEventListener('click', function() {
                if (!timerRunning) {
                    timerRunning = true;
                    this.textContent = 'Pause';
                    timerInterval = setInterval(function() {
                        timerSeconds++;
                        var m = String(Math.floor(timerSeconds / 60)).padStart(2, '0');
                        var s = String(timerSeconds % 60).padStart(2, '0');
                        document.getElementById('timerDisplay').textContent = m + ':' + s;
                    }, 1000);
                } else {
                    timerRunning = false;
                    this.textContent = 'Resume';
                    clearInterval(timerInterval);
                }
            });
        }

        // Reset timer
        var resetTimerBtn = document.getElementById('resetTimerBtn');
        if (resetTimerBtn) {
            resetTimerBtn.addEventListener('click', function() {
                clearInterval(timerInterval);
                timerRunning = false;
                timerSeconds = 0;
                kickCount = 0;
                document.getElementById('timerDisplay').textContent = '00:00';
                document.getElementById('timerBtn').textContent = 'Start';
                document.getElementById('kickCount').textContent = '0';
                var dots = document.getElementById('kickDots').children;
                for (var i = 0; i < dots.length; i++) {
                    dots[i].className = 'w-3 h-3 rounded-full bg-muted';
                }
            });
        }

        // Mood buttons
        var moodButtons = document.querySelectorAll('.mood-btn');
        moodButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                moodButtons.forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
            });
        });

        // Symptom buttons
        var symptomButtons = document.querySelectorAll('.symptom-tag');
        symptomButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });

        // Save symptoms button
        var saveSymptomsBtn = document.getElementById('saveSymptomsBtn');
        if (saveSymptomsBtn) {
            saveSymptomsBtn.addEventListener('click', function() {
                var btn = this;
                btn.innerHTML = '<iconify-icon icon="lucide:check" width="20" height="20"></iconify-icon><span class="text-[17px] font-semibold tracking-wide">Saved!</span>';
                btn.classList.remove('bg-primary');
                btn.classList.add('bg-accent');
                setTimeout(function() {
                    btn.innerHTML = '<iconify-icon icon="lucide:save" width="20" height="20"></iconify-icon><span class="text-[17px] font-semibold tracking-wide">Save Today\'s Log</span>';
                    btn.classList.remove('bg-accent');
                    btn.classList.add('bg-primary');
                }, 2000);
            });
            console.log('Save symptoms button listener attached');
        }
    });
</script>
@vite('resources/js/telegram_bot/monitoring.js')
@endpush

@section('content')
<div class="flex flex-col h-screen bg-background overflow-hidden relative">

    <!-- Header -->
    <header class="flex justify-between items-end px-6 pt-6 pb-3 shrink-0">
        <div class="flex flex-col gap-1">
            <a href="{{ route('telegram.webapp.dashboard') }}" class="flex items-center gap-2">
                <button class="w-10 h-10 flex items-center justify-center bg-card rounded-xl shadow-sm border border-border/40">
                    <iconify-icon icon="lucide:chevron-left" width="20" height="20" class="text-foreground"></iconify-icon>
                </button>
                <span class="text-sm font-medium text-muted-foreground">{{ __('common.back') }}</span>
            </a>
            <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground mt-2">{{ __('monitoring.monitoring') }}</h1>
        </div>
        @include('telegram_bot.components.language-switcher')
    </header>

    <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-24">

        <!-- KICK COUNTER SECTION -->
        <div class="mt-4 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col items-center anim-in">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-2xl">👣</span>
                <h2 class="text-xl font-bold font-heading text-foreground">{{ __('monitoring.kick_counter') }}</h2>
            </div>
            <p class="text-sm text-muted-foreground mb-6 text-center">{{ __('monitoring.kick_counter_desc') }}</p>

            <!-- Timer -->
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-muted rounded-full px-5 py-2 flex items-center gap-2">
                    <iconify-icon icon="lucide:timer" width="16" height="16" class="text-muted-foreground"></iconify-icon>
                    <span class="text-lg font-mono font-bold text-foreground" id="timerDisplay">00:00</span>
                </div>
                <button id="timerBtn" class="bg-primary/10 text-primary rounded-full px-4 py-2 text-sm font-bold">{{ __('monitoring.timer_start') }}</button>
                <button id="resetTimerBtn" class="bg-muted text-muted-foreground rounded-full px-4 py-2 text-sm font-bold">{{ __('monitoring.timer_reset') }}</button>
            </div>

            <!-- Big Kick Button -->
            <button id="kickBtn" class="w-36 h-36 rounded-full bg-gradient-to-br from-primary to-[#93C5FD] text-white flex flex-col items-center justify-center shadow-[0_20px_50px_-12px_rgba(167,139,250,0.6)] active:scale-90 transition-transform mb-4">
                <span class="text-5xl font-extrabold font-heading" id="kickCount">0</span>
                <span class="text-sm font-semibold opacity-80 mt-1">{{ __('monitoring.kicks') }}</span>
            </button>

            <p class="text-xs text-muted-foreground mt-2">{{ __('monitoring.goal', ['target' => __('monitoring.goal_value'), 'hours' => __('monitoring.goal_hours')]) }}</p>

            <!-- Progress dots -->
            <div class="flex gap-2 mt-4" id="kickDots">
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
                <div class="w-3 h-3 rounded-full bg-muted"></div>
            </div>
        </div>

        <!-- Today's Kick History -->
        <div class="mt-8 anim-in" style="animation-delay:.1s">
            <h3 class="text-lg font-bold font-heading text-foreground mb-3">{{ __('monitoring.today_sessions') }}</h3>
            <div class="space-y-3" id="kickHistory">
                <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-[1rem] bg-accent/20 flex items-center justify-center text-accent-foreground">
                            <iconify-icon icon="lucide:check-circle-2" width="22" height="22"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-[15px] font-semibold text-foreground">{{ __('monitoring.morning_session') }}</p>
                            <p class="text-xs text-muted-foreground">{{ __('monitoring.morning_kicks') }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-muted-foreground">8:30 AM</span>
                </div>
                <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-[1rem] bg-accent/20 flex items-center justify-center text-accent-foreground">
                            <iconify-icon icon="lucide:check-circle-2" width="22" height="22"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-[15px] font-semibold text-foreground">{{ __('monitoring.after_lunch') }}</p>
                            <p class="text-xs text-muted-foreground">{{ __('monitoring.after_lunch_kicks') }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-muted-foreground">1:15 PM</span>
                </div>
            </div>
        </div>

        <!-- SYMPTOM LOGGER SECTION -->
        <div class="mt-10 anim-in" style="animation-delay:.2s">
            <h2 class="text-xl font-bold font-heading text-foreground mb-4">{{ __('monitoring.how_feeling') }}</h2>

            <!-- Mood Selection -->
            <div class="bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20 mb-4">
                <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">{{ __('monitoring.mood') }}</p>
                <div class="flex justify-between">
                    <button data-mood="Happy" class="mood-btn flex flex-col items-center gap-2 p-3 rounded-2xl transition-all">
                        <span class="text-3xl">😊</span>
                        <span class="text-xs font-semibold text-muted-foreground">{{ __('monitoring.mood_happy') }}</span>
                    </button>
                    <button data-mood="Calm" class="mood-btn flex flex-col items-center gap-2 p-3 rounded-2xl transition-all">
                        <span class="text-3xl">😌</span>
                        <span class="text-xs font-semibold text-muted-foreground">{{ __('monitoring.mood_calm') }}</span>
                    </button>
                    <button data-mood="Tired" class="mood-btn flex flex-col items-center gap-2 p-3 rounded-2xl transition-all">
                        <span class="text-3xl">😴</span>
                        <span class="text-xs font-semibold text-muted-foreground">{{ __('monitoring.mood_tired') }}</span>
                    </button>
                    <button data-mood="Anxious" class="mood-btn flex flex-col items-center gap-2 p-3 rounded-2xl transition-all">
                        <span class="text-3xl">😟</span>
                        <span class="text-xs font-semibold text-muted-foreground">{{ __('monitoring.mood_anxious') }}</span>
                    </button>
                </div>
            </div>

            <!-- Physical Symptoms -->
            <div class="bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20">
                <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">{{ __('monitoring.symptoms') }}</p>
                <div class="flex flex-wrap gap-3">
                    <button class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">{{ __('monitoring.symptom_nausea') }}</button>
                    <button class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">{{ __('monitoring.symptom_back_pain') }}</button>
                    <button class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">{{ __('monitoring.symptom_swelling') }}</button>
                    <button class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">{{ __('monitoring.symptom_headache') }}</button>
                    <button class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">{{ __('monitoring.symptom_heartburn') }}</button>
                    <button class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">{{ __('monitoring.symptom_cramping') }}</button>
                    <button class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">{{ __('monitoring.symptom_insomnia') }}</button>
                    <button class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">{{ __('monitoring.symptom_dizziness') }}</button>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-6 pb-8">
                <button id="saveSymptomsBtn" class="w-full bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97]">
                    <iconify-icon icon="lucide:save" width="20" height="20"></iconify-icon>
                    <span class="text-[17px] font-semibold tracking-wide">{{ __('monitoring.save_log') }}</span>
                </button>
            </div>
        </div>

    </main>

    @include('telegram_bot.components.bottom-nav')
</div>
@endsection