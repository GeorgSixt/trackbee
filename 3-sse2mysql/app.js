// [START app]
'use strict';

// [START setup]




var mysql     =    require('mysql');

var express = require('express');
var EventSource = require('eventsource');
var particle_access_token = '9a1109fa8db44c28951f17ba06d2a4d02f1bc6e3';
var particle_event = 'Shocks';
var url = "https://api.particle.io/v1/events/"+particle_event+"?access_token=" + particle_access_token;
var app = express();
app.enable('trust proxy');

var pool      =    mysql.createPool({
    connectionLimit : 100, //important
    host     : 'localhost',
    user     : 'siotix_trackbee',
    password : 'Vvgeo3524!',
    database : 'siotix_trackbee',
    debug    :  false
});

var source = new EventSource(url);
	source.addEventListener(particle_event, function(event) {
		var data=JSON.parse(event.data);
		if(typeof(data.coreid)!="undefined" && data.coreid!=""){
			var key = datastore.key('Shocks_'+data.coreid);
			var dataset=JSON.parse("["+data.data+"]");
			
			for (var i=0; i < dataset.length; i++) {
				var ds=dataset[i];
				if(ds.length==15){
					var obj = {
						device_id: data.coreid,
						event: particle_event,
						published_at: data.published_at,
						s0 : ds[0],
						s1 : ds[1],
						s2 : ds[2],
						s3 : ds[3],
						s4 : ds[4],
						s5 : ds[5],
						lat :ds[6],
						lon :ds[7],
						speed:ds[8],
						angle:ds[9],
						satellites : ds[10],
						hdop : ds[11],
						max_z_g : ds[12],
						time : ds[13],
						rms : ds[14]
					}
					
					pool.getConnection(
						function(err,connection){
							if (err) {
								res.json({"code" : 100, "status" : "Error in connection database"});
								return;
							}   
							console.log('connected as id ' + connection.threadId);
        
							connection.query(
								"INSERT INTO `trackbee` SET ?",obj,
								function(err,results){
									connection.release();
									if(!err) {
										res.json(rows);
									}           
								}
							);

							connection.on('error', function(err) {      
								res.json({"code" : 100, "status" : "Error in connection database"});
								return;     
							});
						}
					);					
				}
			}
		}
	}, false);

// [END app]

module.exports = app;
