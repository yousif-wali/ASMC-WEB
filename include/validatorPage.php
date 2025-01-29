<?php 
 $validator = fopen($folder."/include/validator.php", "w+");
 $validatorText = 
 "<?php
// Including your database file     
include 'database.php';
// Initializing the credentials for your database
\$con = new MySQLiConnection('$DatabaseHost', '$DatabaseUser', '$DatabasePassword', '$DatabaseName');
// Connecting to your database
\$db = new DB(\$con);
 ";
 fwrite($validator, $validatorText);
 fclose($validator);