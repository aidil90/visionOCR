<?php

namespace App\Http\Controllers;

//require 'C:/Users/aidil/Documents/work/project/vendor/autoload.php';

use Google\Cloud\Vision\VisionClient;

class GoogleController extends Controller
{
    public function getData()
    {
    	$projectId = '';
    	$path = public_path('receipt2.jpg');

    	$vision = new VisionClient([
     	   'projectId' => $projectId,
    	]);

        $data = '';
        $gstId = '';
        $company = '';
        $gstAmnt = '-';
        $totalAmnt = '';

    	
        $image = $vision->image(file_get_contents($path), ['TEXT_DETECTION']);
    	$result = $vision->annotate($image);
    	
    	foreach ((array) $result->text() as $text) {
        	$data = $data . ($text->description() . PHP_EOL);
    	}
        
        
    	$receipt = explode(" ", $data);
    	$max = count($receipt);
        
        //Company Name
        $company =  implode(PHP_EOL , array_slice($receipt,0,3)) . PHP_EOL;
        
        //GST ID
        for ($i=0; $i < $max; $i++) { 
            $gst = implode(PHP_EOL , array_slice($receipt,0)) . PHP_EOL;
            preg_match_all("/\d+(\.\d+)?/", $gst, $matches);
            for ($j=0; $j < sizeof($matches[0]); $j++) { 
                if(strlen($matches[0][$j]) == 12){
                    $gstId = $matches[0][$j];
                }
            }
        }
        
        //Total Amount
        $amnt = stristr($data, ('Total'));
        $receipt = explode(" ", $amnt);
        $total = implode(PHP_EOL , array_slice($receipt,0,8)) . PHP_EOL;
        preg_match_all("/\d+(\.\d+)?/", $total, $matches);
        
        if(stristr($amnt, 'Total Amount ')){
            $totalAmnt = '<b>TOTAL AMOUNT (excl GST): </b>RM ' . $matches[0][0];
            $gstAmnt = 'RM ' . $matches[0][1];
        } 
        
        elseif (stristr($amnt, 'Total Sales ')) {
                $totalAmnt = '<b>TOTAL AMOUNT (incl GST): </b>RM ' . $matches[0][1];
        }    

        else {
            $totalAmnt = "No Total Amount Detected!";
        }

        echo "<b>COMPANY NAME: </b>" . $company . "<br>"
           . "<b>GST ID: </b>" . $gstId . "<br>"
           . "<b>GST AMOUNT: </b>" . $gstAmnt . "<br>"
           .  $totalAmnt;
    }
}
?>


