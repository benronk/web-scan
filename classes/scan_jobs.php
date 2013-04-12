<?php 

require 'phpQuery.php';

class Scan_Job
{
	public $contents = '';
	public $filename = '';
	
	public function run() {}
}

class FliteTest_Scan extends Scan_Job
{
	public $url = '';
	
	function __construct ($url, $filename)
	{
		$this->url = $url;
		$this->filename = $filename;
	}
	
	public function run()
	{
		phpQuery::newDocumentHTML(file_get_contents($this->url));
		$this->contents = pq('div#main')->html();
	}
}



/*
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
	//my_file_put_contents($item['filename'], $contents);
}
*/