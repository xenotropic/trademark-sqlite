<?php

/*
Parses USPTO Trademark data from https://data.uspto.gov/data3/trademark/dailyxml/applications/
See also https://developer.uspto.gov/product/trademark-daily-xml-file-tdxf-applications-assignments-ttab

(c) 2016 Joseph Morris joe at morris.cloud
Permission to use under the MIT license https://opensource.org/licenses/MIT

 */

// change to your path
$path_to_databases = '/home/joe/trademark-sqlite';
$database_connect_string = 'sqlite:'.$path_to_databases.'/tmdb.sqlite3';

$database = new PDO ( $database_connect_string );
// $tmdb->exec ( "DELETE from trademarks" );  // clean database first, sometimes necessary depending on what you're doing.

function load_txml_file ( $filename, $tmdb ) { 

  echo ("Processing " . $filename . "\n" );
  
  $z = new XMLReader;
  $z->open($filename);  
  
  $doc = new DOMDocument;
  
  while ($z->read() && $z->name !== 'case-file');
  
  $limit = 10000000;  // reduce this for testing to only parse a certain number of records
  $counter = 0;
  
  while ($z->name === 'case-file' && $counter < $limit ) {
      if ( $counter % 10000 == 0 ) echo $counter . "-";
      $node = simplexml_import_dom($doc->importNode($z->expand(), true));
      
      $serial = (string)($node->{"serial-number"});
      $reg_no = (string)($node->{"registration-number"});
      $ch_xml = $node->{"case-file-header"};
      $filing_date = (string)($ch_xml->{"filing-date"});
      $filing_date = substr_replace($filing_date, "-", 6, 0);
      $filing_date = substr_replace($filing_date, "-", 4, 0);
      $reg_date = (string)($ch_xml->{"registration-date"});
      $reg_date = substr_replace($reg_date, "-", 6, 0);
      $reg_date = substr_replace($reg_date, "-", 4, 0);
      $mark_text = (string)($ch_xml->{"mark-identification"});
      $class_xml = $node->{"classifications"}->{"classification"}; // TODO make work for multi-class applications
      $int_class = 0;
      if ( $class_xml != null ) $int_class = $class_xml->{"international-code"};
      // There are many other fields to add. Legally, I think the 
      // most useful fields to add would be goods and services description
      // and live/dead. For other details, a link to TSDR would probably be fine.
      $statement = $tmdb->prepare ('INSERT into trademarks ( serial, reg_no, filing_date, reg_date, mark_text, int_class) VALUES ( ?,?,?,?,?,? ) ');
      
      $statement->execute (array ( $serial, $reg_no, $filing_date, $reg_date, $mark_text, $int_class ) );
      $z->next('case-file');  
      $counter++;
    }
  echo ("\n");
}
?>
