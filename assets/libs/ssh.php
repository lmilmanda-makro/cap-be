<?php
header('content-type: text/plain');
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');

include('Net/SSH2.php');


$ssh = new Net_SSH2('10.49.2.24');
if (!$ssh->login('hoserv', 'hoserv')) {
    exit('Login Failed');
}

$sql = $ssh->exec('cat /home/Disk_A/HeadOffice/progs/sql/invent_per.sql');

?>