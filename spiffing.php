<?php

/**
 *	Spiffing, by @idiot.
 *	A simple preprocessor that transforms correctly-spelt Queen's English into valid CSS.
 *
 *	Jolly good show.
 */
 
//  Set a custom header
function set_header($header) {
	//  Safely set the header
	if(!headers_sent()) header($header);
}

//  Create a 404 function, since we do that a lot
function not_found() {
	//  Set the header
	set_header('HTTP/1.0 404 Not Found');
	
	//  And stop execution. We don't need no content.
	exit;
}

$s = $_SERVER;
$f = str_replace('spiffing=', '', filter_input(INPUT_GET, 'file', FILTER_SANITIZE_STRING));
 
//  Check the file exists
if(isset($f)) {
	
	//  Build a path based on the file
	$file = dirname(__FILE__) . '/' . $f;
	
	//  Make sure the file exists and is readable
	if(!file_exists($file) or !is_readable($file)) {
		not_found();
	} else {
		/*
		//  Build the file URL
		$url = str_replace('?' . $s['QUERY_STRING'], '', $s['REQUEST_URI']) . $f;
		$url = 'http://' . $s['HTTP_HOST'] . str_replace(basename(__FILE__), '', $url);
		
		//  Check it's actually a CSS file
		$headers = get_headers($url, 1);
		
		//  What is this tripe you're giving me?
		if($headers['Content-Type'] !== 'text/css') {
			not_found();
		}*/
		
		//  Set a CSS header
		set_header('Content-Type: text/css');
		
		//  Create an array of find and replaces
		$find = array('colour', 'grey', '!please', 'transparency', 'centre', 'plump', 'photograph', 'capitalise');
		$replace = array('color', 'gray', '!important', 'opacity', 'center', 'bold', 'image', 'capitalize');
		
		//  Read the file contents and spit 'em out
		echo str_ireplace($find, $replace, file_get_contents($file));
		
		//  YOU SHALL NOT PASS
		exit;
	}
	
	//  Stop any other execution afterwards
	exit;
}

not_found();