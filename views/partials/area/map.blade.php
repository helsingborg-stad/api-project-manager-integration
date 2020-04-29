    <script>
      var jsonPlots = {!!json_encode($data)!!}
    </script>
    <div class="grid-xs-12">

      <style scoped>
        .area-map {
          width: 100%;
        }

        #areaMap {
          height: 400px;
          margin-bottom: 30px;
        }

      </style>

      <div class="area-map c-area-map t-area-map">

          <div id="areaMap"></div>
          <script>

            function areaInitMap() {

              var map = new google.maps.Map(document.getElementById('areaMap'), {
                zoom: 11,
                center: {!!json_encode($center)!!},
              });

              var markers;

              var markers = jsonPlots.map(function(jsonPlot) {
                var name = jsonPlot.location;
                
                var marker = new google.maps.Marker({
                  position: new google.maps.LatLng(jsonPlot.geo.lat, jsonPlot.geo.lng),
                  name: name,
                });

                name = jsonPlot.location;
                info = jsonPlot.excerpt;
                link = jsonPlot.permalink;

                
                jsonPlot.info = '<h3>' + name + '</h3>' + '<p>' + info + '</p>' + '<br><a target="_top" class="btn btn-md btn-primary" href="' + link + '"><?php _e("Läs mer om ", 'innovations-db') ?> ' + name + '</a>';

                // Add infowindow trigger
                google.maps.event.addListener(marker, 'click', (function(marker, item) {
                  return function() {
                      var infoWindow = new google.maps.InfoWindow();
                      infoWindow.setContent(item.info);
                      infoWindow.open(map, marker);
                  }
                })(marker, jsonPlot));
                
                return marker;
              });


              

              // Add a marker clusterer to manage the markers.
              var markerCluster = new MarkerClusterer(map, markers,
                  {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
              
            
            }
            
          </script>
          <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
          <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcTrRdDFsoCu3bNbfBMU5Me1-9iqChOM8&callback=areaInitMap"></script>
      </div>
    </div>
