<?php

// this example.php file was last update May 28th 2009, 10:55am NZST

require_once('analytics_api.php');

// enter your login, password and id into the variables below to try it out

$login = '';
$password = '';

// NOTE: the id is in the form ga:12345 and not just 12345
// if you do e.g. 12345 then no data will be returned
// read http://www.electrictoolbox.com/get-id-for-google-analytics-api/ for info about how to get this id from the GA web interface
// or load the accounts (see below) and get it from there
// if you don't specify an id here, then you'll get the "Badly formatted request to the Google Analytics API..." error message
$id = '';

$api = new analytics_api();

if($api->connect($login, $password)) {

	echo "login success\n";
	$api->setProfileId($id);
	if(false) {
		
		// ->load_accounts() loads the accounts in your profile you have access to into ->accounts
		// read more about this at the following urls:
		// http://www.electrictoolbox.com/get-google-analytics-profile-id-accounts-list/
		// http://www.electrictoolbox.com/php-google-analytics-load-accounts-list/
		// note: you don't actually need to load the accounts to use the analytics class
		$api->load_accounts();
		print_r($api->accounts);
	}
		
	// get some account summary information without a dimension
	// change to if(true) to echo the example
	if(false) {
		$data = $api->data('', 'ga:bounces,ga:newVisits,ga:visits,ga:pageviews,ga:uniquePageviews');
		foreach($data as $metric => $count) {
			echo "$metric: $count\n";
		}
	}

	// get the pagePath vs pageviews & unique pageviews
	// change to if(true) to echo the example
	if(false) {
		
		$data = $api->data('ga:pagePath', 'ga:pageviews,ga:uniquePageviews');
//		print_r($data);
		
		// how to loop through the data
		foreach($data as $dimension => $metrics) {
			echo "$dimension pageviews: {$metrics['ga:pageviews']} unique pageviews: {$metrics['ga:uniquePageviews']}\n";
		}
		
	}

	// get the browser vs visits & pageviews	
	// change to if(true) to echo the example
	if(false) {
	
		$data = $api->data('ga:browser,ga:browserVersion', 'ga:visits,ga:pageviews', false, false, false, 100);
		//print_r($data);

		// you can then access the metrics for a specific dimension vs metric like e.g.
		echo $data['Internet Explorer']['8.0']['ga:pageviews'], "\n";
		// or loop through the data
		foreach($data as $dimension1 => $array) {
			foreach($array as $dimension2 => $metrics) {
				echo "$dimension1 $dimension2 visits: {$metrics['ga:visits']} pageviews: {$metrics['ga:pageviews']}\n";
			}
		}
		
	}

	// get a summary for the selected profile just for yesterday
	if(false) {
		$data = $api->get_summary();
		print_r($data);
	}

				
	if(false) {
		// get data filtered by canada, using a string as the filters parameter
		$data = $api->data('', 'ga:visits,ga:pageviews', false, false, false, 10, 1, 'ga:country%3d%3dCanada');
		print_r($data);
		// get data filtered by canada and firefox browser, using a string as the filters parameter
		$data = $api->data('', 'ga:visits,ga:pageviews', false, false, false, 10, 1, 'ga:country%3d%3dCanada;ga:browser%3d@Firefox');
		print_r($data);
		// same as the second example above but using the filtering class
		$filters = new analytics_filters('ga:country', '==', 'Canada');
		$filters->add_and('ga:browser', '=@', 'Firefox');
		$data = $api->data('', 'ga:visits,ga:pageviews', false, false, false, 10, 1, $filters);
		print_r($data);
		// using the filtering class to filter where USA or Canada
		$filters = new analytics_filters('ga:country', '==', 'Canada');
		$filters->add_or('ga:country', '==', 'France');
		$data = $api->data('', 'ga:visits,ga:pageviews', false, false, false, 10, 1, $filters);
		print_r($data);
	}
	
}
else {

	echo "login failed\n";
	
}
