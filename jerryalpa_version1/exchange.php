<?php
    session_start();
    date_default_timezone_set("America/New_York");

    if(!isset($_SESSION['this_user'])){
        header("Location: login");
        exit();
    }

    if(isset($_GET['itemNum']) && isset($_GET['productID'])){
        $i = $_GET['itemNum'];
    }else{
        echo '<h1>Error Occured :\</h1>';
    }

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once 'vendor/autoload.php';
    require_once 'connectDB.php';
    require_once 'productAPICall.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b1a33"/>
    <link rel="icon" href="images/icon2.jpg">
    <link rel="apple-touch-icon" href="images/icon2.jpg">
    <link rel="stylesheet" href="stylePages/exchange.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Terrapin Exchange -> Exchange</title>
</head>

<body>
<div class="page-container">
    <div class="content-wrap">
        <div class="big-container">
            <h1>Exchanger</h1>
            <div class="container">
                <?php
                    if(isset($_GET['exchangeWith']) && isset($_GET['itemNum']) && isset($_GET['productID'])){
                        echo '<h3>Reminder: All transaction records will be publicly visible to everyone.<br> 
                        If you sign your userName below, it means you argeed to exchange with this user: <strong><mark>' . 
                        $_GET['exchangeWith'] . '</strong>.</h3><hr>';

                        echo '<h3>Step 1 out of 2 <i class="fa fa-arrow-circle-right"></i> 
                        <br>Type this userName as an agreement(no copy and paste): 
                        <br><br><mark>' . 
                        '<div class="user">' . $_SESSION['this_user'] . '</div></h3>';

                        echo '<form method="POST">
                                <input type="text" name="text" id="sign" onpaste="return false;" 
                                ondrop="return false;" autocomplete="off" required/>
                                <input type="submit" name="sign" id="submit" value="sign">
                             </form>';

                             echo '<a id="goBack" class="back" href="exchangeInfo?itemNum=' . $_GET['itemNum'] . 
                             '&productID=' . $_GET['productID']. '">Go Back</a>';                         
                    }else{
                        header("Location: login");
                    }
                ?>
            </div>
            <?php
                if(isset($_POST['sign'])){
                    echo '<script>document.getElementById("goBack").style.display = "none";</script>';
                    //let #sign input field be disabled.
                    echo '<script>document.getElementById("sign").disabled = true;
                    document.getElementById("sign").style.cursor = "not-allowed";
                    document.getElementById("submit").disabled = true;
                    document.getElementById("submit").style.cursor = "not-allowed";</script>';

                    $sendEmailTo = '';

                    echo '<div class="container">';
                    if($_POST['text'] != $_SESSION['this_user']){
                        echo '<h2 style="border: 1px; solid black;">Wrong user name...</h2>';
                        echo '<center><a href="exchange?exchangeWith=' . $_GET['exchangeWith'] . 
                        '&itemNum=' . $_GET['itemNum'] . '&productID=' . $_GET['productID'] . 
                        '" class="back">Refresh</a></center>';
                        exit();
                    }


                    //sql_1 means select the email recipient email
                    $sql_1 = "SELECT userName, email FROM users WHERE userName = '" . 
                    $_GET['exchangeWith'] . "'";
                    $result_1 = $connect->query($sql_1); 
                    if (mysqli_num_rows($result_1) == 1) {
                        $row_1 = mysqli_fetch_assoc($result_1); 
                        $sendEmailTo = $row_1['email'];
                    }

                    //sql_2 means select the person who signed the agreement, his or her email
                    $sql_2 = "SELECT userName, email FROM users WHERE userName = '" . 
                    $_SESSION['this_user'] . "'";
                    $result_2 = $connect->query($sql_2); 
                    if (mysqli_num_rows($result_2) == 1) {
                        $row_2 = mysqli_fetch_assoc($result_2); 
                        $contactUser = $row_2['email'];
                    }

                    $text = 'Location:' . $content[$i][2] . '<br>Text:' .  $content[$i][3] . '<br>';
                    emailThePoster($_GET['exchangeWith'], $sendEmailTo, $content[$i][1], 
                                   $text , $contactUser);

                    echo '<center><a id="goHome" class="back" href="index">Go Home <i class="fa fa-home"></i></a></center>';   
                    echo '</div>';

                    //Notes: For product delete, I can just let the user who posted the product to do that manually.
                    //I can link the Exchange in Manager.php to product.jerryalpa.com/something.php. It is better
                    //because I have the product database in that server as well. The user
                    //can then delete it.  

                    //The code below is to store the transaction in database.
                    //later, I can use this to create a blockchain. Users can see every transactions, which is some way, broadcasting 
                    //all the transactions to the "p2p network"; that is, people can peek the record whenever they want instead of making
                    //them download the whole thing.
                    $time = date('Y-m-d H:i:s', time());
                    $signature = $_POST['text'];
                    $transaction = 'ProductID <i class="fa fa-qrcode"></i> [' . $content[$i][4] . '] From user <i class="fa fa-user"></i>: ['. $content[$i][0] . '] 
                    <i class="fa fa-arrow-right"></i> to user <i class="fa fa-user"></i>: [' . $signature . ']';
                    $stmt = $connect->prepare("INSERT INTO transactions(transaction, signature, transaction_time) 
                    VALUES(?, ?, ?)");
                    $stmt->bind_param("sss", $transaction, $signature, $time);
                    $execval = $stmt->execute();
                    // if($execval){
                    //     echo '<h1>Inserted transaction</h1>';
                    // }
                }                    
            ?>
        </div>
    </div>
    <br>
      <footer>
        <hr>
        <div>
          <p class="copyright-text">Copyright &copy; 2021 All Rights Reserved by 
            <a href="#" target="_blank">Jerry D. Li</a>
          </p>
          <br>
        </div>   
      </footer>
</div>
</body>
</html>

<?php

/*
 * @name The poster's name
 * @sendEmailTo The poster's email
 * @category The poster's post category
 * @description The poster's description
 * @contactUser The person who signed, his or her email
 */

function emailThePoster($name, $sendEmailTo, $category, $text, $contactUser){
    $sender = '';
    $senderName = 'Terrapin Exchange - Exchanger';
    $mail = new PHPMailer(true);
    // Replace recipient@example.com with a "To" address. If your account
    // is still in the sandbox, this address must be verified.
    $recipient = $sendEmailTo;
    // Replace smtp_username with your Amazon SES SMTP user name.
    $usernameSmtp = '';
    // Replace smtp_password with your Amazon SES SMTP password.
    $passwordSmtp = '';
    $host = '';
    $port = ;
    // The subject line of the email
    $subject = 'Terrapin Exchange - Exchanger';
    // The HTML-formatted body of the email
    $bodyHtml = "
    <p style=\"font-family: 'Courier New', Courier, monospace;\">  
    Hi {$name}, someone agreed to exchange {$category} with you! <br><br>
    This is your Post item/skill: </p><br>{$text}<br>" .
   "<p style=\"font-family: 'Courier New', Courier, monospace;\">
    Contact the user via {$contactUser}<br><br>
    Reminder: Your item/skill post will remain to the public.<br><br>
    You can delete it by logging into your user account if you want to.<br><br> 
    Good luck with the Exchange!<br><br>
    Terrapin Exchange 
    </p>";
    try {
        // Specify the SMTP settings.
        $mail->isSMTP();
        $mail->setFrom($sender, $senderName);
        $mail->Username   = $usernameSmtp;
        $mail->Password   = $passwordSmtp;
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';
        // Specify the message recipients.
        $mail->addAddress($recipient);
        // Specify the content of the message.
        $mail->isHTML(true);
        $mail->Subject    = $subject;
        $mail->Body       = $bodyHtml;
        $mail->Send();
        echo '<h3>Step 2 out of 2 <i class="fa fa-arrow-circle-right"></i> <br>An Exchange Invitation Email was sent to <a>' . $recipient . '</a><h3><hr>' , PHP_EOL;
        echo "<br><h3 style=\"margin-bottom: 50px;\">You can contact the person using this email.<br>Good luck with the exchange!</h3>";
    } catch (Exception $e) {
        echo "An error occurred.", PHP_EOL; //Catch errors from PHPMailer.
    } catch (Exception $e) {
        echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
    }

}
?>
