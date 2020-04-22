<?php

    session_start();

    require_once("dbconnect.php");

    $pname = $_POST["privateIn"];

    $swi = $_POST["swi"];

    $query = $conn->query("SELECT id, username FROM accInfo WHERE username='$pname'");
    
    $row = $query->fetch_array();

    $count = $query->num_rows;

if($swi == "global"){
    $chatFile1 = $_SESSION["chatFile"];
    $leaveFile = fopen("$chatFile1","a");
    fwrite($leaveFile, "<i class='leave'>" . $_SESSION['username'] . " has <b>left</b> the chat</i><br>");
    fclose($leaveFile);

    $_SESSION['privateId'] = NULL;
    $_SESSION['privateUName'] = NULL;
    $_SESSION["chatFile"] = NULL;

    $joinFile1 = fopen("logs/chatLog.html","a");
    fwrite($joinFile1, "<i class='join'>".$_SESSION['username']." has <b>joined</b> the chat</i><br>");
    fclose($joinFile1);
}elseif($count == 1){

    $leaveFile1 = fopen("logs/chatLog.html","a");
    fwrite($leaveFile1, "<i class='leave'>" . $_SESSION['username'] . " has <b>left</b> the chat</i><br>");
    fclose($leaveFile1);


    $_SESSION['privateId'] = $row["id"];
    $_SESSION['privateUName'] = $row['username'];
    

    if(file_exists("logs/pri_" .  $_SESSION['privateUName'] . "_" . $_SESSION['username'] . ".html")){
        $_SESSION["chatFile"] = "logs/pri_" .  $_SESSION['privateUName'] . "_" . $_SESSION['username'] . ".html";
    }else{
        $_SESSION["chatFile"] = "logs/pri_" .  $_SESSION['username'] . "_" . $_SESSION['privateUName'] . ".html";
    }
    


    $chatUrl = $_SESSION["chatFile"];
    $joinFile = fopen($chatUrl,"a");
    fwrite($joinFile, "<i class='join'>" .$_SESSION['username']." has <b>joined</b> the chat</i><br>");
    fclose($joinFile);

}else{
    $_SESSION['privateId'] = NULL;
    $_SESSION['privateUName'] = NULL;
}


?>