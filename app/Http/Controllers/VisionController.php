<?php
namespace App\Http\Controllers;

use TesseractOCR;

class VisionController extends Controller
{
	public function getTest()
	{
		$filepath = public_path("receipt.tiff");

		// Create an instanceof tesseract with the filepath as first parameter
		$tesseractInstance = new TesseractOCR($filepath);

		// Execute tesseract to recognize text
		dd($result = $tesseractInstance->run());

		// Show recognized text
		echo $result;

	}

}

?>
