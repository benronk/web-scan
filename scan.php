<?php 

require 'classes/scan_jobs.php';

//return;

$start_time = microtime(true);

/* 
Scrape for FliteTest
*/

echo "Begin FliteTest scan\r\n";
$scan_start_time = microtime(true);

for ($i = 0; $i < count(FliteTest_Scan::$data); $i++)
{
	$scan = new FliteTest_Scan($i);
	$scan->run();
	my_file_put_contents($scan->filename, $scan->contents);
}

git_commit('Autobot FliteTest commit');

echo 'End FliteTest scan. Total elapsed time: ' . round(microtime(true)-$scan_start_time, 3) . " seconds\r\n\r\n";

/*
Scrape for HUD
*/

echo "Begin HUD scan\r\n";
$scan_start_time = microtime(true);

for ($i = 0; $i < count(HUD_Scan::$data); $i++)
{
	$scan = new HUD_Scan($i);
	$scan->run();
	my_file_put_contents($scan->filename, $scan->contents);
}

git_commit('Autobot HUD commit');

echo 'End HUD scan. Total elapsed time: ' . round(microtime(true)-$scan_start_time, 3) . " seconds\r\n\r\n";

git_push();

echo 'End scan. Total elapsed time: ' . round(microtime(true)-$start_time, 3) . " seconds\r\n\r\n";




function my_file_put_contents($filename, $contents)
{
	echo 'Writing file to: scans/'.$filename.' Length: '.strlen($contents)."\r\n";
	file_put_contents('scans/'.$filename, $contents);
}

function git_commit($message = 'Autobot commit')
{
	echo "\r\n";

	echo "git add -A scans/";
	echo exec('git add -A scans/') . "\r\n";

	echo 'git commit -m "'.$message.'"'."\r\n";
	echo "\t" . exec('git commit -m "'.$message.'"') . "\r\n";
	
	echo "\r\n";
}

function git_push()
{
	echo "\r\n";

	echo "git push origin master\r\n";
	echo "\t" . exec('git push origin master') . "\r\n";

	echo "\r\n";
}
