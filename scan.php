<?php 

require 'classes/scan_jobs.php';

//return;

$start_time = microtime(true);

if ($argc <= 1) return;
for ($j = 1; $j < $argc; $j++)
{
	$scan_class = $argv[$j];
	
	echo 'Begin '.$scan_class." scan\r\n";
	$scan_start_time = microtime(true);

	for ($i = 0; $i < count($scan_class::$data); $i++)
	{
		$scan = new $scan_class($i);
		$scan->run();
		my_file_put_contents($scan->filename, $scan->contents);
	}

	git_commit('Autobot '.$scan_class.' commit');

	echo 'End '.$scan_class.' scan. Total elapsed time: ' . round(microtime(true)-$scan_start_time, 3) . " seconds\r\n\r\n";
}

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
