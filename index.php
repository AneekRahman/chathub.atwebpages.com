<?php

    session_start();

    


    if(!isset($_SESSION["joined"])){
        $_SESSION["joined"] = 0;
    }else{
        $_SESSION["joined"] = 1;
    }

    if(!isset($_SESSION["privateUName"])){
        $_SESSION["privateUName"] = NULL;
        $_SESSION["chatType"] = "GLOBAL";
    }else{
        $_SESSION["chatType"] = "PRIVATE";
    }

    require_once("dbconnect.php");

    function stripper($data){
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = trim($data);
        return $data;
    }

    $ermsg = NULL;

    if(isset($_POST["loginBtn"])){

        $username = $_POST["luname"];
        $password = $_POST["lpass"];

        $username = stripper($username);
        $password = stripper($password);

        if(($username == "" || $username == " ") && ($password == "" || $password == " ")){
            $ermsg = "<p class='error'>Please enter your username!</p><p class='error'>Please enter your password!</p>";
            $row = NULL;
            $count = NULL;
        }elseif($username == "" || $username == " "){
            $ermsg = "<p class='error'>Please enter your username!</p>";
            $row = NULL;
            $count = NULL;
                      
        }elseif($password == "" || $password == " "){
            $ermsg = "<p class='error'>Please enter your password!</p>";
            $row = NULL;          
            $count = NULL;  
            
        }else{
            $query = $conn->query("SELECT id, username, password FROM accInfo WHERE username='$username'");
            
            $row = $query->fetch_array();
    
            $count = $query->num_rows;
        }

        if($password == $row["password"] && $count == 1){
            $_SESSION['userSession'] = $row["id"];
            $_SESSION['username'] = $row['username'];
        }

    }

?>

<?php include_once("header.php") ?>
<!DOCTYPE html>
<html>
    <head>
        <title>ChatHUB</title>

        <!-- ATTETION!!! - COPYRIGHT TERMS - ATTENTION!!! -->

        <meta name="copyrightTemrs" content="NOBODY IS ALLOWED TO USE OR DISTRIBUTE OR REPRODUSE OR ADAPT MY CODES OR ANY OF MY METERIALS OR WORKS THAT I CREATED OR WROTE FOR ANY PURPOSE THAT INCLUDES PERSONAL, NON-COMMERCIAL AND COMMERCIAL USE. ONLY IF I GIVE MY CONSENT OR PERMISSION TO USE MY CODE IN CERTAIN MANNERS CAN SOMEONE USE OR DISTRIBUTE OR REPRODUSE OR ADAPT IT IN THE SPECIFIED MANNERS. IT WILL BE A VIOLATION TO MY COPYRIGHT RIGHTS TO USE OR DISTRIBUTE OR REPRODUSE OR ADAPT MY CODES WITHOUT MY CONSENT OR PERMISSION">
        
        <!-- ATTETION!!! - COPYRIGHT TERMS - ATTENTION!!! -->
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="developer" content="Aneek Rahman">
        <meta name="application-name" content="chatHUB">
        <meta name="description" content="Online social chatting website">
        <meta name="keywords" content="online, chat, chatting, social, talk, website">
        <link href="indexStyle.css" type="text/css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    </head>
    <body>
    <div class="hover"></div>
    <div class="bigDiv">
        <?php

            if(isset($_SESSION["chatFile"])){
                if(!file_exists($_SESSION["chatFile"])){
                    $chatUrl = "logs/chatLog.html";
                }else{
                    $chatUrl = $_SESSION["chatFile"];
                }
            }else{
                $chatUrl = "logs/chatLog.html";
            }
                

            $phpself = $_SERVER["PHP_SELF"];

            #FILE SHOW


            if(file_exists("$chatUrl") && filesize("$chatUrl") > 0){
                $logFile = fopen("$chatUrl", "r");
                $contents = fread($logFile, filesize("$chatUrl"));
                fclose($logFile);
             }

             #LOGOUT SCRIPT

        if(isset($_GET["logout"])){
            
            if(isset($_SESSION["userSession"])){
                $leaveFile = fopen("$chatUrl","a");
                fwrite($leaveFile, "<i class='leave'>" . $_SESSION['username'] . " has <b>left</b> the chat</i><br>");
                fclose($leaveFile);

                $_SESSION["chatType"] = NULL;
                
                session_unset();
                session_destroy();

                $_SESSION["chatType"] = NULL;
            
            }

            

            #header("Location: $phpself");
            
        }
             
             


            function privateChat(){
                echo <<<EOT

                        <div class="privateWrap">
                            <p>Private Chat With:</p>
                            <form action="" method="post">
                                <input type="text" name="privateIn" placeholder="Enter a username">
                                <input type=submit name="privateBtn" value="START">
                            </form>
                        </div>

EOT;
            }


            function logBox(){
                global $ermsg, $phpself;

                echo <<<EOT
                
                        <div class="logWrap">
                            <h3>LOGIN HERE:</h3>
                            <form action="$phpself" method="post" id="logBox">
                            <div class="unameWrap">
                                <p>Username:</p>
                                <input type="text" name="luname">
                            </div>
                            <div class="passWrap">
                                <p>Password:</p>
                                <input type="password" name="lpass">
                            </div>
                            
                            <input type="submit" name="loginBtn" value="LOGIN">
                        </form>
                        $ermsg
                        </div>
                        <div class="orSign">
                            <p>~ OR ~</p>
                            <a href="register.php">REGISTER</a>
                        </div>
                
EOT;
            }


            if($_SESSION["chatType"] == "GLOBAL" || $_SESSION["chatType"] == NULL){
                $chatBtn = '<button id="private">ADD<br>CONTACTS</button>';
            }else{
                $chatBtn = '<button id="global">SWITCH TO <br>GLOBAL CHAT</button>';
            }
            

            function chatBox(){
                global $row,$contents ,$chatBtn, $chatUrl;

                $chatType = $_SESSION["chatType"];

                $name = $_SESSION['username'];

                if(isset($_SESSION["privateUName"])){
                    $puName = ": @" . $_SESSION["privateUName"];
                }else{
                    $puName = NULL;
                }

                
                
                if(isset($_SESSION["userSession"])){
                    if($_SESSION['joined'] == 0){

                        if(!isset($chatUrl)){
                            $joinFile = fopen("logs/chatLog.html","a");
                            fwrite($joinFile, "<i class='join'>$name has <b>joined</b> the chat</i><br>");
                            fclose($joinFile);
                        }else{
                            $joinFile = fopen("$chatUrl","a");
                            fwrite($joinFile, "<i class='join'>$name has <b>joined</b> the chat</i><br>");
                            fclose($joinFile);
                        }
                        

                        $_SESSION['joined'] = 1;
                    }
                }

                echo  <<<EOT
                <div class="wrapper">
                <div id="top">
                    <p class="welcome">Welcome, <b>  $name  </b></p>
                    <p class="type">$chatType $puName</p>
                    <p class="logoutC"><a href="#" id="logout">LOGOUT</a></p>
                </div>

                <div id="chat-box"> <div id="contBox">$contents</div> </div>
                
                
                <form action="" method="post">
                    <input type="text" id="msg-input" name="msg-input" placeholder="Type message here">
                    <input type="submit" name="submit" value="Send" id="sendBTN">
                </form>
                </div>
                <div class="widgets">
                    $chatBtn
                    <button id="requests">CONTACT <br>REQUESTS <span></span></button>
                    <button id="showContacts">SHOW <br>CONTACTS</button>
                    <button id="upPho">UPLOAD<br>PHOTOS</button>
                </div>
EOT;
            }

            
        

            if(isset($_SESSION["userSession"]) != ""){
                
                chatBox();

            }else{
                logBox();
            }
            
        


            $conn->close();
        ?>

