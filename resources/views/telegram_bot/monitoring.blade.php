@extends('telegram_bot.layouts.webapp')

@section('title', 'Monitoring Hub - MamaCare')

@push('styles')
@vite('resources/css/telegram_bot/monitoring.css')
@endpush

@push('scripts')
@vite('resources/js/telegram_bot/monitoring.js')
@endpush

@section('content')
    <div class="flex flex-col h-screen bg-background overflow-hidden relative">

        <!-- Header -->
        <header class="flex justify-between items-end px-6 pt-14 pb-4 shrink-0">
            <div class="flex flex-col gap-1">
                <a href="{{ route('telegram.webapp.dashboard') }}" class="flex items-center gap-2">
                    <button class="w-10 h-10 flex items-center justify-center bg-card rounded-xl shadow-sm border border-border/40">
                        <iconify-icon icon="lucide:chevron-left" width="20" height="20" class="text-foreground"></iconify-icon>
                    </button>
                    <span class="text-sm font-medium text-muted-foreground">Back</span>
                </a>
                <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground mt-2">Monitoring</h1>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-40">

            <!-- KICK COUNTER SECTION -->
            <div class="mt-4 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col items-center anim-in">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">👣</span>
                    <h2 class="text-xl font-bold font-heading text-foreground">Kick Counter</h2>
                </div>
                <p class="text-sm text-muted-foreground mb-6 text-center">Tap the button each time you feel baby move</p>

                <!-- Timer -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-muted rounded-full px-5 py-2 flex items-center gap-2">
                        <iconify-icon icon="lucide:timer" width="16" height="16" class="text-muted-foreground"></iconify-icon>
                        <span class="text-lg font-mono font-bold text-foreground" id="timerDisplay">00:00</span>
                    </div>
                    <button onclick="toggleTimer()" class="bg-primary/10 text-primary rounded-full px-4 py-2 text-sm font-bold" id="timerBtn">Start</button>
                    <button onclick="resetTimer()" class="bg-muted text-muted-foreground rounded-full px-4 py-2 text-sm font-bold">Reset</button>
                </div>

                <!-- Big Kick Button -->
                <button onclick="addKick()" id="kickBtn" class="w-36 h-36 rounded-full bg-gradient-to-br from-primary to-[#93C5FD] text-white flex flex-col items-center justify-center shadow-[0_20px_50px_-12px_rgba(167,139,250,0.6)] active:scale-90 transition-transform mb-4">
                    <span class="text-5xl font-extrabold font-heading" id="kickCount">0</span>
                    <span class="text-sm font-semibold opacity-80 mt-1">kicks</span>
                </button>

                <p class="text-xs text-muted-foreground mt-2">Goal: <span class="font-bold text-foreground">10 kicks</span> in 2 hours</p>

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
                <h3 class="text-lg font-bold font-heading text-foreground mb-3">Today's Sessions</h3>
                <div class="space-y-3" id="kickHistory">
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-[1rem] bg-accent/20 flex items-center justify-center text-accent-foreground">
                                <iconify-icon icon="lucide:check-circle-2" width="22" height="22"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-[15px] font-semibold text-foreground">Morning Session</p>
                                <p class="text-xs text-muted-foreground">10 kicks in 45 min</p>
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
                                <p class="text-[15px] font-semibold text-foreground">After Lunch</p>
                                <p class="text-xs text-muted-foreground">12 kicks in 30 min</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-muted-foreground">1:15 PM</span>
                    </div>
                </div>
            </div>

            <!-- SYMPTOM LOGGER SECTION -->
            <div class="mt-10 anim-in" style="animation-delay:.2s">
                <h2 class="text-xl font-bold font-heading text-foreground mb-4">How are you feeling?</h2>

                <!-- Mood Selection -->
                <div class="bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20 mb-4">
                    <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">Mood</p>
                    <div class="flex justify-between">
                        <button onclick="selectMood(this,'Happy')" class="mood-btn flex flex-col items-center gap-2 p-3 rounded-2xl transition-all">
                            <span class="text-3xl">😊</span>
                            <span class="text-xs font-semibold text-muted-foreground">Happy</span>
                        </button>
                        <button onclick="selectMood(this,'Calm')" class="mood-btn flex flex-col items-center gap-2 p-3 rounded-2xl transition-all">
                            <span class="text-3xl">😌</span>
                            <span class="text-xs font-semibold text-muted-foreground">Calm</span>
                        </button>
                        <button onclick="selectMood(this,'Tired')" class="mood-btn flex flex-col items-center gap-2 p-3 rounded-2xl transition-all">
                            <span class="text-3xl">😴</span>
                            <span class="text-xs font-semibold text-muted-foreground">Tired</span>
                        </button>
                        <button onclick="selectMood(this,'Anxious')" class="mood-btn flex flex-col items-center gap-2 p-3 rounded-2xl transition-all">
                            <span class="text-3xl">😟</span>
                            <span class="text-xs font-semibold text-muted-foreground">Anxious</span>
                        </button>
                    </div>
                </div>

                <!-- Physical Symptoms -->
                <div class="bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20">
                    <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">Symptoms</p>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="toggleSymptom(this)" class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">Nausea</button>
                        <button onclick="toggleSymptom(this)" class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">Back Pain</button>
                        <button onclick="toggleSymptom(this)" class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">Swelling</button>
                        <button onclick="toggleSymptom(this)" class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">Headache</button>
                        <button onclick="toggleSymptom(this)" class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">Heartburn</button>
                        <button onclick="toggleSymptom(this)" class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">Cramping</button>
                        <button onclick="toggleSymptom(this)" class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">Insomnia</button>
                        <button onclick="toggleSymptom(this)" class="symptom-tag px-4 py-2 rounded-full border border-border text-sm font-semibold text-foreground transition-all">Dizziness</button>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-6 pb-8">
                    <button onclick="saveSymptoms()" class="w-full bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97]">
                        <iconify-icon icon="lucide:save" width="20" height="20"></iconify-icon>
                        <span class="text-[17px] font-semibold tracking-wide">Save Today's Log</span>
                    </button>
                </div>
            </div>

        </main>

        @include('telegram_bot.components.bottom-nav')
    </div>
@endsection
