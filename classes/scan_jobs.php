<?php 

require 'phpQuery.php';

class Scan_Job
{
	public $name = '';
	public $url = '';
	public $contents = '';
	public $filename = '';
	
	public function run() {}
	
	protected function get_html($url = "NA")
	{
		if ($url == "NA")
			$url = $this->url;
		
		$opts = array('http'=>array('header' => "User-Agent:Mozilla/5.0\r\n"));
		//$opts = array('http' => array('header' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n"));
		$context = stream_context_create($opts);
		$html = file_get_contents($url, false, $context);
		return $html;
	}
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
		$html = $this->get_html();
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
			'filename' => 'com.hudhomestore.summit.md'
		),array(
			'name' => 'Medina County HUD Homes',
			'url' => 'https://www.hudhomestore.com/Listing/PropertySearchResult.aspx?pageId=1&zipCode=&city=&county=Medina&sState=OH&fromPrice=0&toPrice=0&fCaseNumber=&bed=0&bath=0&street=&buyerType=0&specialProgram=&Status=0&sPageSize=250&OrderbyName=SCASENUMBER&OrderbyValue=ASC&sLanguage=ENGLISH',
			'filename' => 'com.hudhomestore.medina.md'
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
		$houses = array();
		$html = $this->get_html();
		$pq_list = phpQuery::newDocumentHTML($html);
		foreach (pq('table#dgPropertyList tr:gt(0)', $pq_list) as $tr)
		{
			$house = array(
				'case_number' => trim(pq('td:eq(1)', $tr)->text()),
				//$address = trim(str_replace('<br>', " ", pq('td:eq(2) span', $tr)->html())),
				'address' => explode('<br>', trim(pq('td:eq(2) span', $tr)->html())),
				'price' => trim(pq('td:eq(3)', $tr)->text()),
				'status' => trim(pq('td:eq(4) img', $tr)->attr('title')),
				'bed' => trim(pq('td:eq(5)', $tr)->text()),
				'bath' => trim(pq('td:eq(6)', $tr)->text()),
				'listing_period' => trim(pq('td:eq(7) b span', $tr)->text()),
				'listing_period_desc' => trim(pq('td:eq(7) img', $tr)->attr('title')),
				'bid_open_date' => trim(pq('td:eq(8)', $tr)->text())
			);
			
			$html = $this->get_html('http://www.hudhomestore.com/Listing/PropertyDetails.aspx?caseNumber='.$house['case_number']);
			$pq_sub_list = phpQuery::newDocumentHTML($html);
			$house['list_date'] = DateTime::createFromFormat('m/d/Y', trim(pq('#ctl00_lblListdate', $pq_sub_list)->text()));;
			$house['period_deadline'] = trim(pq('#ctl00_lblBidDeadline')->text());
			
			array_push($houses, $house);
		}
		
		usort($houses, function($a, $b)
		{
			if ($a['list_date'] == $b['list_date']) {
				return 0;
			}
			return ($a['list_date'] < $b['list_date']) ? 1 : -1;
		});
		
		$this->contents .= '# ' . $this->name . "\r\n\r\n";
		
		foreach ($houses as $h)
		{
			//var_dump($case_number, $address, $price, $status, $bed, $bath, $listing_period, $bid_open_date);
			//echo "\r\n";
			
			//image link
			//https://www.hudhomestore.com/pages/ImageShow.aspx?Case=412-634528
			//details link
			//http://www.hudhomestore.com/Listing/PropertyDetails.aspx?caseNumber=412-495739
			//http://maps.google.com/maps?q=255+Virginia+Avenue+Wadsworth,+OH
			//http://www.zillow.com/homes/255+Virginia+Avenue+Wadsworth,+OH,+44281/
			$this->contents .= '[<img alt="'.$h['status'].'" src="https://www.hudhomestore.com/pages/ImageShow.aspx?Case='.$h['case_number'].'" align="right" style="height:150px;">](http://www.hudhomestore.com/Listing/PropertyDetails.aspx?caseNumber='.$h['case_number'].')';
			$this->contents .= '**'.trim($h['address'][0]).' '.trim($h['address'][1])."**  \r\n";
			$this->contents .= '[HUD](http://www.hudhomestore.com/Listing/PropertyDetails.aspx?caseNumber='.$h['case_number'].'), '
							  .'[Google Maps](http://maps.google.com/maps?q='.urlencode(trim($h['address'][0]).' '.trim($h['address'][1])).'), '
							  .'[Zillow](http://www.zillow.com/homes/'.urlencode(trim($h['address'][0]).' '.trim($h['address'][1])).'/)'."  \r\n"; 
			$this->contents .= '**Price:** '.$h['price']. "  \r\n";
			$this->contents .= '**List Date:** '.$h['list_date']->format('n/d/Y'). "  \r\n";
			$this->contents .= '**Listing Period:** ' . $h['listing_period'].' ('.$h['listing_period_desc'].')'."  \r\n";
			$this->contents .= '**Period Deadline:** '.$h['period_deadline']. "  \r\n";
			$this->contents .= '**Status:** ' . $h['status'] . "  \r\n";
			$this->contents .= '**Bed/Bath:** '.$h['bed'].'/'.$h['bath']."  \r\n";
			$this->contents .= '**Bid Open Date:** ' . $h['bid_open_date'] . "\r\n\r\n";
			$this->contents .= "***\r\n\r\n";
		}
	}
}