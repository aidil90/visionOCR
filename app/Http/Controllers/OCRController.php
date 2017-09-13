<?php

namespace App\Http\Controllers;

//use App\Libraries\TesseractOCR;
use Illuminate\Http\Request;
use TesseractOCR;

class OCRController extends Controller
{
    public function getData()
    {
        
        $tesseract = (new TesseractOCR(public_path("receipt2.jpg")))
                  ->executable('"C:\Program Files (x86)\Tesseract-OCR\tesseract"');

        $data = (string)$tesseract ->run();
        
        $receipt = explode(" ", $data);
        $max = count($receipt);

         //Company Name
        //$company = "Below your Eyes 3.2 (2013) Unrated From database";
        $words = explode(" ", $data, 5);
        array_pop($words);
        $words = implode(" ", $words);
        dd($words);
        //$company =  implode(PHP_EOL , array_slice($receipt,0,3)) . PHP_EOL;
        
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
            $gstAmnt = 'RM ' . $matches[0][2];
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