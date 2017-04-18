<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TRACKBEE</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <style>
  body{
  font-family:Verdana;
  }
  .ui-slider-handle {
    width: 3.6em!important;
    height: 3.6em!important;
    top: 50%;
    margin-top: -1.8em!important;
    text-align: center;
    line-height: 1.1em;
	border-radius:50%!important;
	
	
  }
  .cont{
	padding:1em;
  }
  #console{
	border:1px solid #cdcdcd;
	max-height:300px;
	overflow:auto;
  }
  #map{
	  width:100%;
	  height:800px;
  }
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
  <script>
  var _access_token = "9a1109fa8db44c28951f17ba06d2a4d02f1bc6e3";
  var particleid = "370022000e51353432393339";
    
	
  $( function() {
	var handle = $( "#custom-handle" );
    $( "#slider" ).slider({
      create: function() {
        handle.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle.text( ui.value );
      },
	  stop: function( event, ui ) {
		//console.log(ui.value);
		$.ajax({
			method:'POST',
			url:'https://api.particle.io/v1/devices/'+particleid+'/threshhold',
			data:{
				access_token: _access_token,
				args: ui.value 
			},
			success:function(data){
				//str = JSON.stringify(data);
				str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
				//console.log(str); // Logs output to dev tools console.
				$('#console').prepend($.now()+'<br>'+str+'<hr>');
			},
			fail:function(data){
				str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
				//console.log(str); // Logs output to dev tools console.
				$('#console').prepend($.now()+'<br>FAILED<br>'+str+'<hr>');
			}
		});
	  }
    });
	
	var handle1 = $( "#custom-handle1" );
    $( "#slider1" ).slider({
      create: function() {
        handle1.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle1.text( ui.value );
      },
	  stop: function( event, ui ) {
		//console.log(ui.value);
		$.ajax({
			method:'POST',
			url:'https://api.particle.io/v1/devices/'+particleid+'/timelimit',
			data:{
				access_token: _access_token,
				args: ui.value 
			},
			success:function(data){
				//str = JSON.stringify(data);
				str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
				//console.log(str); // Logs output to dev tools console.
				$('#console').prepend($.now()+'<br>'+str+'<hr>');
			},
			fail:function(data){
				str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
				//console.log(str); // Logs output to dev tools console.
				$('#console').prepend($.now()+'<br>FAILED<br>'+str+'<hr>');
			}
		});
	  }
    });
	
	var handle2 = $( "#custom-handle2" );
    $( "#slider2" ).slider({
      create: function() {
        handle2.text( $( this ).slider( "value" ) );
      },
      slide: function( event, ui ) {
        handle2.text( ui.value );
      },
	  stop: function( event, ui ) {
		//console.log(ui.value);
		$.ajax({
			method:'POST',
			url:'https://api.particle.io/v1/devices/'+particleid+'/timelatency',
			data:{
				access_token: _access_token,
				args: ui.value 
			},
			success:function(data){
				//str = JSON.stringify(data);
				str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
				//console.log(str); // Logs output to dev tools console.
				$('#console').prepend($.now()+'<br>'+str+'<hr>');
			},
			fail:function(data){
				str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
				//console.log(str); // Logs output to dev tools console.
				$('#console').prepend($.now()+'<br>FAILED<br>'+str+'<hr>');
			}
		});
	  }
    });
	$.ajax({
		method:'GET',
		url:'https://api.particle.io/v1/devices/'+particleid+'/vthreshhold',
		data:{
			access_token: _access_token
		},
		success:function(data){
			$( "#slider" ).slider( "option", "value", data.result);
			handle.text( data.result );
		},
		fail:function(data){
			str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
			//console.log(str); // Logs output to dev tools console.
			$('#console').prepend($.now()+'<br>FAILED<br>'+str+'<hr>');
		}
	}).always(function (jqXHR) {
		if(jqXHR.status==408)$('#console').prepend($.now()+'<br>FAILED<br>device offline<hr>');
	});
	$.ajax({
		method:'GET',
		url:'https://api.particle.io/v1/devices/'+particleid+'/vtimelimit',
		data:{
			access_token: _access_token
		},
		success:function(data){
			$( "#slider1" ).slider( "option", "value", data.result);
			handle1.text( data.result );
		},
		fail:function(data){
			str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
			//console.log(str); // Logs output to dev tools console.
			$('#console').prepend($.now()+'<br>FAILED<br>'+str+'<hr>');
		}
	}).always(function (jqXHR) {
		if(jqXHR.status==408)$('#console').prepend($.now()+'<br>FAILED<br>device offline<hr>');
	});
	$.ajax({
		method:'GET',
		url:'https://api.particle.io/v1/devices/'+particleid+'/vtimelatency',
		data:{
			access_token: _access_token
		},
		success:function(data){
			$( "#slider2" ).slider( "option", "value", data.result);
			handle2.text( data.result );
		},
		fail:function(data){
			str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
			//console.log(str); // Logs output to dev tools console.
			$('#console').prepend($.now()+'<br>FAILED<br>'+str+'<hr>');
		}
	}).always(function (jqXHR) {
		if(jqXHR.status==408)$('#console').prepend($.now()+'<br>FAILED<br>device offline<hr>');
	});
	update_batt_status();
	$('#batt_status').parent().click(
		function(){
			update_batt_status();			
		}
	);
	$('#download').click(
		function(){
			if($('#dl_mapping').prop("checked")){
				$.ajax({
					method:'GET',
					url:'downloader.php',
					data:{
						von: $('#dl_von').val(),
						bis: $('#dl_bis').val(),
						snapping: $('#dl_snapping').prop("checked"),
						mapping: $('#dl_mapping').prop("checked")
					},
					success:function(data){
						data1=JSON.parse(data);
						//console.log(data1);
						initMap(data1);
					},
					fail:function(data){
						str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
						//console.log(str); // Logs output to dev tools console.
						$('#console').prepend($.now()+'<br>FAILED<br>'+str+'<hr>');
					}
				});
			}else{
				document.location.href="downloader.php?von="+$('#dl_von').val()+"&bis="+$('#dl_bis').val()+"&snapping="+$('#dl_snapping').prop("checked");
			}
		}
	);
  } );
  function update_batt_status(){
	  var _ele=$('#batt_status');
			$.ajax({
				method:'GET',
				url:'https://api.particle.io/v1/devices/'+particleid+'/vsoc',
				data:{
					access_token: _access_token
				},
				success:function(data){
					_ele.text( data.result+"% SoC" );
				},
				fail:function(data){
					str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
					//console.log(str); // Logs output to dev tools console.
					$('#console').prepend($.now()+'<br>FAILED<br>'+str+'<hr>');
				}
			}).always(function (jqXHR) {
				if(jqXHR.status==408)$('#console').prepend($.now()+'<br>FAILED<br>device offline<hr>');
			});
  }
  function rainbow(n) {
	return 'hsl(' + n + ',100%,50%)';
  }
  /*
  for (var i = 0; i <= 120; i+=1) {
      $('<b>X</b>').css({
        color: rainbow(i)
      }).appendTo('#colors');
  }*/
  
  function initMap(coords) {
	var bounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 3,
		center: {lat: 0, lng: -180},
		mapTypeId: google.maps.MapTypeId.TERRAIN
	});
	var symbolOne = {
		path: 'M -1,0 0,-1 1,0 0,1 z',
		strokeColor: '#000',
		fillColor: '#000',
		fillOpacity: 1
	};
	for(var i=0;i<coords.length;i++){
		var c = coords[i];
		//console.log(c.coords);
		c.coords[0].lat=Math.abs(parseFloat(c.coords[0].lat));
		c.coords[0].lng=Math.abs(parseFloat(c.coords[0].lng));
		c.coords[1].lat=Math.abs(parseFloat(c.coords[1].lat));
		c.coords[1].lng=Math.abs(parseFloat(c.coords[1].lng));
		//if(typeof(c.coords[0].lat)!="number")console.log(typeof(c.coords[0].lat));
		//if(typeof(c.coords[0].lng)!="number")console.log(typeof(c.coords[0].lng));
		//if(typeof(c.coords[1].lat)!="number")console.log(typeof(c.coords[1].lat));
		//if(typeof(c.coords[1].lng)!="number")console.log(typeof(c.coords[1].lng));
		bounds.extend(c.coords[0]);
		bounds.extend(c.coords[1]);
		if($('#dl_waypoints').prop("checked")){
			var Path = new google.maps.Polyline({
				path: c.coords,
				geodesic: true,
				strokeColor: rainbow((120-c.shocks*10)),
				strokeOpacity: 1.0,
				strokeWeight: 3,
				icons: [{
					icon: symbolOne,
					offset: '0%'
				}]
			});
		}else{
			var Path = new google.maps.Polyline({
				path: c.coords,
				geodesic: true,
				strokeColor: rainbow((120-c.shocks*10)),
				strokeOpacity: 1.0,
				strokeWeight: 3
			});
		}
		Path.setMap(map);
	}	
	//console.log(bounds);
	map.fitBounds(bounds);
  }
  
  </script>
