<?php 

require 'phpQuery.php';

class Scan_Job
{
	public $name = '';
	public $url = '';
	public $contents = '';
	public $filename = '';
	
	public function run() {}
}

class FliteTest_Scan extends Scan_Job
{
	public static $data = array(
		 array(
			'name' => 'Flitetest Shop Main',
			'url' => 'http://shop.flitetest.com/',
			'filename' => 'com.flitetest.shop.html'
		),array(
			'name' => 'Flitetest Shop Airplane Kits',
			'url' => 'http://shop.flitetest.com/airplane-kits',
			'filename' => 'com.flitetest.shop.airplane-kits.html'
		),array(
			'name' => 'Flitetest Shop Multirotors',
			'url' => 'http://shop.flitetest.com/multirotors',
			'filename' => 'com.flitetest.shop.multirotors.html'
		),array(
			'name' => 'Flitetest Shop Accessories',
			'url' => 'http://shop.flitetest.com/accessories/',
			'filename' => 'com.flitetest.shop.accessories.html'
		)
	);
	
	function __construct ($data_key = 0)
	{
		$this->name = self::$data[$data_key]['name'];
		$this->url = self::$data[$data_key]['url'];
		$this->filename = self::$data[$data_key]['filename'];
	}
	
	public function run()
	{
		$html = file_get_contents($this->url);
		phpQuery::newDocumentHTML($html);
		$this->contents = pq('div#main')->html();
	}
}

class HUD_Scan extends Scan_Job
{
	public static $data = array(
		array(
			'name' => 'Summit County HUD Homes',
			'url' => 'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=Summit&sState=OH&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&sPageSize=250&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sLanguage=ENGLISH',
			'filename' => 'com.hudhomestore.summit'
		),array(
			'name' => 'Medina County HUD Homes',
			'url' => 'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=Medina&sState=OH&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&sPageSize=250&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sLanguage=ENGLISH',
			'filename' => 'com.hudhomestore.medina'
		)
	);
	
	function __construct ($data_key = 0)
	{
		$this->name = self::$data[$data_key]['name'];
		$this->url = self::$data[$data_key]['url'];
		$this->filename = self::$data[$data_key]['filename'];
	}
	
	public function run()
	{
		$opts = array('http'=>array('header' => "User-Agent:Mozilla/5.0\r\n"));
		//$opts = array('http' => array('header' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n"));
		$context = stream_context_create($opts);
		$html = file_get_contents($this->url, false, $context);
		
		$this->contents .= '# ' . $this->name . "\r\n\r\n";
		
		phpQuery::newDocumentHTML($html);
		foreach (pq('table#dgPropertyList tr:gt(0)') as $tr)
		{
			$case_number = trim(pq('td:eq(1)', $tr)->text());
			$address = trim(str_replace('<br>', ", ", pq('td:eq(2) span', $tr)->html()));
			$price = trim(pq('td:eq(3)', $tr)->text());
			$status = trim(pq('td:eq(4) img', $tr)->attr('title'));
			$bed = trim(pq('td:eq(5)', $tr)->text());
			$bath = trim(pq('td:eq(6)', $tr)->text());
			$listing_period = trim(pq('td:eq(7) b span', $tr)->text());
			$bid_open_date = trim(pq('td:eq(8)', $tr)->text());
			
			//var_dump($case_number, $address, $price, $status, $bed, $bath, $listing_period, $bid_open_date);
			//echo "\r\n";
			
			//image link
			//https://www.hudhomestore.com/pages/ImageShow.aspx?Case=412-634528
			//details link
			//http://www.hudhomestore.com/Listing/PropertyDetails.aspx?caseNumber=412-495739
			$this->contents .= '## ' . $case_number . "\r\n\r\n";
			$this->contents .= $address . "\r\n\r\n";
			$this->contents .= $price . ' - ' . $status . "\r\n\r\n";
		}
	}
}