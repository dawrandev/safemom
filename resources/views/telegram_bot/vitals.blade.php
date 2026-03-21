@extends('telegram_bot.layouts.webapp')

@section('title', 'Vitals & AI Analysis - MamaCare')

@push('styles')
@vite('resources/css/telegram_bot/vitals.css')
@endpush

@push('scripts')
@vite('resources/js/telegram_bot/vitals.js')
@endpush

@section('content')
    <div class="flex flex-col h-screen bg-background overflow-hidden relative" id="app">

        <!-- INPUT VIEW -->
        <div id="inputView">
            <!-- Header -->
            <header class="flex flex-col px-6 pt-14 pb-4 shrink-0 gap-6">
                <div class="flex justify-between items-center">
                    <a href="{{ route('telegram.webapp.dashboard') }}" class="w-12 h-12 flex items-center justify-center bg-card rounded-[1.2rem] shadow-[0_4px_16px_rgb(0,0,0,0.03)] border border-border/40">
                        <iconify-icon icon="lucide:chevron-left" width="24" height="24" class="text-foreground"></iconify-icon>
                    </a>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs font-bold text-primary uppercase tracking-widest">Step 2 of 3</span>
                        <div class="w-24 h-1.5 bg-muted rounded-full overflow-hidden">
                            <div class="w-2/3 h-full bg-primary rounded-full"></div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-1">
                    <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground leading-tight">How are your<br />vitals today?</h1>
                    <p class="text-[15px] text-muted-foreground">Slide to adjust your current readings.</p>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-32" style="max-height: calc(100vh - 260px);">
                <!-- Blood Pressure -->
                <div class="mt-4 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col gap-6 anim-in">
                    <div class="flex justify-between items-start">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Blood Pressure</span>
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
                        <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-2 block">Systolic</label>
                        <input type="range" min="80" max="200" value="120" id="systolic" oninput="updateBP()">
                        <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-2 block mt-4">Diastolic</label>
                        <input type="range" min="40" max="130" value="80" id="diastolic" oninput="updateBP()">
                        <div class="flex justify-between mt-3 text-[10px] font-bold text-muted-foreground uppercase tracking-widest">
                            <span>Low</span><span>Normal</span><span>High</span>
                        </div>
                    </div>
                </div>

                <!-- Heart Rate -->
                <div class="mt-6 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col gap-6 anim-in" style="animation-delay:.1s">
                    <div class="flex justify-between items-start">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Resting Heart Rate</span>
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
                        <input type="range" min="40" max="140" value="72" id="heartRate" oninput="document.getElementById('hrDisplay').textContent=this.value">
                        <div class="flex justify-between mt-3 text-[10px] font-bold text-muted-foreground uppercase tracking-widest">
                            <span>40</span><span>80</span><span>120+</span>
                        </div>
                    </div>
                </div>

                <!-- Temperature -->
                <div class="mt-6 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col gap-6 anim-in" style="animation-delay:.2s">
                    <div class="flex justify-between items-start">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Temperature</span>
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
                        <input type="range" min="960" max="1040" value="984" id="temp" oninput="document.getElementById('tempDisplay').textContent=(this.value/10).toFixed(1)">
                    </div>
                </div>

                <!-- AI Insight -->
                <div class="mt-8 px-4 flex items-start gap-4 bg-primary/5 p-6 rounded-[2rem] border border-primary/10 anim-in" style="animation-delay:.3s">
                    <div class="w-10 h-10 rounded-full bg-primary flex flex-shrink-0 items-center justify-center shadow-lg shadow-primary/20 text-white">
                        <iconify-icon icon="lucide:sparkles" width="20" height="20"></iconify-icon>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-primary mb-1 tracking-tight">AI insight</h4>
                        <p class="text-[14px] leading-relaxed text-muted-foreground">I'll analyze these numbers against your historical data to ensure everything is within your personal healthy range.</p>
                    </div>
                </div>
            </main>

            <!-- Analyze Button -->
            <div class="absolute bottom-0 left-0 right-0 p-6 pt-2 pb-10 bg-gradient-to-t from-background via-background to-transparent z-40">
                <button onclick="analyzeVitals()" class="w-full bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97]">
                    <span class="text-[17px] font-semibold tracking-wide">Analyze Results</span>
                    <iconify-icon icon="lucide:arrow-right" width="22" height="22"></iconify-icon>
                </button>
            </div>
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
                <h2 class="text-2xl font-bold font-heading text-foreground">Analyzing...</h2>
                <p class="text-muted-foreground mt-2 text-[15px]">AI is reviewing your vitals against 40 weeks of data</p>
            </div>
        </div>

        <!-- RESULT VIEW -->
        <div id="resultView" class="hidden absolute inset-0 bg-background z-50 flex flex-col overflow-hidden">
            <header class="flex justify-between items-end px-6 pt-14 pb-4 shrink-0">
                <div class="flex flex-col gap-1">
                    <span class="text-sm font-medium text-muted-foreground tracking-wide uppercase">Analysis Complete</span>
                    <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground">AI Summary</h1>
                </div>
                <button onclick="resetView()" class="w-12 h-12 flex items-center justify-center bg-card rounded-[1.2rem] shadow-[0_4px_16px_rgb(0,0,0,0.03)] border border-border/40">
                    <iconify-icon icon="lucide:x" width="24" height="24" class="text-muted-foreground"></iconify-icon>
                </button>
            </header>

            <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-32">
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
                    <h3 class="text-lg font-bold font-heading text-foreground">Logged Vitals</h3>
                </div>
                <div class="space-y-3" id="vitalsCards"></div>

                <!-- AI Nutrition Advice Card -->
                <div class="mt-8 bg-primary/5 border border-primary/10 rounded-[2rem] p-6" id="nutritionCard">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white">
                            <iconify-icon icon="lucide:salad" width="20" height="20"></iconify-icon>
                        </div>
                        <h4 class="text-lg font-bold font-heading text-foreground">Nutrition Advice</h4>
                    </div>
                    <p class="text-[14px] leading-relaxed text-muted-foreground" id="nutritionText"></p>
                </div>

                <!-- Actions -->
                <div class="mt-10 flex flex-col gap-4 pb-12">
                    <button class="w-full bg-destructive text-white rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(253,164,175,0.6)]" id="emergencyBtn" style="display:none">
                        <iconify-icon icon="lucide:phone" width="22" height="22"></iconify-icon>
                        <span class="text-[17px] font-semibold tracking-wide">Call Dr. Smith (Emergency)</span>
                    </button>
                    <button onclick="resetView()" class="w-full bg-muted text-foreground border border-border/60 rounded-full py-5 px-6 flex items-center justify-center gap-3">
                        <span class="text-[17px] font-semibold tracking-wide">Save & Return to Home</span>
                    </button>
                </div>
            </main>
        </div>

        @include('telegram_bot.components.bottom-nav')
    </div>
@endsection
