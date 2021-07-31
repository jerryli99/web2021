<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b1a33"/>
    <title>Terrapin Exchange - Login</title>
    <link rel="icon" href="images/icon2.jpg">
    <link rel="apple-touch-icon" href="images/icon2.jpg">
    <link rel="stylesheet" href="stylePages/login.css">
    <script src="https://www.recaptcha.net/recaptcha/api.js" async defer></script>
</head>

<?php
    date_default_timezone_set("America/New_York");
    session_start();
    session_regenerate_id();
    if(isset($_SESSION['userName']) && isset($_SESSION['time']) && isset($_SESSION['valid'])){
        //check if user is already logged in. If logged in, no need to login.
        header('Location: manager');
    }else{
        
        include_once('connectDB.php');
        //after connected to db, verify user email and password
        if(isset($_POST['userName']) && isset($_POST['password']) && isset($_POST['login'])){
          //
            $userName = $_POST['userName'];
            $password = $_POST['password'];
            //only save the username and the time last visited in cookie!!!For a month 31 days
            setcookie('userName', $_POST['userName'], time()+ 31*86400, true);
            //match password with DB
            $stmt = $connect->prepare("SELECT userName, password, verify_status FROM users WHERE userName = ?");
            $stmt->bind_param('s', $userName);
            $stmt->execute();
            $user_row = $stmt->get_result()->fetch_assoc(); //fetch DB results

            if (!empty($user_row)) { // checks if the user actually exists(true/false returned)
                if (password_verify($_POST['password'], $user_row['password'])) {
                    // echo '<h1 style="font-size:400%; color:white;">valid!!!</h1>'; // password_verify success!
                    if($user_row['verify_status'] == 1){

                        //google captcha
                        $secretKey = "6LfxiIEbAAAAAJ3Lq2mkduZ9IR3nRzrqTGbTqlsM";
                        $responseKey = $_POST['g-recaptcha-response'];
                        $userIP = $_SERVER['REMOTE_ADDR'];
                        $url = "https://www.recaptcha.net/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $responseKey .  "&userip=" . $userIP;
                        $response = file_get_contents($url);
                        $response_info = json_decode($response);
                        if(!$response_info->success){
                            header("Location: login");
                            exit();
                        }
                        //set login session veriables
                        $_SESSION['valid'] = true;
                        $_SESSION['time'] = time();
                        $_SESSION['userName'] = $user_row['userName'];
                        header("Location:manager");
                    }else{
                        //handel account activation user direction flow
                        echo "<h1 style=\"border: 3px solid black;\">Account is not activated. 
                        Please check your email for Terrapin Exchange - Activate, OR register a new account!</h1>";
                    }
                } else {
                    echo '<h1 style="border: 3px solid black;">Password not correct :(</h1>';
                }
            } else {
                echo '<h1 style="border: 3px solid black;">This user does not exist :(</h1>'; //userName entered does not match in DB
            }
            
            $stmt->close();
            $connect->close();
        }else{
            $connect->close(); //else, there is no need to keep connecting to the database.
        }
    }
?>

<body>
<div class="page-container">
<div class="content-wrap">
<div class="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        <strong>Hi~:) <?php if(isset($_COOKIE['userName'])){ 
                                echo $_COOKIE['userName'] . " (:~";
                            }else{
                                if(isset($_COOKIE['userName1'])){
                                    echo $_COOKIE['userName1'] . " (:~";
                                }else{
                                    echo "";
                                }  
                            }?>
        </strong> This site uses cookies🍪🍪. Don't worry, only your <a style="color:yellow;"><strong>userName </strong></a> is stored. 
        Cookies will expire after 31 days of keeping your user name. 
    </div>
<br>
<!-- <a href="index.php"><img class="icon2" src="images/icon2.jpg" alt="icon2"></a> -->
<h1>Login</h1>
   <form action="" method="POST">
        <div>
            <label for="userName"><h3>Enter userName: </h3></label>
            <input type="text" id="userName" name="userName" placeholder=" user name"
            value="<?php 
                if(isset($_COOKIE['userName'])){
                    echo $_COOKIE['userName'];
                }else{
                    if(isset($_COOKIE['userName1'])){
                        echo $_COOKIE['userName1'];
                    }else{
                        echo "";
                    }
                }
            ?>" required/>
        </div>

        <div>
            <label for="password"><h3>Enter password: </h3></label>
            <input type="password" id="password" name="password" placeholder=" password" required/>
        </div>

        <div>
            <div style ="display:inline-block;" class="g-recaptcha" data-sitekey="6LfxiIEbAAAAAMmml4_sUt00qgO4tbxvGkajnz-J"></div>
        </div>

        <br>
        <div>
            <input type="submit" class="button" name="login" value="Login"/>
        </div>
    </form>
    <div>   
        <a class="return-link" href="register">Register</a>
        <br>
        <br>
        <a class="return-link" href="index">Back to Home</a>
    </div>
</div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <!-- Site footer -->
    <footer>
    <hr>
        <div>
            <p class="copyright-text">Copyright &copy; 2021 All Rights Reserved by <a href="#" target="_blank">Jerry D. Li</a></p>
        </div>
    </footer>
    <!-- end of page container -->
</div>
</body>
</html>