</div>

        <div class="privateRow">
            <div class="privateWrap">
                <p>Private Chat With:</p>
                <form action="requestCreate.php" method="get">
                    <input type="text" name="privateIn" placeholder="Enter a username" id="privateIn">
                    <input type="submit" name="privateBtn" value="SEND REQUEST" id="privateBtn">
                </form>
            </div>
        </div>
        <div class="upPhoRow"><iframe src="uphtml.html"></iframe></div>
        <div class="reBoxRow">
            <div class="requestWrap">
                <h3>REQUESTS:</h3>
                <div class="reContainer">
                    <?php
                        if(isset($_SESSION['username'])){
                            if(!file_exists("requests/" . $_SESSION['username'] . "_requests.html")){
                                $handle = fopen("requests/" . $_SESSION['username'] . "_requests.html", "w");
                                fclose($handle);
                            }
                            if(empty(file_get_contents("requests/" . $_SESSION['username'] . "_requests.html"))){
                                echo "NO REQUESTS";
                            }else{
                                $reData = file_get_contents("requests/" . $_SESSION['username'] . "_requests.html");
                                echo $reData;
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="contRow">
            <div class="contBox">
                <h3>CONTACTS:</h3>
                <div class="contactWrap">
                    <?php
                    if(isset($_SESSION['username'])){
                        if(!file_exists("contacts/" . $_SESSION['username'] . "_contacts.html")){
                            $handle = fopen("contacts/" . $_SESSION['username'] . "_contacts.html", "w");
                            fclose($handle);
                        }
                        if(empty(file_get_contents("contacts/" . $_SESSION["username"] . "_contacts.html"))){
                            echo "NO CONTACTS";
                        }else{
                            $conData = file_get_contents("contacts/" . $_SESSION["username"] . "_contacts.html");
                            echo $conData;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>


                <!-- END -->
                <script
  src="https://code.jquery.com/jquery-1.5.min.js"
  integrity="sha256-IpJ49qnBwn/FW+xQ8GVI/mTCYp9Z9GLVDKwo5lu5OoM="
  crossorigin="anonymous"></script>



        <!-- IN HTML JAVASCRIPT -->

        <script>
            
            $(document).ready(function(){

            $('.privateWrap').hide();
            $('.upPhoRow').hide();
            $('.reBoxRow').hide();
            $('.contRow').hide();

            var height = 250;
            var autoScroll = false;


            var oldHeight = $("#chat-box").attr("scrollHeight");
            

            function loadLog(){

                var oldNotiNum = $("#contBox p").size();


                $.ajax({
                url: "<?php echo $chatUrl ?>",
                cache: false,
                success :function(html){
                    $("#chat-box").html(html);
                },
                })

                var newHeight = $("#chat-box").attr("scrollHeight");

                $('.wrapper').click(function(){
                    $('title').text("ChatHUB");
                    oldNotiNum = newNotiNum;
                })

                var newNotiNum = $("#contBox p").size();

                var notiDiff = newNotiNum - oldNotiNum;

                if(newNotiNum > oldNotiNum){
                    $('title').text("(1) ChatHUB");
                }

                var diff = newHeight - oldHeight;

                if(oldHeight > height){
					$("#chat-box").animate({ scrollTop: oldHeight }, 'normal');
                    height = height + oldHeight;
				}

                if(diff > 0){
                    $("#chat-box").animate({ scrollTop: newHeight }, 'normal');
                    $('title').text("(1) ChatHUB");

                    oldHeight = newHeight;
                
                }


            }   

                //CONTINOUSLY LOADING CHATLOG

                setInterval(loadLog, 1000);


                

                    $('#logout').click(function(){
                        window.location = "index.php?logout=true";
                    })
                        

                    $('#sendBTN').click(function(){
                        var sendInput = $('#msg-input').val();
                        $.post("post.php",{msg : sendInput});
                        $("#msg-input").attr("value", "");

                        return false;
                    });

                    $('#privateBtn').click(function(){
                        var sendInput = $('#privateIn').val();

                        if(sendInput != ""){
                            $.post("requestCreate.php",{privateIn : sendInput, swi : "private"});
                            $("#privateIn").attr("value", "");
                        }
                        
                        return false;
                    });

                    $('.contactWrap p').click(function(){
                        var name = $(this).attr("id");

                        if(name != ""){
                            $.post("startPrivate.php",{privateIn : name, swi : "private"},function(){
                                setTimeout(function(){
                                    window.location = "index.php";
                                }, 2000);
                            });
                        }
                        
                        return false;
                    });

                    


                    $("#global").click(function(){

                        $.post("startPrivate.php",{swi : "global"},function(e){
                                setTimeout(function(){
                                    window.location = "index.php";
                                }, 2000);
                            });
                    });
                    

                    $("#private").click(function(){
                        $('.privateWrap').toggle();
                        $(".upPhoRow").hide();
                        $('.reBoxRow').hide();
                        $('.contRow').hide();
                    });

                    $("#upPho").click(function(){
                        $(".upPhoRow").toggle();
                        $('.reBoxRow').hide();
                        $('.privateWrap').hide();
                        $('.contRow').hide();
                    })
                    
                    $('#requests').click(function(){
                        $('.reBoxRow').toggle();
                        $('.privateWrap').hide();
                        $('.upPhoRow').hide();
                        $('.contRow').hide();
                    })

                    $('#showContacts').click(function(){
                        $('.contRow').toggle();
                        $('.reBoxRow').hide();
                        $('.privateWrap').hide();
                        $('.upPhoRow').hide();
                    })

                    $(".reContainer .accept").click(function(){
                        var sendInput = $(this).parent().attr("id");
                        var lineNum = $(this).parent().attr("line");
                        $.post("requestDone.php",{dec : "accept", name : sendInput, line: lineNum});
                        
                    })

                    $(".reContainer .reject").click(function(){
                        var sendInput = $(this).parent().attr("id");
                        var lineNum = $(this).parent().attr("line");
                        $.post("requestDone.php",{dec : "reject", name : sendInput, line: lineNum});
                    })

                    $('#chat-box img').click(function(){
                        $(this).addClass("clickGrow")
                        $(this).addClass("clickimg")
                        $(this).removeClass("clickglow")
                        $(this).animate({
                            top:'50%',
                            left:'50%'
                        },500)
                        $(".hover").css({height: "100%", width : "100%"})
                        alert("hello")
   
                    })
               
                   $(".hover").click(function(){
                        $(this).css({height: "0", width : "0"})
                        $('#chat-box img').removeClass("clickimg")
                        $('#chat-box img').addClass("clickglow")
                        $('#chat-box img').animate({
                            top:'0',
                            left:'0'
                        },500)
                       $('#chat-box img').removeClass("clickGrow")
                   })

                

                }) //THE END

        </script>
        <?php include_once("footer.php") ?>
    </body>
</html>