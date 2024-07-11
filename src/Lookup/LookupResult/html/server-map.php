<?php
$data = $_SESSION["lookup"]["geo"];

// Server data
$serverContinent = isset($data["continent"]) ? htmlspecialchars($data["continent"]) : "";
$serverCountry = isset($data["country"]) ? htmlspecialchars($data["country"]) : "";
$serverCity = isset($data["city"]) ? htmlspecialchars($data["city"]) : "";
$serverLat = isset($data["lat"]) ? $data["lat"] : "";
$serverLon = isset($data["lon"]) ? $data["lon"] : "";

?>

<h4>Server map</h4>
<?php if ($data) : ?>
    <small>
        This map displays the approximate location of the server that delivers <?php echo $_SESSION["lookup"]["host"]; ?>
    </small>
    <hr>
    <?php echo "Server owner: " . htmlspecialchars($data["isp"] . ", " . $data["as"]); ?>
    <br><br>
    <div id="map" style="width: 100%; height: 580px"></div>
    <script>
        var map;

        function initializeMap() {
            if (!map) {
                var serverLat = <?php echo json_encode($serverLat); ?>;
                var serverLon = <?php echo json_encode($serverLon); ?>;

                var mapOptions = {
                    center: [serverLat, serverLon],
                    zoom: 10
                };
                map = new L.map('map', mapOptions);

                // Add OpenStreetMap layer
                var layer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
                map.addLayer(layer);

                // Add marker for the server with popup
                var serverMarker = L.marker([serverLat, serverLon]).addTo(map);
                serverMarker.bindPopup("<b>Server Location</b><br>Continent: <?php echo $serverContinent; ?><br>Country: <?php echo $serverCountry; ?><br>City: <?php echo $serverCity; ?><br>Coordinates: " + serverLat + ", " + serverLon);


                // Adjust map to show all markers
                map.fitBounds(polyline.getBounds());
            }
        }

        initializeMap();
    </script>
    <br>
    <span class="text-muted">* Locations may be inaccurate up to 150 km / 93 miles</span>
    <br>
    <br>
<?php else : ?>
    <p>Geo data could not be obtained for this location.</p>
<?php endif; ?>