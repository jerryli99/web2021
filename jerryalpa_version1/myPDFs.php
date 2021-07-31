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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
*{
  box-sizing: border-box;
}

body, html{
  font-family: 'Courier New', Courier, monospace;
  height: 100%;
  margin: 0;
  width: 100%;
  padding: 0;
  background-color: rgb(243,246,247);
  scroll-behavior: smooth;
}

::-webkit-scrollbar{
  width: 10px;
}

::-webkit-scrollbar-track{
  background: #f1f1f1;
}

::-webkit-scroll-thumb{
  background: rgb(107, 106, 106);
}

::-webkit-scrollbar-thumb:hover{
  background: rgb(51,51,51);
}

a{
  color: white;
  overflow-wrap: break-word;
  border-radius: 15px;
  margin-bottom: 10px;
  text-decoration: none;
}

ul{
 /* margin: 0px; */
  padding: 0px;
  list-style-type: none;
  margin-bottom: 20px;
}
li{
  margin-bottom: 10px;
}

h1, h5{
  text-align: center;
}


h3{
  text-align: center;
  color: white;
  background-color: black;
  padding: 4px 10px;
  display: inline-flex;
  border-radius: 15px;
  align-items: center;
  font-size: 90%;
  margin-top: 5px;
  margin-bottom: 5px;
  margin-left: auto;
  margin-right: auto;
}

h3 a{
  padding: 0.1rem;
  margin-top: 5px;
  margin-bottom: 5px;
  text-align: center;
}

h3:hover{
  background-color: gray;
}
.container{
  font-size: 95%;
  border: 1px solid black;
  background-color: white;
  padding: 0.5rem;
  margin-top: 0%;
  margin-right: 0.5rem;
  margin-left: 0.5rem;
  height: fit-content;
  border-radius: 15px;
  box-shadow: 0 4px 10px 0 rgba(0,0,0,0.2);
  width: 100%;
}

.big-container{
  display: flex;
  padding: 0.5rem;
  margin: 0;
  flex-wrap: wrap;
  align-content: space-around;
  flex-direction: row;
}

h2{
  text-align: center;
}

</style>
</head>
<body>
<h2>PDF and TEXT Files</h2>
   <div class="big-container">
       <div class="container">
       <center><h3><a href="fileUpload1">Upload File</a></h3>
       <h3><a href="manager">Account</a></h3>
       <h3><a href="index">Home</a></h3></center>
       <hr>
        <ul>
        <?php
            if(isset($_SESSION['this_user'])){
                $userName = $_SESSION['this_user'];
                $sql = "SELECT * FROM userFiles WHERE userName = '$userName' ORDER BY time DESC";
                $result = $connect->query($sql);
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        if($row['fileType'] == 'pdf' || $row['fileType'] == 'txt'){
                            $date = date("Y-m-d H:i:s", $row['time']);
                            $src = "https://test1-1.s3.amazonaws.com/" . $userName . "/" . $row["fileName"] . "." . $row["fileType"];

                            //the reason of repeating this logic is to style txt and pdf icons....
			    if($row["fileType"] == 'pdf'){
				echo "<i class=\"fa fa-file-pdf-o\"></i><a style=\"background-color: white; color: blue;\" href=\"$src\">
                                     {$row['fileName']}.{$row['fileType']}</a><br>Uploaded time:{$date}</li><hr><br><br>";
			    }else{
                                echo "<i class=\"fa fa-file-text-o\"></i><a style=\"background-color: white; color: blue;\"href=\"$src\">
                                     {$row['fileName']}.{$row['fileType']}</a><br>Uploaded time:{$date}</li><hr><br><br>";
			    }
                        }
                    }
                }
                else {
                    echo "<h2>0 results</h2>";
                }
                $connect->close();
            }else{
               header("Location: login");
	    }
        ?>
        </ul>
  </div>
</div>
</body>
</html>
