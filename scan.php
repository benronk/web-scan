<?php 

include 'phpQuery.php';

//$this->load->helper('phpQuery');
//$this->load->model('scans_model');
//$this->load->model('scanned_stats_model');

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
	echo $item['url'] . '<br><div><pre>' . htmlentities($contents) . '</pre></div><br>';
	my_file_put_contents($item['filename'], $contents);
	echo '<br><br>';
}

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



echo '<br><br>Total time: ' . round(microtime(true)-$start_time, 3) . ' seconds';




function my_file_put_contents($filename, $contents)
{
	echo 'Writing file to: scans/'.$filename.' Length: '.strlen($contents).'<br>';
	file_put_contents('scans/'.$filename, $contents);
}

// Parse scraped data
/*foreach (pq('table tbody tr') as $tr)
{
	$character_data = array(
		'scan_id' => $scan_id,
		'character' => trim(pq('td:eq(2)', $tr)->text()),
		'scan_timestamp' => time(),
		'level' => trim(pq('td:eq(1)', $tr)->text()),
		'deaths' => trim(pq('td:eq(3)', $tr)->text()),
		'kills' => trim(pq('td:eq(4)', $tr)->text()),
		'quests_completed' => trim(pq('td:eq(5)', $tr)->text()),
		'button_clicks' => trim(pq('td:eq(6)', $tr)->text()),
		'best_kill_streak' => trim(pq('td:eq(7)', $tr)->text()),
		'best_travel_streak' => trim(pq('td:eq(8)', $tr)->text()),
		'longest_slow_death' => trim(pq('td:eq(9)', $tr)->text())
	);
	array_push($parsed_data, $character_data);
}

// insert stats into DB
$this->scanned_stats_model->insert_batch($parsed_data);

// recored scan info
$sql = $this->scans_model->update($scan_id, time()-$start_time, memory_get_usage(), count($parsed_data));

$data['scanned_content'] = $sql;
$data['content'] = '';//$scraped_string;

$this->load->view('index', $data);
*/