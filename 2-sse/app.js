// [START app]
'use strict';

// [START setup]
// By default, the client will authenticate using the service account file
// specified by the GOOGLE_APPLICATION_CREDENTIALS environment variable and use
// the project specified by the GCLOUD_PROJECT environment variable. See
// https://googlecloudplatform.github.io/gcloud-node/#/docs/google-cloud/latest/guides/authentication
// These environment variables are set automatically on Google App Engine
var Datastore = require('@google-cloud/datastore');
// Instantiate a datastore client
var datastore = Datastore();

var express = require('express');
var EventSource = require('eventsource');
var particle_access_token = '9a1109fa8db44c28951f17ba06d2a4d02f1bc6e3';
var particle_event = 'Shocks';
var url = "https://api.particle.io/v1/events/"+particle_event+"?access_token=" + particle_access_token;
var app = express();

app.enable('trust proxy');

var source = new EventSource(url);
	source.addEventListener(particle_event, function(event) {
		var data=JSON.parse(event.data);
		if(typeof(data.coreid)!="undefined" && data.coreid!=""){
			var key = datastore.key('Shocks_'+data.coreid);
			var dataset=JSON.parse("["+data.data+"]");
			
			for (var i=0; i < dataset.length; i++) {
				var ds=dataset[i];
				if(ds.length==14){
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
						time : ds[13]
					}
					
					datastore.insert({
						key: key,
						data: obj
					}, function(err) {
						if(err) {
							console.log('There was an error storing the event', err);
						}
						console.log('stored in datastore', obj);
					});
				}
				/*if (dataset.hasOwnProperty(ds)) {
					var obj = {
						device_id: data.coreid,
						event: particle_event,
						published_at: data.published_at
						
					}
					for (var prop in dataset[ds]) {
						if (dataset[ds].hasOwnProperty(prop)) {
							obj[prop] = dataset[ds][prop];
						}
					}
					
				}*/
			}
		}
		
		//var data = jQuery.parseJSON(event.data);
		//live.addItem(data.update);
	}, false);

// [END app]

module.exports = app;
