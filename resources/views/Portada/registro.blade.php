<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear cuenta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/filament-style-register.css') }}">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">

        <h2>Crear una cuenta</h2>
        <p class="auth-subtitle">Regístrate para avisarte por dónde va el camión</p>

        @if($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('registro.ciudadano.store') }}">
            @csrf

            {{-- Hidden lat/lng --}}
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            <div class="grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Correo electrónico *</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Teléfono *</label>
                    <input type="text" name="telephone" required>
                </div>

                <div class="form-group">
                    <label>Dirección *</label>
                    <input type="text" name="adress" required>
                </div>

                <div class="form-group">
                    <label>Número *</label>
                    <input type="text" name="numero" required>
                </div>

                <div class="form-group">
                    <label>Colonia *</label>
                    <input type="text" name="colonia" required>
                </div>

                <div class="form-group full">
                    <label>Municipio *</label>
                    <input type="text" name="municipio" required>
                </div>

                <div class="form-group">
                    <label>Contraseña *</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" required>
                        <button type="button" onclick="togglePassword('password')">👁</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirmar contraseña *</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                        <button type="button" onclick="togglePassword('password_confirmation')">👁</button>
                    </div>
                </div>

                {{-- Ubicación --}}
                <div class="form-group full">
                    <label>Ubicación (opcional)</label>

                    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <button type="button" class="btn-secondary" onclick="obtenerUbicacion()">
                            Usar mi ubicación
                        </button>
                        <span id="ubicacion-status" style="color:#6b7280; font-size:14px;">
                            Aún no compartida
                        </span>
                    </div>

                    <small style="color:#6b7280; display:block; margin-top:8px;">
                        Esto nos ayuda a asignarte automáticamente tu ruta.
                    </small>
                </div>

                {{-- Notificaciones --}}
                <div class="form-group full">
                    <label style="margin-bottom:10px;">Notificaciones</label>

                    <label style="display:flex; gap:10px; align-items:center; font-weight:500; color:#111827;">
                        <input type="checkbox" name="notifications_enabled" value="1">
                        Deseo recibir notificaciones cuando el camión esté cerca de mi zona
                    </label>

                    <small style="color:#6b7280; display:block; margin-top:8px;">
                        Puedes cambiar esto después en tu perfil.
                    </small>
                </div>

            </div>

            <button type="submit" class="btn-primary">
                Registrarse
            </button>
        </form>

    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function obtenerUbicacion() {
    const status = document.getElementById('ubicacion-status');
    status.textContent = 'Solicitando permiso...';

    if (!navigator.geolocation) {
        status.textContent = 'Tu navegador no soporta ubicación.';
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
            status.textContent = 'Ubicación capturada ✅';
        },
        (error) => {
            // Permiso denegado u otro error
            status.textContent = 'No se pudo obtener ubicación (permiso denegado o error).';
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        }
    );
}
</script>

</body>
</html>