<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#0b1a33"/>
<title>Terrapin Exchange - Account</title>
<link rel="icon" href="images/icon2.jpg">
<link rel="apple-touch-icon" href="images/icon2.jpg">
<link rel="stylesheet" href="stylePages/manager.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        text-align:center;
    }
    </style>
</head>

<?php
    date_default_timezone_set("America/New_York");
    session_start();
    session_regenerate_id();
    include_once('connectDB.php');

    if(isset($_SESSION['userName']) && isset($_SESSION['time']) && isset($_SESSION['valid'])){
        $_SESSION['this_user'] = $_SESSION['userName'];
    }else{
        header("Location:login");
    }
?>

<body onload="myFunction()" style="margin:0;">
<div id="loader"></div>
<div class="animate-bottom" style="display:none;" id="myDiv">
<div class="page-container">
    <div class="content-wrap">
        <div class="big-container">
            <h1>My Exchange Account</h1>
            <div class="container1">
                <?php
                    $stmt = $connect->prepare("SELECT last_time_visited FROM users WHERE userName = ?");
                    $stmt->bind_param('s', $_SESSION['this_user']);
                    $stmt->execute();
                    $user_row = $stmt->get_result()->fetch_assoc(); //fetch DB results
                    if (isset($_SESSION['this_user'])){
                        echo "
                            <ul>
                                <li class=\"block1\"><h3>Hello &#128034; | {$_SESSION['this_user']}</h3></li>
                                <hr>
                                <li class=\"block1\"><h3 style=\"text-align:left; padding-left:0.5rem; font-size:70%;\">Last Visited Time( or registered time): ". date("Y-m-d H:i:s", $user_row['last_time_visited']) ." </h3></li>
                                <li class=\"block1\"><h3 style=\"text-align:left; padding-left:0.5rem; font-size:70%;\">USA, New York time zone</h3></li>
                                <li class=\"block1\"><h3 style=\"text-align:left;\"><a href=\"index\">🏠 Home Page</a></h3></li>
                                <li class=\"block1\"><h3 style=\"text-align:left;\"><a href=\"logout\" title=\"logout\"><i class=\"fa fa-sign-out\"></i> Logout</a></h3></li>
                            </ul>";                       
                        } 
                ?>
            </div>
    <!-- end of container1-->
    <h1>My Terrapin Exchange</h1>
    <div class="container2">
        <?php
            if (isset($_SESSION['this_user'])){
                echo "
                    <ul class=\"upload\">
                        <li class=\"block2\"><h2><a href=\"exchange\">Exchange Posts <i class=\"fa fa-refresh\"></i></a></h2></li>
                        <li class=\"block2\"><h2><a href=\"exchangeAdd\">ADD <i class=\"fa fa-plus-circle\"></i></a></h2></li>
                        <li class=\"block2\"><h2><a href=\"exchangeDelete\">DELETE Post <i class=\"fa fa-trash\"></i></a></h2></li>
                    </ul>";
            }
        ?>
    </div>
    <!-- end of container 2 -->
    <h1>My Terrapin Storage</h1>
    <div class="container3">
            <?php
            if (isset($_SESSION['this_user'])){
                echo "
                    <ul class=\"upload\">
                        <li class=\"block3\"><h2><a href=\"fileUpload1\">Upload <i class=\"fa fa-cloud-upload\"></i></a></h2></li>
                        <li class=\"block3\"><h2><a href=\"myPictures\">My Pictures <i class=\"fa fa-image\"></i></a></h2></li>
                        <li class=\"block3\"><h2><a href=\"myPDFs\">My PDFs <i class=\"fa fa-file-pdf-o\"></i></a></h2></li>
                        <li class=\"block3\"><h2><a href=\"fileUpload1\">DELETE Uploads <i class=\"fa fa-trash\"></i></a></h2></li>
                    </ul>";
                }
            ?>
    </div>
    <!-- end of container 4 -->

    <div class="container4">
            <?php
                if (isset($_SESSION['this_user'])){
                    echo "
                        <ul class=\"upload\">
                            <li class=\"block4\"><h2><a href=\"history\">History <i class=\"fa fa-history\"></i></a></h2></li>
                            <li class=\"block4\"><h2><a style =\"color: white; background-color: black;\" href=\"help\">Help ?</a></h2></li>
                        </ul>";
                }
            ?>
    </div>
    <!-- end of container 4 -->
</div>
<!-- end of big container -->
    <!-- end of contentwrap -->
    </div>
    <footer>
    <hr>
        <div>
            <p class="copyright-text">Copyright &copy; 2021 All Rights Reserved by <a href="#" target="_blank">Jerry D. Li</a></p>
            <br>
        </div>   
    </footer>
    <!-- end of page-container -->
</div>

<!-- end of animate bottom (page loader)-->
</div>
</body>


<!-- this javascript is to handle the loading animation page -->
<script>
    function myFunction(){
        myVar = setTimeout(showPage, 1500);
    }
    function showPage(){
        document.getElementById("loader").style.display = "none";
        document.getElementById("myDiv").style.display = "block";
    }
</script>


<!-- this php script is to update last visited time -->
<?php
    $stmt = $connect->prepare("SELECT last_time_visited FROM users WHERE userName = ?");
    $stmt->bind_param('s', $_SESSION['this_user']);
    $stmt->execute();
    $user_row = $stmt->get_result()->fetch_assoc(); //fetch DB results   
    $time = time();
    if (!empty($user_row)) { // checks if the user actually exists(true/false returned)
        $sql = "UPDATE users SET last_time_visited = {$time} WHERE userName = '". $_SESSION['this_user'] ."'"; // change in query
        $result = mysqli_query($connect,$sql);             
        if(!$result){
            echo '<h1>Didn\'t update time</h1>';
        }
    }
?>
</html>
