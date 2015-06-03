<?php
date_default_timezone_set('America/Los_Angeles');

$googleTrendData = array(
	'pn' => 'p1',
	'ajax' => 1,
	'htv' => 'l',
	'htd' => date("Ymd")
);

$googleTrendUrl = 'http://www.google.com/trends/hottrends/hotItems';

$cacheFilePath = "cached_data.txt";

function getTrendingStories()
{
	global $googleTrendData, $googleTrendUrl;
	
	$cachedData = getCachedData();
	if ($cachedData == null)
	{
		// Make a request to google trend to get today's trending stories
		$resp = makeRequest($googleTrendUrl, "POST", $googleTrendData);
		
		// Decode json string and find out titles of trending stories
		$rawJson = json_decode($resp, true);
		$trendLists = recursiveFind($rawJson, "trendsList");
		$trendTitleLists = array();	
		foreach($trendLists[0] as $value)
		{
			$trendTitleLists[] = $value['title'];
		}

		// Slice the array to only get top 30 titles
		$trendTitleLists = array_slice($trendTitleLists, 0, 3);
		
		$count = 0;			
		$dom = new DOMDocument;
		$container = $dom->createElement('div');
		$rowContainer = NULL;
		foreach($trendTitleLists as $value)
		{
			$imageItem = getImageTitleAndLink($value);
			$imageContainer = constructNewPhotoNode($dom, $imageItem);			
			if ($count % 3 == 0)
			{
				$rowContainer = $dom->createElement('div');
				$rowContainer->setAttribute("class", "row");
				$container->appendChild($rowContainer);
			}
			$rowContainer->appendChild($imageContainer);
			$count++;			
		}
		$dom->appendChild($container);
		
		writeCachedData($dom->saveHTML($container));
		echo $dom->saveHTML($container);
	}
	else
	{
		echo $cachedData;
	}
}

getTrendingStories();

// ------------------ helper functions -----------------------------

/***************************************************
Construct a dom element that represents an image item
param $imageItem: an object that has title, imgUrl, imgPageLink properties
****************************************************/
function constructNewPhotoNode($dom, $imageItem)
{
	$imageContainer = $dom->createElement('div');
	$imageContainer->setAttribute("class", "col-md-4 portfolio-item");
	
	$imageLink = $dom->createElement('a');
	$imageLink->setAttribute("href", "$imageItem->imgPageLink");
	$imageContainer->appendChild($imageLink);
	
	$img = $dom->createElement('img');
	$img->setAttribute("class", "img-responsive");
	$img->setAttribute("src", "$imageItem->imgUrl");
	$imageLink->appendChild($img);
	
	$imgTitleContainer = $dom->createElement('h3');
	$imgTitle = $dom->createElement('a', "$imageItem->title");
	$imgTitle->setAttribute("href", "$imageItem->imgPageLink");
	$imgTitleContainer->appendChild($imgTitle);
	$imageContainer->appendChild($imgTitleContainer);
	
	$pinLink = $dom->createElement('a', "Click to Pin");
	$pinLink->setAttribute("class", "floatMenu");
	$pinLink->setAttribute("onclick", "popWindow()");
	$pinLink->setAttribute("href", "#");
	$imageContainer->appendChild($pinLink);

	return $imageContainer;
}


/***************************************************
Given an array and return first item that is not NULL or empty
****************************************************/
function getValidItem($array)
{
	foreach($array as $item)
	{
		if($item != NULL && !empty($item))
		{
			return $item;
		}
	}
}

/***************************************************
Given a keyword and use google custom API to search 
and parse the returned data to get link of image thumbnail
and page link
param title: keyword to search
return: an object that consists of keyword, image thumbnail's link and page link
****************************************************/
function getImageTitleAndLink($title)
{
	$url = "https://www.googleapis.com/customsearch/v1?cx=007306240209605891397%3Ah9qucp1qv8g&q=".urlencode($title)."&num=5&key=AIzaSyDqHSQNdJfxBFIvTmwHfpBR2HWo4FZ7aOI";
	$resp = makeRequest($url, "GET");
	$rawJson = json_decode($resp, true);
	
	$thumbnails = recursiveFind($rawJson, "pagemap", ["cse_thumbnail"]);
	$thumbnailLinks = recursiveFind($thumbnails, "src");
	$pageLinks = recursiveFind($rawJson, "link");
	$thumbnailLink = getValidItem($thumbnailLinks);
	$pageLink = getValidItem($pageLinks);
	$imageItem = (object) array('title' => $title,
								'imgUrl' => $thumbnailLink,
								'imgPageLink' => $pageLink);
	
	return $imageItem;
}

/***************************************************
Make POST request using curl and return the response 
param url: url to send the request to
param data: data to include in the POST request. Needs to be a dictionary which contains key value
pairs for each parameter 
param requestType: string that represents request type, it needs to be either GET or POST, default to GET
return: response from server
****************************************************/
function makeRequest($url, $requestType, $data = NULL)
{
	// Get cURL resource
	$curl = curl_init($url);
	
	if ($requestType == "POST")
	{
		# Form data string
		$postString = http_build_query($data, '', '&');
		
		# Setting our options
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postString);
	}
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	
	if(!$resp){
		die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
	}
	
	// Close request to clear up some resources
	curl_close($curl);
	return $resp;
}

/****************************************************
Recursively search array for given key, for each matched key parse its value and add it to an array then return it at the end 
param array: array to search
param expected_key: keyword to search
param value_keys: An array that contains list of keywords to filter $value. For each found key's $value, optionally we can filter out the info in the $value that we need. Ie, if $value for a given key is an array that contains {'name':'John', 'age':25, 'height':'180cm'}. We can filter out only name and age by setting $value_keys to be {'name', 'age'}  
****************************************************/
function recursiveFind(array $array, $expected_key, $value_keys = NULL)
{
	$count = 0;
	$results = array();
    $iterator  = new RecursiveArrayIterator($array);
    $recursive = new RecursiveIteratorIterator($iterator,
                         RecursiveIteratorIterator::SELF_FIRST);
    foreach ($recursive as $key => $value) {
        if ($key === $expected_key) {
			$item = array();
			if (is_null($value_keys))
			{
				$item = $value;
			}
			else
			{
				foreach ($value_keys as $value_key)
				{
					$item[$value_key] = $value[$value_key];
				}
			}
			$results[] = $item;
        }
    }
	
	return $results;
}

// simple implementation for caching. This might cause trouble if there are lots of concurrent request

/****************************************************
Get cached data from file, return null if file doesn't exist
or cached data has expired(cache expires every calendar day)
****************************************************/
function getCachedData()
{
	global $cacheFilePath;
	if (!file_exists($cacheFilePath))
		return null;
	$lines = file($cacheFilePath);
	$date = $lines[0];
	$date = preg_replace('/([\r\n\t])/','', $date); // remove EOL
	if ($date == date("Ymd"))
	{	
		$trending_data = $lines[1];
		$trending_data = preg_replace('/([\r\n\t])/','', $trending_data); // remove EOL
		return $trending_data;
	}
	else
	{
		return null;
	}
}

/****************************************************
Write cached data to file, first line will be date on 
which the cached is written
****************************************************/
function writeCachedData($data)
{
	global $cacheFilePath;
	$data = preg_replace('/([\r\n\t])/','', $data); // remove EOL
	file_put_contents($cacheFilePath, date("Ymd").PHP_EOL );
	file_put_contents($cacheFilePath, $data.PHP_EOL , FILE_APPEND);
}

?>