<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Onboarding - SafeMom</title>
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
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOutDown {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.92);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes confettiDrop {
            0% {
                opacity: 1;
                transform: translateY(0) rotate(0deg);
            }

            100% {
                opacity: 0;
                transform: translateY(120px) rotate(360deg);
            }
        }

        @keyframes dashboardSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(30px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .anim-in {
            animation: fadeInUp 0.5s ease both;
        }

        .anim-out {
            animation: fadeOutDown 0.35s ease both;
        }

        .anim-scale {
            animation: scaleIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .anim-dashboard {
            animation: dashboardSlideIn 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .step-container {
            display: none;
        }

        .step-container.active {
            display: flex;
            flex-direction: column;
        }

        /* Custom input styling */
        .form-input {
            width: 100%;
            background: rgb(241 245 249);
            border: 2px solid transparent;
            border-radius: 1.2rem;
            padding: 16px 20px;
            font-size: 16px;
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

        .form-select {
            width: 100%;
            background: rgb(241 245 249);
            border: 2px solid transparent;
            border-radius: 1.2rem;
            padding: 16px 20px;
            font-size: 16px;
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
            color: rgb(51 65 85);
            outline: none;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
        }

        .form-select:focus {
            border-color: rgb(167 139 250);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.12);
        }

        /* Blood group chip selector */
        .blood-chip {
            padding: 12px 20px;
            border-radius: 1rem;
            border: 2px solid rgb(226 232 240);
            background: rgb(241 245 249);
            font-size: 15px;
            font-weight: 700;
            color: rgb(51 65 85);
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Quicksand', sans-serif;
        }

        .blood-chip:hover {
            border-color: rgb(167 139 250);
            background: rgba(167, 139, 250, 0.05);
        }

        .blood-chip.selected {
            border-color: rgb(167 139 250);
            background: rgb(167 139 250);
            color: white;
            box-shadow: 0 4px 16px rgba(167, 139, 250, 0.3);
        }

        /* Progress bar */
        .progress-fill {
            transition: width 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Confetti particle */
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 2px;
            animation: confettiDrop 1.5s ease-out forwards;
        }
    </style>
</head>

<body>
    <div class="flex flex-col h-screen bg-background overflow-hidden relative" id="app">

        <!-- ==================== WELCOME SCREEN ==================== -->
        <div id="welcomeScreen" class="absolute inset-0 z-50 bg-background flex flex-col items-center justify-center px-8">
            <div class="flex flex-col items-center anim-scale">
                <!-- Logo / Welcome Illustration -->
                <div class="relative w-32 h-32 mb-8">
                    <div class="absolute inset-0 bg-primary/10 rounded-full"></div>
                    <div class="absolute inset-3 bg-primary/20 rounded-full"></div>
                    <div class="absolute inset-6 bg-primary rounded-full flex items-center justify-center shadow-lg shadow-primary/30">
                        <iconify-icon icon="lucide:heart" width="40" height="40" class="text-white"></iconify-icon>
                    </div>
                </div>

                <h1 class="text-4xl font-extrabold font-heading text-foreground tracking-tight text-center">SafeMom</h1>
                <p class="text-lg text-muted-foreground mt-3 text-center leading-relaxed max-w-[280px]">
                    Sizning sog'lom homiladorlik yo'ldoshingiz
                </p>

                <div class="mt-4 inline-flex items-center gap-2 bg-accent/20 px-4 py-2 rounded-full">
                    <iconify-icon icon="lucide:shield-check" width="16" height="16" class="text-accent-foreground"></iconify-icon>
                    <span class="text-sm font-bold text-accent-foreground">AI-powered monitoring</span>
                </div>
            </div>

            <button onclick="startOnboarding()" class="mt-12 w-full max-w-xs bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97] anim-in" style="animation-delay:.3s">
                <span class="text-[17px] font-semibold tracking-wide">Boshlash</span>
                <iconify-icon icon="lucide:arrow-right" width="22" height="22"></iconify-icon>
            </button>

            <p class="mt-6 text-xs text-muted-foreground text-center anim-in" style="animation-delay:.4s">Ma'lumotlaringiz xavfsiz saqlanadi</p>
        </div>

        <!-- ==================== ONBOARDING FORM ==================== -->
        <div id="onboardingScreen" class="absolute inset-0 z-40 bg-background flex flex-col overflow-hidden" style="display:none">

            <!-- Header with progress -->
            <header class="flex flex-col px-6 pt-14 pb-2 shrink-0 gap-4">
                <div class="flex justify-between items-center">
                    <button onclick="prevStep()" id="backBtn" class="w-12 h-12 flex items-center justify-center bg-card rounded-[1.2rem] shadow-[0_4px_16px_rgb(0,0,0,0.03)] border border-border/40 opacity-0 pointer-events-none transition-opacity">
                        <iconify-icon icon="lucide:chevron-left" width="24" height="24" class="text-foreground"></iconify-icon>
                    </button>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs font-bold text-primary uppercase tracking-widest" id="stepLabel">1 / 3</span>
                        <div class="w-28 h-2 bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-primary to-accent rounded-full progress-fill" id="progressBar" style="width:33.3%"></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Steps Container -->
            <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-32">

                <!-- ===== STEP 1: Personal Info ===== -->
                <div id="step1" class="step-container active">
                    <div class="mt-4 mb-8 anim-in">
                        <div class="w-16 h-16 rounded-[1.6rem] bg-primary/10 flex items-center justify-center mb-5">
                            <iconify-icon icon="lucide:user-circle" width="32" height="32" class="text-primary"></iconify-icon>
                        </div>
                        <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground leading-tight">Keling,<br />tanishamiz!</h1>
                        <p class="text-[15px] text-muted-foreground mt-2">Asosiy ma'lumotlaringizni kiriting</p>
                    </div>

                    <div class="space-y-5 anim-in" style="animation-delay:.1s">
                        <!-- Ism -->
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-2 block">Ism</label>
                            <input type="text" class="form-input" placeholder="Ismingiz" id="firstName" />
                        </div>
                        <!-- Familiya -->
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-2 block">Familiya</label>
                            <input type="text" class="form-input" placeholder="Familiyangiz" id="lastName" />
                        </div>
                        <!-- Yosh -->
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-2 block">Yosh</label>
                            <input type="number" class="form-input" placeholder="28" id="age" min="14" max="55" />
                        </div>
                    </div>
                </div>

                <!-- ===== STEP 2: Pregnancy & Health ===== -->
                <div id="step2" class="step-container">
                    <div class="mt-4 mb-8 anim-in">
                        <div class="w-16 h-16 rounded-[1.6rem] bg-secondary/40 flex items-center justify-center mb-5">
                            <iconify-icon icon="lucide:calendar-heart" width="32" height="32" class="text-secondary-foreground"></iconify-icon>
                        </div>
                        <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground leading-tight">Homiladorlik<br />ma'lumotlari</h1>
                        <p class="text-[15px] text-muted-foreground mt-2">Biz haftalik kuzatuvni hisoblash uchun ishlatamiz</p>
                    </div>

                    <div class="space-y-5 anim-in" style="animation-delay:.1s">
                        <!-- Oxirgi hayz sanasi -->
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-2 block">Oxirgi hayz sanasi (LMP)</label>
                            <div class="relative">
                                <input type="date" class="form-input pr-12" id="lmpDate" />
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <iconify-icon icon="lucide:calendar" width="20" height="20" class="text-muted-foreground"></iconify-icon>
                                </div>
                            </div>
                        </div>
                        <!-- Bo'y -->
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-2 block">Bo'y</label>
                            <div class="relative">
                                <input type="number" class="form-input pr-16" placeholder="165" id="height" min="100" max="220" />
                                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-sm font-bold text-muted-foreground">sm</span>
                            </div>
                        </div>
                        <!-- Vazn -->
                        <div>
                            <label class="text-xs font-bold text-muted-foreground uppercase tracking-widest mb-2 block">Vazn</label>
                            <div class="relative">
                                <input type="number" class="form-input pr-16" placeholder="62" id="weight" min="30" max="200" />
                                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-sm font-bold text-muted-foreground">kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== STEP 3: Blood Group ===== -->
                <div id="step3" class="step-container">
                    <div class="mt-4 mb-8 anim-in">
                        <div class="w-16 h-16 rounded-[1.6rem] bg-accent/20 flex items-center justify-center mb-5">
                            <iconify-icon icon="lucide:droplets" width="32" height="32" class="text-accent-foreground"></iconify-icon>
                        </div>
                        <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground leading-tight">Qon guruhingiz<br />qaysi?</h1>
                        <p class="text-[15px] text-muted-foreground mt-2">Shoshilmasdan tanlang — keyinroq o'zgartirish mumkin</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 anim-in" style="animation-delay:.1s" id="bloodGroupGrid">
                        <button onclick="selectBlood(this,'A+')" class="blood-chip flex items-center justify-center gap-2">
                            <span class="text-lg">A+</span>
                        </button>
                        <button onclick="selectBlood(this,'A-')" class="blood-chip flex items-center justify-center gap-2">
                            <span class="text-lg">A−</span>
                        </button>
                        <button onclick="selectBlood(this,'B+')" class="blood-chip flex items-center justify-center gap-2">
                            <span class="text-lg">B+</span>
                        </button>
                        <button onclick="selectBlood(this,'B-')" class="blood-chip flex items-center justify-center gap-2">
                            <span class="text-lg">B−</span>
                        </button>
                        <button onclick="selectBlood(this,'AB+')" class="blood-chip flex items-center justify-center gap-2">
                            <span class="text-lg">AB+</span>
                        </button>
                        <button onclick="selectBlood(this,'AB-')" class="blood-chip flex items-center justify-center gap-2">
                            <span class="text-lg">AB−</span>
                        </button>
                        <button onclick="selectBlood(this,'O+')" class="blood-chip flex items-center justify-center gap-2">
                            <span class="text-lg">O+</span>
                        </button>
                        <button onclick="selectBlood(this,'O-')" class="blood-chip flex items-center justify-center gap-2">
                            <span class="text-lg">O−</span>
                        </button>
                    </div>

                    <!-- Don't know option -->
                    <button onclick="selectBlood(null,'unknown')" class="mt-4 w-full text-center text-sm font-semibold text-muted-foreground underline underline-offset-4 decoration-border hover:text-primary">Bilmayman / Keyinroq kiritaman</button>
                </div>

            </main>

            <!-- Bottom CTA -->
            <div class="absolute bottom-0 left-0 right-0 p-6 pt-2 pb-10 bg-gradient-to-t from-background via-background to-transparent z-40">
                <button onclick="nextStep()" id="nextBtn" class="w-full bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97]">
                    <span class="text-[17px] font-semibold tracking-wide" id="nextBtnText">Davom etish</span>
                    <iconify-icon icon="lucide:arrow-right" width="22" height="22" id="nextBtnIcon"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- ==================== SUCCESS / TRANSITION SCREEN ==================== -->
        <div id="successScreen" class="absolute inset-0 z-60 bg-background flex flex-col items-center justify-center px-8" style="display:none">
            <div id="confettiContainer" class="absolute inset-0 overflow-hidden pointer-events-none"></div>

            <div class="flex flex-col items-center relative z-10 anim-scale">
                <div class="relative w-28 h-28 mb-6">
                    <div class="absolute inset-0 bg-accent/30 rounded-full animate-ping" style="animation-duration:1.5s"></div>
                    <div class="relative w-28 h-28 bg-accent rounded-full flex items-center justify-center shadow-lg shadow-accent/30">
                        <iconify-icon icon="lucide:check" width="48" height="48" class="text-accent-foreground"></iconify-icon>
                    </div>
                </div>

                <h1 class="text-3xl font-extrabold font-heading text-foreground tracking-tight text-center" id="successName">Tabriklaymiz!</h1>
                <p class="text-lg text-muted-foreground mt-3 text-center leading-relaxed max-w-[300px]">Profilingiz tayyor. Endi sog'lom homiladorlik safaryingizni boshlaymiz!</p>

                <div class="mt-6 flex items-center gap-3 bg-card rounded-[1.6rem] px-6 py-4 shadow-sm border border-border/30">
                    <div class="w-10 h-10 rounded-full bg-primary/15 flex items-center justify-center">
                        <span class="text-xl" id="successEmoji">🤰</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-foreground" id="successWeek">Hafta 24</p>
                        <p class="text-xs text-muted-foreground" id="successDue">Taxminiy tug'ilish: —</p>
                    </div>
                </div>
            </div>

            <button onclick="goToDashboard()" class="mt-10 w-full max-w-xs bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)] active:scale-[0.97] relative z-10 anim-in" style="animation-delay:.5s">
                <iconify-icon icon="lucide:home" width="22" height="22"></iconify-icon>
                <span class="text-[17px] font-semibold tracking-wide">Dashboard-ga o'tish</span>
            </button>
        </div>

        <!-- ==================== MINI DASHBOARD PREVIEW ==================== -->
        <div id="dashboardPreview" class="absolute inset-0 z-70 bg-background flex flex-col overflow-hidden" style="display:none">
            <header class="flex justify-between items-end px-6 pt-14 pb-4 shrink-0 anim-dashboard">
                <div class="flex flex-col gap-1">
                    <span class="text-sm font-medium text-muted-foreground" id="dashDate"></span>
                    <h1 class="text-3xl font-bold font-heading tracking-tight text-foreground" id="dashGreeting">Hi, Sarah</h1>
                </div>
                <div class="relative w-12 h-12 flex items-center justify-center bg-card rounded-[1.2rem] shadow-[0_4px_16px_rgb(0,0,0,0.03)] border border-border/40">
                    <iconify-icon icon="lucide:bell" width="22" height="22" class="text-foreground"></iconify-icon>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto no-scrollbar px-6 pb-40">
                <div class="mt-4 bg-card rounded-[2.5rem] p-8 shadow-[0_12px_40px_rgb(0,0,0,0.04)] border border-border/30 flex flex-col items-center anim-dashboard" style="animation-delay:.15s">
                    <div class="relative flex flex-col items-center justify-center w-[200px] h-[200px]">
                        <svg viewBox="0 0 200 200" class="w-full h-full absolute inset-0">
                            <defs>
                                <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#A78BFA" />
                                    <stop offset="100%" stop-color="#A7F3D0" />
                                </linearGradient>
                            </defs>
                            <circle cx="100" cy="100" r="84" fill="none" stroke="#F1F5F9" stroke-width="12" />
                            <circle cx="100" cy="100" r="84" fill="none" stroke="url(#grad)" stroke-width="14" stroke-linecap="round" stroke-dasharray="527.78" id="dashRing" stroke-dashoffset="527.78" transform="rotate(-90 100 100)" style="transition: stroke-dashoffset 1.5s cubic-bezier(0.16, 1, 0.3, 1)" />
                        </svg>
                        <div class="flex flex-col items-center justify-center relative z-10 pt-2">
                            <span class="text-xs font-semibold text-muted-foreground uppercase tracking-[0.2em] mb-1" id="dashTrimester">Trimester 2</span>
                            <span class="text-6xl font-extrabold text-foreground font-heading tracking-tighter" id="dashWeek">24</span>
                            <span class="text-sm font-medium text-primary mt-1 bg-primary/10 px-3 py-1 rounded-full">Hafta</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 anim-dashboard" style="animation-delay:.3s">
                    <button class="w-full bg-primary text-primary-foreground rounded-full py-5 px-6 flex items-center justify-center gap-3 shadow-[0_16px_32px_-12px_rgba(167,139,250,0.6)]">
                        <iconify-icon icon="lucide:sparkles" width="22" height="22"></iconify-icon>
                        <span class="text-[17px] font-semibold tracking-wide">Start AI Check-up</span>
                    </button>
                </div>
            </main>

            <!-- Bottom Nav -->
            <div class="absolute bottom-8 left-6 right-6 z-50 anim-dashboard" style="animation-delay:.45s">
                <div class="bg-card/90 backdrop-blur-xl rounded-[2.5rem] shadow-[0_12px_40px_rgb(0,0,0,0.08)] border border-white px-5 py-5 flex justify-between items-center">
                    <div class="flex flex-col items-center gap-1.5 text-primary">
                        <div class="relative flex items-center justify-center w-8 h-8">
                            <div class="absolute inset-0 bg-primary/15 rounded-full scale-125"></div>
                            <iconify-icon icon="lucide:home" width="22" height="22" class="relative z-10"></iconify-icon>
                        </div>
                        <span class="text-[10px] font-bold tracking-wide">Home</span>
                    </div>
                    <div class="flex flex-col items-center gap-1.5 text-muted-foreground">
                        <iconify-icon icon="lucide:heart-pulse" width="22" height="22"></iconify-icon>
                        <span class="text-[10px] font-medium tracking-wide">Monitoring</span>
                    </div>
                    <div class="flex flex-col items-center gap-1.5 text-muted-foreground">
                        <iconify-icon icon="lucide:message-circle" width="22" height="22"></iconify-icon>
                        <span class="text-[10px] font-medium tracking-wide">Chat</span>
                    </div>
                    <div class="flex flex-col items-center gap-1.5 text-muted-foreground">
                        <iconify-icon icon="lucide:bar-chart-3" width="22" height="22"></iconify-icon>
                        <span class="text-[10px] font-medium tracking-wide">History</span>
                    </div>
                    <div class="flex flex-col items-center gap-1.5 text-muted-foreground">
                        <iconify-icon icon="lucide:user-circle" width="22" height="22"></iconify-icon>
                        <span class="text-[10px] font-medium tracking-wide">Profile</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;
        let selectedBlood = '';

        function startOnboarding() {
            document.getElementById('welcomeScreen').style.display = 'none';
            document.getElementById('onboardingScreen').style.display = '';
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                // Animate out current
                const cur = document.getElementById('step' + currentStep);
                cur.classList.remove('active');
                currentStep++;

                // Animate in next
                const next = document.getElementById('step' + currentStep);
                next.classList.add('active');
                next.querySelectorAll('.anim-in').forEach(el => {
                    el.style.animation = 'none';
                    el.offsetHeight; // reflow
                    el.style.animation = '';
                });

                // Update progress
                document.getElementById('progressBar').style.width = ((currentStep / totalSteps) * 100) + '%';
                document.getElementById('stepLabel').textContent = currentStep + ' / ' + totalSteps;

                // Back button
                const backBtn = document.getElementById('backBtn');
                backBtn.style.opacity = '1';
                backBtn.style.pointerEvents = 'auto';

                // Last step => change button text
                if (currentStep === totalSteps) {
                    document.getElementById('nextBtnText').textContent = 'Tugallash';
                    document.getElementById('nextBtnIcon').setAttribute('icon', 'lucide:check');
                }
            } else {
                // Complete!
                finishOnboarding();
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                document.getElementById('step' + currentStep).classList.remove('active');
                currentStep--;
                document.getElementById('step' + currentStep).classList.add('active');

                document.getElementById('progressBar').style.width = ((currentStep / totalSteps) * 100) + '%';
                document.getElementById('stepLabel').textContent = currentStep + ' / ' + totalSteps;

                if (currentStep === 1) {
                    document.getElementById('backBtn').style.opacity = '0';
                    document.getElementById('backBtn').style.pointerEvents = 'none';
                }
                document.getElementById('nextBtnText').textContent = 'Davom etish';
                document.getElementById('nextBtnIcon').setAttribute('icon', 'lucide:arrow-right');
            }
        }

        function selectBlood(btn, group) {
            selectedBlood = group;
            document.querySelectorAll('.blood-chip').forEach(b => b.classList.remove('selected'));
            if (btn) btn.classList.add('selected');
        }

        async function finishOnboarding() {
            const name = document.getElementById('firstName').value || 'Sarah';
            const surname = document.getElementById('lastName').value || '';
            const age = document.getElementById('age').value;
            const lmp = document.getElementById('lmpDate').value;
            const height = document.getElementById('height').value;
            const weight = document.getElementById('weight').value;

            // Validate required fields
            if (!name || !surname || !age || !lmp || !height || !weight) {
                alert('Iltimos, barcha maydonlarni to\'ldiring');
                return;
            }

            // Send data to backend
            try {
                const response = await fetch('{{ route("telegram.webapp.profile.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        surname: surname,
                        age: parseInt(age),
                        lmp_date: lmp,
                        height: parseInt(height),
                        weight: parseFloat(weight),
                        blood_type: selectedBlood || 'unknown'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    const weeks = result.data.pregnancy_week;
                    const trimester = result.data.trimester;

                    // Calculate due date
                    let dueDate = '—';
                    if (lmp) {
                        const lmpDate = new Date(lmp);
                        const due = new Date(lmpDate.getTime() + 280 * 24 * 60 * 60 * 1000);
                        const months = ['Yan', 'Fev', 'Mar', 'Apr', 'May', 'Iyn', 'Iyl', 'Avg', 'Sen', 'Okt', 'Noy', 'Dek'];
                        dueDate = due.getDate() + ' ' + months[due.getMonth()] + ' ' + due.getFullYear();
                    }

                    // Show success
                    document.getElementById('onboardingScreen').style.display = 'none';
                    document.getElementById('successScreen').style.display = '';
                    document.getElementById('successName').textContent = name + ', tabriklaymiz!';
                    document.getElementById('successWeek').textContent = 'Hafta ' + weeks;
                    document.getElementById('successDue').textContent = 'Taxminiy tug\'ilish: ' + dueDate;

                    // Store for dashboard
                    window._profile = {
                        name,
                        weeks,
                        trimester,
                        dueDate
                    };

                    // Confetti!
                    spawnConfetti();
                } else {
                    alert('Xatolik: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ma\'lumotlarni saqlashda xatolik yuz berdi');
            }
        }

        function spawnConfetti() {
            const container = document.getElementById('confettiContainer');
            const colors = ['#A78BFA', '#A7F3D0', '#FDE047', '#FDA4AF', '#93C5FD'];
            for (let i = 0; i < 40; i++) {
                const c = document.createElement('div');
                c.className = 'confetti';
                c.style.left = Math.random() * 100 + '%';
                c.style.top = '-10px';
                c.style.background = colors[Math.floor(Math.random() * colors.length)];
                c.style.animationDelay = (Math.random() * 0.8) + 's';
                c.style.animationDuration = (1 + Math.random()) + 's';
                c.style.width = (6 + Math.random() * 8) + 'px';
                c.style.height = (6 + Math.random() * 8) + 'px';
                c.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
                container.appendChild(c);
            }
        }

        function goToDashboard() {
            // Redirect to dashboard
            window.location.href = '{{ route("telegram.webapp.dashboard") }}';
        }
    </script>
</body>

</html>