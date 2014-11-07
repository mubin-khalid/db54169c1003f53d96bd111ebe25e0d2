<?php
	set_time_limit(0);
	error_reporting(0);
	require_once 'spider.php';
	require_once 'parser.php';
	/**
	 * pdOnline
	 *
	 * @package pdOnline
	 * @author Mubin Khalid
	 * @copyright 2014
	 * @version 1.2(revised)
	 * @access mixed
	 */
	class pdOnline {
		public $outFile;
		public $post;
		public $mainURI;
		public $referer;
		public $header;
		private $toSend;
		public $delay;
		public $useProxy;
		public $continueScrapping;
		public $counter;
                private $cachedURIs;
		function __construct() {
			$this->outFile = "output.csv";
			$this->counter = 0;
			$this->cachedURIs = array();
                        $this->toSend = '&_ctl2%3Arp1=&_ctl4%3AbtnOk=I+agree&txtBack=1&txtConfirmURL=';
			$this->post = "__VIEWSTATE=dDwxOTUxNDk5NTA3O3Q8O2w8aTwxPjs%2BO2w8dDw7bDxpPDI%2BO2k8Nj47aTwxMj47aTwxND47PjtsPHQ8O2w8aTwwPjs%2BO2w8dDw7bDxpPDA%2BOz47bDx0PHA8cDxsPFRleHQ7PjtsPFw8YSBocmVmPSJodHRwOi8vY2l0eXBsYW4yMDE0bWFwcy5icmlzYmFuZS5xbGQuZ292LmF1L0NpdHlQbGFuLyIgdGFyZ2V0PSJfYmxhbmsiXD5JbnRlcmFjdGl2ZSBNYXBwaW5nXDwvYVw%2BIFw8YSBocmVmPSJodHRwOi8vY2l0eXBsYW4yMDE0bWFwcy5icmlzYmFuZS5xbGQuZ292LmF1L0NpdHlQbGFuLyIgdGFyZ2V0PSJfYmxhbmsiXD5cPC9hXD47Pj47Pjs7Pjs%2BPjs%2BPjt0PDtsPGk8MD47PjtsPHQ8O2w8aTwxPjs%2BO2w8dDxAMDxwPHA8bDxFeHBhbmRNb2RlOz47bDxUZWxlcmlrLldlYkNvbnRyb2xzLlBhbmVsYmFyRXhwYW5kTW9kZSwgUmFkUGFuZWxiYXIsIFZlcnNpb249NC4wLjEuMCwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj1lMGQxNmY2ZjRjN2UwNWRlPFNpbmdsZUV4cGFuZGVkSXRlbT47Pj47PjtAMDxAMDxwPHA8bDxUZXh0O0V4cGFuZGVkO1NlbGVjdGVkOz47bDxQcm9wZXJ0eSBlbnF1aXJ5O288dD47bzxmPjs%2BPjs%2BO0AwPEAwPHA8cDxsPFRleHQ7TmF2aWdhdGVVcmw7U2VsZWN0ZWQ7PjtsPEhvbWUgXDxpbWcgc3JjPSIuLlxcLi5cXGNvbW1vblxcUmFkQ29udHJvbHNcXHBhbmVsYmFyXFxza2luc1xcZGVmYXVsdFxcaW1nXFxoZWFkZXJhcnJvdy5naWYiIGJvcmRlcj0iMCIvXD47Li4vLi4vZW5xdWlyZXIvZGVmYXVsdC5hc3B4P3BhZ2U9aG9tZTtvPHQ%2BOz4%2BOz47Oz47QDA8cDxwPGw8VGV4dDtOYXZpZ2F0ZVVybDtTZWxlY3RlZDs%2BO2w8RGlzY2xhaW1lcjsuLi8uLi9lbnF1aXJlci9kZWZhdWx0LmFzcHg%2FcGFnZT1kaXNjbGFpbWVyO288Zj47Pj47Pjs7PjtAMDxwPHA8bDxUZXh0O05hdmlnYXRlVXJsO1NlbGVjdGVkOz47bDxTZWFyY2g7Li4vLi4vZW5xdWlyZXIvZGVmYXVsdC5hc3B4P3BhZ2U9c2VhcmNoO288Zj47Pj47Pjs7Pjs%2BOz47Pjs%2BO2w8aTwwPjs%2BO2w8dDxwPHA8bDxUZXh0O0V4cGFuZGVkO1NlbGVjdGVkOz47bDxQcm9wZXJ0eSBlbnF1aXJ5O288dD47bzxmPjs%2BPjs%2BO2w8aTwwPjtpPDE%2BO2k8Mj47PjtsPHQ8cDxwPGw8VGV4dDtOYXZpZ2F0ZVVybDtTZWxlY3RlZDs%2BO2w8SG9tZSBcPGltZyBzcmM9Ii4uXFwuLlxcY29tbW9uXFxSYWRDb250cm9sc1xccGFuZWxiYXJcXHNraW5zXFxkZWZhdWx0XFxpbWdcXGhlYWRlcmFycm93LmdpZiIgYm9yZGVyPSIwIi9cPjsuLi8uLi9lbnF1aXJlci9kZWZhdWx0LmFzcHg%2FcGFnZT1ob21lO288dD47Pj47Pjs7Pjt0PHA8cDxsPFRleHQ7TmF2aWdhdGVVcmw7U2VsZWN0ZWQ7PjtsPERpc2NsYWltZXI7Li4vLi4vZW5xdWlyZXIvZGVmYXVsdC5hc3B4P3BhZ2U9ZGlzY2xhaW1lcjtvPGY%2BOz4%2BOz47Oz47dDxwPHA8bDxUZXh0O05hdmlnYXRlVXJsO1NlbGVjdGVkOz47bDxTZWFyY2g7Li4vLi4vZW5xdWlyZXIvZGVmYXVsdC5hc3B4P3BhZ2U9c2VhcmNoO288Zj47Pj47Pjs7Pjs%2BPjs%2BPjs%2BPjs%2BPjt0PHA8bDxUZXh0Oz47bDxcPGRpdiBpZD0ib3ZlckRpdiIgc3R5bGU9InBvc2l0aW9uOmFic29sdXRlXDt2aXNpYmlsaXR5OmhpZGRlblw7ei1pbmRleDo5OThcOyJcPlw8L2Rpdlw%2BDQpcPHNjcmlwdCBzcmM9Ii4uLy4uL2NvbW1vbi9vdmVyTGliLmpzIiBsYW5ndWFnZT0namF2YXNjcmlwdCdcPlw8L3NjcmlwdFw%2BDQoNClw8c2NyaXB0IGxhbmd1YWdlPSJKYXZhU2NyaXB0Ilw%2BV2FpdC5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnXDtcPC9zY3JpcHRcPg0KXDxkaXYgaWQ9ImRpdkJhY2tUb1RvcCIgIG9uQ2xpY2s9ImphdmFzY3JpcHQ6bXRfcGFnZXRvcCgpIiBjbGFzcz0iSGlkZGVuTmF2Ilw%2BXDxpbWcgc3JjPSIuLi8uLi9jb21tb24vYmFzZWltYWdlcy90b3AuZ2lmIiB3aWR0aD0iMTgiIGhlaWdodD0iMjciIGJvcmRlcj0iMCIgYWx0PSJUb3Agb2YgcGFnZSJcPlw8L2Rpdlw%2BXDxkaXYgaWQ9ImRpdk1vcmVPblBhZ2UiIG9uQ2xpY2s9ImphdmFzY3JpcHQ6bXRfcGFnZWRvd24oKSIgY2xhc3M9IkhpZGRlbk5hdiJcPlw8aW1nIHNyYz0iLi4vLi4vY29tbW9uL2Jhc2VpbWFnZXMvbW9yZS5naWYiIHdpZHRoPSIyMiIgaGVpZ2h0PSIyNyIgYm9yZGVyPSIwIiBhbHQ9Ik1vcmUgaW5mb3JtYXRpb24gb24gcGFnZSJcPlw8L2Rpdlw%2BDQpcPHNjcmlwdCBsYW5ndWFnZT0namF2YXNjcmlwdCdcPg0KdmFyIHg9JzEnXDsNCnZhciBpc0JhY2tcOw0KZnVuY3Rpb24gaGFuZGxlQmFja0J1dHRvbigpIHsNCmlzQmFjayA9ICh4ICE9IGRvY3VtZW50LmZybU1hc3RlclBsYW4udHh0QmFjay52YWx1ZSlcOw0KZG9jdW1lbnQuZnJtTWFzdGVyUGxhbi50eHRCYWNrLnZhbHVlPTJcOw0KZG9jdW1lbnQuZnJtTWFzdGVyUGxhbi50eHRCYWNrLmRlZmF1bHRWYWx1ZT0yXDsNCmlmKGlzQmFjaykNCnsNCnZhciBzdHJVUkxcOw0Kc3RyVVJMPWRvY3VtZW50LmFsbC5pdGVtKCJ0eHRDb25maXJtVVJMIikudmFsdWVcOw0KdmFyIHN0clw7DQpzdHI9d2luZG93LmxvY2F0aW9uLmhyZWZcOw0KaWYoc3RyLmluZGV4T2YoJ3BhZ2U9dXNlcycpIT0tMSkNCnsNCmhpc3RvcnkuYmFjaygxKVw7DQp9DQppZihzdHIuaW5kZXhPZigncGFnZT1jb25maXJtJykhPS0xKQ0Kew0KfQ0KaWYoc3RyLmluZGV4T2YoJ3BhZ2U9dXNlcycpPT0tMSAmJiBzdHIuaW5kZXhPZigncGFnZT1jb25maXJtJyk9PS0xKQ0Kew0KaWYoc3RyLmluZGV4T2YoJ2NvdW50ZXI9JykhPS0xKQ0Kew0KdmFyIGNvdW50ZXIgPSBnZXRQYXJhbWV0ZXIoJ2NvdW50ZXInKVw7DQp2YXIgc3RlcHNiYWNrXDsNCnN0ZXBzYmFjaz0wXDsNCmlmKGNvdW50ZXIhPSdudWxsJyAmJiBjb3VudGVyIT0nJykNCnsNCnN0ZXBzYmFjayA9IHN0ZXBzYmFjayArIHBhcnNlSW50KGNvdW50ZXIpXDsNCn0NCnZhciBiYWNrbnVtXDsNCmJhY2tudW09cGFyc2VJbnQoc3RlcHNiYWNrKVw7DQpiYWNrbnVtPS1iYWNrbnVtXDsNCmlmKHN0ZXBzYmFjayAhPSAwKQ0Kew0KaGlzdG9yeS5nbyhiYWNrbnVtKVw7DQp9DQp9DQp9DQp9DQp9DQpcPC9zY3JpcHRcPg0KXDxzY3JpcHQgbGFuZ3VhZ2U9J2phdmFzY3JpcHQnXD4NCmZ1bmN0aW9uIGdldFBhcmFtZXRlcihwYXJhbWV0ZXJOYW1lKSB7DQp2YXIgcXVlcnlTdHJpbmcgPSB3aW5kb3cubG9jYXRpb24uc2VhcmNoLnN1YnN0cmluZygxKS50b0xvd2VyQ2FzZSgpXDsNCnZhciBwYXJhbWV0ZXJzID0gbmV3IEFycmF5KClcOw0KcGFyYW1ldGVycyA9IHF1ZXJ5U3RyaW5nLnNwbGl0KCcmJylcOw0KZm9yKHZhciBpID0gMFw7IGkgXDwgcGFyYW1ldGVycy5sZW5ndGhcOyBpKyspIA0Kew0KaWYocGFyYW1ldGVyc1tpXS5pbmRleE9mKHBhcmFtZXRlck5hbWUudG9Mb3dlckNhc2UoKSlcPj0wKSANCnsNCnZhciBwYXJhbWV0ZXJWYWx1ZSA9IG5ldyBBcnJheSgpXDsNCnBhcmFtZXRlclZhbHVlID0gcGFyYW1ldGVyc1tpXS5zcGxpdCgnPScpXDsNCnJldHVybiBwYXJhbWV0ZXJWYWx1ZVsxXVw7DQp9DQp9DQpyZXR1cm4gIm51bGwiXDsNCn0NClw8L3NjcmlwdFw%2BDQo7Pj47Oz47dDxwPGw8VmlzaWJsZTs%2BO2w8bzxmPjs%2BPjs7Pjs%2BPjs%2BPjtsPF9jdGwyOnJwMTs%2BPg7Usj5HcIZQZ9rUeeSnIR0YsHYi";
			$this->mainURI  = 'http://pdonline.brisbane.qld.gov.au/MasterView/masterplan/enquirer/default.aspx';
			$this->referer = 'http://pdonline.brisbane.qld.gov.au/MasterView/masterplan/enquirer/default.aspx';
			$this->header['Host'] = 'pdonline.brisbane.qld.gov.au';
			$this->header['Accept'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
			$this->header['Accept-Language'] = 'en-US,en;q=0.5';
			$this->header['Content-Type'] = 'application/x-www-form-urlencoded';
			$this->header['Content-Length'] = '4912';
		}
		private function throwError($status = '404 Page Not Found'){
			file_put_contents('ErrorLog.txt', $status ." Error occured at ".date("m-d-Y h:m:i").PHP_EOL);
		}
		public function setCookie(){
			$page = Spider::spider($this->header, false, $this->mainURI, false, false, $this->useProxy);
			$this->post = $this->getViewState($page).$this->toSend;
			Spider::spider($this->header, false, $this->mainURI, false, $this->post, $this->useProxy);
			
		}
		private function getViewState($page){
			
			preg_match('/__VIEWSTATE\" value=\"(.*)\"/i', $page, $matches);
			return "__VIEWSTATE=".urlencode(trim($matches[1]));
			
		}
		private function parserPage($page){
			sleep($this->delay);
			$data = array();
			if($page){
				$page = str_get_html($page);
				foreach($page->find('#_ctl4_lblHead') as $address){
					$addr = trim(strip_tags($address->innertext));
					$addr = explode(",", $addr);
					$data['street'] = $addr[0];
					$data['suburb'] = $addr[1];
				}
				foreach($page->find("#lbldetail") as $detail){
					$details = $detail->innertext;
					$details = explode("<br/>", $details);
					foreach ($details as $field){
						if($field == ""){continue;}
						$subField = (explode(":", strip_tags($field)));
						if($subField){
							for($counter = 0; $counter < count($subField); $counter++){
								$data[trim($subField[0])] = trim($subField[1]);
								$counter += 1;
							}
						}
					}
				}
				$land = explode(",", $data['Land Number(s)']);
				unset($data['Land Number(s)']);
				$land = array_filter($land);
				if(isset($land[1])){
					$data['Land Number 2'] = $land[1];
					unset($land[1]);
				}
				$data["Land Number 1"] = $land[0];
				unset($land[0]);
				if(isset($land[2])){
					$data["Land Numbers"] = trim(implode(" | ", $land));
				}
				foreach($page->find("#lblstat") as $status){
					$data['status'] = trim(strip_tags($status->innertext));
				}
				foreach ($page->find("#lblarea") as $area){
					$areas = explode('<br/>', trim($area->innertext));
					$areas = array_filter($areas);
					if(isset($areas[1]) && !empty($areas[1])){
						$data['area2'] = $areas[1];
						unset($areas[1]);
					}
					$data['area1'] = $areas[0];
					unset($areas[0]);
					if(isset($areas[2]) && !empty($areas[2])){
						$data['areas'] = implode(" | ", $areas);
					}
				}
				foreach ($page->find("#lblzone") as $area){
					$areas = explode('<br/>', trim($area->innertext));
					$areas = array_filter($areas);
					if(isset($areas[1]) && !empty($areas[1])){
						$data['zone2'] = $areas[1];
						unset($areas[1]);
					}
					$data['zone1'] = $areas[0];
					unset($areas[0]);
					if(isset($areas[2]) && !empty($areas[2])){
						$data['zones'] = implode(" | ", $areas);
					}
				}
				foreach ($page->find('#lblapp') as $app){
					$data['application'] = trim(strip_tags($app));
				}
				foreach ($page->find("#lblconst") as $contnt){
					$data['content'] = trim(str_replace("<br/>", " | ", $contnt->innertext));
				}
				$this->putToFile($data);
			}
		}
		private function putToFile($data){
			$contents = array();
			$opener = fopen($this->outFile, "a");
			if(isset($data)){
				if(isset($data['street']) && !empty($data['street'])){
					$contents['Street'] = trim($data['street']);
				}else{
					$contents['Street'] = "N/A";
				}
				if(isset($data['suburb']) && !empty($data['suburb'])){
					$contents['Suburb'] = trim($data['suburb']);
				}else{
					$contents['Suburb'] = "N/A";
				}
				if(isset($data['Property Number']) && !empty($data['Property Number'])){
					$contents['Property Number'] = $data['Property Number'];
				}else{
					$contents['Property Number'] = "N/A";
				}
				if(isset($data['Lot/DP']) && !empty($data['Lot/DP'])){
					$contents['Lot/DP'] = $data['Lot/DP'];
				}else{
					$contents['Lot/DP'] = "N/A";
				}
				if(isset($data['Land Number 1']) && !empty($data['Land Number 1'])){
					$contents['Land Number 1'] = $data['Land Number 1'];
				}else{
					$contents['Land Number 1'] = "N/A";
				}
				if(isset($data['Land Number 2']) && !empty($data['Land Number 2'])){
					$contents['Land Number 2'] = $data['Land Number 2'];
				}else{
					$contents['Land Number 2'] = "N/A";
				}
				if(isset($data['Land Numbers']) && !empty($data['Land Numbers'])){
					$contents['Land Numbers'] = $data['Land Numbers'];
				}else{
					$contents['Land Numbers'] = "N/A";
				}
				if(isset($data['Description']) && !empty($data['Description'])){
					$contents['Description'] = $data['Description'];
				}else{
					$contents['Description'] = "N/A";
				}
				if(isset($data['Ward']) && !empty($data['Ward'])){
					$contents['Ward'] = $data['Ward'];
				}else{
					$contents['Ward'] = "N/A";
				}
				if(isset($data['status']) && !empty($data['status'])){
					$contents['Status'] = $data['status'];
				}else{
					$contents['Status'] = "N/A";
				}
				if(isset($data['area1']) && !empty($data['area1'])){
					$contents['Area1'] = $data['area1'];
				}else{
					$contents['Area1'] = "N/A";
				}
				if(isset($data['area2']) && !empty($data['area2'])){
					$contents['Area2'] = $data['area2'];
				}else{
					$contents['Area2'] = "N/A";
				}
				if(isset($data['areas']) && !empty($data['areas'])){
					$contents['Areas'] = $data['areas'];
				}else{
					$contents['Areas'] = "N/A";
				}
				if(isset($data['zone1']) && !empty($data['zone1'])){
					$contents['Zone1'] = $data['zone1'];
				}else{
					$contents['Zone1'] = "N/A";
				}
				if(isset($data['zone2']) && !empty($data['zone2'])){
					$contents['Zone2'] = $data['zone2'];
				}else{
					$contents['Zone2'] = "N/A";
				}
				if(isset($data['zones']) && !empty($data['zones'])){
					$contents['Zones'] = $data['zones'];
				}else{
					$contents['Zones'] = "N/A";
				}
				if(isset($data['application']) && !empty($data['application'])){
					$contents['Application'] = $data['application'];
				}else{
					$contents['Application'] = "N/A";
				}
				if(isset($data['content']) && !empty($data['content'])){
					$contents['Content'] = $data['content'];
				}else{
					$contents['Content'] = "N/A";
				}
                                if($contents['Street'] !== "N/A" && $contents['Suburb'] !== "N/A")
                                    fputcsv($opener, $contents);
				fclose($opener);
			}
		}
		public function search($stTo = 1, $stFrom = 1, $street = '', $suburb = ''){	
			$toSend = "&_ctl2%3Arp1=&_ctl4%3AnrUnit%3Anum1=&_ctl4%3AnrUnit%3Anum2=&_ctl4%3AnrStreet%3Anum1=$stTo&_ctl4%3AnrStreet%3Anum2=$stFrom&_ctl4%3AtxtStreet%3Atb=$street&_ctl4%3AtxtSuburb%3Atb=$suburb&_ctl4%3A_ctl3=&_ctl4%3A_ctl6=&_ctl4%3AbtnSearch.x=36&_ctl4%3AbtnSearch.y=4&txtBack=1&txtConfirmURL=";
			$view = $this->getViewState(Spider::spider($this->header, $this->referer, $this->mainURI.'?page=search', false, false, $this->useProxy));
			$this->post = $view.$toSend;
			$this->referer = "http://pdonline.brisbane.qld.gov.au/MasterView/masterplan/enquirer/default.aspx?page=search";
recheck:			
			$page = Spider::spider($this->header, $this->referer, $this->mainURI.'?page=search', false, $this->post, $this->useProxy);
			
			if(!$page){
                            $page = Spider::spider($this->header, $this->referer, $this->mainURI.'?page=search', false, $this->post, $this->useProxy);
                            if(!$page){
                                $this->throwError();
                                goto recheck;
                            }
			}
			$page = str_get_html($page);
			$links = array();
			foreach ($page->find('#_ctl4_lblData') as $table){
                            foreach ($table->find('a') as $a){
                                $links[] = "http://pdonline.brisbane.qld.gov.au/MasterView/masterplan/enquirer/".$a->href;
                            }
			}
			
			$links = array_unique($links);
			if(count($links == 0)){
				{
					if($this->counter == 400){
						$this->continueScrapping = false;
						$this->counter = 0;
						return;
					}else{
						$this->counter += 50;
					}
					
				}
			}
                        $this->cachedURIs = array();
			foreach($links as $link){
                                if($this->pushCache(str_replace("&amp;", "&", $link))){
                                    echo $link."<br />";
                                    continue;
                                }
                                file_put_contents('links.txt', str_replace("&amp;", "&", $link).PHP_EOL, FILE_APPEND);
                                $page = Spider::spider($this->header, false, str_replace("&amp;", "&", $link), false, false, $this->useProxy);
				if(!$page){
					$page = Spider::spider($this->header, false, str_replace("&amp;", "&", $link), false, false, $this->useProxy);
					if(!$page){
						$this->throwError();
						continue;
					}
				}
				$this->parserPage($page);
			}
		}
                private function pushCache($check){
                    if(in_array($check, $this->cachedURIs)){
                        return true;
                    }else{
                        $this->cachedURIs[] = $check;
                        return false;
                    }
                }
		public function parseFile($fileName)
		{
                    $csv = new parseCSV();
                    $csv->auto($fileName);
                    if (count($csv->data)) {
                            return $csv->data;
                    } else
                            return false;
		}

		public function getFilesName(){
                    $CSVs = array();
                    foreach (glob("./inputs/*.csv") as $file) {
                    if($file){
                            $CSVs[] = $file;
                    }
        	}
        	return $CSVs;
		}
	}

	function process(){
		$to;$from;$st;$sub;
		$scrapper = new pdOnline();
		$files = $scrapper->getFilesName();
		foreach ($files as $file) {
			$data = ($scrapper->parseFile($file));
                        
			if(isset($_POST['delay'])){
				$scrapper->delay = $_POST['delay'];
			}
			if(isset($_POST['proxyCheck'])){
				$scrapper->useProxy = true;
			}
			$scrapper->continueScrapping = true;
			$scrapper->setCookie();
			foreach($data as $single){
                                $scrapper->continueScrapping = true;
				$street = explode(" ", trim($single['Street Number']));
				if(count($street) < 2 ){
					$street = explode("-", trim($single['Street Number']));
				}
			
				if($street[0] === ""){
					$to = 1;
					$from = 10000;
				}else{
						
					if(isset($street[1])){
						$to = $street[0];
						$from = $street[1];
					}else{
						$street = explode(",", trim($single['Street Number']));
						if(isset($street[1])){
							$to = $street[0];
							$from = $street[1];
						}else{
							$to = $street[0];
							$from = $street[0];
						}
					}
				}
			
				$street = array();
				$st = $single['Street'];
				$sub = $single["Suburb"];
				if($from == 10000){
					$from = 50;
                                        
					while ($from <= 10000) {
						if($scrapper->continueScrapping === false){
							break 1;
						}
						$scrapper->search($to, $from, urlencode("$st"), urlencode("$sub"));
						$to = (int)$from + 1;
						$from += 50;
                                                
					}
				}else{
					$scrapper->search($to, $from, urlencode("$st"), urlencode("$sub"));
				}
			}
		}
	}
	if(isset($_POST['process'])){
		process();
	}	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Spiders</title>
	<script type="text/javascript" src = "script.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			getProxies();
		});
		function proxyChecker(){
			if(document.getElementById('proxyCheck').checked === true){
				
			}		   
		}
		var repeater;
		function getProxies() {
			$.ajax({
			    url: 'http://localhost/Mubin/pdOnline/lib_proxy/index.php',
			    method: 'GET',
			    success: function(msg) {
			        console.info(msg)
			    }
			});
			repeater = setTimeout(getProxies, 300000);
		}
		function isNumberKey(evt){
		    var charCode = (evt.which) ? evt.which : event.keyCode
		    if (charCode > 31 && (charCode < 48 || charCode > 57))
		        return false;
		    return true;
		}
	</script>
	<style>
	#wrapper{
		height: 250px;
		background-color: azure;
		text-align: center;
		display: block;
	}
	label{
		margin-right: 15px;
	}
	input[type='submit']{
		margin-top: 40px;
		margin-left: 5%;
		height: 31px;
		width: 140px;
		font-size: larger;
		color: white;
		background-color: green;
		border: none;
		box-shadow: 0px 0px 30px green;
		cursor: pointer;
		
	}
	#proxyArea{
		margin-top: 40px;
	}
	#delayArea{
		padding-top: 3%;
	}
	</style>
</head>
<body>
	<form action = "" method = "post">
		<section id = "wrapper">
			<div id = "delayArea">
				<label for = "delay" name = "labdelay">Delay</label>
				<input type = "text" name = 'delay' onkeypress="return isNumberKey(event)" required/>
			</div>
			<div id = "proxyArea">
				<label for = "proxyCheck">Use Proxies</label>
				<input type = "checkbox" checked = true name = "proxyCheck" id = 'proxyCheck' class = "proxyCheck"/>
			</div>
			<div>
				<input type = "submit" name = "process" value = "Crawl" onclick="proxyChecker()"/>
			</div>
		</section>
	</form>
</body>
</html>
