<?php 

include 'classes/scan_jobs.php';

//for ($i = 0; $i < count(HUD_Scan::$data); $i++)
//{
	//$scan = new HUD_Scan($i);
	$scan = new HUD_Scan(1);
	//$scan = new FliteTest_Scan(1);
	$scan->run();
			
	echo $scan->contents . "\r\n\r\n";
//}