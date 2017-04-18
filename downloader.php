<?php
$vt=strtotime($_GET['von']);
$bt=strtotime($_GET['bis']);
// GOOGLE API SCHLÜSSEL AIzaSyAHjZc-2fvn4AEyqbGOq1H_LjDdClPXb4o
$url="https://downloader-dot-trackbee-159216.appspot.com/?von=".$vt."&bis=".$bt."&time=".time();

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
	// convert from degrees to radians
	$latFrom = deg2rad($latitudeFrom);
	$lonFrom = deg2rad($longitudeFrom);
	$latTo = deg2rad($latitudeTo);
	$lonTo = deg2rad($longitudeTo);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	return $angle * $earthRadius;
}


if($_GET['mapping']!="true"){
	$data="shocks > 1.00G,shocks > 1.25G,shocks > 1.50G,shocks > 1.75G,shocks > 2.00G,lat,lon,speed,angle,satellites,timestamp,published_at,distance1\r\n".file_get_contents($url);
	$file="/tmp/".time().".csv";
	$fp = fopen($file, "wb");
	fwrite($fp,$data);
	fclose ($fp);
}else{
	$data=file_get_contents($url);
}

if($_GET['snapping']=="true"){
	
	$array=explode("\r\n",$data);
	$c=0;
	$str_array=array();
	$str=array();
	$save_str=array();
	if($_GET['mapping']!="true")
		$result_array=array("shocks > 1.00G,shocks > 1.25G,shocks > 1.50G,shocks > 1.75G,shocks > 2.00G,lat,lon,speed,angle,satellites,timestamp,published_at,snapped_lat,snapped_lon,distance1,distance2");
	else
		$result_array=array();
	function create_result($str,$save_str,&$result_array){
		
		$qry_str="?path=".implode("|",$str)."&interpolate=false&key=AIzaSyAHjZc-2fvn4AEyqbGOq1H_LjDdClPXb4o";
			$ch = curl_init();
			// Set query data here with the URL
			curl_setopt($ch, CURLOPT_URL, 'https://roads.googleapis.com/v1/snapToRoads' . $qry_str); 
			//var_dump('https://roads.googleapis.com/v1/snapToRoads' . $qry_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, '3');
			$content = trim(curl_exec($ch));
			curl_close($ch);
			$data=json_decode($content,true);
			foreach($data["snappedPoints"] as $snappedpoint){
				$save_str[$snappedpoint["originalIndex"]].=",".$snappedpoint["location"]["latitude"].",".$snappedpoint["location"]["longitude"];
				$result_array[]=$save_str[$snappedpoint["originalIndex"]];
			}
	}
	foreach($array as $k => $v){
		if($c==99 || $c == (count($array)-2)){
			$c=0;
			create_result($str,$save_str,$result_array);
			$str=array();
			$save_str=array();
		}
		
		$temp=explode(",",$v);
		
		if($temp[5]!="lat"){
			$str[]=abs($temp[5]).",".abs($temp[6]);
			$save_str[]=$v;
			$c++;		
		}
	}
	$data=implode("\r\n",$result_array);
	$file="/tmp/".time().".csv";
	$fp = fopen($file, "wb");
	fwrite($fp,$data);
	fclose ($fp);
	
}
if($_GET['mapping']!="true"){
	if ($fd = fopen ($file, "r")) {
		$fsize = filesize($file);
		$path_parts = pathinfo($file);
		$ext = strtolower($path_parts["extension"]);
		switch ($ext) {
			case "csv":
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
			break;
			// add more headers for other content types here
		default;
			header("Content-type: application/octet-stream");
			header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
			break;
		}
		header("Content-length: $fsize");
		//header("Cache-control: private"); //use this to open files directly
		header("Cache-Control: no-cache, no-store, must-revalidate");
		header("Pragma: no-cache");
		header("Expires: 0");
		while(!feof($fd)) {
			$buffer = fread($fd, 2048);
			echo $buffer;
		}
	}
	else echo "File not found";
	fclose ($fd);
}else{
	//echo $data;
	$result_array=array();
	$array=explode("\r\n",$data);
	foreach($array as $k => $waypoint){
		$wp0=explode(",",$waypoint);
		$wp1=explode(",",$array[($k+1)]);
		if(($k)<(count($array)-2)){
			if($_GET['snapping']=="true"){
				$wp_0["lat"]=$wp0[12];
				$wp_0["lng"]=$wp0[13];
				
				$wp_1["lat"]=$wp1[12];				
				$wp_1["lng"]=$wp0[13];
				
				
				$result_array[]=array(
					"coords" => array(
						array(
							"lat" => $wp_0["lat"],
							"lng" => $wp_0["lng"]
						),
						array(
							"lat" => $wp_1["lat"],
							"lng" => $wp_1["lng"]
						)
					),
					"shocks" => ($wp1[0] + $wp1[1] + $wp1[2] + $wp1[3] + $wp1[4])
				);
			}else{
				
				$result_array[]=array(
					"coords" => array(
						array(
							"lat" => $wp0[5],
							"lng" => $wp0[6]
						),
						array(
							"lat" => $wp1[5],
							"lng" => $wp1[6]
						)
					),
					"shocks" => ($wp1[0] + $wp1[1] + $wp1[2] + $wp1[3] + $wp1[4])
				);
			}
		}
	}
	echo json_encode($result_array);
	
}


?>