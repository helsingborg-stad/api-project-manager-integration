    <script>
      var jsonPlots = {!!json_encode($data)!!}
    </script>
    <div>
      <style scoped>
        .area-map {
          width: 100%;
          display: none;
        }

        @media only screen and (min-width: 56em) {
          .area-map {
            display: block;
          }
        }

        #areaMap {
          height: 400px;
          max-height: 33vh;
        }



        @media only screen and (min-width: 1441px) {
          #areaMap {
            height: 700px;
          }
        }

      </style>

      <div class="area-map c-area-map t-area-map">

          <div id="areaMap"></div>
          <script>
            function areaInitMap() {

              var mapStylePurpleSilver = [
                {
                  "elementType": "geometry",
                  "stylers": [
                    {
                      "color": "#f5f5f5"
                    }
                  ]
                },
                {
                  "elementType": "labels.icon",
                  "stylers": [
                    {
                      "visibility": "off"
                    }
                  ]
                },
                {
                  "elementType": "labels.text.fill",
                  "stylers": [
                    {
                      "color": "#616161"
                    }
                  ]
                },
                {
                  "elementType": "labels.text.stroke",
                  "stylers": [
                    {
                      "color": "#f5f5f5"
                    }
                  ]
                },
                {
                  "featureType": "administrative.land_parcel",
                  "elementType": "labels.text.fill",
                  "stylers": [
                    {
                      "color": "#bdbdbd"
                    }
                  ]
                },
                {
                  "featureType": "poi",
                  "elementType": "geometry",
                  "stylers": [
                    {
                      "color": "#eeeeee"
                    }
                  ]
                },
                {
                  "featureType": "poi",
                  "elementType": "labels.text.fill",
                  "stylers": [
                    {
                      "color": "#757575"
                    }
                  ]
                },
                {
                  "featureType": "poi.park",
                  "elementType": "geometry",
                  "stylers": [
                    {
                      "color": "#e5e5e5"
                    }
                  ]
                },
                {
                  "featureType": "poi.park",
                  "elementType": "labels.text.fill",
                  "stylers": [
                    {
                      "color": "#9e9e9e"
                    }
                  ]
                },
                {
                  "featureType": "road",
                  "elementType": "geometry",
                  "stylers": [
                    {
                      "color": "#ffffff"
                    }
                  ]
                },
                {
                  "featureType": "road.arterial",
                  "elementType": "labels.text.fill",
                  "stylers": [
                    {
                      "color": "#757575"
                    }
                  ]
                },
                {
                  "featureType": "road.highway",
                  "elementType": "geometry",
                  "stylers": [
                    {
                      "color": "#dadada"
                    }
                  ]
                },
                {
                  "featureType": "road.highway",
                  "elementType": "labels.text.fill",
                  "stylers": [
                    {
                      "color": "#616161"
                    }
                  ]
                },
                {
                  "featureType": "road.local",
                  "elementType": "labels.text.fill",
                  "stylers": [
                    {
                      "color": "#9e9e9e"
                    }
                  ]
                },
                {
                  "featureType": "transit.line",
                  "elementType": "geometry",
                  "stylers": [
                    {
                      "color": "#e5e5e5"
                    }
                  ]
                },
                {
                  "featureType": "transit.station",
                  "elementType": "geometry",
                  "stylers": [
                    {
                      "color": "#eeeeee"
                    }
                  ]
                },
                {
                  "featureType": "water",
                  "elementType": "geometry",
                  "stylers": [
                    {
                      "color": "#c9c9c9"
                    }
                  ]
                },
                {
                  "featureType": "water",
                  "elementType": "labels.text.fill",
                  "stylers": [
                    {
                      "color": "#9e9e9e"
                    }
                  ]
                }
              ];

              var map = new google.maps.Map(document.getElementById('areaMap'), {
                zoom: 10,
                center: {!!json_encode($center)!!},
                styles: mapStylePurpleSilver
              });

              var markers = jsonPlots.map(function(jsonPlot) {
                var name = jsonPlot.location;
                
                var marker = new google.maps.Marker({
                  position: new google.maps.LatLng(jsonPlot.geo.lat, jsonPlot.geo.lng),
                  name: name,
                  icon: {
                    url: 'https://inovation-db-recourses.s3.eu-north-1.amazonaws.com/marker/svg/marker.svg',
                    scaledSize: new google.maps.Size(40, 40),
                  }
                });

                name = jsonPlot.location;
                info = jsonPlot.excerpt;
                link = jsonPlot.permalink;

                
                jsonPlot.info = '<h3>' + name + '</h3>' + '<p>' + info + '</p>' + '<br><a target="_top" class="btn btn-md btn-primary" href="' + link + '"><?php _e("Läs mer om ", 'innovations-db') ?> ' + name + '</a>';

                // Add infowindow trigger.
                google.maps.event.addListener(marker, 'click', (function(marker, item) {
                  return function() {
                      var infoWindow = new google.maps.InfoWindow();
                      infoWindow.setContent(item.info);
                      infoWindow.open(map, marker);
                  }
                })(marker, jsonPlot));
                
                return marker;
              });
                var inovationClusterStyle = [
                  {
                    width: 58,
                    height: 58,
                    textColor: 'white',
                    textSize: 21,
                    url: 'https://inovation-db-recourses.s3.eu-north-1.amazonaws.com/cluster/m1.png'
                  },
                  {
                    width: 58,
                    height: 58,
                    textColor: 'white',
                    textSize: 21,
                    url: 'https://inovation-db-recourses.s3.eu-north-1.amazonaws.com/cluster/m1.png'
                  },
                  {
                    width: 58,
                    height: 58,
                    textColor: 'white',
                    textSize: 21,
                    url: 'https://inovation-db-recourses.s3.eu-north-1.amazonaws.com/cluster/m1.png'
                  }
                ];
               
                // Add a marker cluster to manage the markers.
                var markerCluster = new MarkerClusterer(map, markers, {styles: inovationClusterStyle});
            }            
          </script>
          <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script>
          <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBcTrRdDFsoCu3bNbfBMU5Me1-9iqChOM8&callback=areaInitMap"></script>
      </div>

    </div> 