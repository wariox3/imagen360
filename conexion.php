<?php
mysql_connect("200.75.83.240", "root", "70143086") or die(mysql_error()) ;
mysql_select_db("bdkl") or die(mysql_error()) ;

$servidor = new mysqli("200.75.83.240", "root", "70143086", "bdkl");
if ($servidor->connect_error) {
    die("Connection failed: " . $ervidor->connect_error);
} 

