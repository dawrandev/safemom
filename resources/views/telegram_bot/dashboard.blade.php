@extends('telegram_bot.layouts.webapp')

@section('title', 'Dashboard - SafeMom')

@push('styles')
@vite('resources/css/telegram_bot/dashboard.css')
@endpush

@push('scripts')
<script>
    window.translations = {
        medCounter: "{{ __('dashboard.med_counter', ['completed' => ':completed', 'total' => ':total']) }}",
        kicksToday: "{{ __('dashboard.kicks_today') }}"
    };
</script>
@vite('resources/js/telegram_bot/dashboard.js')
@endpush

@section('content')
    <div class="flex flex-col h-screen bg-background overflow-hidden relative">

        <!-- Header -->
        <header class="flex justify-between items-end px-6 pt-6 pb-3 shrink-0 anim-in">
            <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-muted-foreground" id="dateText">{{ __('dashboard.date_format') }}</span>
                <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground">{{ __('dashboard.greeting', ['name' => $user['name'] ?? 'Sarah']) }}</h1>
            </div>
            <div class="flex items-center gap-3">
                @include('telegram_bot.components.language-switcher')
                <div class="relative w-12 h-12 flex items-center justify-center bg-card rounded-[1.2rem] shadow-[0_4px_16px_rgb(0,0,0,0.03)] border border-border/40">
                    <iconify-icon icon="lucide:bell" width="22" height="22" class="text-foreground"></iconify-icon>
                    <div class="absolute top-3 right-3 w-2.5 h-2.5 bg-destructive rounded-full border-2 border-card"></div>
                </div>
            </div>
        </header>

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-24">

            <!-- Pregnancy Progress Card -->
            <div class="mt-4 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col items-center anim-in anim-d1">
                <div class="relative flex flex-col items-center justify-center w-[220px] h-[220px]">
                    <svg viewBox="0 0 200 200" class="w-full h-full absolute inset-0">
                        <defs>
                            <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#A78BFA" />
                                <stop offset="100%" stop-color="#A7F3D0" />
                            </linearGradient>
                            <filter id="inset-shadow">
                                <feOffset dx="0" dy="2" />
                                <feGaussianBlur stdDeviation="3" result="b" />
                                <feComposite operator="out" in="SourceGraphic" in2="b" result="inv" />
                                <feFlood flood-color="black" flood-opacity="0.05" result="c" />
                                <feComposite operator="in" in="c" in2="inv" result="s" />
                                <feComposite operator="over" in="s" in2="SourceGraphic" />
                            </filter>
                        </defs>
                        <circle cx="100" cy="100" r="84" fill="none" stroke="#F1F5F9" stroke-width="12" filter="url(#inset-shadow)" />
                        <circle cx="100" cy="100" r="84" fill="none" stroke="url(#grad)" stroke-width="14" stroke-linecap="round" stroke-dasharray="527.78" stroke-dashoffset="211.18" transform="rotate(-90 100 100)" />
                    </svg>
                    <div class="flex flex-col items-center justify-center relative z-10 pt-2">
                        <span class="text-xs font-semibold text-muted-foreground uppercase tracking-[0.2em] mb-1">{{ __('dashboard.trimester', ['number' => $user['trimester'] ?? 2]) }}</span>
                        <span class="text-6xl font-extrabold text-foreground font-heading tracking-tighter">{{ $user['week'] ?? 24 }}</span>
                        <span class="text-sm font-medium text-primary mt-1 bg-primary/10 px-3 py-1 rounded-full">{{ __('dashboard.week') }}</span>
                    </div>
                </div>
                <div class="mt-8 px-2 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent/20 flex flex-shrink-0 items-center justify-center">
                        <span class="text-xl">🌽</span>
                    </div>
                    <p class="text-[15px] leading-relaxed text-muted-foreground">
                        {{ __('dashboard.baby_size', ['item' => __('dashboard.baby_size_corn'), 'weight' => __('dashboard.baby_weight')]) }}
                    </p>
                </div>
            </div>

            <!-- Start AI Check-up -->
            <div class="mt-8 anim-in anim-d2">
                <a href="{{ route('telegram.webapp.vitals') }}" class="block w-full bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97]">
                    <iconify-icon icon="lucide:sparkles" width="22" height="22"></iconify-icon>
                    <span class="text-[17px] font-semibold tracking-wide">{{ __('dashboard.start_ai_checkup') }}</span>
                </a>
            </div>

            <!-- Daily Meds Checklist -->
            <div class="mt-10 anim-in anim-d3">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold font-heading text-foreground">{{ __('dashboard.daily_meds') }}</h2>
                    <span class="text-xs font-bold text-primary uppercase tracking-widest" id="medCounter">{{ __('dashboard.med_counter', ['completed' => 0, 'total' => 4]) }}</span>
                </div>
                <div class="bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20 space-y-4">
                    <div class="flex items-center gap-4">
                        <input type="checkbox" id="med1" class="med-check hidden" onchange="updateMedCount()">
                        <label for="med1" class="flex items-center gap-4 flex-1 cursor-pointer">
                            <div class="check-box w-7 h-7 rounded-lg border-2 border-border flex items-center justify-center transition-all">
                                <iconify-icon icon="lucide:check" width="16" height="16" class="text-white hidden"></iconify-icon>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-secondary flex items-center justify-center text-lg">💊</div>
                            <div class="flex-1">
                                <p class="med-name text-[15px] font-semibold text-foreground transition-all">{{ __('dashboard.prenatal_vitamin') }}</p>
                                <p class="text-xs text-muted-foreground">{{ __('dashboard.prenatal_vitamin_dose') }}</p>
                            </div>
                        </label>
                    </div>
                    <div class="flex items-center gap-4">
                        <input type="checkbox" id="med2" class="med-check hidden" onchange="updateMedCount()">
                        <label for="med2" class="flex items-center gap-4 flex-1 cursor-pointer">
                            <div class="check-box w-7 h-7 rounded-lg border-2 border-border flex items-center justify-center transition-all">
                                <iconify-icon icon="lucide:check" width="16" height="16" class="text-white hidden"></iconify-icon>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-accent/30 flex items-center justify-center text-lg">🟢</div>
                            <div class="flex-1">
                                <p class="med-name text-[15px] font-semibold text-foreground transition-all">{{ __('dashboard.iron_supplement') }}</p>
                                <p class="text-xs text-muted-foreground">{{ __('dashboard.iron_supplement_dose') }}</p>
                            </div>
                        </label>
                    </div>
                    <div class="flex items-center gap-4">
                        <input type="checkbox" id="med3" class="med-check hidden" onchange="updateMedCount()">
                        <label for="med3" class="flex items-center gap-4 flex-1 cursor-pointer">
                            <div class="check-box w-7 h-7 rounded-lg border-2 border-border flex items-center justify-center transition-all">
                                <iconify-icon icon="lucide:check" width="16" height="16" class="text-white hidden"></iconify-icon>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-primary/15 flex items-center justify-center text-lg">🐟</div>
                            <div class="flex-1">
                                <p class="med-name text-[15px] font-semibold text-foreground transition-all">{{ __('dashboard.omega_3') }}</p>
                                <p class="text-xs text-muted-foreground">{{ __('dashboard.omega_3_dose') }}</p>
                            </div>
                        </label>
                    </div>
                    <div class="flex items-center gap-4">
                        <input type="checkbox" id="med4" class="med-check hidden" onchange="updateMedCount()">
                        <label for="med4" class="flex items-center gap-4 flex-1 cursor-pointer">
                            <div class="check-box w-7 h-7 rounded-lg border-2 border-border flex items-center justify-center transition-all">
                                <iconify-icon icon="lucide:check" width="16" height="16" class="text-white hidden"></iconify-icon>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-[#FDE047]/30 flex items-center justify-center text-lg">☀️</div>
                            <div class="flex-1">
                                <p class="med-name text-[15px] font-semibold text-foreground transition-all">{{ __('dashboard.vitamin_d3') }}</p>
                                <p class="text-xs text-muted-foreground">{{ __('dashboard.vitamin_d3_dose') }}</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Kick Counter Quick Widget -->
            <div class="mt-8 anim-in anim-d4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold font-heading text-foreground">{{ __('dashboard.kick_counter') }}</h2>
                    <span class="text-xs font-bold text-accent-foreground bg-accent/20 px-3 py-1 rounded-full uppercase tracking-widest">{{ __('dashboard.quick') }}</span>
                </div>
                <div class="bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[1.4rem] bg-primary/10 flex items-center justify-center">
                            <span class="text-2xl">👣</span>
                        </div>
                        <div>
                            <p class="text-3xl font-extrabold font-heading text-foreground" id="quickKickNum">0</p>
                            <p class="text-xs text-muted-foreground font-medium">{{ __('dashboard.kicks_today') }}</p>
                        </div>
                    </div>
                    <button data-kick-button class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center shadow-[0_8px_20px_-4px_rgba(167,139,250,0.5)] active:scale-90 transition-transform">
                        <iconify-icon icon="lucide:plus" width="28" height="28"></iconify-icon>
                    </button>
                </div>
            </div>

            <!-- Daily Tips -->
            <div class="mt-10 mb-2 flex justify-between items-center anim-in anim-d5">
                <h2 class="text-xl font-bold font-heading text-foreground">{{ __('dashboard.daily_tips') }}</h2>
            </div>
            <div class="flex gap-4 overflow-x-auto no-scrollbar -mx-6 px-6 pb-6 pt-2 anim-in anim-d5">
                <div class="flex-shrink-0 w-64 bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20 flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-[1.2rem] bg-secondary flex items-center justify-center text-secondary-foreground">
                        <iconify-icon icon="lucide:droplets" width="24" height="24"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-foreground font-semibold text-lg mb-1">{{ __('dashboard.hydration_goal') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('dashboard.hydration_desc') }}</p>
                    </div>
                </div>
                <div class="flex-shrink-0 w-64 bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20 flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-[1.2rem] bg-accent/30 flex items-center justify-center text-accent-foreground">
                        <iconify-icon icon="lucide:activity" width="24" height="24"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-foreground font-semibold text-lg mb-1">{{ __('dashboard.gentle_movement') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('dashboard.gentle_movement_desc') }}</p>
                    </div>
                </div>
                <div class="flex-shrink-0 w-64 bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20 flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-[1.2rem] bg-primary/15 flex items-center justify-center text-primary">
                        <iconify-icon icon="lucide:moon" width="24" height="24"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-foreground font-semibold text-lg mb-1">{{ __('dashboard.sleep_position') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('dashboard.sleep_position_desc') }}</p>
                    </div>
                </div>
            </div>

        </main>

        @include('telegram_bot.components.bottom-nav')

    </div>
@endsection
