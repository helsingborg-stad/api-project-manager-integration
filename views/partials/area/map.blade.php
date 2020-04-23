@if(isset($data) && is_array($data) && !empty($data))
<div></div>
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
              var marker, item, map;

              // Create new map
              map = new google.maps.Map(document.getElementById('areaMap'), {
                zoom: 11,
                // center: {!!json_encode($center)!!},

                // Temporary center position, this shall be calculated
                center: {lat: 56.050921, lng: 12.697870}
              });

              for(var item in jsonPlots) {

                //Get details about pin
                name  = jsonPlots[item].location;
                info  = jsonPlots[item].excerpt;
                link = jsonPlots[item].permalink;

                //Append html markup for infowindow
                jsonPlots[item].info  = '<h3>' + name + '</h3>' + '<p>' + info + '</p>' + '<br><a target="_top" class="btn btn-md btn-primary" href="' + link + '"><?php _e("Läs mer om ", 'innovations-db') ?> ' + name + '</a>';

                //Create new marker
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(jsonPlots[item].geo.lat,jsonPlots[item].geo.lng),
                    name: name,
                    map: map
                });

                //Add infowindow trigger
                google.maps.event.addListener(marker, 'click', (function(marker, item) {
                  return function() {
                      var infoWindow = new google.maps.InfoWindow();
                      infoWindow.setContent(jsonPlots[item].info);
                      infoWindow.open(map, marker);
                  }
                })(marker, item));

              }
            }
          </script>
          <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcTrRdDFsoCu3bNbfBMU5Me1-9iqChOM8&callback=areaInitMap"></script>
      </div>
    </div>
@endif
