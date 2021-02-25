<?php

ini_set("error_reporting", E_ERROR);

/**
 * ECC200 Datamatrix Generation in PHP
 */

#header('Content-Type: text/html; charset=utf-8');
#mb_internal_encoding('Windows-1252');
#ini_set('mbstring.substitute_character', "Windows-1252");

require_once 'TCPDF/tcpdf.php';
require_once 'dp_freimachung.php';

$strFileError  = "generated_errors_php.txt";
$strDirSamples = "generated_samples";

// clean test folder before generating
file_put_contents($strFileError, "");
system("rm {$strDirSamples}/datamatrixtest_*");

// define DP Settings
define("DP_PRODUKTSCHLUESSEL", "00001");
define("DP_TEILNAHMENUMMER", "34");
define("DP_FRANKIERART", "18");
define("DP_VERSIONPRODUKTE", "36");
define("DP_ENCODE_UTF8", false);
define("USE_TCPDF", true);

$maxLoops = 2;

for ($i = 1; $i <= $maxLoops; $i++) {
   $objPackingslip = array(
	   "Kundennummer"             => '5123456789',
	   "Einlieferungsbelegnummer" => "1" . rand(1, 235),
	   "Sendungsnummer"           => "1" . rand(1, 235),
	   "Frankierwert"             => "0." . rand(1, 235),
	   "Einlieferungsdatum"       => "" . rand(1, 235) . "19",
   );
   $strMatrixCode  = genDMECC200($objPackingslip);
   $matrixStrLen   = strlen($strMatrixCode["bin"]); // check if 42 bytes
   $hexRevers      = strtoupper(bin2hex(utf8_decode($strMatrixCode["bin"])));
   if ($strMatrixCode["hex"] != $hexRevers) {
	  echo "[ PHP DECODED HEX REVERS ERROR !!! ]---------------------" . PHP_EOL;
	  print $strMatrixCode["hex"] . PHP_EOL;
	  print $hexRevers . PHP_EOL;
	  #echo "--------------------------------------------------" . PHP_EOL;
	  #file_put_contents("{$strFileError}", $i . " REVERS ERR!!!!!" . $response . PHP_EOL, FILE_APPEND);
	  #file_put_contents("{$strFileError}", print_r($objPackingslip, true) . PHP_EOL, FILE_APPEND);
   }
   else {
	  echo "[ PHP DECODED BIN2HEX REVERS OK] " . PHP_EOL;
	  print $strMatrixCode["hex"] . PHP_EOL;
	  print $hexRevers . PHP_EOL;
	  #echo "--------------------------------------------------" . PHP_EOL;
   }
   # echo PHP_EOL;
   // die();

   /**
	* Create Bar Code PDF
	*/

   if (USE_TCPDF) {
	  $style = array(
		  'border'        => true,
		  'vpadding'      => 'auto',
		  'hpadding'      => 'auto',
		  'fgcolor'       => array(0, 0, 0),
		  'bgcolor'       => false, //array(255,255,255)
		  'module_width'  => 1, // width of a single module in points
		  'module_height' => 1, // height of a single module in points
		  'position'      => 'S',
	  );
	  $pdf   = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	  $pdf->SetCreator(PDF_CREATOR);
	  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	  $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
	  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	  $pdf->AddPage();
	  $strBin = utf8_decode($strMatrixCode["bin"]);
	  $pdf->write2DBarcode($strBin, 'DATAMATRIX', 50, 50, 96, 96, $style, 'N', true);
	  $pdf->Output(__DIR__ . "/{$strDirSamples}/datamatrixtest_" . $i . ".pdf", 'F'); // I - print F - save
	  // Convert Bar Code PDF into JPG
	  $cmd = "gs -sDEVICE=jpeg -r300x300 -dQUIET -dBATCH -dNOPAUSE -dNOGC -sOutputFile={$strDirSamples}/datamatrixtest_" . $i . ".jpg {$strDirSamples}/datamatrixtest_" . $i . ".pdf";
	  system($cmd);
   }
   else {
	  $strBin = utf8_decode($strMatrixCode["bin"]);
	  $ifile  = "dm_test_image.txt";
	  $image  = "{$strDirSamples}/datamatrixtest_" . $i . ".jpg";
	  file_put_contents($ifile, $strBin);
	  $cmdDmtxwrite = "dmtxwrite {$ifile} -o {$image}";
	  $output       = shell_exec($cmdDmtxwrite);
	  #echo var_export($output, true);
   }

   /**
	* Read sequence back with dmtxread
	*/

   $cmd = "dmtxread -v -N 1  {$strDirSamples}/datamatrixtest_" . $i . ".jpg | tail -n 1 > {$strDirSamples}/datamatrixtest_" . $i . ".txt";
   system($cmd);
   $response = file_get_contents("{$strDirSamples}/datamatrixtest_" . $i . ".txt");
   echo PHP_EOL;
   if (strtoupper($strMatrixCode["hex"]) != strtoupper(bin2hex($response))) {
	  print "[ DMTXREAD DECODED BIN2HEX REVERS ERROR]  " . PHP_EOL;
	  echo "" . $strMatrixCode["hex"] . PHP_EOL;
	  echo "" . strtoupper(bin2hex($response)) . PHP_EOL;
	  file_put_contents("{$strFileError}", "#######################################\n" . PHP_EOL, FILE_APPEND);
	  file_put_contents("{$strFileError}", $i . " PDF JPG DMTXREAD ERR!!!!! \n" . PHP_EOL, FILE_APPEND);
	  file_put_contents("{$strFileError}", $response . "\n" . PHP_EOL, FILE_APPEND);
	  file_put_contents("{$strFileError}", "---------------------------------\n" . PHP_EOL, FILE_APPEND);
	  #file_put_contents("{$strFileError}", print_r($objPackingslip, true) . PHP_EOL, FILE_APPEND);
   }
   else {
	  print "[ DMTXREAD DECODED BIN2HEX REVERS OK]  " . PHP_EOL;
	  echo "" . $strMatrixCode["hex"] . PHP_EOL;
	  echo "" . strtoupper(bin2hex($response)) . PHP_EOL;
   }
   #die();

}
