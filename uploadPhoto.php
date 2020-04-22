<?php

session_start();

date_default_timezone_set("Asia/Dhaka");

require_once("dbconnect.php");

        $id = $_SESSION["userSession"];

        $query = $conn->query("SELECT id, username, password FROM accInfo WHERE id=$id");

        $row = $query->fetch_array();

        $count = $query->num_rows;

    $targetDir = "photoUploads/";
    $uploadFile = $targetDir . basename($_FILES["file"]["name"]);

    $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["file"]["tmp_name"]);

        if($check !== false){
            $uploadOk = 1;
        }else{
            $filemsg = "<span class='error'>ERROR: File has to be an image!</span>";
            $uploadOk = 0;
        }

    if(file_exists($uploadFile)){

        $uploadFile = $targetDir . date("Y-m-d") . "_" . date("h:i:sa") . "_" . basename($_FILES["file"]["name"]);
        
    }

    if($_FILES["file"]["size"] > 1000000){
        if(!isset($filemsg)){
            $filemsg = "<span class='error'>ERROR: File must be below 1MB in size!</span>";            
        }
        $uploadOk = 0;
    }else{

    }


    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif"){
        if(!isset($filemsg)){
            $filemsg =  "<span class='error'>ERROR: File must be a jpg, jpeg, png or gif image file!</span>";            
        }            
        $uploadOk = 0;
    }

    if($uploadOk != 0){
        move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFile);
        $filemsg = "<span><img src='$uploadFile' class='uploadPhoto'></span>";
    }else{
        if(!isset($filemsg)){
            $filemsg = "<span class='error'>ERROR: Error uploading image! Try again later!</span>";                            
        }
    }

    $msg = "Uploaded an image: " . $filemsg;


    if(isset($_SESSION["chatFile"])){
        if(!file_exists($_SESSION["chatFile"])){
            $chatUrl = "logs/chatLog.html";
        }else{
            $chatUrl = $_SESSION["chatFile"];
        }
    }else{
        $chatUrl = "logs/chatLog.html";
    }

    if(isset($_SESSION["userSession"])){
        if(!empty(basename($_FILES["file"]["name"]))){
            if($row["username"] == "aneek"){      
                $logFile = fopen("$chatUrl", "a");
                fwrite($logFile, "<p><b class='admin'>ADMIN</b> [" . $row["username"] . "]: " . $msg . " <i class='time'>(" . date("g:i A") .  ")</i>" ."</p>");
                fclose($logFile);
            }elseif($row["username"] == "mou"){           
                $logFile = fopen("$chatUrl", "a");
                fwrite($logFile, "<p><b class='mod'>ADMIN-BOU</b> [" . $row["username"] . "]: " . $msg . " <i class='time'>(" . date("g:i A") .  ")</i>" ."</p>");
                fclose($logFile);
            }else{            
                $logFile = fopen("$chatUrl", "a");
                fwrite($logFile, "<p><b class='normie'>NORMIE</b> [" . $row["username"] . "]: " . $msg . " <i class='time'>(" . date("g:i A") .  ")</i>" ."</p>");
                fclose($logFile);
            }
        }
        

    }
?>