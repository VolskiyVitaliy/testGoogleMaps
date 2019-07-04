<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

//$this->registerJsFile('js/point.js');
?>
<div>
    <table class="table">
        <thead>
        <th>latitude</th>
        <th>longitude</th>
        </thead>
        <?php foreach ($correctPoints as $point): ?>
            <tr>
                <td><?= Html::encode($point['latitude']) ?></td>
                <td><?= Html::encode($point['longitude']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div id="map">

</div>

<script>
    var points = JSON.parse('<?=$json?>');
    var point = JSON.parse('<?=$pointMain?>');
    console.log(points);

    function initMap() {
        var element = document.getElementById('map');
        var options = {
            zoom: 2,
            center: {lat: 55.7558, lng: 37.6173}
        };
        var myMap = new google.maps.Map(element, options);
        addMarker({
            lat: Number(point[0]),
            lng: Number(point[1])
        }, "http://maps.google.com/mapfiles/ms/icons/green-dot.png");
        points.forEach(function (element) {
            addMarker({
                lat: element.latitude,
                lng: element.longitude
            }, "http://maps.google.com/mapfiles/ms/icons/blue-dot.png");
        });

        function addMarker(coordinates, icon) {
            var marker = new google.maps.Marker({
                position: coordinates,
                map: myMap,
                icon: icon
            });
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIVtAdd-WLE01KZ-KNcF8n4nNtSdawcAk&callback=initMap"></script>