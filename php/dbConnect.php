<?php
$mySQLservername = "localhost";
    $database = "techno_tv";
    $mySQLusername = "root";
    $mySQLpassword = "";
    
    try {
        $conn = new PDO("mysql:host=$mySQLservername;dbname=$database", $mySQLusername, $mySQLpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }