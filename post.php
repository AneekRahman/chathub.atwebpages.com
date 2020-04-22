<?php

session_start();

date_default_timezone_set("Asia/Dhaka");

require_once("dbconnect.php");

        $id = $_SESSION["userSession"];

        $query = $conn->query("SELECT id, username, password FROM accInfo WHERE id=$id");

        $row = $query->fetch_array();

        $count = $query->num_rows;

        if(isset($_SESSION["chatFile"])){
            if(!file_exists($_SESSION["chatFile"])){
                $chatUrl = "logs/chatLog.html";
            }else{
                $chatUrl = $_SESSION["chatFile"];
            }
        }else{
            $chatUrl = "logs/chatLog.html";
        }

        

    if(isset($_SESSION["userSession"]) && $_POST["msg"] != "" && $count == 1){
        if($row["username"] == "aneek"){
            $msg = $_POST["msg"];
            
            $logFile = fopen("$chatUrl", "a");
            fwrite($logFile, "<p><b class='admin'>FOUNDER</b> [" . $row["username"] . "]: " . $msg . " <i class='time'>(" . date("g:i A") .  ")</i>" ."</p>");
            fclose($logFile);
        }elseif($row["username"] == "mou"){
            $msg = $_POST["msg"];
            
            $logFile = fopen("$chatUrl", "a");
            fwrite($logFile, "<p><b class='mod'>CO-FOUNDER</b> [" . $row["username"] . "]: " . $msg . " <i class='time'>(" . date("g:i A") .  ")</i>" ."</p>");
            fclose($logFile);
        }else{
            $msg = $_POST["msg"];
            
            $logFile = fopen("$chatUrl", "a");
            fwrite($logFile, "<p><b class='normie'>GENERAL</b> [" . $row["username"] . "]: " . $msg . " <i class='time'>(" . date("g:i A") .  ")</i>" ."</p>");
            fclose($logFile);
        }

    }
?>