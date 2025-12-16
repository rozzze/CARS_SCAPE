<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iniciar Sesión - {{ config('app.name', 'Cars Scape') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts / Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        
        <!-- Fallback styles to match Welcome page exactly -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'sans-serif'],
                        },
                        colors: {
                            pastel: {
                                blue: '#A5B4FC',
                                pink: '#FDA4AF',
                                purple: '#D8B4FE',
                            }
                        }
                    }
                }
            }
        </script>
        
        <style>
            body { font-family: 'Outfit', sans-serif; }
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
        </style>
    </head>
    <body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden selection:bg-indigo-300 selection:text-white min-h-screen flex flex-col">
        
        <!-- Abstract Pastel Background -->
        <div class="fixed inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50"></div>
            
            <!-- Soft Blobs for depth -->
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        </div>

        <div class="relative z-10 flex flex-col min-h-screen items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Navbar / Logo Area (Home Link) -->
            <div class="absolute top-6 left-6 sm:top-8 sm:left-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group" wire:navigate>
                    <div class="bg-indigo-400 rounded-xl p-2 shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-700 hover:text-indigo-600 transition-colors">CARS SCAPE</span>
                </a>
            </div>

            <!-- Login Card -->
            <div class="w-full max-w-md">
                
                <div class="bg-white/80 backdrop-blur-xl border border-white p-8 rounded-3xl shadow-xl shadow-indigo-100/50 relative overflow-hidden">
                    
                    <div class="relative z-10 flex flex-col gap-6">
                        
                        <!-- Header -->
                        <div class="text-center space-y-2">
                            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Bienvenido de nuevo</h2>
                            <p class="text-slate-500 text-sm">Inicia sesión en tu cuenta</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="text-center" :status="session('status')" />

                        <!-- Form -->
                        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                            @csrf

                            <!-- Email Address -->
                            <div class="space-y-1">
                                <flux:input
                                    name="email"
                                    label="Correo electrónico"
                                    type="email"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    placeholder="correo@ejemplo.com"
                                    class="bg-white border-slate-200 text-slate-800 placeholder-slate-400 focus:border-indigo-400 focus:ring-indigo-400/50 rounded-xl"
                                />
                            </div>

                            <!-- Password -->
                            <div class="relative space-y-1">
                                <div class="flex justify-between items-center mb-1">
                                    <label for="password" class="block text-sm font-medium text-slate-600">Contraseña</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-xs text-indigo-500 hover:text-indigo-600 font-medium transition-colors" wire:navigate>
                                            ¿Olvidaste tu contraseña?
                                        </a>
                                    @endif
                                </div>
                                
                                <flux:input
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Ingresa tu contraseña"
                                    viewable
                                    class="bg-white border-slate-200 text-slate-800 placeholder-slate-400 focus:border-indigo-400 focus:ring-indigo-400/50 rounded-xl"
                                />
                            </div>

                            <!-- Remember Me -->
                            <flux:checkbox 
                                name="remember" 
                                label="Recordarme" 
                                :checked="old('remember')" 
                                class="text-slate-600"
                            />

                            <!-- Submit Button -->
                            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-slate-800 to-slate-700 hover:from-indigo-600 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-200/50 transition-all duration-300 transform hover:-translate-y-0.5 mt-2">
                                Iniciar Sesión
                            </button>
                        </form>

                        <!-- Sign Up Link -->
                        @if (Route::has('register'))
                            <div class="text-center text-sm text-slate-500 mt-4">
                                ¿No tienes una cuenta? 
                                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-bold transition-colors ml-1" wire:navigate>
                                    Regístrate
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center text-xs text-slate-400/80">
                &copy; {{ date('Y') }} Cars Scape. Acceso Seguro.
            </div>
        </div>

        @fluxScripts
    </body>
</html>
