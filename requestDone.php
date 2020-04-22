<?php

    session_start();

    $action = $_POST["dec"];
    $pname = $_POST["name"];
    $name = $_SESSION["username"];
    $lineNumber = (int)$_POST["line"];

    if(!file_exists("contacts/" . $name . "_contacts.html")){
        $handle = fopen("contacts/" . $name . "_contacts.html", "w");
        fclose($handle);
    }

    $conData = file_get_contents("contacts/" . $name . "_contacts.html");

    if(!strstr($conData, "id='$pname'") && $action == "accept"){
        $fileName = "contacts/" . $name . "_contacts.html";

        $lines = file($fileName);
        $lineNum = count($lines);
        $handle1 = fopen($fileName, "a+");
        fwrite($handle1,"<p line='$lineNum' id='$pname'>@$pname</p>" . PHP_EOL);
        fclose($handle1);

        $fileName1 = "requests/" . $name . "_requests.html";
        $lines1 = file($fileName1);
        $lines1[$lineNumber] = "\n";
        file_put_contents($fileName1,$lines1);


        $fileName2 = "contacts/" . $pname . "_contacts.html";

        if(!file_exists($fileName2)){
            $handle3 = fopen("contacts/" . $name . "_contacts.html", "w");
            fclose($handle3);
        }

        $lines2 = file($fileName2);
        $lineNum2 = count($lines2);
        $handle2 = fopen($fileName2, "a+");
        fwrite($handle2,"<p line='$lineNum2' id='$name'>@$name</p>" . PHP_EOL);
        fclose($handle2);
    }else{
        $fileName1 = "requests/" . $name . "_requests.html";
        $lines3 = file($fileName1);
        $lines3[$lineNumber] = "\n";
        file_put_contents($fileName1,$lines3);
    }

?>