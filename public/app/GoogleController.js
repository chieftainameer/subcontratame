function initMap() {
  //For New Branch Office
  autocomplete = new google.maps.places.Autocomplete(
      $('#frmNew input[name=address]')[0], {types: ['geocode']});
  autocomplete.setFields(['geometry']);
  autocomplete.addListener('place_changed', function(){
      var place = autocomplete.getPlace();
        $('#frmNew input[name=lat]').val(place.geometry.location.lat());
        $('#frmNew input[name=lng]').val(place.geometry.location.lng());
        if(map!=null) {
            map.setCenter(new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()));
            removeMapMarkersSearch();
            map.setZoom(17);
            addMapMarkerSearch({lat: place.geometry.location.lat(), lng: place.geometry.location.lng()});
            moveMapTo({lat: place.geometry.location.lat(), lng: place.geometry.location.lng()});
      }
  });

  //For Edit Branch Office
  autocompleteEdit = new google.maps.places.Autocomplete(
      $('#frmEdit input[name=address]')[0], {types: ['geocode']});
  autocompleteEdit.setFields(['geometry']);
  autocompleteEdit.addListener('place_changed', function() {
      var place = autocompleteEdit.getPlace();
        $('#frmEdit input[name=lat]').val(place.geometry.location.lat());
        $('#frmEdit input[name=lng]').val(place.geometry.location.lng());
        if(map!=null) {
            console.log(place.geometry.location);
            map.setCenter(new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()));
            removeMapMarkersSearch();
            map.setZoom(17);
            addMapMarkerSearch({lat: place.geometry.location.lat(), lng: place.geometry.location.lng()});
            moveMapTo({lat: place.geometry.location.lat(), lng: place.geometry.location.lng()});
        }
  });
}
let googleKey = $('#google_maps_key').val();
let google_plugins = $('#google_maps_libraries').val();
document.write(`<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>`);
document.write(`<script src="https://maps.googleapis.com/maps/api/js?key=${googleKey}&libraries=${google_plugins}&callback=initMap" async defer></script>`);


