<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7Map</title>
    <link href="favicon.png" rel="shortcut icon" type="image/png">
    <link rel="stylesheet" href="assets/css/styles.css<?php echo '?v=' . rand(99, 9999999) ?>" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body>
    <div class="main">
        <div class="head">
            <input type="text" id="search" placeholder="دنبال کجا می گردی؟">
        </div>
        <div class="mapContainer">
            <div id="map"></div>
        </div>
    </div>
    <div class="modal-overlay" style="display: none;">
        <div class="modal">
            <span class="close">x</span>
            <h3 class="modal-title">ثبت لوکیشن</h3>
            <div class="modal-content">
                <form id="addLocationForm" action="<?php echo site_url('process/addLocation.php') ?>" method="POST">
                    <div class="field-row">
                        <div class="field-title">مختصات</div>
                        <div class="field-content">
                            <input type="text" name="lat" id="lat-display" readonly style="width: 160px; text-align: center;">
                            <input type="text" name="lng" id="lng-display" readonly style="width: 160px; text-align: center;">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-title">نام مکان</div>
                        <div class="field-content">
                            <input type="text" name="title" id="l-title" placeholder="مثلا:دفتر مرکزی سون لرن">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-title">نوع</div>
                        <div class="field-content">
                            <select name="type" id="l-type">
                            <?php foreach(locationTypes as $key=>$value): ?>
                            <option value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-title">ذخیره نهایی</div>
                        <div class="field-content">
                            <input type="submit" value="ثبت">
                        </div>
                    </div>
                    <div class="ajax-result"></div>
                </form>
            </div>
        </div>
    </div>









    <script>
        const defaultLocation = [35.722, 51.261];
        const defaultZoom = 15;


        var map = L.map('map').setView(defaultLocation, defaultZoom);

        var tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: '7learn.ac; <a href="https://7learn.com/course/php-expert">7Map Project</a> ',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1
        }).addTo(map);


        document.getElementById('map').style.setProperty('height', window.innerHeight + 'px');

        // set view in map
        map.setView([29.5921951, 52.5752018], defaultZoom);


        // show and pin markers
        // L.marker(defaultLocation).addTo(map).bindPopup("7Learn Office 1").openPopup();

        // L.marker([35.712,51.338]).addTo(map).bindPopup("7Learn Office 2");




        // map.on("popupopen",function(){
        //     alert("PopUp Opened!");
        // });


        // get view  Bound information
        // var northLine = map.getBounds().getNorth(); //khat shomali
        // var westLine = map.getBounds().getWest();   //khat Gharbi
        // var southLine = map.getBounds().getSouth(); //khat Jonoobi
        // var eastLine = map.getBounds().getEast();   //khat Sharghi



        // Use Map Events
        map.on("zoomend", function() {
            // alert(map.getBounds().getCenter());
            // 1: get bound lines
            // 2: send bound lines to server
            // 3: search locations in map windows
            // 4: display locations markers in map


        });




        // Use Map Events
        map.on("dblclick", function(event) {
            // alert(event.latlng.lat +","+event.latlng.lng);
            // 1: add marker in clicked position
            // L.marker([event.latlng.lat,event.latlng.lng]).addTo(map);
            L.marker(event.latlng).addTo(map);
            // 2: open modal (form) for save the clicked location
            $('.modal-overlay').fadeIn(500);
            $('#lat-display').val(event.latlng.lat);
            $('#lng-display').val(event.latlng.lng);
            $('#l-type').val(0);
            $('#l-title').val('');
            // 3 done: fill the form and submit location data to server
            // 4 done: save location in database (status: pending review)
            // 5: review locations and verify if Ok


        });


        // find Current Location (at first,Use shekan.ir)
        var current_position, current_accuracy;
        map.on("locationfound", function(e) {
            // if position defined ,then remove the existing position marker and  accuracy circle from 
            if (current_position) {
                map.removeLayer(current_position);
                map.removeLayer(current_accuracy);
            }
            var radius = e.accuracy / 2;
            current_position = L.marker(e.latlng).addTo(map)
                .bindPopup("دقت تقریبی" + radius + "متر").openPopup();
            current_accuracy = L.circle(e.latlng, radius).addTo(map);
        });

        map.on("locationerror", function(e) {
            alert(e.message);
        });

        // wrap map.locate in a function
        function locate() {
            map.locate({
                setView: true,
                maxZoom: defaultZoom
            });
        }
        // call locate every 5 seconds ...
        // setInterval(locate, 5000);





        setTimeout(function() {
            map.setView([southLine, westLine], defaultZoom);
        }, 3000);
    </script>




    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/scripts.js"></script>

    <script>
        $(document).ready(function() {
            $("form#addLocationForm").submit(function(){
                e.preventDefault(); //prevent form submiting
                // alert($(this).serialize());
                var form = $(this);
                var resultTag = form.find('.ajax-result');
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response){
                        resultTag.html(response);
                    }
                });
            });
            $('.modal-overlay .close').click(function() {
                $('.modal-overlay').fadeOut();
                
            });
        });
    </script>



    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>
</html>

