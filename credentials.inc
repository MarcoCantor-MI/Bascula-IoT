<?php
//define("HOST" , "localhost");
define("HOST" , "localhost");
define("DDBB_USER", "admin");
define("DDBB_PSW", "123456");
define("DDBB_DB","proyecto");
$link = new mysqli(HOST, DDBB_USER, DDBB_PSW, DDBB_DB);

?>
