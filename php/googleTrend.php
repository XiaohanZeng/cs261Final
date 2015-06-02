<?php

$google_trend_data = array(
	'pn' => 'p1',
	'ajax' => 1,
	'htv' => 'l',
	'htd' => date("Ymd")
);

$google_trend_url = 'http://www.google.com/trends/hottrends/hotItems';
	
function getTrendingStories()
{
	global $google_trend_data, $google_trend_url;
	
	// Make a request to google trend to get today's trending stories
	$resp = makeRequest($google_trend_url, "POST", $google_trend_data);
	
	// Decode json string and find out titles of trending stories
	$raw_json = json_decode($resp, true);
	$trend_lists = recursiveFind($raw_json, "trendsList");
	$trend_title_lists = array();	
	foreach($trend_lists[0] as $value)
	{
		//var_dump($value);
		$trend_title_lists[] = $value['title'];
	}

	// Slice the array to only get top 30 titles
	$trend_title_lists = array_slice($trend_title_lists, 0, 3);
	
	// Encode the results array to JSON string
	$trend_lists_encoded = json_encode(array_values($trend_title_lists));
	
	echo $trend_lists_encoded;
}

getTrendingStories();



// ------------------ helper functions -----------------------------

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

?>