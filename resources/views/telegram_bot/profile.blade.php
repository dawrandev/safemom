<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Profile - SafeMom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&family=Quicksand:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: 'rgb(var(--background) / <alpha-value>)',
                        foreground: 'rgb(var(--foreground) / <alpha-value>)',
                        card: {
                            DEFAULT: 'rgb(var(--card) / <alpha-value>)',
                            foreground: 'rgb(var(--card-foreground) / <alpha-value>)'
                        },
                        primary: {
                            DEFAULT: 'rgb(var(--primary) / <alpha-value>)',
                            foreground: 'rgb(var(--primary-foreground) / <alpha-value>)'
                        },
                        secondary: {
                            DEFAULT: 'rgb(var(--secondary) / <alpha-value>)',
                            foreground: 'rgb(var(--secondary-foreground) / <alpha-value>)'
                        },
                        muted: {
                            DEFAULT: 'rgb(var(--muted) / <alpha-value>)',
                            foreground: 'rgb(var(--muted-foreground) / <alpha-value>)'
                        },
                        accent: {
                            DEFAULT: 'rgb(var(--accent) / <alpha-value>)',
                            foreground: 'rgb(var(--accent-foreground) / <alpha-value>)'
                        },
                        destructive: {
                            DEFAULT: 'rgb(var(--destructive) / <alpha-value>)',
                            foreground: 'rgb(var(--destructive-foreground) / <alpha-value>)'
                        },
                        border: 'rgb(var(--border) / <alpha-value>)',
                        input: 'rgb(var(--input) / <alpha-value>)',
                        ring: 'rgb(var(--ring) / <alpha-value>)',
                    },
                    fontFamily: {
                        sans: ['Nunito', 'system-ui', 'sans-serif'],
                        heading: ['Quicksand', 'system-ui', 'sans-serif'],
                        serif: ['Playfair Display', 'Georgia', 'serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    borderRadius: {
                        sm: 'calc(var(--radius) - 4px)',
                        md: 'calc(var(--radius) - 2px)',
                        lg: 'var(--radius)',
                        xl: 'calc(var(--radius) + 4px)',
                        '2xl': 'calc(var(--radius) + 8px)',
                        '3xl': 'calc(var(--radius) + 12px)',
                    },
                },
            },
        }
    </script>
    <style>
        :root {
            --background: 253 251 247;
            --foreground: 51 65 85;
            --card: 255 255 255;
            --card-foreground: 51 65 85;
            --primary: 167 139 250;
            --primary-foreground: 255 255 255;
            --secondary: 255 228 230;
            --secondary-foreground: 190 18 60;
            --muted: 241 245 249;
            --muted-foreground: 148 163 184;
            --accent: 167 243 208;
            --accent-foreground: 6 95 70;
            --destructive: 253 164 175;
            --destructive-foreground: 255 255 255;
            --border: 226 232 240;
            --input: 226 232 240;
            --ring: 167 139 250;
            --radius: 1.5rem;
        }

        body {
            font-family: 'Nunito', system-ui, sans-serif;
            background: rgb(253 251 247);
            color: rgb(51 65 85);
            -webkit-font-smoothing: antialiased;
        }

        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        *::-webkit-scrollbar {
            display: none;
        }

        button,
        a,
        [role="button"] {
            transition: all 0.15s ease;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .anim-in {
            animation: fadeInUp 0.5s ease both;
        }

        .anim-d1 {
            animation-delay: .05s;
        }

        .anim-d2 {
            animation-delay: .12s;
        }

        .anim-d3 {
            animation-delay: .2s;
        }

        .anim-d4 {
            animation-delay: .28s;
        }

        .form-input {
            width: 100%;
            background: rgb(241 245 249);
            border: 2px solid transparent;
            border-radius: 1.2rem;
            padding: 14px 18px;
            font-size: 15px;
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
            color: rgb(51 65 85);
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: rgb(167 139 250);
            background: white;
            box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.12);
        }

        .form-input::placeholder {
            color: rgb(148 163 184);
            font-weight: 500;
        }

        .form-input:disabled {
            opacity: 0.7;
            cursor: default;
        }

        .form-select {
            width: 100%;
            background: rgb(241 245 249);
            border: 2px solid transparent;
            border-radius: 1.2rem;
            padding: 14px 18px;
            font-size: 15px;
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
            color: rgb(51 65 85);
            outline: none;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
        }

        .form-select:focus {
            border-color: rgb(167 139 250);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.12);
        }

        .form-select:disabled {
            opacity: 0.7;
            cursor: default;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                max-height: 200px;
                transform: translateY(0);
            }
        }

        .slide-down {
            animation: slideDown 0.35s ease both;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(100%);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes overlayIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-in {
            animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .overlay-in {
            animation: overlayIn 0.3s ease both;
        }
    </style>
</head>

<body>
    <div class="flex flex-col h-screen bg-background overflow-hidden relative">

        <!-- Header -->
        <header class="flex justify-between items-end px-6 pt-14 pb-4 shrink-0 anim-in">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <a href="{{ route('telegram.webapp.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-card rounded-xl shadow-sm border border-border/40">
                        <iconify-icon icon="lucide:chevron-left" width="20" height="20" class="text-foreground"></iconify-icon>
                    </a>
                    <span class="text-sm font-medium text-muted-foreground">Orqaga</span>
                </div>
                <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground mt-2">Profil</h1>
            </div>
            <button onclick="toggleEdit()" id="editToggle" class="h-10 px-5 flex items-center justify-center gap-2 bg-primary/10 text-primary rounded-full font-bold text-sm tracking-wide">
                <iconify-icon icon="lucide:pencil" width="16" height="16" id="editIcon"></iconify-icon>
                <span id="editLabel">Tahrirlash</span>
            </button>
        </header>

        <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-40">

            <!-- Avatar & Name Card -->
            <div class="mt-4 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col items-center anim-in anim-d1">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-lg">
                        <span class="text-4xl font-extrabold text-white font-heading">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-accent border-4 border-card flex items-center justify-center">
                        <iconify-icon icon="lucide:camera" width="14" height="14" class="text-accent-foreground"></iconify-icon>
                    </div>
                </div>
                <h2 class="text-2xl font-extrabold font-heading text-foreground mt-4">{{ $user->name ?? '' }} {{ $user->surname ?? '' }}</h2>
                <div class="flex items-center gap-3 mt-2">
                    <div class="flex items-center gap-1.5 bg-primary/10 px-3 py-1 rounded-full">
                        <iconify-icon icon="lucide:calendar" width="14" height="14" class="text-primary"></iconify-icon>
                        <span class="text-xs font-bold text-primary">{{ $profile?->getPregnancyWeek() ?? 0 }}-hafta</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-secondary/40 px-3 py-1 rounded-full">
                        <iconify-icon icon="lucide:droplets" width="14" height="14" class="text-secondary-foreground"></iconify-icon>
                        <span class="text-xs font-bold text-secondary-foreground">{{ $profile->blood_type ?? 'unknown' }} guruh</span>
                    </div>
                </div>
            </div>

            <!-- Personal Info Section -->
            <div class="mt-8 anim-in anim-d2">
                <h3 class="text-lg font-bold font-heading text-foreground mb-4 flex items-center gap-2">
                    <iconify-icon icon="lucide:user" width="20" height="20" class="text-primary"></iconify-icon>
                    Shaxsiy ma'lumotlar
                </h3>
                <div class="bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Ism</label>
                            <input type="text" class="form-input profile-field" value="{{ $user->name ?? '' }}" disabled />
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Familiya</label>
                            <input type="text" class="form-input profile-field" value="{{ $user->surname ?? '' }}" disabled />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Yosh</label>
                            <input type="number" class="form-input profile-field" value="{{ $profile->age ?? '' }}" disabled />
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Qon guruhi</label>
                            <select class="form-select profile-field" disabled>
                                <option {{ ($profile->blood_type ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option {{ ($profile->blood_type ?? '') == 'A-' ? 'selected' : '' }}>A−</option>
                                <option {{ ($profile->blood_type ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option {{ ($profile->blood_type ?? '') == 'B-' ? 'selected' : '' }}>B−</option>
                                <option {{ ($profile->blood_type ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option {{ ($profile->blood_type ?? '') == 'AB-' ? 'selected' : '' }}>AB−</option>
                                <option {{ ($profile->blood_type ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option {{ ($profile->blood_type ?? '') == 'O-' ? 'selected' : '' }}>O−</option>
                                <option {{ ($profile->blood_type ?? 'unknown') == 'unknown' ? 'selected' : '' }}>Unknown</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Bo'y (sm)</label>
                            <input type="number" class="form-input profile-field" value="{{ $profile->height ?? '' }}" disabled />
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Vazn (kg)</label>
                            <input type="number" class="form-input profile-field" value="{{ $profile->current_weight ?? '' }}" disabled />
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Oxirgi hayz sanasi</label>
                        <input type="date" class="form-input profile-field" value="{{ $profile->lmp_date?->format('Y-m-d') ?? '' }}" disabled />
                    </div>
                </div>
            </div>

            <!-- Medications Section -->
            <div class="mt-8 anim-in anim-d3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold font-heading text-foreground flex items-center gap-2">
                        <iconify-icon icon="lucide:pill" width="20" height="20" class="text-primary"></iconify-icon>
                        Dori-darmonlar
                    </h3>
                    <button onclick="openMedModal()" class="h-9 px-4 flex items-center gap-2 bg-primary text-white rounded-full text-sm font-bold shadow-md shadow-primary/20 active:scale-95">
                        <iconify-icon icon="lucide:plus" width="16" height="16"></iconify-icon>
                        <span>Qo'shish</span>
                    </button>
                </div>

                <div class="space-y-3" id="medsList">
                    @forelse($medications as $medication)
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center med-item" data-med-id="{{ $medication->id }}">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-[1.2rem] bg-secondary flex items-center justify-center text-lg">💊</div>
                            <div>
                                <p class="text-[15px] font-bold text-foreground">{{ $medication->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <iconify-icon icon="lucide:clock" width="13" height="13" class="text-muted-foreground"></iconify-icon>
                                    <span class="text-xs font-medium text-muted-foreground">{{ $medication->time_to_take }}{{ $medication->dosage ? ' — ' . $medication->dosage : '' }}</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="removeMed(this, {{ $medication->id }})" class="w-9 h-9 rounded-full bg-muted flex items-center justify-center text-muted-foreground hover:bg-destructive/20 hover:text-destructive transition-colors">
                            <iconify-icon icon="lucide:trash-2" width="16" height="16"></iconify-icon>
                        </button>
                    </div>
                    @empty
                    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm text-center">
                        <p class="text-sm text-muted-foreground">Hozircha dorilar yo'q</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Doctor Info Section -->
            <div class="mt-8 anim-in anim-d4">
                <h3 class="text-lg font-bold font-heading text-foreground mb-4 flex items-center gap-2">
                    <iconify-icon icon="lucide:stethoscope" width="20" height="20" class="text-primary"></iconify-icon>
                    Shifokor ma'lumotlari
                </h3>
                <div class="bg-card rounded-[2rem] p-6 shadow-[0_8px_24px_rgb(0,0,0,0.03)] border border-border/20">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-16 h-16 rounded-[1.4rem] bg-primary/10 flex items-center justify-center">
                            <iconify-icon icon="lucide:user-round" width="32" height="32" class="text-primary"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <p class="text-lg font-bold text-foreground">Dr. Sarah Smith</p>
                            <p class="text-sm text-muted-foreground font-medium">Ginekolog-akusher</p>
                        </div>
                        <button class="w-11 h-11 rounded-full bg-accent flex items-center justify-center shadow-md shadow-accent/20">
                            <iconify-icon icon="lucide:phone" width="20" height="20" class="text-accent-foreground"></iconify-icon>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3 bg-muted rounded-[1.2rem] px-5 py-3.5">
                            <iconify-icon icon="lucide:building-2" width="18" height="18" class="text-muted-foreground"></iconify-icon>
                            <div class="flex-1">
                                <p class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest">Klinika</p>
                                <p class="text-sm font-semibold text-foreground">City Women's Health Center</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-muted rounded-[1.2rem] px-5 py-3.5">
                            <iconify-icon icon="lucide:phone" width="18" height="18" class="text-muted-foreground"></iconify-icon>
                            <div class="flex-1">
                                <p class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest">Telefon</p>
                                <p class="text-sm font-semibold text-foreground">+998 71 234 56 78</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-muted rounded-[1.2rem] px-5 py-3.5">
                            <iconify-icon icon="lucide:calendar-check" width="18" height="18" class="text-muted-foreground"></iconify-icon>
                            <div class="flex-1">
                                <p class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest">Keyingi tashrif</p>
                                <p class="text-sm font-semibold text-foreground">28 Okt, 2025 — 10:00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings & Logout -->
            <div class="mt-8 space-y-3 pb-8">
                <button class="w-full bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex items-center gap-4">
                    <div class="w-11 h-11 rounded-[1rem] bg-muted flex items-center justify-center">
                        <iconify-icon icon="lucide:bell" width="20" height="20" class="text-foreground"></iconify-icon>
                    </div>
                    <span class="text-[15px] font-semibold text-foreground flex-1 text-left">Bildirishnomalar</span>
                    <iconify-icon icon="lucide:chevron-right" width="20" height="20" class="text-muted-foreground"></iconify-icon>
                </button>
                <button class="w-full bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex items-center gap-4">
                    <div class="w-11 h-11 rounded-[1rem] bg-muted flex items-center justify-center">
                        <iconify-icon icon="lucide:globe" width="20" height="20" class="text-foreground"></iconify-icon>
                    </div>
                    <span class="text-[15px] font-semibold text-foreground flex-1 text-left">Til: O'zbekcha</span>
                    <iconify-icon icon="lucide:chevron-right" width="20" height="20" class="text-muted-foreground"></iconify-icon>
                </button>
                <button class="w-full bg-destructive/10 rounded-[1.8rem] p-5 border border-destructive/10 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-[1rem] bg-destructive/20 flex items-center justify-center">
                        <iconify-icon icon="lucide:log-out" width="20" height="20" class="text-destructive"></iconify-icon>
                    </div>
                    <span class="text-[15px] font-semibold text-destructive flex-1 text-left">Chiqish</span>
                </button>
            </div>

        </main>

        <!-- Add Medication Modal -->
        <div id="medModal" class="absolute inset-0 z-50" style="display:none">
            <div class="absolute inset-0 bg-black/30 overlay-in" onclick="closeMedModal()"></div>
            <div class="absolute bottom-0 left-0 right-0 bg-background rounded-t-[2.5rem] p-6 pb-10 modal-in">
                <div class="w-10 h-1.5 bg-border rounded-full mx-auto mb-6"></div>
                <h3 class="text-xl font-bold font-heading text-foreground mb-6">Yangi dori qo'shish</h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Dori nomi</label>
                        <input type="text" class="form-input" placeholder="Masalan: Folic Acid" id="newMedName" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Vaqti</label>
                            <input type="time" class="form-input" value="09:00" id="newMedTime" />
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Payt</label>
                            <select class="form-select" id="newMedPeriod">
                                <option>Ertalab</option>
                                <option>Tushlik</option>
                                <option>Kechqurun</option>
                                <option>Uxlashdan oldin</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1.5 block">Emoji (ixtiyoriy)</label>
                        <div class="flex gap-3">
                            <button onclick="selectEmoji(this,'💊')" class="emoji-btn w-12 h-12 rounded-xl bg-muted flex items-center justify-center text-xl border-2 border-transparent transition-all">💊</button>
                            <button onclick="selectEmoji(this,'💉')" class="emoji-btn w-12 h-12 rounded-xl bg-muted flex items-center justify-center text-xl border-2 border-transparent transition-all">💉</button>
                            <button onclick="selectEmoji(this,'🟢')" class="emoji-btn w-12 h-12 rounded-xl bg-muted flex items-center justify-center text-xl border-2 border-transparent transition-all">🟢</button>
                            <button onclick="selectEmoji(this,'🐟')" class="emoji-btn w-12 h-12 rounded-xl bg-muted flex items-center justify-center text-xl border-2 border-transparent transition-all">🐟</button>
                            <button onclick="selectEmoji(this,'☀️')" class="emoji-btn w-12 h-12 rounded-xl bg-muted flex items-center justify-center text-xl border-2 border-transparent transition-all">☀️</button>
                            <button onclick="selectEmoji(this,'🫐')" class="emoji-btn w-12 h-12 rounded-xl bg-muted flex items-center justify-center text-xl border-2 border-transparent transition-all">🫐</button>
                        </div>
                    </div>
                </div>

                <button onclick="addMedication()" class="mt-6 w-full bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97]">
                    <iconify-icon icon="lucide:plus-circle" width="22" height="22"></iconify-icon>
                    <span class="text-[17px] font-semibold tracking-wide">Dori qo'shish</span>
                </button>
            </div>
        </div>

        <!-- Save Banner (shown after edit) -->
        <div id="saveBanner" class="absolute top-0 left-0 right-0 z-50 p-4 pt-16 flex justify-center" style="display:none">
            <div class="bg-accent text-accent-foreground px-6 py-3 rounded-full font-bold text-sm flex items-center gap-2 shadow-lg shadow-accent/20 slide-down">
                <iconify-icon icon="lucide:check-circle-2" width="18" height="18"></iconify-icon>
                Ma'lumotlar saqlandi!
            </div>
        </div>

        <!-- Bottom Navigation (Updated with Profile) -->
        <div class="absolute bottom-8 left-6 right-6 z-40">
            <div class="bg-card/90 backdrop-blur-xl rounded-[2.5rem] shadow-[0_12px_40px_rgb(0,0,0,0.08)] border border-white px-5 py-5 flex justify-between items-center">
                <a href="{{ route('telegram.webapp.dashboard') }}" class="flex flex-col items-center gap-1.5 text-muted-foreground">
                    <div class="relative flex items-center justify-center w-8 h-8">
                        <iconify-icon icon="lucide:home" width="22" height="22"></iconify-icon>
                    </div>
                    <span class="text-[10px] font-medium tracking-wide">Home</span>
                </a>
                <a href="{{ route('telegram.webapp.monitoring') }}" class="flex flex-col items-center gap-1.5 text-muted-foreground">
                    <div class="relative flex items-center justify-center w-8 h-8">
                        <iconify-icon icon="lucide:heart-pulse" width="22" height="22"></iconify-icon>
                    </div>
                    <span class="text-[10px] font-medium tracking-wide">Monitoring</span>
                </a>
                <a href="{{ route('telegram.webapp.community_support') }}" class="flex flex-col items-center gap-1.5 text-muted-foreground">
                    <div class="relative flex items-center justify-center w-8 h-8">
                        <iconify-icon icon="lucide:message-circle" width="22" height="22"></iconify-icon>
                    </div>
                    <span class="text-[10px] font-medium tracking-wide">Chat</span>
                </a>
                <a href="{{ route('telegram.webapp.health_trend') }}" class="flex flex-col items-center gap-1.5 text-muted-foreground">
                    <div class="relative flex items-center justify-center w-8 h-8">
                        <iconify-icon icon="lucide:bar-chart-3" width="22" height="22"></iconify-icon>
                    </div>
                    <span class="text-[10px] font-medium tracking-wide">History</span>
                </a>
                <a href="{{ route('telegram.webapp.profile') }}" class="flex flex-col items-center gap-1.5 text-primary">
                    <div class="relative flex items-center justify-center w-8 h-8">
                        <div class="absolute inset-0 bg-primary/15 rounded-full scale-125"></div>
                        <iconify-icon icon="lucide:user-circle" width="22" height="22" class="relative z-10"></iconify-icon>
                    </div>
                    <span class="text-[10px] font-bold tracking-wide">Profile</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        let isEditing = false;
        let selectedEmojiVal = '💊';

        function toggleEdit() {
            isEditing = !isEditing;
            const fields = document.querySelectorAll('.profile-field');
            fields.forEach(f => f.disabled = !isEditing);

            const label = document.getElementById('editLabel');
            const icon = document.getElementById('editIcon');
            const toggle = document.getElementById('editToggle');

            if (isEditing) {
                label.textContent = 'Saqlash';
                icon.setAttribute('icon', 'lucide:check');
                toggle.className = 'h-10 px-5 flex items-center justify-center gap-2 bg-primary text-white rounded-full font-bold text-sm tracking-wide shadow-md shadow-primary/20';
            } else {
                label.textContent = 'Tahrirlash';
                icon.setAttribute('icon', 'lucide:pencil');
                toggle.className = 'h-10 px-5 flex items-center justify-center gap-2 bg-primary/10 text-primary rounded-full font-bold text-sm tracking-wide';
                // Show save banner
                showSaveBanner();
            }
        }

        function showSaveBanner() {
            const banner = document.getElementById('saveBanner');
            banner.style.display = '';
            setTimeout(() => {
                banner.style.display = 'none';
            }, 2500);
        }

        function openMedModal() {
            document.getElementById('medModal').style.display = '';
            document.getElementById('newMedName').value = '';
            document.getElementById('newMedTime').value = '09:00';
            selectedEmojiVal = '💊';
            document.querySelectorAll('.emoji-btn').forEach(b => b.style.borderColor = 'transparent');
        }

        function closeMedModal() {
            document.getElementById('medModal').style.display = 'none';
        }

        function selectEmoji(btn, emoji) {
            selectedEmojiVal = emoji;
            document.querySelectorAll('.emoji-btn').forEach(b => b.style.borderColor = 'transparent');
            btn.style.borderColor = 'rgb(167 139 250)';
        }

        async function addMedication() {
            const name = document.getElementById('newMedName').value.trim();
            const time = document.getElementById('newMedTime').value;
            const period = document.getElementById('newMedPeriod').value;

            if (!name) {
                document.getElementById('newMedName').style.borderColor = 'rgb(253 164 175)';
                setTimeout(() => document.getElementById('newMedName').style.borderColor = 'transparent', 1500);
                return;
            }

            try {
                const response = await fetch('{{ route("telegram.webapp.medications.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        dosage: period,
                        time_to_take: time
                    })
                });

                const result = await response.json();

                if (result.success) {
                    const bgColors = ['bg-secondary', 'bg-accent/30', 'bg-primary/15', 'bg-[#FDE047]/30', 'bg-[#93C5FD]/20'];
                    const bgColor = bgColors[Math.floor(Math.random() * bgColors.length)];

                    const html = `
    <div class="bg-card rounded-[1.8rem] p-5 border border-border/20 shadow-sm flex justify-between items-center med-item slide-down" data-med-id="${result.data.id}">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-[1.2rem] ${bgColor} flex items-center justify-center text-lg">${selectedEmojiVal}</div>
        <div>
          <p class="text-[15px] font-bold text-foreground">${name}</p>
          <div class="flex items-center gap-2 mt-1">
            <iconify-icon icon="lucide:clock" width="13" height="13" class="text-muted-foreground"></iconify-icon>
            <span class="text-xs font-medium text-muted-foreground">${time} — ${period}</span>
          </div>
        </div>
      </div>
      <button onclick="removeMed(this, ${result.data.id})" class="w-9 h-9 rounded-full bg-muted flex items-center justify-center text-muted-foreground hover:bg-destructive/20 hover:text-destructive transition-colors">
        <iconify-icon icon="lucide:trash-2" width="16" height="16"></iconify-icon>
      </button>
    </div>
  `;
                    document.getElementById('medsList').insertAdjacentHTML('beforeend', html);
                    closeMedModal();
                } else {
                    alert('Xatolik: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Dori qo\'shishda xatolik yuz berdi');
            }
        }

        async function removeMed(btn, medId) {
            const item = btn.closest('.med-item');

            try {
                const response = await fetch('{{ route("telegram.webapp.medications.destroy", ":id") }}'.replace(':id', medId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(80px)';
                    item.style.transition = 'all 0.3s ease';
                    setTimeout(() => item.remove(), 300);
                } else {
                    alert('Xatolik: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Dorini o\'chirishda xatolik yuz berdi');
            }
        }
    </script>
</body>

</html>