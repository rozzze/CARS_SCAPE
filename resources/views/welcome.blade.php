<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Cars Scape') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Fallback if Vite is not running locally -->
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['Outfit', 'sans-serif'],
                            },
                            colors: {
                                pastel: {
                                    blue: '#A5B4FC',    /* indigo-300 */
                                    pink: '#FDA4AF',    /* rose-300 */
                                    purple: '#D8B4FE',  /* purple-300 */
                                    mint: '#6EE7B7',    /* emerald-300 */
                                    cream: '#FDFBF7',
                                }
                            }
                        }
                    }
                }
            </script>
        @endif
        
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden selection:bg-indigo-300 selection:text-white">
        
        <!-- Abstract Pastel Background -->
        <div class="fixed inset-0 z-0">
            <!-- Gradient Base -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100"></div>
            
            <!-- Soft Blobs for depth -->
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-1/3 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>
            
            <!-- Optional: Clean Car Silhoutte or detail could go here, but keeping it abstract and clean for "Pastel" vibe -->
        </div>

        <div class="relative z-10 flex flex-col min-h-screen">
            
            <!-- Navbar -->
            <header class="w-full py-6 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center gap-2 group">
                        <div class="bg-indigo-400 rounded-xl p-2 shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-700">CARS SCAPE</span>
                    </div>

                    <!-- Auth Links -->
                    @if (Route::has('login'))
                        <div class="flex gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 text-sm font-semibold text-slate-700 bg-white/60 border border-white/40 rounded-full backdrop-blur-md hover:bg-white hover:shadow-lg hover:shadow-indigo-100 transition-all duration-300">
                                    Panel de Control
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                                    Iniciar Sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="hidden sm:block px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-400 to-purple-400 rounded-full shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:scale-105 transition-all duration-300">
                                        Registrarse
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </header>

            <!-- Hero Section -->
            <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center space-y-8">
                    
                    <!-- Decorative Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-indigo-200 bg-white/50 backdrop-blur-sm shadow-sm mb-4 animate-fade-in-up">
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                        </span>
                        <span class="text-xs font-bold text-indigo-600 tracking-wide uppercase">Sistema Premium</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold tracking-tight leading-tight text-slate-800 drop-shadow-sm">
                        Controla tu viaje <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                            Sin Límites
                        </span>
                    </h1>

                    <!-- Subheadline -->
                    <p class="text-lg sm:text-x md:text-2xl text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal">
                        La plataforma definitiva para gestionar, rastrear y optimizar tu flota automotriz con precisión y estilo.
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-5 justify-center items-center pt-8">
                        @if (Route::has('register'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="group relative px-8 py-4 bg-slate-800 rounded-full text-white font-bold text-lg shadow-xl shadow-slate-300 hover:bg-slate-700 hover:shadow-2xl transition-all duration-300 w-full sm:w-auto overflow-hidden">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        Ir al Panel
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="group relative px-9 py-4 bg-slate-800 rounded-full text-white font-bold text-lg shadow-xl shadow-slate-300 hover:bg-slate-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        Comenzar Ahora
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </span>
                                </a>
                                
                                <a href="{{ route('login') }}" class="px-9 py-4 bg-white/70 border border-white/50 backdrop-blur-md rounded-full text-slate-700 font-bold text-lg hover:bg-white hover:shadow-lg hover:text-indigo-600 transition-all duration-300 w-full sm:w-auto">
                                    Ingresar
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="w-full py-8 text-center text-slate-400 text-sm relative z-10">
                <p>&copy; {{ date('Y') }} Cars Scape. Todos los derechos reservados.</p>
                <div class="flex justify-center gap-6 mt-4">
                    <div class="h-1.5 w-1.5 rounded-full bg-indigo-200"></div>
                    <div class="h-1.5 w-1.5 rounded-full bg-purple-200"></div>
                    <div class="h-1.5 w-1.5 rounded-full bg-pink-200"></div>
                </div>
            </footer>
        </div>

        <style>
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
            .animation-delay-4000 {
                animation-delay: 4s;
            }
            .animate-fade-in-up {
                animation: fadeInUp 1s ease-out;
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </body>
</html>
