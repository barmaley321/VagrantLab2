<?php
$link = mysql_connect('172.28.128.100', 'myproject', 'mypassword');
if (!$link) {
        die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
mysql_close($link);
?>