</head>
<body>
<div class="cont">
	<h3>Trackbee <span id="batt_status"></span></h3>
	<h4>Threshhold</h4><br><br>
	<div id="slider">
		<div id="custom-handle" class="ui-slider-handle"></div>
	</div>
	<h4>Timelimit</h4><br><br>
	<div id="slider1">
		<div id="custom-handle1" class="ui-slider-handle"></div>
	</div>
	<h4>Timelatency</h4><br><br>
	<div id="slider2">
		<div id="custom-handle2" class="ui-slider-handle"></div>
	</div>
	<hr>
	<h4>Download</h4>
	von:&nbsp;<input value="<?php echo date("d.m.Y H:i:s"); ?>" type="text" placeholder="DD.MM.YYYY hh:mm:ss" name="von" id="dl_von" /><br>
	bis:&nbsp;<input value="<?php echo date("d.m.Y H:i:s"); ?>" type="text" placeholder="DD.MM.YYYY hh:mm:ss" name="bis" id="dl_bis" /><br>
	<input type="checkbox" value="1" name="snapping" id="dl_snapping" />&nbsp;Google Roads API point snapping<br>
	<input type="checkbox" value="1" name="mapping" id="dl_mapping" />&nbsp;display Google Map<br>
	<input type="checkbox" value="1" name="waypoints" id="dl_waypoints" />&nbsp;display Waypoints<br>
	<input type="button" id="download" value="Send" />	
	<div id="map"></map>
	<br><br>	
	<div id="console"></div>
</div>
 
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHjZc-2fvn4AEyqbGOq1H_LjDdClPXb4o" async defer></script>
</body>
</html>