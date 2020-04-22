<?php
    
    
    $server = "fdb18.awardspace.net";
    $database = "2577875_accounts";
    $un = "2577875_accounts";
    $ps = "passhere";

    $conn = new mysqli($server, $un, $ps, $database);

    if($conn->connect_error){
        die("Failed to connect. Error: ". $conn->connect_error);
    }else{
        
    }



?>