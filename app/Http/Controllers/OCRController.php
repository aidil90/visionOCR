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

        // $gstId = '';
        // $company =  implode(" ", array_slice($result,0,2)) . PHP_EOL;
        
        // // echo $company;
        
        // //get GST ID
        // $pos1 = (strpos($data, "GST ID : "));
        // $gst = (string)substr($data, $pos1, $pos1 + 1);
        // //$gst = implode(PHP_EOL, string_slice($gst,0,2)) . PHP_EOL;
        // $gstId = '';

        // dd($gst);

        
        // // $subject = "abcdef";
        // // $pattern = '/^def/';
        // // preg_match($pattern, substr($subject,3), $matches, PREG_OFFSET_CAPTURE);
        // // print_r($matches);
        // //'~[0-9]~'

        // stristr($data, ('GST ID : '));
    
        // //GET TOTAL GST
        // $pos2 = (strpos($data, "6%"));
        // $gstTotal = substr($data, $pos2);
        // $gstTotal = explode(" ", $gstTotal);
        // $gstAmnt = $gstTotal[0];
        
        // //GET TOTAL AMOUNT
        // $pos3 = (strpos($data, "Total "));
        // $total = substr($data, $pos3, $pos3 + 3);
        // $total = explode(" ", $total);
        // $totalAmnt = $total[0];
        
        // echo "COMPANY NAME: " . $company . "<br>"
        //    . $gstId . "<br>"
        //    . "GST AMOUNT: " . $gstAmnt . "<br>"
        //    . "TOTAL " . $totalAmnt;

    }
}
?>