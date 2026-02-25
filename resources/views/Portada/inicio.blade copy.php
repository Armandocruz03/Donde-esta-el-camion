<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¿Dónde está el Camión?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg,  rgba(42, 152, 82, 1), rgba(42, 152, 82, 1));
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            overflow: hidden;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            animation: fadeIn 1.5s ease;
        }

        h1 {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: rgba(255,255,255,0.15);
            border-radius: 15px;
            padding: 25px;
            transition: 0.3s;
            position: relative;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .card h3 {
            margin-bottom: 10px;
        }

        .status {
            margin-top: 15px;
            font-weight: bold;
            color: #00ffae;
        }

        .btn {
            display: inline-block;
            margin-top: 30px;
            padding: 15px 30px;
            background: #00c6ff;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            border-radius: 50px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        .live-dot {
            height: 10px;
            width: 10px;
            background-color: #00ff00;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.3; }
            100% { opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

    </style>
  <script>

document.addEventListener("DOMContentLoaded", function () {

    let ultimoPunto = null;

    function mostrarAlerta(nombreParada) {

    Swal.fire({
        icon: 'success',
        title: '🚛 El camión avanzó',
        html: '<strong>Ahora está en:</strong><br>' + nombreParada,
        timer: 3000,
        showConfirmButton: false,
        position: 'center',
        backdrop: true
    });

}

    function actualizarEstado() {

        fetch("{{ url('ruta-actual') }}")
            .then(response => response.json())
            .then(data => {

                console.log("Respuesta del servidor:", data);

                if (!data || !data.estado) return;

                if (data.estado === "en_ruta") {

                    document.getElementById("estado").innerText = "🟢 En línea";
                    document.getElementById("punto").innerText = data.punto ?? "--";
                    document.getElementById("calle").innerText = data.calle ?? "--";

                    // Detectar avance
                    if (ultimoPunto !== null && ultimoPunto !== data.punto) {
                        mostrarAlerta(data.calle);
                    }

                    ultimoPunto = data.punto;

                } else if (data.estado === "finalizada") {

                    document.getElementById("estado").innerText = "🏁 Ruta finalizada";
                    document.getElementById("punto").innerText = "--";
                    document.getElementById("calle").innerText = "--";

                    if (ultimoPunto !== null) {
                        mostrarToast("🚛 La ruta ha finalizado.");
                    }

                    ultimoPunto = null;

                } else {

                        document.getElementById("estado").innerText = "🔴 Ruta detenida";

                        if (ultimoPunto !== null) {

                            Swal.fire({
                                icon: 'info',
                                title: '🏁 Ruta Finalizada',
                                text: 'El recorrido ha terminado.',
                                timer: 3000,
                                showConfirmButton: false,
                                position: 'center'
                            });
                        }

                        ultimoPunto = null;
                    }

            })
            .catch(error => {
                console.error("Error consultando ruta:", error);
            });
    }

    actualizarEstado();
    setInterval(actualizarEstado, 5000);

});

</script>
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
            <p style="margin-top:10px;">
                <a href="{{ route('registro.ciudadano') }}" class="btn" style="margin-top:10px; display:inline-block;">
                    Registrarse
                </a>
            </p>
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

            <div class="card">
                <h3>🔔 Notificaciones</h3>
                <p>Recibe alertas cuando el camión esté cerca de ti.</p>
                <div class="status">
                    Activadas
                </div>
            </div>

        </div>

<a href="{{ route('registro.ciudadano') }}"
   class="btn">
   Registrarse
</a>  
    </div>
<div id="toast" style="
position: fixed;
bottom: 20px;
right: 20px;
background: #16a34a;
color: white;
padding: 15px 25px;
border-radius: 12px;
display: none;
box-shadow: 0 10px 20px rgba(0,0,0,0.3);
font-weight: bold;
z-index: 9999;
">
🚛 Mensaje
</div>

</body>
</html>