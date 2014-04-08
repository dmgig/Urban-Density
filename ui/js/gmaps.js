/*
 * get distance between two coordinates in nautical miles
 * using google's geometry library
 */
function getDistance(latlng1,latlng2)
{
	var m = google.maps.geometry.spherical.computeDistanceBetween(latlng1,latlng2);	//return dist in meters
	var nm = (m * .000539957); // convert to nautical miles
	return nm;
}

/*
 * MapManager object
 * set style option, build map, and plot & remove routes & markers, zoom to fit
 */
function MapManager()
{
	this.map     = null;
	this.paths   = new Array();
	this.markers = new Array();
	
	/* MAP STYLES AND OPTIONS */
	var BW_MAP = 'black_and_white';

	/* see: http://software.stadtwerk.org/google_maps_colorizr/ */
	var bw_mapstyle = [ { featureType: 'landscape', elementType: 'all', stylers: [ { hue: '#BF9D30' }, { saturation: 45 }, { lightness: -47 }, { visibility: 'simplified' } ] },{ featureType: 'landscape.man_made', elementType: 'all', stylers: [ { visibility: 'off' } ] },{ featureType: 'poi', elementType: 'all', stylers: [ { visibility: 'off' } ] },{ featureType: 'administrative.neighborhood', elementType: 'all', stylers: [ { visibility: 'off' } ] },{ featureType: 'administrative.land_parcel', elementType: 'all', stylers: [ { visibility: 'off' } ] },{ featureType: 'road', elementType: 'all', stylers: [ { visibility: 'off' } ] },{ featureType: 'water', elementType: 'all', stylers: [ { hue: '#1f1AB2' }, { saturation: 54 }, { lightness: -47 }, { visibility: 'on' } ] } ];
	
	var bw_mapopts = {
		name: 'b/w'
	};
	
	var bw_maptype = new google.maps.StyledMapType(bw_mapstyle, bw_mapopts);
	
	var mapopts = {
		zoom:3,
		center:new google.maps.LatLng(45.8282,-98.5795),
		minZoom: 2,
		mapTypeControl: false,
		streetViewControl: false,
		zoomControl: false,
		panControl: false,
		mapTypeId: BW_MAP
	};		
	
	this.map = null;
	
	/* METHODS */
	this.baseMap = function(div_id){
		this.map = new google.maps.Map(document.getElementById(div_id), mapopts);	
		this.map.mapTypes.set(BW_MAP, bw_maptype);
	}
	
	/*
	 * plot route
	 */
	this.plotRoute = function(latlng1,latlng2)
	{
		var parr = [latlng1, latlng2];
		this.plotPoints(parr);
		var path = new google.maps.Polyline({ 
			path: parr,
			strokeColor: '#FFDD73',
			strokeOpacity: 1.0,
			strokeWeight: 6,
			geodesic: true
		});
		path.setMap(this.map);
		this.paths.push(path);
		this.zoomToFit(parr);			
	}
	
	this.plotPoints = function(parr)
	{
		for(i in parr){
			var iconurl  = "/ui2/img/airport.png";
			var marker = new google.maps.Marker({ icon:iconurl, anchor:google.maps.Point(15,15) });
			marker.setPosition(parr[i]);
			marker.setMap(this.map);
			this.markers.push(marker);
		}
	}		
	
	/* Clears all routes & markers */
	this.clearRoutes = function()
	{
		for(i in this.paths){
			this.paths[i].setMap(null);	
		}
		this.paths = new Array();
	}
	this.clearMarkers = function()
	{
		for(i in this.markers){
			this.markers[i].setMap(null);	
		}
		this.markers = new Array();
	}	
	
	/* Zoom to fit */
	this.zoomToFit = function(parr)
	{
		var bounds = new google.maps.LatLngBounds();
		for (i in parr) {
			if(typeof parr[i].position !== 'undefined'){
				bounds.extend(parr[i].position);
			}else{
				bounds.extend(parr[i]);
			}
		}
		this.map.fitBounds(bounds);
	}			
}