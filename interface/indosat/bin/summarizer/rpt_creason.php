<?php

ini_set ( "memory_limit", "500M");
set_time_limit(0);

require_once('/var/fo/class/summary/class_db.php');

// insert summary from query
$datenow	= date('Y-m-d');
$timestmp	= date('Y-m-d H:m:s');
$yesterday 	= time() - (1 * 24 * 60 * 60); // 1 days; 24 hours; 60 mins; 60secs
$last2days	= time() - (2 * 24 * 60 * 60); // 2 days; 24 hours; 60 mins; 60secs

$date = date('Y-m-d', $yesterday);
if ($argv[1]) { $date = $argv[1]; }

$tbl = 'tbl_msgtransact';
if($argv[2]) { $tbl = $argv[2]; }
echo "-- ".$date."\n";

$d0 = new db(0);
$d2 = new db(2);

$d0->dir = "/var/fo/class/summary/"; 

// select summary
 $sql = "SELECT DATE(msgtimestamp) AS sumdate, SUBSTRING(subject, 1, 2) AS momt, SUBSTRING(iac, 5, 4) AS shortcode,
  			SUBSTRING_INDEX(service,'_',1) as service, SUBSTRING(iac, 1, 2) AS operator, msgstatus, if(instr(closereason,'1:CoGW'),'1',closereason) as creason, COUNT(*) AS total
				FROM $tbl
				WHERE DATE(msgtimestamp) = '$date' AND SUBSTRING(subject, 1, 2) <> 'MO' 
				GROUP BY sumdate, momt, shortcode, service, operator, msgstatus, creason
				ORDER BY sumdate, momt, shortcode, service, operator, msgstatus, creason";
$data = $d0->fetch($sql);

// delete today
$sqldel = "DELETE FROM rpt_creason WHERE sumdate = '$date'";
$d2->exec($sqldel);

// insert to table
foreach($data as $row){
$sqlins = "INSERT INTO rpt_creason(sumdate, shortcode, service, operator, msgstatus, closereason, total) VALUES("
						.db::fmt($row['sumdate'],0).db::fmt2($row['shortcode'],0).db::fmt2($row['service'],0).db::fmt2($row['operator'],0)
						.db::fmt2($row['msgstatus'],0).db::fmt2($row['creason'],0).db::fmt2($row['total'],0).")";
	//echo $sqlins.";\n";
	$d2->exec($sqlins);
	//if ($adaerror = mysql_error())
	//	echo "--".$adaerror."\n";
} 

?>
