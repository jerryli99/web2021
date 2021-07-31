<?php
    date_default_timezone_set('America/New_York');
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="This is Terrapin Exchange! You can exchange items, knowledge, labor with
    other people moneyless...">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b1a33"/>
    <title>Terrapin Exchange - Home</title>
    <link rel="icon" href="images/icon2.jpg">
    <link rel="apple-touch-icon" href="images/icon2.jpg">
    <link rel="stylesheet" href="stylePages/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
    <style>
        /* Notes: Every id is unique and we can only style the element with the id within the page with the # symbol */
        #Home {background-color: rgb(253, 207, 0);}
        #What {background-color: rgb(253, 207, 0);}
        #How {background-color: rgb(253, 207, 0);}
        #Who {background-color: rgb(253, 207, 0);}
	#Top{
	  position: fixed;
          width: 45px;
          height: 45px;
          background-color: #00AAFF;
          bottom: 75px;
          right: 50px;
          text-align: center;
          font-size:22px;
          line-height: 45px;
	}

        #message-to-user{
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


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
        from { opacity:0 } 
        to { opacity:1 }
        }

        @keyframes animatebottom { 
        from{ opacity:0 } 
        to{ opacity:1 }
        }

        #myDiv {
            display: none;
            text-align: center;
        }

        #description{
            text-align: center;
            display: block;
            display:-webkit-box;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 70ch;
            height: 1.2em;
            line-height: 1.2rem;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        #userName{
	    margin-top: 0;
            padding: 0.5rem;
            display: block;
            display:-webkit-box;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 2em;
            max-width: 50ch;
            line-height: 2rem;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    </style>
</head>

<body onload="myFunction()" style="margin:0;">
    <div id="loader"></div>
    <div class="animate-bottom" style="display:none;" id="myDiv">
    <?php
        if(isset($_SESSION['userName']) && isset($_SESSION['time']) && isset($_SESSION['valid'])){
            echo '<ul class="top-bar">
                    <li>
                        <p id="message-to-user" class="message-to-user">Hi~ ' . $_SESSION['userName'] . '</p>
                    </li>
                    <li>
                        <a href="manager">Account <i class="fa fa-user-circle"></i></a>
                    </li>
                    <li>
                        <a href="fileUpload1" title="upload item/skill"> <i class="fa fa-cloud-upload"></i> </a>
                    </li>
                    <li>
                        <a title="logout" href="logout" style="cursor:help;"><i class="fa fa-sign-out"></i></a>
                    </li>
                  </ul>';
            }else{
                    echo '
                        <ul class="top-bar">
                            <li class="userWelcome">
                                <p class="message-to-user">Welcome!</p>
                            </li>
                            <li>
                                <a href="login">Login <i class="fa">&#xf0a9;</i></a>
                            </li>
                        </ul>';
            }
        ?>
    <div id="Home" class="tabcontent">
        <h1>Terrapin Exchange</h1>
        <h4>
            <a href="about.php" class="homeLink">
                Learn more about Terrapin Exchange
            </a>
        </h4> 
        <a href="about" alt="icon2" title="About Terrapin Exchange"><img class="icon2" src="images/icon2.jpg" alt="icon2"></a>
    </div>

    <div id="What" class="tabcontent">
        <h1>What to Exchange?</h1>
        <h4> Classnotes, Books, Furnitures...</h4>
        <a href="about" alt="icon2" title="About Terrapin Exchange"><img class="icon2" src="images/icon2.jpg" alt="icon2"></a>
    </div>
    
    <div id="How" class="tabcontent">
        <h1>How to Exchange?</h1>
        <h4>Exchange items or skills moneyless</h4>
        <a href="about" alt="icon2" title="About Terrapin Exchange"><img class="icon2" src="images/icon2.jpg" alt="icon2"></a>
    </div>

    <div id="Who" class="tabcontent">
        <h1>Who Can Use This?</h1>
        <h4>The UMD Community</h4>
        <a href="about" alt="icon2" title="About Terrapin Exchange"><img class="icon2" src="images/icon2.jpg" alt="icon2"></a>
    </div>

    <div>
        <button class="tablink" onclick="openCity('Home', this, 'rgb(255, 213, 32)')" id="defaultOpen">Home</button>
        <button class="tablink" onclick="openCity('What', this, ' rgb(255, 213, 32)')">What</button>
        <button class="tablink" onclick="openCity('How', this, ' rgb(255, 213, 32)')">How</button>
        <button class="tablink" onclick="openCity('Who', this, ' rgb(255, 213, 32)')">Who</button>
    </div>
    
