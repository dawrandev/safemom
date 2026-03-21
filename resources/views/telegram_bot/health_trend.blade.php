@extends('telegram_bot.layouts.webapp')

@section('title', 'Health Trends - MamaCare')

@push('styles')
@vite('resources/css/telegram_bot/health_trend.css')
@endpush

@push('scripts')
@vite('resources/js/telegram_bot/health_trend.js')
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
                <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground mt-2">Health Trends</h1>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-40">

            <!-- Export PDF Button -->
            <div class="mt-4 anim-in">
                <button onclick="exportPDF(this)" class="w-full bg-card border border-border/40 rounded-full py-4 px-6 flex items-center justify-center gap-3 shadow-[0_4px_16px_rgb(0,0,0,0.03)] active:scale-[0.97]">
                    <iconify-icon icon="lucide:file-text" width="22" height="22" class="text-primary"></iconify-icon>
                    <span class="text-[16px] font-semibold tracking-wide text-foreground">Export Monthly PDF Report</span>
                </button>
            </div>

            <!-- Chart Tabs -->
            <div class="mt-8 flex gap-6 border-b border-border/40 anim-in" style="animation-delay:.05s">
                <button onclick="showChart('bp')" class="tab-btn pb-3 text-sm font-bold tab-active" id="tabBP">Blood Pressure</button>
                <button onclick="showChart('weight')" class="tab-btn pb-3 text-sm font-bold text-muted-foreground" id="tabWeight">Weight</button>
            </div>

            <!-- Blood Pressure Chart -->
            <div class="mt-6 bg-card rounded-[2.5rem] p-6 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 anim-in" style="animation-delay:.1s" id="chartBP">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Systolic / Diastolic</p>
                        <p class="text-2xl font-extrabold font-heading text-foreground mt-1">Last 7 Days</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-semibold">
                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 rounded-full bg-primary"></div>Systolic
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 rounded-full bg-accent"></div>Diastolic
                        </div>
                    </div>
                </div>
                <svg viewBox="0 0 340 180" class="w-full">
                    <!-- Grid lines -->
                    <line x1="40" y1="20" x2="330" y2="20" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="40" y1="55" x2="330" y2="55" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="40" y1="90" x2="330" y2="90" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="40" y1="125" x2="330" y2="125" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="40" y1="160" x2="330" y2="160" stroke="#F1F5F9" stroke-width="1" />
                    <!-- Y labels -->
                    <text x="5" y="24" fill="#94A3B8" font-size="10" font-family="Nunito">160</text>
                    <text x="5" y="59" fill="#94A3B8" font-size="10" font-family="Nunito">140</text>
                    <text x="5" y="94" fill="#94A3B8" font-size="10" font-family="Nunito">120</text>
                    <text x="5" y="129" fill="#94A3B8" font-size="10" font-family="Nunito">100</text>
                    <text x="12" y="164" fill="#94A3B8" font-size="10" font-family="Nunito">80</text>
                    <!-- X labels -->
                    <text x="50" y="178" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">Mon</text>
                    <text x="98" y="178" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">Tue</text>
                    <text x="146" y="178" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">Wed</text>
                    <text x="194" y="178" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">Thu</text>
                    <text x="242" y="178" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">Fri</text>
                    <text x="290" y="178" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">Sat</text>
                    <text x="322" y="178" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">Sun</text>
                    <!-- Systolic line (118,120,122,119,130,125,128) -->
                    <polyline points="50,93 98,90 146,86 194,92 242,73 290,80 322,76" fill="none" stroke="#A78BFA" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    <!-- Diastolic line (76,78,75,77,85,80,82) -->
                    <polyline points="50,146 98,143 146,148 194,144 242,131 290,140 322,137" fill="none" stroke="#A7F3D0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    <!-- Systolic dots -->
                    <circle cx="50" cy="93" r="4" fill="#A78BFA" />
                    <circle cx="98" cy="90" r="4" fill="#A78BFA" />
                    <circle cx="146" cy="86" r="4" fill="#A78BFA" />
                    <circle cx="194" cy="92" r="4" fill="#A78BFA" />
                    <circle cx="242" cy="73" r="5" fill="#A78BFA" stroke="white" stroke-width="2" />
                    <circle cx="290" cy="80" r="4" fill="#A78BFA" />
                    <circle cx="322" cy="76" r="4" fill="#A78BFA" />
                    <!-- Diastolic dots -->
                    <circle cx="50" cy="146" r="4" fill="#A7F3D0" />
                    <circle cx="98" cy="143" r="4" fill="#A7F3D0" />
                    <circle cx="146" cy="148" r="4" fill="#A7F3D0" />
                    <circle cx="194" cy="144" r="4" fill="#A7F3D0" />
                    <circle cx="242" cy="131" r="5" fill="#A7F3D0" stroke="white" stroke-width="2" />
                    <circle cx="290" cy="140" r="4" fill="#A7F3D0" />
                    <circle cx="322" cy="137" r="4" fill="#A7F3D0" />
                    <!-- Alert zone -->
                    <rect x="40" y="20" width="290" height="35" fill="#FDA4AF" fill-opacity="0.08" rx="4" />
                    <text x="335" y="40" fill="#FDA4AF" font-size="8" font-family="Nunito" text-anchor="end">High</text>
                </svg>
            </div>

            <!-- Weight Chart (hidden by default) -->
            <div class="mt-6 bg-card rounded-[2.5rem] p-6 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 hidden" id="chartWeight">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Weight Trend</p>
                        <p class="text-2xl font-extrabold font-heading text-foreground mt-1">Weeks 18–24</p>
                    </div>
                    <div class="flex items-center gap-1 text-xs font-semibold">
                        <div class="w-3 h-3 rounded-full bg-primary"></div>lbs
                    </div>
                </div>
                <svg viewBox="0 0 340 160" class="w-full">
                    <line x1="40" y1="20" x2="330" y2="20" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="40" y1="50" x2="330" y2="50" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="40" y1="80" x2="330" y2="80" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="40" y1="110" x2="330" y2="110" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="40" y1="140" x2="330" y2="140" stroke="#F1F5F9" stroke-width="1" />
                    <text x="5" y="24" fill="#94A3B8" font-size="10" font-family="Nunito">160</text>
                    <text x="5" y="54" fill="#94A3B8" font-size="10" font-family="Nunito">155</text>
                    <text x="5" y="84" fill="#94A3B8" font-size="10" font-family="Nunito">150</text>
                    <text x="5" y="114" fill="#94A3B8" font-size="10" font-family="Nunito">145</text>
                    <text x="5" y="144" fill="#94A3B8" font-size="10" font-family="Nunito">140</text>
                    <text x="65" y="158" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">W18</text>
                    <text x="121" y="158" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">W19</text>
                    <text x="177" y="158" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">W20</text>
                    <text x="233" y="158" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">W21</text>
                    <text x="289" y="158" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">W22</text>
                    <text x="325" y="158" fill="#94A3B8" font-size="9" font-family="Nunito" text-anchor="middle">W24</text>
                    <!-- Gradient area -->
                    <defs>
                        <linearGradient id="wg" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#A78BFA" stop-opacity="0.2" />
                            <stop offset="100%" stop-color="#A78BFA" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    <path d="M65,128 L121,116 L177,104 L233,86 L289,68 L325,50 L325,140 L65,140 Z" fill="url(#wg)" />
                    <polyline points="65,128 121,116 177,104 233,86 289,68 325,50" fill="none" stroke="#A78BFA" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    <circle cx="65" cy="128" r="4" fill="#A78BFA" />
                    <circle cx="121" cy="116" r="4" fill="#A78BFA" />
                    <circle cx="177" cy="104" r="4" fill="#A78BFA" />
                    <circle cx="233" cy="86" r="4" fill="#A78BFA" />
                    <circle cx="289" cy="68" r="4" fill="#A78BFA" />
                    <circle cx="325" cy="50" r="5" fill="#A78BFA" stroke="white" stroke-width="2" />
                </svg>
                <div class="mt-4 flex items-center gap-3 bg-accent/10 p-4 rounded-2xl">
                    <iconify-icon icon="lucide:trending-up" width="20" height="20" class="text-accent-foreground"></iconify-icon>
                    <p class="text-sm text-muted-foreground"><span class="font-bold text-foreground">+8 lbs</span> total gain — within healthy range for week 24</p>
                </div>
            </div>

            <!-- History List -->
            <div class="mt-10 anim-in" style="animation-delay:.15s">
                <h3 class="text-lg font-bold font-heading text-foreground mb-4">Past Analyses</h3>
                <div class="space-y-3">
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-[1rem] bg-accent/20 flex items-center justify-center"><iconify-icon icon="lucide:check-circle-2" width="20" height="20" class="text-accent-foreground"></iconify-icon></div>
                            <div>
                                <p class="text-[15px] font-semibold text-foreground">All Clear</p>
                                <p class="text-xs text-muted-foreground">BP: 118/76 · HR: 72 · Temp: 98.4°F</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-muted-foreground">Today</span>
                    </div>
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-[1rem] bg-secondary/30 flex items-center justify-center"><iconify-icon icon="lucide:alert-triangle" width="20" height="20" class="text-secondary-foreground"></iconify-icon></div>
                            <div>
                                <p class="text-[15px] font-semibold text-foreground">Monitor</p>
                                <p class="text-xs text-muted-foreground">BP: 132/86 · HR: 88 · Temp: 98.8°F</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-muted-foreground">Oct 11</span>
                    </div>
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-[1rem] bg-accent/20 flex items-center justify-center"><iconify-icon icon="lucide:check-circle-2" width="20" height="20" class="text-accent-foreground"></iconify-icon></div>
                            <div>
                                <p class="text-[15px] font-semibold text-foreground">All Clear</p>
                                <p class="text-xs text-muted-foreground">BP: 115/74 · HR: 70 · Temp: 98.2°F</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-muted-foreground">Oct 10</span>
                    </div>
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-[1rem] bg-accent/20 flex items-center justify-center"><iconify-icon icon="lucide:check-circle-2" width="20" height="20" class="text-accent-foreground"></iconify-icon></div>
                            <div>
                                <p class="text-[15px] font-semibold text-foreground">All Clear</p>
                                <p class="text-xs text-muted-foreground">BP: 120/78 · HR: 74 · Temp: 98.6°F</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-muted-foreground">Oct 9</span>
                    </div>
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-[1rem] bg-destructive/20 flex items-center justify-center"><iconify-icon icon="lucide:alert-circle" width="20" height="20" class="text-destructive"></iconify-icon></div>
                            <div>
                                <p class="text-[15px] font-semibold text-foreground">Alert</p>
                                <p class="text-xs text-muted-foreground">BP: 142/91 · HR: 95 · Temp: 100.2°F</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-muted-foreground">Oct 8</span>
                    </div>
                </div>
            </div>

            <div class="h-8"></div>
        </main>

        @include('telegram_bot.components.bottom-nav')
    </div>
@endsection
