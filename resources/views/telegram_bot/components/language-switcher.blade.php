<!-- Language Switcher -->
<div class="flex items-center gap-2">
    <a href="{{ route('setLanguage', 'uz') }}"
       class="px-3 py-1.5 rounded-full text-xs font-bold transition-all {{ app()->getLocale() === 'uz' ? 'bg-primary text-white' : 'bg-muted text-muted-foreground hover:bg-primary/10' }}">
        UZ
    </a>
    <a href="{{ route('setLanguage', 'ru') }}"
       class="px-3 py-1.5 rounded-full text-xs font-bold transition-all {{ app()->getLocale() === 'ru' ? 'bg-primary text-white' : 'bg-muted text-muted-foreground hover:bg-primary/10' }}">
        RU
    </a>
    <a href="{{ route('setLanguage', 'en') }}"
       class="px-3 py-1.5 rounded-full text-xs font-bold transition-all {{ app()->getLocale() === 'en' ? 'bg-primary text-white' : 'bg-muted text-muted-foreground hover:bg-primary/10' }}">
        EN
    </a>
</div>
