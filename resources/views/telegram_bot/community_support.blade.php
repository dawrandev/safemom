@extends('telegram_bot.layouts.webapp')

@section('title', 'Community & Support - SafeMom')

@push('styles')
@vite('resources/css/telegram_bot/community_support.css')
@endpush

@push('scripts')
<script>
    window.translations = {
        typeMessage: "{{ __('community.type_message') }}",
        doctorName: "{{ __('community.doctor_name') }}"
    };
</script>
@vite('resources/js/telegram_bot/community_support.js')
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
                <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground mt-2">{{ __('community.support') }}</h1>
            </div>
            @include('telegram_bot.components.language-switcher')
        </header>

        <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-24">

            <!-- Emergency SOS Button -->
            <div class="mt-4 anim-in">
                <button class="w-full bg-destructive text-white rounded-[2rem] py-5 px-6 flex items-center gap-4 shadow-[0_16px_32px_-12px_rgba(253,164,175,0.6)] active:scale-[0.97]">
                    <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <iconify-icon icon="lucide:phone-call" width="28" height="28"></iconify-icon>
                    </div>
                    <div class="text-left">
                        <p class="text-[17px] font-bold tracking-wide">{{ __('community.emergency_call') }}</p>
                        <p class="text-sm opacity-80 font-medium">{{ __('community.call_doctor') }}</p>
                    </div>
                </button>
            </div>

            <!-- Doctor Chat Section -->
            <div class="mt-8 anim-in" style="animation-delay:.1s">
                <h2 class="text-xl font-bold font-heading text-foreground mb-4">{{ __('community.doctor_chat') }}</h2>

                <div class="bg-card rounded-[2.5rem] border border-border/30 shadow-[0_12px_40px_rgb(0,0,0,0.04)] overflow-hidden">
                    <!-- Doctor Info Bar -->
                    <div class="p-5 border-b border-border/30 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary/15 flex items-center justify-center">
                            <iconify-icon icon="lucide:stethoscope" width="24" height="24" class="text-primary"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <p class="text-[15px] font-bold text-foreground">{{ __('community.doctor_name') }}</p>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-accent"></div>
                                <p class="text-xs text-muted-foreground font-medium">{{ __('community.online_status', ['minutes' => __('community.online_minutes')]) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="p-5 space-y-4 max-h-[320px] overflow-y-auto no-scrollbar" id="chatMessages">
                        <!-- Doctor message -->
                        <div class="flex gap-3 items-end">
                            <div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="lucide:stethoscope" width="14" height="14" class="text-primary"></iconify-icon>
                            </div>
                            <div class="bg-muted rounded-[1.4rem] rounded-bl-lg px-5 py-3 max-w-[80%]">
                                <p class="text-[14px] leading-relaxed text-foreground">Good morning Sarah! I saw your BP readings from yesterday. How are you feeling today?</p>
                                <p class="text-[10px] text-muted-foreground mt-1">9:30 AM</p>
                            </div>
                        </div>
                        <!-- User message -->
                        <div class="flex gap-3 items-end justify-end">
                            <div class="bg-primary text-white rounded-[1.4rem] rounded-br-lg px-5 py-3 max-w-[80%]">
                                <p class="text-[14px] leading-relaxed">Hi Dr. Smith! I'm feeling better today. The headache went away after rest.</p>
                                <p class="text-[10px] opacity-70 mt-1">9:45 AM</p>
                            </div>
                        </div>
                        <!-- Doctor message -->
                        <div class="flex gap-3 items-end">
                            <div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="lucide:stethoscope" width="14" height="14" class="text-primary"></iconify-icon>
                            </div>
                            <div class="bg-muted rounded-[1.4rem] rounded-bl-lg px-5 py-3 max-w-[80%]">
                                <p class="text-[14px] leading-relaxed text-foreground">That's great to hear! Keep monitoring your BP today and let me know if the systolic goes above 130 again. Also remember to stay hydrated 💧</p>
                                <p class="text-[10px] text-muted-foreground mt-1">9:52 AM</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="p-4 border-t border-border/30 flex items-center gap-3">
                        <button class="w-10 h-10 rounded-full bg-muted flex items-center justify-center flex-shrink-0">
                            <iconify-icon icon="lucide:paperclip" width="18" height="18" class="text-muted-foreground"></iconify-icon>
                        </button>
                        <div class="flex-1 bg-muted rounded-full px-5 py-3 flex items-center">
                            <input type="text" placeholder="{{ __('community.type_message') }}" class="bg-transparent w-full text-[14px] text-foreground outline-none placeholder:text-muted-foreground" id="chatInput">
                        </div>
                        <button id="sendMessageBtn" class="w-10 h-10 rounded-full bg-primary flex items-center justify-center flex-shrink-0 shadow-md shadow-primary/20">
                            <iconify-icon icon="lucide:send" width="18" height="18" class="text-white"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Hub / Articles -->
            <div class="mt-10 anim-in" style="animation-delay:.2s">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold font-heading text-foreground">{{ __('community.info_hub') }}</h2>
                    <span class="text-sm font-bold text-primary">{{ __('common.see_all') }}</span>
                </div>

                <!-- Weekly Tip Card -->
                <div class="bg-gradient-to-br from-primary/10 to-accent/10 rounded-[2rem] p-6 border border-primary/10 mb-4">
                    <div class="flex items-center gap-2 mb-3">
                        <iconify-icon icon="lucide:lightbulb" width="18" height="18" class="text-primary"></iconify-icon>
                        <span class="text-xs font-bold text-primary uppercase tracking-widest">{{ __('community.week_tip', ['week' => 24]) }}</span>
                    </div>
                    <h3 class="text-lg font-bold font-heading text-foreground mb-2">{{ __('community.glucose_test') }}</h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">{{ __('community.glucose_desc') }}</p>
                </div>

                <!-- Article Cards -->
                <div class="space-y-3">
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-secondary/30 flex items-center justify-center flex-shrink-0">
                            <iconify-icon icon="lucide:baby" width="24" height="24" class="text-secondary-foreground"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <p class="text-[15px] font-semibold text-foreground">{{ __('community.baby_development', ['week' => 24]) }}</p>
                            <p class="text-xs text-muted-foreground mt-0.5">{{ __('community.baby_development_desc') }}</p>
                        </div>
                        <iconify-icon icon="lucide:chevron-right" width="20" height="20" class="text-muted-foreground"></iconify-icon>
                    </div>
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-accent/20 flex items-center justify-center flex-shrink-0">
                            <iconify-icon icon="lucide:salad" width="24" height="24" class="text-accent-foreground"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <p class="text-[15px] font-semibold text-foreground">{{ __('community.nutrition_trimester') }}</p>
                            <p class="text-xs text-muted-foreground mt-0.5">{{ __('community.nutrition_desc') }}</p>
                        </div>
                        <iconify-icon icon="lucide:chevron-right" width="20" height="20" class="text-muted-foreground"></iconify-icon>
                    </div>
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-primary/15 flex items-center justify-center flex-shrink-0">
                            <iconify-icon icon="lucide:bed" width="24" height="24" class="text-primary"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <p class="text-[15px] font-semibold text-foreground">{{ __('community.sleep_positions') }}</p>
                            <p class="text-xs text-muted-foreground mt-0.5">{{ __('community.sleep_positions_desc') }}</p>
                        </div>
                        <iconify-icon icon="lucide:chevron-right" width="20" height="20" class="text-muted-foreground"></iconify-icon>
                    </div>
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-[#FDE047]/20 flex items-center justify-center flex-shrink-0">
                            <iconify-icon icon="lucide:heart-handshake" width="24" height="24" class="text-[#CA8A04]"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <p class="text-[15px] font-semibold text-foreground">{{ __('community.mental_health') }}</p>
                            <p class="text-xs text-muted-foreground mt-0.5">{{ __('community.mental_health_desc') }}</p>
                        </div>
                        <iconify-icon icon="lucide:chevron-right" width="20" height="20" class="text-muted-foreground"></iconify-icon>
                    </div>
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[1.2rem] bg-destructive/15 flex items-center justify-center flex-shrink-0">
                            <iconify-icon icon="lucide:alert-triangle" width="24" height="24" class="text-destructive"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <p class="text-[15px] font-semibold text-foreground">{{ __('community.warning_signs') }}</p>
                            <p class="text-xs text-muted-foreground mt-0.5">{{ __('community.warning_signs_desc') }}</p>
                        </div>
                        <iconify-icon icon="lucide:chevron-right" width="20" height="20" class="text-muted-foreground"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="h-8"></div>
        </main>

        @include('telegram_bot.components.bottom-nav')
    </div>
@endsection
