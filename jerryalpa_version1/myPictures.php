<?php
    date_default_timezone_set("America/New_York");
    session_start();
    include_once('connectDB.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b1a33"/>
    <title>Terrapin Exchange - Pictures</title>
    <link rel="icon" href="images/icon2.jpg">
    <link rel="apple-touch-icon" href="images/icon2.jpg">
    <link rel="stylesheet" href="stylePages/myPictures.css">
</head>
<body>
<div class="container">
    <h1>Your Gallery-></h1>
     <ul>
        <?php
            if(isset($_SESSION['this_user'])){
                $userName = $_SESSION['this_user'];
                $sql = "SELECT * FROM userFiles WHERE userName = '$userName' ORDER BY time DESC";
                $result = $connect->query($sql); 
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        if($row['fileType'] != 'pdf' && $row['fileType'] != 'txt'){
                            $src = "https://test1-1.s3.amazonaws.com/" . $userName . "/" . $row["fileName"] . "." . $row["fileType"];
                            echo "<li><img src=\"$src\"></li>";
			    //echo $src . "<br>";
			    //echo '<img src="https://test1-1.s3.amazonaws.com/w4/18-3.png">';
                        }
                    }
                } else {
                echo "0 results";
                }
                $connect->close();
            }else{
              header("Location: login");
            }
        ?>
     </ul>
</div>
<h2><a href="fileUpload1">Upload Files</a></h2>
<h2><a href="manager">Account</a></h2>
<h2><a href="index">Home</a></h2>
</body>
</html>
