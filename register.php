<?php

    $phpself = $_SERVER["PHP_SELF"];

    require_once("dbconnect.php");

    function stripper($data){
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = trim($data);
        return $data;
    }

    $ermsg = NULL;

    if(isset($_POST["regiBtn"])){
        $username = stripper($_POST["runame"]);
        $username = strtolower($username);
        $password = stripper($_POST["rpass"]);

        $username = $conn->real_escape_string($username);
        $password = $conn->real_escape_string($password);

        if(($username == "" || $username == " ") && ($password == "" || $password == " ")){
            $msg = "<p class='error'>Please set a username!</p><p class='error'>Please set a password!</p>";
            $row = NULL;
            $count = NULL;
        }elseif($username == "" || $username == " "){
            $msg = "<p class='error'>Please set a username!</p>";
            $row = NULL;
            $count = NULL;
                      
        }elseif($password == "" || $password == " "){
            $msg = "<p class='error'>Please set a password!</p>";
            $row = NULL;          
            $count = NULL;  
            
        }else{
            
            if(strlen($username) < 5|| strlen($username) > 31){
                $msg = "<p class='error'>Username must be between 6 to 30 characters!</p>";
            }elseif(strlen($password) < 5 || strlen($password) > 31){
                $msg = "<p class='error'>Passwrod must be between 6 to 30 characters!</p>";        
            }else{
        
                $hashedPass = $password; #password_hash($password, PASSWORD_DEFAULT);
                
                $query = $conn->query("SELECT username FROM accInfo WHERE username='$username'");
                
                $count = $query->num_rows;
        
                if($count == 0){
                    $sql = "INSERT INTO accInfo(username, password)
                    VALUES('$username', '$hashedPass')";
        
                    if($conn->query($sql)){
                        $msg = "<p class='success'>Successfully created an account with username: $username</p>";
                    }else{
                        $msg = "<p class='error'>Error creating account! Please try again after sometime.</p>";
                    }
                }else{
                    $msg = "<p class='error'>Username already taken!</p>";
                }
        
            }
        }
        
    }
    $conn->close();   

?>


<!DOCTYPE html>
<html>
    <head>
        <title>-=| ChatHUB |=-</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="developer" content="Aneek Rahman">
        <link href="regiStyle.css" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    </head>
    <body>
        <div class="regiWrap">
            <h3>REGISTER HERE:</h3>
            <form action="<?php echo $phpself ?>" method="post" id="regiBox">
                <div class="unameWrap">
                    <p>Set your username:</p>
                    <input type="text" name="runame">
                </div>
                <div class="passWrap">
                    <p>Set your password:</p>
                    <input type="password" name="rpass">
                </div>
                <input type="submit" name="regiBtn" value="REGISTER">
            </form>
            <?php
                if(isset($msg)){
                    echo $msg;
                }
            ?>
        </div>
        <div class="orLog">
            <p>~ OR ~</p>
            <a href="index.php">LOGIN</a>
        </div>
    </body>
</html>