<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111827">
    <title>Task Go | Service Stopped</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top, rgba(251, 191, 36, 0.18), transparent 32%),
                linear-gradient(160deg, #0f172a 0%, #111827 48%, #1f2937 100%);
        }

        .panel-glow {
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
        }

        .status-pulse {
            animation: pulse 2.2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.82;
                transform: scale(0.96);
            }
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">
    <main class="relative isolate overflow-hidden min-h-screen">
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:72px_72px] opacity-40"></div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-6 py-16 lg:px-10">
            <div class="grid w-full gap-10 lg:grid-cols-[1.15fr_0.85fr]">
                <section class="rounded-[2rem] border border-white/10 bg-white/8 p-8 backdrop-blur-xl panel-glow sm:p-10 lg:p-12">
                    <div class="mb-8 flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-400 text-xl font-bold text-slate-950">TG</div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.35em] text-amber-300">Task Go</p>
                            <p class="text-xs text-slate-300">Public service notice</p>
                        </div>
                    </div>

                    <div class="mb-6 inline-flex items-center gap-3 rounded-full border border-red-400/35 bg-red-500/12 px-4 py-2 text-sm text-red-100">
                        <span class="status-pulse h-2.5 w-2.5 rounded-full bg-red-400"></span>
                        Payment operations unclear - platform stopped
                    </div>

                    <h1 class="max-w-3xl text-4xl font-black uppercase leading-tight text-white sm:text-5xl lg:text-6xl">
                        This site is currently stopped.
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
                        Task Go has been suspended because payment handling is not currently clear enough to keep the service running responsibly.
                        The homepage has been replaced with this notice until payment operations are reviewed and clarified.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl border border-white/10 bg-slate-950/35 p-5">
                            <p class="text-xs uppercase tracking-[0.28em] text-amber-300">Current status</p>
                            <p class="mt-3 text-2xl font-semibold text-white">Service unavailable</p>
                            <p class="mt-2 text-sm leading-6 text-slate-300">Public access remains on hold while payment clarity issues are resolved.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-slate-950/35 p-5">
                            <p class="text-xs uppercase tracking-[0.28em] text-amber-300">Effective date</p>
                            <p class="mt-3 text-2xl font-semibold text-white">{{ now()->format('F j, Y') }}</p>
                            <p class="mt-2 text-sm leading-6 text-slate-300">This notice is now the default home page for all visitors.</p>
                        </div>
                    </div>
                </section>

                <aside class="flex flex-col justify-center gap-6">
                    <div class="rounded-[2rem] border border-white/10 bg-slate-950/45 p-7 panel-glow backdrop-blur-md">
                        <p class="text-xs uppercase tracking-[0.28em] text-amber-300">Why visitors see this page</p>
                        <ul class="mt-5 space-y-4 text-sm leading-7 text-slate-200">
                            <li class="rounded-2xl border border-white/8 bg-white/5 px-4 py-3">Payment-related information is not sufficiently clear for continued operation.</li>
                            <li class="rounded-2xl border border-white/8 bg-white/5 px-4 py-3">The public landing page has been intentionally replaced to avoid sending the wrong message.</li>
                            <li class="rounded-2xl border border-white/8 bg-white/5 px-4 py-3">The platform will remain stopped until payment handling is reviewed and made explicit.</li>
                        </ul>
                    </div>

                    <div class="rounded-[2rem] border border-amber-300/20 bg-amber-400/10 p-7 backdrop-blur-md">
                        <p class="text-xs uppercase tracking-[0.28em] text-amber-200">Contact</p>
                        <div class="mt-3 space-y-3 text-base leading-7 text-amber-50">
                            <p>Support phone: <a href="tel:+8801700000000" class="font-semibold underline decoration-amber-200/60 underline-offset-4">+880 1700-000000</a></p>
                            <p>Telegram: <a href="https://t.me/sp1d3jr" target="_blank" rel="noopener noreferrer" class="font-semibold underline decoration-amber-200/60 underline-offset-4">t.me/sp1d3jr</a></p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
</body>
</html>
