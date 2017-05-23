<?php

// Fetches data from https://data.uspto.gov/data3/trademark/dailyxml/applications/

for ($x = 1; $x <= 53; $x++) {
  exec ( "wget http://trademarks.reedtech.com/downloads/TrademarkDailyXML/1884-2015/apc151231-".str_pad ( (string)$x, 2, '0', STR_PAD_LEFT ).".zip");
} 


?>
