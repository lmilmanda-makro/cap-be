<?php
// Create connection to Oracle
$conn = oci_connect("dba_ho", "Interno1", "//10.49.3.93:1525/ARMSTQT3");

if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
else {
   print "Connected to Oracle!";
}
// Close the Oracle connection
oci_close($conn);
?>