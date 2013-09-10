<?php

function age($birthday){
	$birthday = explode(strcheck($birthday,'-') == true ? '-' : '/',$birthday);

	if (count($birthday) == 3)
		return (date("md", date("U", mktime(0, 0, 0, $birthday[0], $birthday[1], $birthday[2]))) > date("md") ? ((date("Y")-$birthday[2])-1):(date("Y")-$birthday[2]));
	else
		return false;
}


function human_time($time){
	if (($time = get_timestamp($time)) != false){
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");	
		$lengths = array('60','60','24','7','4.35','12','10','1');	
		$tense   = (time() > $time) ? ' ago' : ' from now';
		$time    = abs(time() - $time);	
		
		for($j = 0; $time >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	        $time /= $lengths[$j];
	    }
		$time = round($time);
		return ($time <= 10) && ($j == 0)? "Just now" : $time.' '.$periods[$j].(($time > 1) ? 's' : '').$tense;
	}
	else return false; 
}


function is_valid_timestamp($timestamp)
{
    return (strval(intval($timestamp)) === $timestamp) 
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX);
}


function is_valid_date($date) {
    if (empty($date)) return false;	
	$date = explode(strcheck($date,'/') ? '/' : '-', $date);
	return (checkdate(intval($date[0]), intval($date[1]), intval($date[2])) == true) ? true : false;  
}
   

   function redirect($url){
	header('Location: ' . $url);
	exit();
}


function strcheck($haystack, $needle) {
	return (strpos($haystack, $needle) !== false) ? true : false;
}


function get_timestamp($date){
	if (empty($date)) return false;
	else {
		if (is_numeric($date) && is_valid_timestamp($date)){
			return $date;
		}
		elseif (is_valid_date($date)){
			$date = new DateTime($date);
			return strtotime($date->format('m/d/Y'));	
		}
		else return false;
	}
}


function is_valid_email($email, $test_mx = false){
	if (filter_var($email, FILTER_VALIDATE_EMAIL)){
		if ($test_mx){  
			$host = substr($email, strpos($email, '@') + 1);
			return ((getmxrr($host, $mxhosts) != false) || (gethostbyname($host) != $host)) ? true : false; 
		}
		return true;
	}
	else return false;
}


function is_assoc_array($arr)
{
	return (is_array($arr) == true) ? (bool) count(array_filter(array_keys($arr), 'is_string')) : false;
}


function create_slug($string){  
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);  
    return $slug;  
}  


function get_ip()  
{
	return (empty($_SERVER['HTTP_CLIENT_IP'])) ? 
		((empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR']) 
			: $_SERVER['HTTP_CLIENT_IP']; 
} 


function force_download($file)  
{  
    if ((isset($file)) && (file_exists($file))) {  
       header("Content-length: ".filesize($file));  
       header('Content-Type: application/octet-stream');  
       header('Content-Disposition: attachment; filename="'.$file.'"');  
       readfile("$file");  
    } 
    else return false;  
}

function make_clickable($text) {
	$text = preg_replace('#([\s|^])(www)#i', '$1http://$2', $text);
    $text = preg_replace('#((http|https|ftp|telnet|news|gopher|file|wais):\/\/[^\s]+)#i', '<a href="$1" target="_blank">$1</a>', $text);
    $text = preg_replace('#([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)#i', '<a href="mailto:\\1">\\1</a>', $text);
    return $text;
} 


?>

