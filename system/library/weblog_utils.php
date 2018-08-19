<?php
// ***************************************************
//                       Weblog
//            Articles & News for Opencart	       
//
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************


class WeblogUtils {
	
	public function __construct() {
	}
	
	public static function sanitize($string) {
		
		$string = sanitize($string); // See leverod/system/library/string.php
		return $string;
	}
}