<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b1a33"/>
    <title>Terrapin Exchange - Register</title>
    <link rel="icon" href="images/icon2.jpg">
    <link rel="apple-touch-icon" href="images/icon2.jpg">
    <link rel="stylesheet" href="stylePages/login.css">
    <script src="https://www.recaptcha.net/recaptcha/api.js" async defer></script>
    <style>
        /* Center the loader */
        #loader {
        position: fixed;
        left: 50%;
        top: 50%;
        z-index: 1;
        width: 100px;
        height: 100px;
        margin: -50px 0 0 -50px;
        border: 12px solid black;
        border-radius: 50%;
        border-top: 12px solid yellow;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }

        /* Add animation to "page content" */
        .animate-bottom {
        position: relative;
        -webkit-animation-name: animatebottom;
        -webkit-animation-duration: 1s;
        animation-name: animatebottom;
        animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
        from { bottom:-100px; opacity:0 } 
        to { bottom:0px; opacity:1 }
        }

        @keyframes animatebottom { 
        from{ bottom:-100px; opacity:0 } 
        to{ bottom:0; opacity:1 }
        }

        #myDiv {
            display: none;
            /* text-align: center; */
        }
    </style>
</head>
<body onload="myFunction()" style="margin:0;">
<div id="loader"></div>
<div class="animate-bottom" style="display:none;" id="myDiv">
<div class="page-container">
<div class="content-wrap">

<?php
    date_default_timezone_set("America/New_York");
    session_start();
    include_once('connectDB.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor/autoload.php';

    //check if the input fields are all filled
    if (isset($_POST['userName']) && isset($_POST['email']) && isset($_POST['password']) 
        && isset($_POST['confirmPass']) && isset($_POST['register'])){
        $userName = $_POST['userName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPass = $_POST['confirmPass'];

        //must hash password before storing to database
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);


        //only save the username in cookie!!!For a month 60 min
        setcookie('userName1', $_POST['userName'], time()+(31*86400), true);

        //check if userName and email already exist
        $query = "SELECT * FROM users WHERE userName='$userName'";
        $check_num_row = $connect->query($query);
        if(mysqli_num_rows($check_num_row) > 0){
            
            //extra: delete cookie when unique name already existed.
            if(isset($_COOKIE['userName1'])){
                unset($_COOKIE['userName1']);
                setcookie('userName1', '', time()-(31*86400), true);
            }
            echo '<h1 style="border: 3px solid black;">
            User name was taken. Try other user names ;)</h1>';
            //header("Location:register");
            $connect->close();
        }else{
            if($password != $confirmPass){
                echo '<h1 style="border: 3px solid black">
                The confirm password is wrong. Please try again ;)</h1>';
            }else{
                
                //user google captcha before insert into database.
                $secretKey = "";
                $responseKey = $_POST['g-recaptcha-response'];
                $userIP = $_SERVER['REMOTE_ADDR'];
                $url = "https://www.recaptcha.net/recaptcha/api/siteverify?secret=" . 
                        $secretKey . "&response=" . $responseKey .  "&userip=" . $userIP;             
                $response = file_get_contents($url);
                $response_info = json_decode($response);
                if(!$response_info->success){
                    header("Location: register");
                    exit();
                }

                //insert name, email, hashed password, generated 6-digit verify code, 
                //and registered time() to database 
                $verified_code = substr(number_format(time()*rand(), 0, '', ''), 0, 6);
                $time = time();
                //set account activate status to 0;
                $status = 0;
                //insert user account into database
                $stmt = $connect->prepare("INSERT INTO users(userName, email, password, 
                verify_code, verify_status, last_time_visited) VALUES(?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $userName, $email, $password_hashed, $verified_code, $status, $time);
                $execval = $stmt->execute();
                //echo $execval;
                if($execval){

                    // This address must be verified with Amazon SES.
     $sender = 'mamamlenglish@gmail.com';
     $senderName = 'Terrapin Exchange - Jerry Li';

     $mail = new PHPMailer(true);
    //                 // Replace recipient@example.com with a "To" address. If your account
    // // is still in the sandbox, this address must be verified.
     $recipient = $_POST['email'];

    // // Replace smtp_username with your Amazon SES SMTP user name.
     $usernameSmtp = '';

    // // Replace smtp_password with your Amazon SES SMTP password.
     $passwordSmtp = '';

     $host = 'email-smtp.us-east-1.amazonaws.com';
     $port = 587;

    // // The subject line of the email
     $subject = 'Terrapin Exchange - Verification Code';

    // // The HTML-formatted body of the email
     $bodyHtml = "
     <p style=\"font-family: 'Courier New', Courier, monospace; padding: 2rem;\">
    
     Hi {$userName},<br><br>

     We just need to verify your email address before you can access your Terrapin Exchange Account.<br><br>

     Verify code: <strong>{$verified_code}</strong><br><br>

     Thanks!! &#128512;<br><br>
    
     Terrapin Exchange 
     </p>";

     try {
    //     // Specify the SMTP settings.
         $mail->isSMTP();
         $mail->setFrom($sender, $senderName);
         $mail->Username   = $usernameSmtp;
         $mail->Password   = $passwordSmtp;
         $mail->Host       = $host;
         $mail->Port       = $port;
         $mail->SMTPAuth   = true;
         $mail->SMTPSecure = 'tls';
    //   //  $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);
    
    //     // Specify the message recipients.
         $mail->addAddress($recipient);
    //     // You can also add CC, BCC, and additional To recipients here.
    
    //     // Specify the content of the message.
         $mail->isHTML(true);
         $mail->Subject    = $subject;
         $mail->Body       = $bodyHtml;
    // //    $mail->AltBody    = $bodyText;
         $mail->Send();
         echo "<h2>Email sent!<h2>" , PHP_EOL;
         echo "<h5>From {$sender} to {$recipient}</h5>";
     } catch (Exception $e) {
         echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
     } catch (Exception $e) {
         echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
     }

                    header("Location:accountActivate");
                }else{
                    echo "<h1>Oops, userName was taken. Try another userName. </h1>";
                }
                $stmt->close();
                $connect->close();
            }
        }
    }else{
        //echo "Inputs are not set.";
        $connect->close();
    }
