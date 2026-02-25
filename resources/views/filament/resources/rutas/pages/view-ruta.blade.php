<x-filament::page>
    <x-filament::section>
        <x-slot name="heading">
            Mapa de Ruta
        </x-slot>

        {{--   {{ $this->infolist }} --}}
        <div class="flex gap-3 mb-4">
            <x-filament::button onclick="saveRoute()" color="success">
                Guardar Cambios
            </x-filament::button>

            <x-filament::button onclick="clearRoute()" color="danger">
                Limpiar
            </x-filament::button>

            <x-filament::button onclick="removeLastPoint()" color="warning">
                Eliminar último punto
            </x-filament::button>
        </div>

        <div wire:ignore>
            <div id="map" style="height:600px; width:100%; border-radius:16px;"></div>
        </div>
    </x-filament::section>

    <x-filament::modal id="modalParada" width="md">
        <x-slot name="heading">
            Nueva Parada
        </x-slot>

        <div class="space-y-4">

            <x-filament::input label="Nombre" wire:model.defer="stopNombre" placeholder="Nombre de la Parada" class="ring-1 ring-gray-300 shadow-sm rounded-lg focus:ring-2 focus:ring-primary-500" />

            <x-filament::input type="time" label="Hora" wire:model.defer="stopHora" />

            <x-filament::input type="number" label="Duración (min)" wire:model.defer="stopDuracion" />

            <div>
                <label class="text-sm font-medium">Días</label>

                <div class="grid grid-cols-2 gap-2 mt-2">

                    @foreach ([
        'lunes' => 'Lunes',
        'martes' => 'Martes',
        'miercoles' => 'Miércoles',
        'jueves' => 'Jueves',
        'viernes' => 'Viernes',
        'sabado' => 'Sábado',
        'domingo' => 'Domingo',
    ] as $value => $label)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" value="{{ $value }}" wire:model="stopDias"
                                class="rounded border-gray-300">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach

                </div>
            </div>

        </div>

        <x-slot name="footer">
            <x-filament::button wire:click="guardarParada" color="success">
                Guardar
            </x-filament::button>
        </x-slot>
    </x-filament::modal>

</x-filament::page>

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>

    <script>
        let map;
        let markers = [];
        let routePath = [];
        let polyline;

        function loadMap() {

            if (!document.getElementById("map")) return;

            const puntosExistentes = @json($puntosIniciales);

            routePath = puntosExistentes.map(p => ({
                lat: parseFloat(p.lat),
                lng: parseFloat(p.lng),
            }));

            map = new google.maps.Map(document.getElementById("map"), {
                center: routePath.length ?
                    routePath[0] : {
                        lat: 17.0732,
                        lng: -96.7266
                    },
                zoom: 13,
                streetViewControl: false,
                mapTypeControl: false,
                fullscreenControl: false,
                clickableIcons: false,
                styles: [{
                        featureType: "poi",
                        stylers: [{
                            visibility: "off"
                        }]
                    },
                    {
                        featureType: "transit",
                        stylers: [{
                            visibility: "off"
                        }]
                    },
                    {
                        featureType: "poi.business",
                        stylers: [{
                            visibility: "off"
                        }]
                    }
                ]
            });

            polyline = new google.maps.Polyline({
                path: routePath,
                geodesic: true,
                strokeColor: @json($record->color),
                strokeOpacity: 1.0,
                strokeWeight: 4,
                map: map,
            });

            routePath.forEach(point => {
                const marker = new google.maps.Marker({
                    position: point,
                    map: map,
                });
                markers.push(marker);
            });

            // 🔥 AQUÍ va el listener correcto
            map.addListener("click", (event) => {

                if (event.domEvent.shiftKey) {
                    addStop(event.latLng);
                } else {
                    addPoint(event.latLng);
                }

            });

            map.addListener("click", (event) => {

                if (event.domEvent.shiftKey) {

                    @this.call('prepararParada',
                        event.latLng.lat(),
                        event.latLng.lng()
                    );

                } else {
                    addPoint(event.latLng);
                }

            });

            const paradas = @json($paradasIniciales);

            paradas.forEach(parada => {

                const marker = new google.maps.Marker({
                    position: {
                        lat: parada.lat,
                        lng: parada.lng
                    },
                    map: map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 9,
                        fillColor: "#ffffff",
                        fillOpacity: 1,
                        strokeColor: @json($record->color),
                        strokeWeight: 3,
                    }
                });

                const info = new google.maps.InfoWindow({
                    content: `
            <div style="font-size:13px">
                <strong>${parada.nombre}</strong><br>
                🕒 ${parada.hora}<br>
                ⏱ ${parada.duracion} min<br>
                📅 ${parada.dias.join(', ')}
            </div>
        `
                });

                marker.addListener('click', function() {
                    info.open(map, marker);
                });

            });
        }

        function addPoint(location) {

            const point = {
                lat: location.lat(),
                lng: location.lng()
            };

            routePath.push(point);

            const marker = new google.maps.Marker({
                position: location,
                map: map,
            });

            markers.push(marker);

            polyline.setPath(routePath);
        }

        function clearRoute() {

            if (!confirm("¿Seguro que deseas borrar la ruta completa?")) return;

            routePath = [];
            polyline.setPath(routePath);

            markers.forEach(marker => marker.setMap(null));
            markers = [];

            // 👇 ESTA es la forma correcta en Blade
            @this.call('limpiarPuntos');
        }

        function removeLastPoint() {

            if (routePath.length === 0) return;

            // Quitar último punto del array
            routePath.pop();

            // Quitar último marcador del mapa
            const lastMarker = markers.pop();
            if (lastMarker) {
                lastMarker.setMap(null);
            }

            // Actualizar polyline
            polyline.setPath(routePath);
        }

        function saveRoute() {
            @this.call('guardarPuntos', routePath);
        }

        document.addEventListener('livewire:navigated', loadMap);
        document.addEventListener('DOMContentLoaded', loadMap);


        function addStop(location) {

            const marker = new google.maps.Marker({
                position: location,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 8,
                    fillColor: "#ffffff",
                    fillOpacity: 1,
                    strokeColor: "#000000",
                    strokeWeight: 2,
                }
            });

            // Aquí luego abrimos modal para capturar:
            // nombre, hora, duración, días
        }
    </script>
@endpush
