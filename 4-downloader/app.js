'use strict';
const express = require('express');
const app = express();
// By default, the client will authenticate using the service account file
// specified by the GOOGLE_APPLICATION_CREDENTIALS environment variable and use
// the project specified by the GCLOUD_PROJECT environment variable. See
// https://googlecloudplatform.github.io/gcloud-node/#/docs/google-cloud/latest/guides/authentication
// These environment variables are set automatically on Google App Engine
var Datastore = require('@google-cloud/datastore');

// Instantiate a datastore client
var datastore = Datastore();







var csv = "";

app.use(function (req, res, next) {
    res.header('Cache-Control', 'private, no-cache, no-store, must-revalidate');
    res.header('Expires', '-1');
    res.header('Pragma', 'no-cache');
    next()
});

app.get('/', function(req, res){
	//res.send('date: ' + req.query.date);
	console.log(typeof req.query.von);
	console.log(typeof req.query.bis);
	if(typeof req.query.von !="undefined" && typeof req.query.bis !="undefined"){
		//console.log("IMIN");
		var query = datastore.createQuery('Shocks_'+req.query.device_id)
			.filter('time', '>=', parseInt(req.query.von))
			.filter('time', '<=', parseInt(req.query.bis))						
			.limit(100000)
			.order('time', {
				descending: true
			});
		datastore.runQuery(query, function(err, entities, info) {
			if (err) {
				console.log("database query error", err);
				return;
			}
			csv = "";
	
			for(var ii = 0; ii < entities.length; ii++) {
				var data = entities[ii].data;
				/*
				if(ii==0){
					for (var prop in data) {
						csv = csv + prop + ',';
					}
					csv =csv + '\r\n';
				}
				for (var prop in data) {	
					if (data.hasOwnProperty(prop)) {
						csv = csv + data[prop] + ',';
					}
				}					
				csv =csv + '\r\n';
				*/
				
				csv = csv + data.s0 + ',' + data.s1 + ',' + data.s2 + ',' + data.s3 + ',' + data.s4 + ',' + data.s5 + ',' + data.lat  + ',' + data.lon  + ',' + (parseFloat(data.speed)*1.852)  + ',' + data.angle  + ',' + data.satellites + ',' + data.hdop + ',' + data.max_z_g + ',' + data.rms + ',' + data.time + ',' + data.published_at + '\r\n';
				
			}
		});
		console.log(csv);
		res.status(200).send(csv);
	}else{
		res.status(406).send("Missing GET Parameters ['von','bis','device_id']");
	}
});

// Start the server
const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
  console.log(`App listening on port ${PORT}`);
  console.log('Press Ctrl+C to quit.');
});
