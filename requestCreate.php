<?php

    session_start();
    require_once("dbconnect.php");
    
    $pname = $_POST["privateIn"];

    $query = $conn->query("SELECT id, username FROM accInfo WHERE username='$pname'");
    
    $row = $query->fetch_array();

    $name = $_SESSION["username"];

    $count = $query->num_rows;

    if(!file_exists("requests/" . $pname . "_requests.html") && $count != 0){
        $handle = fopen("requests/" . $pname . "_requests.html", "w");
        fclose($handle);
    }

    $reData = file_get_contents("requests/" . $pname . "_requests.html");

    if($count != 0 && !strstr($reData, "id='$name'") ){

        $fileName = "requests/" . $pname . "_requests.html";

        $lines = file($fileName);
        $lineNum = count($lines);
        $handle = fopen($fileName, "a+");
        fwrite($handle,"<p line='$lineNum' id='$name' class='re'><b>$name</b> has sent you a request! <button class='accept'>ACCEPT</button><button class='reject'>REJECT</button></p>" . PHP_EOL);
        fclose($handle);

    }

    