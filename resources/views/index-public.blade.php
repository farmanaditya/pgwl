@extends('layouts.template')

@section('styles')

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

        </div>
    @endsection

    @section('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
        <script>
            //Map
            var map = L.map('map').setView([-6.1753924, 106.8271528], 13);

            // Basemap
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);


            /* GeoJSON Point */
            var point = L.geoJson(null, {
                onEachFeature: function(feature, layer) {
                    var popupContent = "<h3>" + feature.properties.name + "</h3>" +
                            " " + feature.properties.description + "<br>" +
                            " <br><img src='{{ asset('storage/images') }}/" + feature.properties.image +
                            "' width='200px'>";

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
            $.getJSON("{{ route('api.points') }}", function(data) {
                point.addData(data);
                map.addLayer(point);
            });

            /* GeoJSON polyline */
            var polyline = L.geoJson(null, {
                onEachFeature: function(feature, layer) {
                    var popupContent = "<h3>" + feature.properties.name + "</h3>" +
                            " " + feature.properties.description + "<br>" +
                            " <br><img src='{{ asset('storage/images') }}/" + feature.properties.image +
                            "' width='200px'>" + "<br>";
                    layer.on({
                        click: function(e) {
                            layer.bindPopup(popupContent).openPopup();
                        },
                        mouseover: function(e) {
                            layer.bindTooltip(feature.properties.name, {
                                direction: "top",
                            }).openTooltip();
                        },
                    });
                },
            });

            $.getJSON("{{ route('api.polylines') }}", function(data) {
                polyline.addData(data);
                map.addLayer(polyline);
            });
            /* GeoJSON polygon */
            var polygon = L.geoJson(null, {
                onEachFeature: function(feature, layer) {
                    var popupContent = "<h3>" + feature.properties.name + "</h3>" +
                            " " + feature.properties.description + "<br>" +
                            " <br><img src='{{ asset('storage/images') }}/" + feature.properties.image +
                            "' width='200px'>"
                    ;
                    layer.on({
                        click: function(e) {
                            polygon.bindPopup(popupContent);
                        },
                        mouseover: function(e) {
                            polygon.bindTooltip(feature.properties.name, {
                                direction: "top",
                            }).openTooltip();
                        },
                    });
                },
            });
            $.getJSON("{{ route('api.polygons') }}", function(data) {
                polygon.addData(data);
                map.addLayer(polygon);
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
