<?php
$time_end = time();

$val = '15104064414,1341279366';
$parts = explode(",",$val);
$time_start = intval($parts[1]);

$time = $time_end - $time_start;

echo "$time_start\n";
echo "$time_end\n";
echo "Did nothing in $time seconds\n";

?>
