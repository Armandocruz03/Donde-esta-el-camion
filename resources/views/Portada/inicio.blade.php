<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¿Dónde está el Camión?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Uso de leaflet para dibujar el mapa-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!--Uso de sweet alert para notificaciones-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--Script de alerta, rutas y mapa-->
    <script src="{{ asset('js/mapa-ruta.js') }}"></script>
    <!--css-->
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
</head>
<body>

    <div class="container">

        <h1>🚍 ¿Dónde está el Camión?</h1>
        <p class="subtitle">
            Una solucion a un problema que vivimos
        </p>
       @if(auth()->check())
    <div style="background:white; padding:18px 25px; border-radius:12px; margin-bottom:20px;">

        <h2 style="margin:0; color:#15803d;">
            Bienvenid@, {{ auth()->user()->name }} 👋
        </h2>

        @if(auth()->user()->ruta)
            <p style="margin-top:8px; color:#374151;">
                Tu ruta asignada: <strong>{{ auth()->user()->ruta->nombre }}</strong>
            </p>
        @else
            <p style="margin-top:8px; color:#b91c1c;">
                Aún no se pudo asignar tu ruta.
            </p>
        @endif

    </div>
@endif

        <div class="grid">

            <div class="card">
    <h3>📍 Ubicación en Vivo</h3>
    <p>Consulta la posición actual del camión en el mapa en tiempo real.</p>

    {{-- Si NO está logueado --}}
    @guest
        <div class="status" style="color:#d1fae5;">
            <span class="live-dot" style="background:#f59e0b;"></span>
            <span>Regístrate o inicia sesión para ver la ruta</span>
        </div>

        <div class="info-ruta" style="margin-top:12px; opacity:.95;">
            
        </div>
    @endguest

    {{-- Si SÍ está logueado --}}
    @auth
        <div class="status">
            <span class="live-dot" id="liveDot"></span>
            <span id="estado">Cargando estado...</span>
        </div>

        <div class="info-ruta">
            <p><strong>Punto actual:</strong> <span id="punto">--</span></p>
            <p><strong>Ubicación actual:</strong><span id="calle">--</span></p>
        </div>
    @endauth
</div>

            <div class="card">
                <h3>⏱ Tiempo Estimado</h3>
                <p>Calcula cuánto falta para que el camión llegue a tu parada.</p>
                <div class="status">
                    ETA: 8 minutos
                </div>
            </div>

            <div class="card">
                <h3>🛣 Ruta Activa</h3>
                <p>Visualiza el recorrido completo y las paradas disponibles.</p>
                <div class="status">
                    Ruta 3 - Centro
                </div>
            </div>
            <!--Dibuja el mapa-->

        </div>
        @auth
<div style="
    margin-top: 40px;
    background: rgba(255,255,255,0.15);
    padding: 25px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
">

    <h2 style="margin-bottom:15px;">Seguimiento en Tiempo Real</h2>

    <div id="map" style="
        width: 100%;
        height: 450px;
        border-radius: 15px;
        overflow: hidden;
    "></div>

</div>
@endauth

@guest
<div style="
    margin-top: 40px;
    background: rgba(255,255,255,0.15);
    padding: 40px;
    border-radius: 20px;
    text-align:center;
">

    <h2>🔒 Seguimiento en tiempo real</h2>
    <p style="margin-top:10px;">
        Debes iniciar sesión para ver la ubicación del camión.
    </p>

    

</div>
@endguest

<div style="margin-top:30px; text-align:center;">

    @guest
        <a href="{{ route('registro.ciudadano') }}" class="btn">
            Registrarse
        </a>

        <a href="{{ route('login') }}" class="btn" style="margin-left:10px;">
            Iniciar sesión
        </a>
    @endguest


    @auth
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn logout-btn">
                Cerrar sesión
            </button>
        </form>
    @endauth

</div>
 
    </div>
</body>
</html>