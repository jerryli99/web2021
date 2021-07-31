<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terrapin Exchange - Activation</title>
    <link rel="icon" href="images/icon2.jpg">
    <link rel="apple-touch-icon" href="images/icon2.jpg">
    <link rel="stylesheet" href="stylePages/activateAccount.css">
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
            session_start();
            include_once('connectDB.php');
            if (isset($_POST['verified_code'])){
                $code = $_POST['verified_code'];
                if (isset($_COOKIE['userName1'])){
                    $v_userName = $_COOKIE['userName1'];
                }

                //database section: check verify_status and update it from 0 to 1. Then, login.
                $v_userName = $_POST['userName'];
                $stmt = $connect->prepare("SELECT userName, verify_code, verify_status FROM users WHERE userName = ?");
                $stmt->bind_param('s', $v_userName);
                $stmt->execute();
                $user_row = $stmt->get_result()->fetch_assoc(); //fetch DB results
                
                if (!empty($user_row)) { // checks if the user actually exists(true/false returned)
                    if ($code == $user_row['verify_code'] && $user_row['verify_status'] == 0){
                        $sql = "UPDATE users SET verify_status= 1 WHERE userName = '". $user_row['userName'] ."'"; // change in query
                        $result = mysqli_query($connect,$sql); 
                        if($result){
                            //user google captcha 
                            $secretKey = "6LfxiIEbAAAAAJ3Lq2mkduZ9IR3nRzrqTGbTqlsM";
                            $responseKey = $_POST['g-recaptcha-response'];
                            $userIP = $_SERVER['REMOTE_ADDR'];
                            $url = "https://www.recaptcha.net/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $responseKey .  "&userip=" . $userIP;
                            $response = file_get_contents($url);
                            $response_info = json_decode($response);
                            if(!$response_info->success){
                            header("Location:accountActivate");
                                exit();
                            }

                            $_SESSION['v_userName'] = $v_userName;
                            $_SESSION['verified'] = true; 


                            //in case user click the back button on the browser, we sign the user out (if the user is already signed in).
                            //and then direct the user to the login page.
                            //this is to make sure one browser, one user only. No linked or overlapping accounts.
                            //if user is logged in, the user should not go to the activation page.
                            if(isset($_SESSION['v_userName']) && isset($_SESSION['verified'])){
                                session_unset();
                                session_destroy();
				echo '<h1 style="text-align:center; margin-bottom:0; border: 3px solid black;\">Going to login page....Terp</h1>';
                                header('Location:login');
                                exit();
                            }


                        }else{
                            die(mysqli_error($connect)); // execute query and catch error if any happen
                        }
                    }else if($user_row['verify_status'] == 1){
                        echo "<h1 style=\"text-align:center; margin-bottom:0; border: 3px solid black;\">Your account was verified. Go to <a href=\"login\">Login</a>!</h1>";
                    }else{
                        echo "<h1 style=\"text-align:center; margin-bottom:0; border: 3px solid black;\">Maybe try enter the code again?</h1>";
                    }
                }else{
                    echo "<h1 style=\"text-align:center; border: 3px solid black;\">Dear, let's <a href=\"register\">Let's Register!</a></h1>";
                }
            }
        ?>
            <a><img class="icon2" src="images/icon2.jpg" alt="icon2"></a>
            <form action="" method="POST">
                <div>    
                    <label for="userName"><h3>Your userName: </h3></label>
                    <input type="text" placeholder=" user name" id="userName" name="userName" 
                    value="<?php if(isset($_COOKIE['userName1'])) echo $_COOKIE['userName1']?>" required="required"/>
                </div>
            
                <div>    
                    <label for="verified_code"><h3>Verification Code: </h3></label>
                    <input type="text" placeholder=" 6 digit code" id="verified_code" name="verified_code" required="required"/>
                </div>
                
                <div>
                    <div style ="display:inline-block;" class="g-recaptcha" data-sitekey="6LfxiIEbAAAAAMmml4_sUt00qgO4tbxvGkajnz-J"></div>
                </div>

                <div>
                    <input type="submit" class="button" name="activateAccount" value="Activate Account"/>
                </div>
            </form>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
        <footer>
        <hr>
            <div>
                <p class="copyright-text">Copyright &copy; 2021 All Rights Reserved by <a href="#" style ="color: black;" target="_blank">Jerry D. Li</a></p>
                <br>
            </div>   
        </footer>
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
</html>
