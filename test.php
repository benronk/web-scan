<!DOCTYPE html>
<html lang="en" ng-app="Webscan">
<head>
	<title>Web Scanner Test Page</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.0.5/angular.min.js" type="text/javascript"></script>
	<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
	
	<script>
		var app = angular.module('Webscan', []);
	</script>
</head>
<html>
<body style="padding-top:50px;">

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand">Web Scanner Test Page</a>
			<!--<ul class="nav">
				<li>An item maybe?</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						Tasks
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li></li>
						<li></li>
					</ul>
				</li>				
			</ul>-->
		</div>
	</div>
</div>

<?php 
	include 'classes/scan_jobs.php';

	$scans = array(
		new FliteTest_Scan('http://shop.flitetest.com/', 				'com.flitetest.shop.html')
		,new FliteTest_Scan('http://shop.flitetest.com/airplane-kits', 	'com.flitetest.shop.airplane-kits.html')
		,new FliteTest_Scan('http://shop.flitetest.com/multirotors', 	'com.flitetest.shop.multirotors.html')
		,new FliteTest_Scan('http://shop.flitetest.com/accessories/', 	'com.flitetest.shop.accessories.html')
	);

?>
			
<div class="container">
	<div class="row">
		<div class="span12">
			<?php 
			$i = 0;
			foreach($scans as $scan) { 
				$scan->run();
			?>
			
			<h4>Run job url: <?php echo $scan->url; ?></h4>
			
			<p><label>
				Results <input type="checkbox" ng-model="checked<?php echo $i; ?>">:
			</label></p>
			<p ng-show="checked<?php echo $i; ?>" style="display:none;">			
				<?php echo htmlentities($scan->contents); ?>
			</p>
			
			<?php $i++; } ?>
		</div>
	</div>
</div>

</body>
</html>