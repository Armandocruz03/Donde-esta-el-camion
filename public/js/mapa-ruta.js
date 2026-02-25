document.addEventListener("DOMContentLoaded", function () {

    let ultimoPunto = null;
    let map;
    let marker;
    let polyline;
    let currentLatLng = null;

    async function cargarRutaCompleta() {

    if (!map) return;

    const response = await fetch("ruta-paradas");
    const paradas = await response.json();

    if (paradas.length === 0) return;

    const coordenadas = paradas.map(p => [p.lat, p.lng]);

    // 🔥 SI YA EXISTE LA LINEA NO VOLVER A CREARLA
    if (polyline) return;

    polyline = L.polyline(coordenadas, {
        color: 'blue',
        weight: 5,
        opacity: 0.7
    }).addTo(map);
}

    function iniciarMapa(lat, lng) {

        map = L.map('map').setView([lat, lng], 18);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let iconoCamion = L.divIcon({
    html: `
        <div style="
            font-size:35px;
            width:90px;
            height:90px;
            display:flex;
            align-items:center;
            justify-content:center;
        ">
            🚛
        </div>
    `,
    className: "",
    iconSize: [90, 90],
    iconAnchor: [45, 45]
});

        marker = L.marker([lat, lng], { icon: iconoCamion }).addTo(map);

        currentLatLng = L.latLng(lat, lng);

        cargarRutaCompleta();
    }

    function animarMovimiento(nuevaLat, nuevaLng) {

        const nuevaPosicion = L.latLng(nuevaLat, nuevaLng);

        const frames = 30;
        let frame = 0;

        const deltaLat = (nuevaPosicion.lat - currentLatLng.lat) / frames;
        const deltaLng = (nuevaPosicion.lng - currentLatLng.lng) / frames;

        const interval = setInterval(() => {

            frame++;

            const lat = currentLatLng.lat + deltaLat * frame;
            const lng = currentLatLng.lng + deltaLng * frame;

           marker.setLatLng([lat, lng]);

marker.setLatLng([lat, lng]);

const bounds = map.getBounds();
const camionPos = L.latLng(lat, lng);

if (!bounds.contains(camionPos)) {
    map.panTo(camionPos, {
        animate: true,
        duration: 0.6
    });
}

            if (frame >= frames) {
                clearInterval(interval);
                currentLatLng = nuevaPosicion;
            }

        }, 30);
    }

    function actualizarEstado() {

    fetch("ruta-actual")
        .then(response => response.json())
        .then(data => {

            console.log(data);

            // 🔥 SI HAY COORDENADAS SIEMPRE INICIALIZAR MAPA
            if (data.lat && data.lng) {

                if (!map) {
                    iniciarMapa(data.lat, data.lng);
                }
            }

            // ------------------------------
            // 🚛 EN RUTA
            // ------------------------------
            if (data.estado === "en_ruta") {

                const estadoEl = document.getElementById("estado");
                if (estadoEl) estadoEl.innerText = "🟢 En línea";
                const puntoEl = document.getElementById("punto");
                if (puntoEl) puntoEl.innerText = data.punto;
                const calleEl = document.getElementById("calle");
                if (calleEl) calleEl.innerText = data.calle;
                const tituloEl = document.getElementById("tituloRuta");
                if (tituloEl) tituloEl.innerText = "Ruta en recorrido";

                if (marker && data.lat && data.lng) {
                    animarMovimiento(data.lat, data.lng);
                }

                if (ultimoPunto !== null && ultimoPunto !== data.punto) {

                    Swal.fire({
                        icon: 'success',
                        title: '🚛 El camión avanzó',
                        text: 'Ahora está en: ' + data.calle,
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'center'
                    });
                }

                ultimoPunto = data.punto;

            }
            
            // ------------------------------
            // 🛑 DETENIDA
            // ------------------------------
            else {
                // 🔥 Quitar línea azul si existe
                if (polyline) {
                    map.removeLayer(polyline);
                    polyline = null;
                }

                const estadoEl = document.getElementById("estado");
                    if (estadoEl) estadoEl.innerText = "🔴 Ruta detenida";

                    const puntoEl = document.getElementById("punto");
                    if (puntoEl) puntoEl.innerText = data.punto ?? "--";

                    const calleEl = document.getElementById("calle");
                    if (calleEl) calleEl.innerText = data.calle ?? "--";

                    const tituloEl = document.getElementById("tituloRuta");
                    if (tituloEl) tituloEl.innerText = "🛑 Ruta finalizada";

                    // 🔔 Mostrar alerta SOLO UNA VEZ
                    if (ultimoPunto !== null) {

                        Swal.fire({
                            icon: 'info',
                            title: '🛑 Ruta finalizada',
                            text: 'El camión llegó a: ' + data.calle,
                            timer: 3000,
                            showConfirmButton: false,
                            position: 'center'
                        });
                    }

                // 🔥 Si el mapa aún no está creado, lo creamos con última posición
                if (!map && data.lat && data.lng) {
                    iniciarMapa(data.lat, data.lng);
                }
                // 🔵 Dibujar línea si no existe
                if (!polyline) {
                    cargarRutaCompleta();
                }
                            // 🔥 Si ya existe, solo moverlo a última posición
                if (map && data.lat && data.lng) {
                    marker.setLatLng([data.lat, data.lng]);
                    map.setView([data.lat, data.lng], 16);
                    // 🚛 Cambiar camión a gris
                    let iconoGris = L.divIcon({
                        html: `
                            <div style="
                                font-size:35px;
                                width:90px;
                                height:90px;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                filter: grayscale(100%);
                            ">
                                🚛
                            </div>
                        `,
                        className: "",
                        iconSize: [90, 90],
                        iconAnchor: [45, 45]
                    });

                    marker.setIcon(iconoGris);
                }

                ultimoPunto = null;
            }

        })
        .catch(error => {
            console.error("Error:", error);
        });
}

    actualizarEstado();
    setInterval(actualizarEstado, 5000);

});
