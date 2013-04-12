<?php 

require 'classes/scan_jobs.php';

$start_time = microtime(true);

/* 
	Scrape for FliteTest
*/

$scans = array(
	new FliteTest_Scan('http://shop.flitetest.com/', 				'com.flitetest.shop.html')
	,new FliteTest_Scan('http://shop.flitetest.com/airplane-kits', 	'com.flitetest.shop.airplane-kits.html')
	,new FliteTest_Scan('http://shop.flitetest.com/multirotors', 	'com.flitetest.shop.multirotors.html')
	,new FliteTest_Scan('http://shop.flitetest.com/accessories/', 	'com.flitetest.shop.accessories.html')
);

foreach($scans as $scan) 
{ 
	$scan->run();
	my_file_put_contents($scan->filename, $scan->contents);
}

echo "\r\n";

echo "git add -A scans/";
echo exec('git add -A scans/') . "\r\n";

echo "git commit -m \"Autobot commit\"\r\n";
echo "\t" . exec('git commit -m "Autobot commit"') . "\r\n";

/*
	Scrape for HUD
*/

$HUD_items = array(
	array('url' => '', 'filename' => '')
	//, array('url' => '', 'filename' => '')
);

/*foreach ($HUD_items as $item)
{
	phpQuery::newDocumentHTML(file_get_contents($item['url']));
	$contents = pq('div#main')->html();
	my_file_put_contents($item['filename'], $contents);
}*/

/*
echo "\r\n";

echo "git add -A scans/";
echo exec('git add -A scans/') . "\r\n";

echo "git commit -m \"Autobot commit\"\r\n";
echo "\t" . exec('git commit -m "Autobot commit"') . "\r\n";
*/

echo "git push origin master\r\n";
echo "\t" . exec('git push origin master') . "\r\n";

echo "\r\n";

echo 'Total time: ' . round(microtime(true)-$start_time, 3) . " seconds\r\n\r\n";




function my_file_put_contents($filename, $contents)
{
	echo 'Writing file to: scans/'.$filename.' Length: '.strlen($contents)."\r\n";
	file_put_contents('scans/'.$filename, $contents);
}
