@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
        }

        #map {
            height: calc(100vh - 56px);
            width: 100%;
            margin: 0%
        }
    </style>
@endsection
</head>

<body>
    @section('content')
        <div id="map"></div>

        <!-- Modal Create Point -->
        <div class="modal fade" id="PointModal" tabindex="-1" aria-labelledby="PointModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Point</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('store-point') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Fill point name">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="geom" class="form-label">Geometry</label>
                                <textarea class="form-control" id="geom_point" name="geom" rows="3" readonly></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image_point" name="image"
                                    onchange="document.getElementById('preview-image-point').src =
                            window.URL.createObjectURL(this.files[0])">

                            </div>
                            <div class="mb-3">
                                <img src="" alt="Preview" id="preview-image-point" class="img-thumbnail"
                                    width="400">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        @endsection

        @section('script')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
            <script src="https://unpkg.com/terraformer@1.0.7/terraformer.js"></script>
            <script src="https://unpkg.com/terraformer-wkt-parser@1.1.2/terraformer-wkt-parser.js"></script>
            <script>
                //Map
                var map = L.map('map').setView([-6.1753924, 106.8271528], 13);

                // Basemap
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                /* Digitize Function */
                var drawnItems = new L.FeatureGroup();
                map.addLayer(drawnItems);

                var drawControl = new L.Control.Draw({
                    draw: {
                        position: 'topleft',
                        polyline: false,
                        polygon: false,
                        rectangle: false,
                        circle: false,
                        marker: true,
                        circlemarker: false
                    },
                    edit: {
                        featureGroup: drawnItems,
                        edit: true,
                        remove: false
                    }
                });

                map.addControl(drawControl);

                map.on('draw:edited', function(e) {
                    var layer = e.layers;
                    var geojson = layer.toGeoJSON();

                    console.log(geojson);

                    layer.eachLayer(function(layer){
                        $ ('#name').val(layer.feature.properties.name);
                        $ ('#description').val(layer.feature.properties.description);
                        $ ('#geom_point').val(layer.toGeoJSON().geometry.coordinates);
                        $ ('#preview-image-point').attr('src', '{{ asset('storage/images') }}/' + layer.feature.properties.image);
                        $('#PointModal').modal('show');
                    });

                });

                /* GeoJSON Point */
                var point = L.geoJson(null, {
                    onEachFeature: function(feature, layer) {

                        drawnItems.addLayer(layer);

                        var popupContent = "Nama: " + feature.properties.name + "<br>" +
                            "Deskripsi: " + feature.properties.description + "<br>" +
                            "Foto: <img src='{{ asset('storage/images') }}/" + feature.properties.image +
                            "' width='100px'>" + "<br>" +
                            "<form action='{{ url('delete-point') }}/" + feature.properties.id + "' method='POST'>" +
                            '{{ csrf_field() }}' +
                            '{{ method_field('DELETE') }}' +
                            "<div class='d-flex flex-row mt-3'>" +
                            "<a href='{{ url('edit-point') }}/" + feature.properties.id +
                            "' class='btn btn-warning me-2'><i class='fa-solid fa-edit'></i></a>" +
                            "<button type='submit' class='btn btn-danger' onclick='return confirm(\"Yakin Anda akan menghapus data ini?\")'><i class='fa-solid fa-trash-can'></i></button>" +
                            "</div>" +
                            "</form>";

                        layer.on({
                            click: function(e) {
                                point.bindPopup(popupContent);
                            },
                            mouseover: function(e) {
                                point.bindTooltip(feature.properties.name, {
                                    direction: "top",
                                }).openTooltip();
                            },
                        });
                    },
                });
                $.getJSON("{{ route('api.point', $id) }}", function(data) {
                    point.addData(data);
                    map.addLayer(point);
                    map.fitBounds(point.getBounds());
                });

                // Layer Control
                var overlayMaps = {
                    "Point": point,
                    "Polyline": polyline,
                    "Polygon": polygon
                };

                var layerControl = L.control.layers(null, overlayMaps).addTo(map);
            </script>
        @endsection
