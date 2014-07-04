<?php 

	$request_array = array (
		'song',
		'search',
		'suggestion',
	);


	$suggestion_url_format = 'http://www.last.fm/search/autocomplete?q=%1$s';
	$song_url_format = 'http://www.nhaccuatui.com/bai-hat/%1$s.html';
	$search_url_format = 'http://www.nhaccuatui.com/tim-nang-cao?%1$s';

	$mode = $_REQUEST['mode'];
	$key = $_REQUEST['key'];
	//$mode = 'search';
	//$key = 'title%3Ddaylight%26singer%3D';

	switch ($mode) {
		case 'song':
			$url = getUrl($song_url_format,urldecode($key));
			handleSongRequest($url);
			break;

		case 'search':
			$url = getUrl($search_url_format,urldecode($key));
			handleSearchRequest($url);
			break;
		
		case 'suggestion':
			$url = getUrl($suggestion_url_format,urldecode($key));
			handleSuggestionRequest($url);
			break;

		default :
			die("api url error");
			break;
	}

	function handleSuggestionRequest($url){
		$json = getResponse($url);
		$data = json_decode($json);
		$returnData = array();
		$checkArray = array('song'=>array(),'singer'=>array());
		foreach($data->response->docs as $suggest){
			$type = "";
			$text = "";
			switch ($suggest->restype) {
				case '9':
					$type = 'song';
					$text = $suggest->track;
					break;

				case '6':
					$type = 'singer';
					$text = $suggest->artist;
					break;
				
				default:
					continue;
					break;
			}
			if(!empty($text) && !empty($type) && !in_array($text, $checkArray[$type])){
				$checkArray[$type][] = $text;
				$tmpData = array(
					'text' => $text,
					'type' => $type
				);
				$returnData[] = $tmpData;
			}
		}
		die(json_encode($returnData));
	}

	function handleSongRequest($url){
		$response = getResponse($url);
		$titleXmlPattern = '/<title>(.*?)<\/title>/';
		$artistXmlPattern = '/<creator>(.*?)<\/creator>/';
		$locationXmlPattern = '/<location>(.*?)<\/location>/';

		$lyricPattern = '/<div id="divLyric".*?>(.*?)<\/div>/';
		
		$xmlUrl = "http://www.nhaccuatui.com/flash/xml?key1=";
		$webContent = getResponse($url);
		//die($webContent);
		$pattern = '/http:\/\/www\.nhaccuatui\.com\/flash\/xml\?key1=(.*?)"/';
		preg_match($pattern,$webContent,$matches);
		preg_match($lyricPattern, $webContent, $lyricMatch);
		$lyric = "";
		if(count($lyricMatch) == 2) {
			$lyric = $lyricMatch[1];
		}

		if(count($matches) == 2){
			$dataUrl = $xmlUrl . $matches[1];
			$xmlData = getResponse($dataUrl);
			$xmlData = preg_replace('/\s+/',' ',$xmlData);
			$xmlData = removeUnicodeAccents($xmlData);
			
			preg_match_all($titleXmlPattern,$xmlData,$matches['title']);
			preg_match_all($artistXmlPattern,$xmlData,$matches['artist']);
			preg_match_all($locationXmlPattern,$xmlData,$matches['location']);
			$song = new songObject();
			$song->name = getDataFromCDATA($matches['title'][1][0]);
			$song->artist = getDataFromCDATA($matches['artist'][1][0]);
			$song->url = getDataFromCDATA($matches['location'][1][0]);
			$song->id = getSongIdFromUrl($url);
			$song->lyric = $lyric;
			die(json_encode($song));
			//print_r($matches);
			
		} else {
			wlog($url);
			die ("cannot get xml id of this song : $url");
		}
	}

	function handleSearchRequest($url){
		$response = getResponse($url);

		$searchReturnPattern = '/<ul class="search_returns_list">(.*?)<\/ul>/';
		$inforSongPattern = '/<a href="(.*?)" class="name_song".*?>(.*?)<\/a>.*?<a href=".*?" class="name_singer".*?>(.*?)<\/a>.*?<\/div>.*?<\/div>/';

		$webContent = getResponse($url);

		preg_match($searchReturnPattern, $webContent, $returnSearch);

		$resultContent = $returnSearch[1];

		$songList = array();

		preg_match_all($inforSongPattern,$resultContent,$matches['infor']);

		foreach($matches['infor'][0] as $key => $asong){
			$song = new songObject();
			$song->name = $matches['infor'][2][$key];
			$song->artist = $matches['infor'][3][$key];
			$song->url = $matches['infor'][1][$key];
			$song->id = getSongIdFromUrl($song->url);
			$songList[] = $song;
		}
		die(json_encode($songList));
		//print_r($matches);
		
	}

	function getSongIdFromUrl($url){
		$pattern = '/http:\/\/www\.nhaccuatui\.com\/bai-hat\/.*?\.(.*?)\.html/';
		$patternLong = '/http:\/\/www\.nhaccuatui\.com\/bai-hat\/(.*?)\.html/';
		preg_match($pattern,$url,$matches);
		preg_match($patternLong,$url,$matchesL);
		return array('id' => $matches[1], 'longId' => $matchesL[1]);
	}

	function getUrl($format,$key){
		return sprintf($format,$key);
	}

	class songObject {
		public $name = "";
		public $artist = "";
		public $url = "";
		public $album = "";
		public $id = "";
		public $lyric = "";
	}
	
	function removeUnicodeAccents($input){
		$marTViet=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
		"ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề"
		,"ế","ệ","ể","ễ",
		"ì","í","ị","ỉ","ĩ",
		"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
		,"ờ","ớ","ợ","ở","ỡ",
		"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
		"ỳ","ý","ỵ","ỷ","ỹ",
		"đ",
		"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
		,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
		"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
		"Ì","Í","Ị","Ỉ","Ĩ",
		"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
		,"Ờ","Ớ","Ợ","Ở","Ỡ",
		"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
		"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
		"Đ");

		$marKoDau=array("a","a","a","a","a","a","a","a","a","a","a"
		,"a","a","a","a","a","a",
		"e","e","e","e","e","e","e","e","e","e","e",
		"i","i","i","i","i",
		"o","o","o","o","o","o","o","o","o","o","o","o"
		,"o","o","o","o","o",
		"u","u","u","u","u","u","u","u","u","u","u",
		"y","y","y","y","y",
		"d",
		"A","A","A","A","A","A","A","A","A","A","A","A"
		,"A","A","A","A","A",
		"E","E","E","E","E","E","E","E","E","E","E",
		"I","I","I","I","I",
		"O","O","O","O","O","O","O","O","O","O","O","O"
		,"O","O","O","O","O",
		"U","U","U","U","U","U","U","U","U","U","U",
		"Y","Y","Y","Y","Y",
		"D");
		return str_replace($marTViet,$marKoDau,$input);
	}
	
	function getDataFromCDATA($input){
		$pattern = '/CDATA\[(.*?)\]/';
		preg_match($pattern,$input,$match);
		if(count($match) > 0) {
			return removeUnicodeAccents($match[1]);
		} else {
			echo "$input";
			die;
			return "";
		}
			
	}
	

	function getResponse($url){
		$response = file_get_contents($url);

		return standardString($response);
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1667.0 Safari/537.36');
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		$return = curl_exec($ch);
		// Then, after your curl_exec call:
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($return, 0, $header_size);
		echo $header;
		$return = $return;
		curl_close($ch);
		wlog($return);
		return standardString($return);
	}

	function standardString($str){
		$str = preg_replace("/\s+/"," ",$str);
		return $str;
	}

	function wlog($data) {
		if (!is_string($data)) $log = print_r($data,1); 
		else $log = $data;
		file_put_contents('log.dat', $log."\n" ,FILE_APPEND);
	}

?>