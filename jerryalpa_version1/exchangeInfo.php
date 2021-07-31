<?php
    session_start();
    require_once 'productAPICall.php';

    if(!isset($_SESSION['this_user'])){
        header('Location: login');
        exit();
    }
	
    if(isset($_GET['itemNum']) && isset($_GET['productID'])){
	$i = $_GET['itemNum'];
    }else{
	echo '<h1>Oops, something went wrong. No exchange here. :\</h1>';
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b1a33"/>
    <title>Terrapin Exchange - ExchangeInfo</title>
    <link rel="icon" href="images/icon2.jpg">
    <link rel="apple-touch-icon" href="images/icon2.jpg">
    <link rel="stylesheet" href="stylePages/exchangeInfo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="page-container">
    <div class="content-wrap">
        <div class="big-container">
        <h1>Exchange Info</h1>
            <div class="container">
                <div class="item">
                    <?php
                        echo '<img src="' . $content[$i][6] . '" alt=\"image missing\">' .
                             '<h3>User:' . $content[$i][0] . '</h3>' .
                             '<strong>Type:' . $content[$i][1] . '</strong>' .
                             '<p><strong>Location:</strong>' . $content[$i][2] .'</p>' .
                             '<div id ="description"><strong>Notes:</strong>' . $content[$i][3] .'</div>' .
                             '<p><strong>ProductID: </strong>' . $content[$i][4] . '</p>' .
                             '<p><strong>Posted Time: </strong>' . date("Y-m-d H:i:s", $content[$i][5]) . '</p><hr>';
                    ?>
                </div>
                <div class="item">
                    <div class="item-child">
			<?php
			    $exchangeWith = $content[$i][0];
			    echo '<a href="exchange?exchangeWith=' . $exchangeWith .
			    '&itemNum=' . $_GET['itemNum'] . '&productID=' . $_GET['productID'] .
                            '" class="back">Exchanger</a></div>';
			?>
                    <hr>
                    <div class="item-child"><a href="manager" class="back">Account <i class="fa fa-user-circle"></i></a></div>
                    <hr>
                    <div class="item-child"><a href="index" class="back">Go Back <i class="fa fa-arrow-circle-left"></i></a></div>
                </div>
            </div>
        </div>
    </div>
    <footer>
    <hr>
        <div>
            <p class="copyright-text">Copyright &copy; 2021 All Rights Reserved by <a href="#" target="_blank">Jerry D. Li</a></p>
            <br>
        </div>   
    </footer>
</div>
</body>
</html>
