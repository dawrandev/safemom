<!-- Bottom Navigation -->
<div id="bottomNav" class="absolute bottom-6 left-6 right-6 z-50">
    <div class="bg-card/90 backdrop-blur-xl rounded-[2.5rem] shadow-[0_12px_40px_rgb(0,0,0,0.08)] border border-white px-6 py-5 flex justify-between items-center">
        <a href="{{ route('telegram.webapp.dashboard') }}" class="flex flex-col items-center gap-1.5 {{ request()->routeIs('telegram.webapp.dashboard') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }} transition-colors">
            <div class="relative flex items-center justify-center w-8 h-8">
                @if(request()->routeIs('telegram.webapp.dashboard'))
                    <div class="absolute inset-0 bg-primary/15 rounded-full scale-125"></div>
                @endif
                <iconify-icon icon="lucide:home" width="24" height="24" class="relative z-10"></iconify-icon>
            </div>
            <span class="text-[11px] {{ request()->routeIs('telegram.webapp.dashboard') ? 'font-bold' : 'font-medium' }} tracking-wide">{{ __('nav.home') }}</span>
        </a>
        <a href="{{ route('telegram.webapp.monitoring') }}" class="flex flex-col items-center gap-1.5 {{ request()->routeIs('telegram.webapp.monitoring') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }} transition-colors">
            <div class="relative flex items-center justify-center w-8 h-8">
                @if(request()->routeIs('telegram.webapp.monitoring'))
                    <div class="absolute inset-0 bg-primary/15 rounded-full scale-125"></div>
                @endif
                <iconify-icon icon="lucide:heart-pulse" width="24" height="24" class="relative z-10"></iconify-icon>
            </div>
            <span class="text-[11px] {{ request()->routeIs('telegram.webapp.monitoring') ? 'font-bold' : 'font-medium' }} tracking-wide">{{ __('nav.monitoring') }}</span>
        </a>
        <a href="{{ route('telegram.webapp.community_support') }}" class="flex flex-col items-center gap-1.5 {{ request()->routeIs('telegram.webapp.community_support') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }} transition-colors">
            <div class="relative flex items-center justify-center w-8 h-8">
                @if(request()->routeIs('telegram.webapp.community_support'))
                    <div class="absolute inset-0 bg-primary/15 rounded-full scale-125"></div>
                @endif
                <iconify-icon icon="lucide:message-circle" width="24" height="24" class="relative z-10"></iconify-icon>
            </div>
            <span class="text-[11px] {{ request()->routeIs('telegram.webapp.community_support') ? 'font-bold' : 'font-medium' }} tracking-wide">{{ __('nav.chat') }}</span>
        </a>
        <a href="{{ route('telegram.webapp.health_trend') }}" class="flex flex-col items-center gap-1.5 {{ request()->routeIs('telegram.webapp.health_trend') ? 'text-primary' : 'text-muted-foreground hover:text-primary' }} transition-colors">
            <div class="relative flex items-center justify-center w-8 h-8">
                @if(request()->routeIs('telegram.webapp.health_trend'))
                    <div class="absolute inset-0 bg-primary/15 rounded-full scale-125"></div>
                @endif
                <iconify-icon icon="lucide:bar-chart-3" width="24" height="24" class="relative z-10"></iconify-icon>
            </div>
            <span class="text-[11px] {{ request()->routeIs('telegram.webapp.health_trend') ? 'font-bold' : 'font-medium' }} tracking-wide">{{ __('nav.history') }}</span>
        </a>
    </div>
</div>
