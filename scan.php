<?php 

include 'phpQuery.php';

$start_time = microtime(true);

/* 
	Scrape for FliteTest
*/

$flitetest_items = array(
	array('url' => 'http://shop.flitetest.com/', 				'filename' => 'com.flitetest.shop.html')
	,array('url' => 'http://shop.flitetest.com/airplane-kits', 	'filename' => 'com.flitetest.shop.airplane-kits.html')
	,array('url' => 'http://shop.flitetest.com/multirotors', 	'filename' => 'com.flitetest.shop.multirotors.html')
	,array('url' => 'http://shop.flitetest.com/accessories/', 	'filename' => 'com.flitetest.shop.accessories.html')
);

foreach ($flitetest_items as $item)
{
	phpQuery::newDocumentHTML(file_get_contents($item['url']));
	$contents = pq('div#main')->html();
	my_file_put_contents($item['filename'], $contents);
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
