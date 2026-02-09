<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Ruta</title>

    <!-- Leaflet CSS -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    <style>
        body {
            margin: 0;
            padding: 0;
        }
        #map {
            width: 100%;
            height: 100vh;
        }
    </style>
</head>
<body>

<div id="map"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Datos desde Laravel (PRIMERO)
    const ubicaciones = @json($ubicaciones);

    console.log('Ubicaciones:', ubicaciones);

    // Crear el mapa
    const map = L.map('map');

    if (ubicaciones.length > 0) {
        map.setView([ubicaciones[0].lat, ubicaciones[0].lng], 14);
    } else {
        map.setView([17.060, -96.724], 14);
    }

    // Capa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Dibujar puntos y línea
    if (ubicaciones.length > 0) {
        const puntos = [];

        ubicaciones.forEach(u => {
            puntos.push([u.lat, u.lng]);

            L.marker([u.lat, u.lng])
                .addTo(map)
                .bindPopup(u.nombre);
        });

        // Línea de la ruta
        const ruta = L.polyline(puntos, {
            color: 'orange',
            weight: 4
        }).addTo(map);

        map.fitBounds(ruta.getBounds());
    }
</script>

</body>
</html>
