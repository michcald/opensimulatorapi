<?php

// admin db account
$adapter = 'mysql';
$host = 'host';
$user = 'USER';
$password = 'password';
$dbname = 'DBNAME';

Lib_Registry::set('db', new Lib_Db($adapter, $host, $user, $password, $dbname));