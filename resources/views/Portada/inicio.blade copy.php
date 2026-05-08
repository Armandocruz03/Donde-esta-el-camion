<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¿Dónde está el Camión? | Oaxaca Limpia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #166534;
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap');
        
        body {
            font-family: 'Inter', system_ui, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2e9 100%);
        }
        
        .title-font {
            font-family: 'Space Grotesk', sans-serif;
        }

        .card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .live-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        #map {
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        .footer-image-card {
            transition: all 0.3s ease;
        }
        
        .footer-image-card:hover {
            transform: scale(1.03);
        }
    </style>
</head>
<body class="min-h-screen text-slate-800">

    <div class="max-w-7xl mx-auto px-6 py-12">

        <!-- HEADER -->
        <div class="flex flex-col items-center text-center mb-12">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-3xl shadow-lg">
                    🚛
                </div>
                <h1 class="title-font text-5xl font-bold tracking-tighter text-emerald-950">
                    ¿Dónde está el Camión?
                </h1>
            </div>
            <p class="text-xl text-slate-600 max-w-md">
                Monitoreo en tiempo real de la recolección de residuos en Oaxaca
            </p>
        </div>

        @if(auth()->check())
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-10 flex items-center gap-6 border border-emerald-100">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center text-4xl flex-shrink-0">
                👋
            </div>
            <div>
                <h2 class="text-3xl font-semibold text-emerald-900">Hola, {{ auth()->user()->name }}</h2>
                @if(auth()->user()->ruta)
                    <p class="text-emerald-700 mt-1 flex items-center gap-2">
                        <span class="inline-block w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                        Ruta asignada: <strong class="font-medium">{{ auth()->user()->ruta->nombre }}</strong>
                    </p>
                @else
                    <p class="text-amber-600 mt-1">⏳ Pendiente de asignación de ruta</p>
                @endif
            </div>
        </div>
        @endif

        <!-- STATS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="card bg-white rounded-3xl p-8 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-emerald-600 text-sm font-medium tracking-widest">UBICACIÓN EN VIVO</p>
                        <h3 class="text-2xl font-semibold mt-1">Camión en Ruta</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl">
                        📍
                    </div>
                </div>
                <div class="flex items-center gap-3 text-emerald-700 bg-emerald-50 px-5 py-3 rounded-2xl">
                    <span class="live-dot w-4 h-4 bg-emerald-500 rounded-full"></span>
                    <span class="font-semibold" id="estado">En movimiento • Calle Tulipanes</span>
                </div>
            </div>

            <div class="card bg-white rounded-3xl p-8 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-amber-600 text-sm font-medium tracking-widest">TIEMPO ESTIMADO</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-2">8-12 min</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-3xl">
                        ⏱
                    </div>
                </div>
                <p class="text-slate-500">Llegada aproximada a tu sector</p>
            </div>

            <div class="card bg-white rounded-3xl p-8 shadow-lg border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-sky-600 text-sm font-medium tracking-widest">RUTA ACTIVA</p>
                        <h3 class="text-2xl font-semibold mt-1">Oaxaca Norte</h3>
                    </div>
                    <div class="w-12 h-12 bg-sky-100 rounded-2xl flex items-center justify-center text-3xl">
                        🛣
                    </div>
                </div>
                <div class="inline-flex items-center gap-2 text-xs font-medium bg-sky-50 text-sky-700 px-4 py-2 rounded-full">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>En servicio</span>
                </div>
            </div>
        </div>

        <!-- MAP -->
        @auth
        <div class="bg-white rounded-3xl p-4 shadow-2xl mb-12">
            <div class="flex justify-between items-center px-4 mb-4">
                <h3 class="font-semibold text-xl flex items-center gap-3">
                    <i class="fa-solid fa-map-location-dot text-emerald-600"></i>
                    Mapa en Tiempo Real
                </h3>
                <div class="text-xs bg-emerald-100 text-emerald-700 px-4 py-2 rounded-2xl font-medium flex items-center gap-2">
                    <span class="live-dot w-3 h-3"></span>
                    ACTUALIZANDO CADA 15 SEG
                </div>
            </div>
            <div id="map" class="w-full h-[520px]"></div>
        </div>
        @endauth

        <!-- LOGIN / REGISTER SECTION -->
        @guest
        <div class="bg-white rounded-3xl p-16 text-center shadow-xl border border-dashed border-slate-200">
            <div class="text-7xl mb-6 opacity-75">🔒</div>
            <h2 class="text-4xl font-semibold mb-4">Accede al seguimiento en vivo</h2>
            <p class="text-slate-600 max-w-md mx-auto mb-10">
                Inicia sesión o crea una cuenta para ver la ubicación exacta del camión y recibir notificaciones.
            </p>
            
            <!-- Botones juntos -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
                <a href="{{ route('registro.ciudadano') }}" 
                   class="flex-1 px-8 py-5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-2xl transition flex items-center justify-center gap-3 shadow-lg">
                    <i class="fa-solid fa-user-plus"></i>
                    Registrarme
                </a>
                
                <a href="{{ route('login') }}" 
                   class="flex-1 px-8 py-5 border-2 border-slate-300 hover:border-slate-400 font-semibold rounded-2xl transition flex items-center justify-center gap-3">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Iniciar sesión
                </a>
            </div>
        </div>
        @endguest

        <!-- CERRAR SESIÓN (solo autenticados) -->
        @auth
        <div class="flex justify-center gap-4 mt-10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="px-10 py-4 bg-red-50 hover:bg-red-100 text-red-700 font-semibold rounded-2xl transition flex items-center gap-3">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
        @endauth

        <!-- FOOTER -->
        <footer class="mt-24 pt-16 border-t border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div>
                    <h4 class="font-semibold text-xl mb-4 text-emerald-900">🌱 Sobre Oaxaca Limpia</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Plataforma tecnológica que optimiza la recolección de residuos y mejora la calidad de vida en nuestra ciudad.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-xl mb-4 text-emerald-900">⚠️ Problemática</h4>
                    <p class="text-slate-600 text-sm">La acumulación de residuos afecta la salud pública y la imagen de nuestra ciudad.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-xl mb-4 text-emerald-900">🤝 Tu compromiso</h4>
                    <ul class="space-y-3 text-slate-600">
                        <li class="flex items-start gap-3"><span class="text-emerald-600 mt-1">•</span> Separa orgánico e inorgánico</li>
                        <li class="flex items-start gap-3"><span class="text-emerald-600 mt-1">•</span> Saca basura solo cuando el camión esté cerca</li>
                        <li class="flex items-start gap-3"><span class="text-emerald-600 mt-1">•</span> Reporta cualquier incidencia</li>
                    </ul>
                </div>
            </div>

            <!-- Image Gallery -->
            <div class="mt-16">
                <h4 class="text-center text-emerald-900 font-semibold text-2xl mb-8 flex items-center justify-center gap-3">
                    <i class="fa-solid fa-truck"></i>
                    Camiones y Ciudad Limpia
                </h4>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="footer-image-card bg-white rounded-3xl overflow-hidden shadow-xl">
                        <img src="https://www.encuentroradiotv.com/wp-content/uploads/2025/05/1-recoleccion-basura.jpg" 
                             class="w-full h-52 object-cover" alt="Camión recolectando">
                        <p class="p-4 text-center text-sm font-medium text-slate-700">Camión recolectando en colonia</p>
                    </div>
                    
                    <div class="footer-image-card bg-white rounded-3xl overflow-hidden shadow-xl">
                        <img src="https://s3.amazonaws.com/arc-wordpress-client-uploads/infobae-wp/wp-content/uploads/2019/08/07143403/Desfile_Residual-3.jpg" 
                             class="w-full h-52 object-cover" alt="Equipo trabajando">
                        <p class="p-4 text-center text-sm font-medium text-slate-700">Equipo de recolección en acción</p>
                    </div>
                    
                    <div class="footer-image-card bg-white rounded-3xl overflow-hidden shadow-xl">
                        <img src="https://editorial.aristeguinoticias.com/wp-content/uploads/2022/11/basura-invade-calles-oaxaca-centro-historico-claudia-sheinbaum-05112022.jpg" 
                             class="w-full h-52 object-cover" alt="Basura acumulada">
                        <p class="p-4 text-center text-sm font-medium text-slate-700">Antes: Acumulación de residuos</p>
                    </div>
                    
                    <div class="footer-image-card bg-white rounded-3xl overflow-hidden shadow-xl">
                        <img src="https://agenciaoaxacamx.com/wp-content/uploads/2024/09/IMG-20240913-WA0060.jpg" 
                             class="w-full h-52 object-cover" alt="Oaxaca limpia">
                        <p class="p-4 text-center text-sm font-medium text-emerald-700 font-semibold">Después: Oaxaca más limpia ✨</p>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center text-xs text-slate-500 py-8 border-t border-slate-200">
                © 2026 Oaxaca Limpia • Desarrollado para una ciudad más limpia
            </div>
        </footer>
    </div>

    <script>
        tailwind.config = { content: [] };

        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('map')) {
                const map = L.map('map').setView([17.073, -96.726], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                
                L.marker([17.073, -96.726]).addTo(map)
                    .bindPopup('🚛 Camión recolector actual')
                    .openPopup();
            }
        });
    </script>
</body>
</html>