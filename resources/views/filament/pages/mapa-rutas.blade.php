<x-filament::page>

    <x-filament::section>
        <x-slot name="heading">
            Centro de Control de Rutas
        </x-slot>

        <div wire:ignore>
            <div id="map" style="height:700px; width:100%; border-radius:16px;"></div>
        </div>

    </x-filament::section>

</x-filament::page>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>

<script>
let map;

function loadMap() {

    if (!document.getElementById("map")) return;

    const rutas = @json($rutas);

    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 17.0732, lng: -96.7266 },
        zoom: 13,
        streetViewControl: false,
        mapTypeControl: false,
        fullscreenControl: false,
        clickableIcons: false,
        styles: [
            { featureType: "poi", stylers: [{ visibility: "off" }] },
            { featureType: "transit", stylers: [{ visibility: "off" }] },
            { featureType: "poi.business", stylers: [{ visibility: "off" }] },
        ]
    });

    rutas.forEach(ruta => {

        if (!ruta.puntos.length) return;

        const polyline = new google.maps.Polyline({
            path: ruta.puntos,
            geodesic: true,
            strokeColor: ruta.color,
            strokeOpacity: 1.0,
            strokeWeight: 4,
            map: map,
        });

        // Opcional: mostrar nombre al hacer click
        polyline.addListener('click', function () {
            alert("Ruta: " + ruta.nombre);
        });

    });
}

document.addEventListener('DOMContentLoaded', loadMap);
</script>
@endpush