?>


<br>
<h1>Register</h1>
    <form action="" method="POST">
        <!-- do not change this <pre>'s indentation. -->
<pre>
 Password format requirement :-)
 Have at least 1 lowercase alphabetical letter 
 Have at least 1 uppercase alphabetical letter 
 Have at least 1 number 
 Have at least 1 special character  
 Have at least 8 characters or longer
</pre>
<br>
<br>
        <div>
            <label for="userName"><h3>Enter user name: </h3></label>
            <input type="text" id="userName" name="userName" placeholder=" user name"
            value="<?php 
                if(isset($_COOKIE['userName1'])){
                    echo $_COOKIE['userName1'];
                }else{
                    echo "";
                }
            ?>"  pattern ="^[A-Za-z0-9]*$" title="only letters and numbers" required/>
        </div>

        <div>
            <label for="email"><h3>Enter your email: </h3></label>
            <input type="email" placeholder=" Your real email" id="email" name="email" required="required"/>
        </div>

        <div>
            <label for="password"><h3>Set new password: </h3></label>
            <input type="password" placeholder=" Password" id="password" name="password" 
            pattern="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$" required="required" 
            onkeyup='check();'/>
        </div>

        <!-- Must contain at least 1 lowercase alphabetical character; 
        must contain at least 1 uppercase alphabetical character;
        must contain at least 1 numeric character;
        must contain at least one special character;
        must be eight characters or longer; -->

        <div>
            <label for="password"><h3>Confirm password: </h3></label>
            <input type="password" placeholder=" Confirm Password"id="confirmPass" name="confirmPass" 
            required="required" onkeyup='check();'/>
            <span id='message'></span>
        </div>

        <div>
            <div style ="display:inline-block;" class="g-recaptcha" 
            data-sitekey="6LfxiIEbAAAAAMmml4_sUt00qgO4tbxvGkajnz-J"></div>
        </div>

        <br>
        <div>
            <input type="submit" class="button" name="register" value="Register"/>
        </div>

    </form>
    <div>
        <a class="return-link" href="login">Back to login</a>
        <br>
        <br>
        <a class="return-link" href="index">Back to Home</a>
    </div>
    </div>
    
    <br>
    <br>
    <br>
    <br>


    <!-- Site footer -->
    <footer>
    <hr>
        <div>
            <p class="copyright-text">Copyright &copy; 2021 All Rights Reserved by 
                <a href="#" target="_blank">Jerry D. Li</a></p>
        </div>
    </footer>
</div>

<!-- end of page loader div -->
</div>
</body>
    <script>
        function myFunction(){
            myVar = setTimeout(showPage, 3000);
        }
        function showPage(){
            document.getElementById("loader").style.display = "none";
            document.getElementById("myDiv").style.display = "block";
        }
    </script>
    <script>
        var check = function(){
            if(document.getElementById('password').value == 
            document.getElementById('confirmPass').value){
                document.getElementById('message').style.color='green';
                document.getElementById('message').innerHTML='&#x2714;';
            } else{
                document.getElementById('message').style.color="red";
                document.getElementById('message').innerHTML="&#x2718";
            }
        }
    </script>
</html>
