<?php

/* This file uses the function in parse_trademarks to 
parse a succession of files. The loop here is for the 
set of files the USPTO dated 12/31/2015, which seems 
to be (USPTO documentation somewhat lacking) trademarks
filed prior to that date. There are also daily files 
for most days in 2016, which this does not parse. 

(c) 2016 Joseph Morris joe at morris dot cloud
Permission to use under the MIT license https://opensource.org/licenses/MIT
*/

include_once ("./parse_trademarks.php");

for ($x = 1; $x <= 53; $x++) {
  $x_str = str_pad ( (string)$x, 2, '0', STR_PAD_LEFT );
  $zipname = "apc151231-".$x_str.".zip"; 
  $xmlname = "apc151231-".$x_str.".xml"; 
  echo ("Unzipping " . $zipname. "\n");
  exec ( "unzip ./data/$zipname -d ./data" );
  load_txml_file ( "./data/".$xmlname, $database );
  // Files are quite large when expanded, so deleting
  // the expanded xml when done. Zip file stays.  
  unlink ( "./data/" . $xmlname );
} 

?>