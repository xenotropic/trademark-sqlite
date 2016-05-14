<?php

// Fetches data from https://data.uspto.gov/data3/trademark/dailyxml/applications/

for ($x = 10; $x <= 53; $x++) {
  exec ( "wget https://data.uspto.gov/data3/trademark/dailyxml/applications/apc151231-".str_pad ( (string)$x, 2, '0', STR_PAD_LEFT ).".zip");
} 


?>