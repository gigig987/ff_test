
var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: 51.5032887, lng: 0.0948602},
    zoom: 11
  });
  

  
  //Add listener
google.maps.event.addListener(map, "click", function (event) {
    var latitude = event.latLng.lat();
    var longitude = event.latLng.lng();
    console.log( latitude + ', ' + longitude );

    radius = new google.maps.Circle({map: map,
        radius: 3 * 1609.344,
        center: event.latLng,
        fillColor: '#777',
        fillOpacity: 0.1,
        strokeColor: '#AA0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        draggable: false,    // Dragable
        editable: false      // Resizable
    });

    // Center of map
    map.panTo(new google.maps.LatLng(latitude,longitude));
    
        $.ajax({
        type:"GET",
        url: "classes/getlist.php",
        data: "lat=" + latitude + "\u0026lon="+ longitude,
        success: function(data){
       
          var sites = JSON.parse (data);         
           render(sites);
        }
        
        
    
    });
       

  
}); //end addListener

function render(sites){
  
        setMarkers(map, sites);
        
	    infowindow = new google.maps.InfoWindow({
                content: "loading..."
            });

  
   


    function setMarkers(map, markers) {
  var length = Object.keys(markers).length;
        for (var i = 0; i < length; i++) {
            var sites = markers[i];
             console.log(sites);
            var siteLatLng = new google.maps.LatLng(sites.latitude, sites.longitude);
            var contents = '<div><h3>'+sites.address+'</h3>';
                contents += '<p>index:<span class="index-number"><img src="./css/spin.gif" class="ajax-loading"/></span></p></div>';
            var marker = new google.maps.Marker({
                position: siteLatLng,
                map: map,
                title: sites.address,
                zIndex: sites.ID,
                html: contents,
                firstPublishedDate: sites.firstPublishedDate
            });

            var contentString = "Some content";
            
            google.maps.event.addListener(marker, "click", function () {
               
                infowindow.setContent(this.html);
                infowindow.open(map, this);
               
               
               
                       $.ajax({
                      type:"POST",
                      url: "classes/getpopindex.php",
                      data: {firstPublishedDate: this.firstPublishedDate, ID: this.zIndex},
                      success: function(response){
                         var html = infowindow.getContent();
                    var $html = $('<div />',{html:html});

                    $html.find('.index-number').html(response);
                   
                          infowindow.setContent($html.html());
                      }
                      
                      
                  
                  });//end ajax visits index
            });
        }    
    }
  } // end render
  
} // end initMap