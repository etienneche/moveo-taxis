var $ = jQuery.noConflict();

var map = {};

var markers = new Array();
var radius = new L.circleMarker();
var stationCords = ['32.08763764609234', '34.77298750727925'];

$(document).ready(function () {

    // global variables - siteObject

    let marker;
    initMap(); // init map
    onToggle(); // on swtich toggle
    onSelect(); // on select taxi change
    filterColor(); // on filter color
    update_form(); // on update form
    register_form(); // on registe form
    onRemove(); // on taxi remove
});

function onRemove(){
    $('.remove-taxi-btn').on('click', function(e){
        e.preventDefault();
        var taxi_id = $('.taxi-update input[name="taxi_id"]').val();
// Remove the modal and the fade
        var modalToggle = $('.modal-content');
        modalToggle.remove();
        var modalBackground = $('.modal-backdrop');
        modalBackground.remove();
        
        $.post({
            dataType: 'json',
            url: siteObject.ajaxurl,
            data: {
                action: 'remove_taxi',
                taxi_id: taxi_id,
            },
            success: function (response) {
                if ( response.ok) {
                   
                    $('.taxi-select option[value="' + taxi_id +  '"]').remove();
                    map.removeLayer(markers[ taxi_id ]);
                }
            }
        });
    });
}

function filterColor(){
    $('.form-filter [name="color"]').on( 'change', function(e){
        var color = $(this).val();
        if ( color == 'all' ) {
            $('.leaflet-marker-icon.color').show();
            $('.taxi-select option').prop( 'disabled', false);
        }else{
            // hide/show markers
            $('.leaflet-marker-icon.color').each( function(){
                if ( $(this).hasClass( color ) ) {
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
            // disable/enable select taxi options
            $('.taxi-select option').each( function(){
                if ( $(this).attr( 'data-color' ) == color ) {
                    $(this).prop( 'disabled', false);
                }else{
                    $(this).prop( 'disabled', true);
                }
            });
        }
    });
}
 function onToggle(){
    $(".toggle-btn").on('change', function(e){
        if ( $(this).prop("checked") == true  ) {
            radius = new L.circle(stationCords, {radius: 2000}).addTo(map);
            map.setView(stationCords, 14 );
        }else{
            map.setView(stationCords, 10 );
            map.removeLayer( radius );
        }
    });
}
function onSelect(){
    $('.taxi-select').on('change', function(e){
        var post_id = $(this).val();
        if ( post_id ) {
            // update hidden field on update form
            $('.taxi-update input[name="taxi_id"]').val( post_id );
            // zoom to taxi
            mapZoomIn( post_id );
            $.each(siteObject.taxies, function(k,taxi){
                if ( taxi.id == post_id ) {
                    $.each( taxi, function(i, data){
                        if ( i != 'color' ) {
                            $('.taxi-update input[name="' + i +'"]').val(data);
                        }else{
                            $('.taxi-update select[name="color"]').val(data.name).change();
                        }
                    });
                }
            });
            $('.taxi-update').slideDown();
        }else{
            $('.taxi-update').slideUp();
        }
    });
}
function mapZoomIn( taxi_id ){
    var lat = siteObject.taxies[taxi_id].lat;
    var lng = siteObject.taxies[taxi_id].lng;
    map.setView([lat, lng], 15 );
}
function update_form(){
    $('.taxi-update').on('submit', function(e){
        e.preventDefault();
        $.post({
            dataType: 'json',
            url: siteObject.ajaxurl,
            data: {
                action: 'update_form',
                data: $(this).serialize(),
            },
            success: function (response) {
                if ( response.ok) {
                    var alert = document.getElementById('update-alert');
                    var toast = new bootstrap.Toast( alert );
                    toast.show();
                    var year = $('.taxi-update [name="year"]').val();
                    var color = $('.taxi-update [name="color"]').val();
                    var model = $('.taxi-update [name="model"]').val();
                    var taxi_id = $('.taxi-update [name="taxi_id"]').val();
                    var title = $('.taxi-update [name="title"]').val();

                    var lat = $('.taxi-update [name="lat"]').val();
                    var lng = $('.taxi-update [name="lng"]').val();
                    // get marker object from array
                    var current_marker = markers[ $('[name="taxi_id"]').val() ];
                    // change marker location
                    current_marker.setLatLng([lat,lng]).update();
                    $('b.taxi-title').data('id', taxi_id ).text( title );
                    $('span.taxi-color-year').data('id', taxi_id ).text(  color + ', ' + year );
                    $('option[value= ' + taxi_id + ']').text( color + ' ' + title ); 
                    

                }
            }
        });
    });
}
function register_form(){
    $('.taxi-register').on('submit', function(e){
        e.preventDefault();
        $.post({
            dataType: 'json',
            url: siteObject.ajaxurl,
            data: {
                action: 'register_form',
                data: $(this).serialize(),
            },
            success: function (response) {
                if ( response.ok) {
                    location.reload();
                    
                }
            }
        });
    });
}

function initMap() {

    // init map
    map = L.map('map').setView( stationCords, 10);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1Ijoic2hheWtlayIsImEiOiJja3hybXEyZWEwejU4MndwNGRwZ3BhbGJmIn0.6gXMWxf2GRIDr2CsZf3V8w'
    }).addTo(map);

    var taxiIconObj = {
        iconUrl: siteObject.themeurl + '/assets/img/taxi.png',
        iconSize: [40, 20],
        shadowSize: [68, 95],
        shadowAnchor: [22, 94]
    };


    var stationIcon = L.icon({
        iconUrl: siteObject.themeurl + '/assets/img/station.png',
        iconSize: [70, 100],
        shadowSize: [68, 40],
        shadowAnchor: [22, 94]
    });
  // add station marker
    var stationMarker = L.marker( stationCords, {icon: stationIcon}).addTo(map);
    stationMarker.bindPopup('<b>Arolozorov Station</b>').openPopup();
  // add taxies markers
    $.each( siteObject.taxies, function(k, i){
        taxiIconObj.className = 'color ' + i.color.name;
      var taxiIcon = L.icon( taxiIconObj );
        markers[ k ] = L.marker([i.lat,  i.lng],{icon: taxiIcon}).addTo(map);
         markers[ k ].bindPopup('<b data-id=' + i.id  + ' class="taxi-title">' + i.title + '</b><br>' + '<span data-id=' + i.id  + ' class="taxi-color-year">' + i.color.name + ', ' + i.year + '</span>');
    });

}


