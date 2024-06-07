<?php

if($_SERVER['REQUEST_METHOD'] =='GET'){
	function formatBytes($size, $precision = 2)
	{
		$base = log($size, 1024);
		return round(pow(1024, $base - floor($base)), $precision);
	}
	function getDiskData(){
		return [
		"used"=>formatBytes(disk_total_space('/')-disk_free_space('/')),
		"total"=>formatBytes(disk_total_space('/')),
		"free"=>formatBytes(disk_free_space('/'))
		];
	}
	echo json_encode(getDiskData());
	
}


?>