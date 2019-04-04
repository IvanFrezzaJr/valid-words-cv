<?php
// Report all errors except E_NOTICE
error_reporting(E_ALL ^ E_NOTICE);

// import class to return content of documents
require_once('class.openDocs.php');

// define path
define ('SITE_ROOT', realpath(dirname(__FILE__)));
define ('SITE_TMP', 'tmp');

// verify if not empty
 if(!empty($_POST['word']) || $_FILES['file'])
 {
	$file_name =  $_FILES["file"]["name"];
	$file_tmp_path =  $_FILES['file']['tmp_name'];
	$words = explode(',', $_POST['word']);
	$total_words = count($words);
	$file_tmp_dir =  SITE_ROOT.'/'.SITE_TMP.'/';

	// get type file
	$file_type = strrev(substr(strrev($file_name), 0, strpos(strrev($file_name), '.')));
	
	// valid if exists directory
	if (folder_exist($file_tmp_dir ) ===false){
		mkdir($file_tmp_dir, 0755);
	}
	
	//save temp directory
	move_uploaded_file($file_tmp_path,  $file_tmp_dir.'/'.$file_name);
	
	// Get document's text
	$openDocs = new DocxConversion($file_tmp_dir.'/'.$file_name);	 
	$data = strtolower($openDocs->convertToText());
	
	// init records variable 
	$records_found = array();
	$records_not_found = array();
	
	// Looking for words in the document
	for ($i=0; $i < $total_words; $i++){
		$pattern = '/' .strtolower($words[$i]).  '/';
		if (preg_match($pattern, $data)) {
			$word_found = $words[$i];
			$records_found[$word_found]++;
			
		} else {
			$word_not_found =  $words[$i];
			$records_not_found[$word_not_found]++;
		}
	}
	
	// get total words founds and not founds
	$word_found_total = count($records_found);
	$word_not_found_total = count($records_not_found);

	// calc percent 
	$rtn = round(((5 * $word_found_total) / $total_words),2);
	
	// return percent
	echo $rtn;
	
 }
   
function folder_exist($folder)
{
    $path = realpath($folder);

    return ($path !== false AND is_dir($path)) ? $path : false;
}
?>