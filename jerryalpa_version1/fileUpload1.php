<?php
session_start();

require 'vendor/autoload.php';
include_once('connectDB.php');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


if(isset($_SESSION['userName']) && isset($_SESSION['time']) && isset($_SESSION['valid'])){
// AWS Info
$bucketName = 'test1-1';
$IAM_KEY = 'AKIAZXTHLVQD7DBWZGEN';
$IAM_SECRET = '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';

// Connect to AWS
try {
    // You may need to change the region. It will say in the URL when the bucket is open
    // and on creation.
    $s3 = S3Client::factory(
        array(
            'credentials' => array(
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
            'version' => 'latest',
            'region'  => 'us-east-1'
        )
    );
} catch (Exception $e) {
    // We use a die, so if this fails. It stops here. Typically this is a REST call so this would
    // return a json object.
    die("Error: " . $e->getMessage());
}

    if(isset($_POST['submit'])){
	$allowed = array('gif', 'png', 'jpg', 'pdf', 'txt');
        $totalFileSize = array_sum($_FILES['fileToUpload']['size']);
	//count the number of files
	$countFiles = count($_FILES['fileToUpload']['name']);
	if($countFiles > 9){
	  header("Location: fileUpload1?fail=1");
	  exit();
	}

        if($totalFileSize > 40*1024*1024){
          //header("Location: fileUpload1?fail=2");
          echo "<div><h2>File excceed 40 MB :\</h2></div>";
        }else{

      for($i = 0; $i < $countFiles; $i++){
	$explode = explode('.', $_FILES['fileToUpload']['name'][$i]);
	$extension = end($explode);
	//echo $extension;
	if(in_array($extension, $allowed)== false){
	   header("Location: fileUpload1?fail=2");
	   exit(); 
        }
	// For this, I would generate a unqiue random string for the key name. But you can do whatever.
	$keyName = "{$_SESSION['this_user']}/" . basename($_FILES["fileToUpload"]['name'][$i]);
	$pathInS3 = 'https://s3.us-east-1.amazonaws.com/' . $bucketName . '/' . $keyName;

	// Add it to S3
	try {
    	// Uploaded:
    	$file = $_FILES["fileToUpload"]['tmp_name'][$i];
    	$s3->putObject(
        	array(
            	'Bucket'=>$bucketName,
            	'Key' =>  $keyName,
            	'SourceFile' => $file,
            	'StorageClass' => 'REDUCED_REDUNDANCY',
		'ContentType' => ''
        	)
    	);

	$getObjectUrl = $s3->getObjectUrl($bucketName, $keyName);
	//echo "<div id=\"alert-box\"><h2>Upload success!</h2></div>";
	//echo "<div style=\"width: 70%; text-align: left;\" class=\"container\">Check this link: <br>
        //      <a style=\"word-wrap: break-word;\" href=\"{$getObjectUrl}\" target=\"_blank\">{$getObjectUrl}</a></div>";

	} catch (S3Exception $e) {
		header("Location: fileUpload1");
   		 die('Error:' . $e->getMessage());
	} catch (Exception $e) {
		header("Location: fileUpload1");
    		die('Error:' . $e->getMessage());
	  }

       $time = time();
       $userName = $_SESSION['this_user'];
       $ext = pathinfo($keyName, PATHINFO_EXTENSION);
       $fileName = basename($keyName, "." . $ext);
       /* store file name to database*/
       $stmt = $connect->prepare("INSERT INTO userFiles (userName, fileName, fileType, time) VALUES(?,?,?,?)");
       $stmt->bind_param("ssss", $userName, $fileName, $extension, $time);
       $execval = $stmt->execute();
       if(!$execval){echo 'Opps! File is uploaded to the cloud, but cannot trace file.';}

     }/*end of for loop. */
	echo "<div id=\"alert-box\"><h2>Upload Successfully!</h2></div>";
     }
   }/*else{
	//echo "<div><h2 style=\"border: 2px solid black;\">Reminder: File submit cannot be empty!</h2></div>";

    }*/
// Now that you have it working, I recommend adding some checks on the files.
// Example: Max size, allowed file types, etc.


//end of checking if the user is in the session.
}else{
    header("Location:login");
}

?>

<?php
if(isset($_GET['fail']) && $_GET['fail'] == 1){
  echo "<div><h2 style=\"border: 2px solid black;\">Reminder: File upload incorrect :\
        <h5><a style=\"text-decoration: underline;\" href=\"fileUpload1\">Close X</a></h5></h2></div>";
}

if(isset($_GET['fail']) && $_GET['fail'] == 2){
  echo "<div><h2 style=\"border: 2px solid black;\">Reminder: File name error :/
        <h5><a style=\"text-decoration: underline;\" href=\"fileUpload1\">Close X</a></h5></h2></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#0b1a33"/>
  <title>Terrapin Exhchange - Upload</title>
  <link rel="icon" href="images/icon2.jpg">
  <link rel="apple-touch-icon" href="images/icon2.jpg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<style>
*{
  box-sizing: border-box;
}

.closebtn{
  margin-left: 8px;
  float: right;
  font-size: 25px;
  line-height: 25px;
  cursor: pointer;
}

body{
  font-family: 'Courier New', Courier, monospace;
  height: 100%;
  margin: 0;
  background-color: rgb(243, 246, 247);
  width: 100%;
  padding: 0;
}

.alert-box{
  padding: 15px;
  margin-bottom: 20px;
  display: none;
}

::-webkit-scrollbar{
  width: 10px;
}

::-webkit-scrollbar-track{
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb{
  background: rgb(107, 106, 106);
}

::-webkit-scrollbar-thumb:hover{
  background: rgb(51,51,51);
}

.page-container{
  position: relative;
  min-height: 100%;
  text-align:center;
}

.content-wrap{
  padding-bottom: 2.5rem;
}

h2{
  padding: 2rem;
}

div{
  margin-top: 0;
  margin-bottom: 2%;
  text-align:center;
}
label{
  /* font-family: Arial; */
  display: inline-block;
  width: 150px;
  padding: 1rem;
  text-align: left;
  font-size: 18px;
  text-align: center;
  border: 2px black solid;
  border-radius: 25px;
}

label:hover{
 background-color: yellow;
}

.hideFile{
  display: none;
  /* font-family: Arial; */
  margin-top: 2%;
  width: 150px;
  font-size: 15px;
  text-align: center;
  margin-left:auto;
  margin-right: auto;
  margin-bottom: 1rem;
}

input[type=submit]{
  font-family: 'Courier New', Courier, monospace;
  background-color: black;
  color: white;
  border: 2px solid black;
  padding: 10px 20px;
  text-align:center;
  display: inline-block;
  margin-left: auto;
  margin-right: auto;
  width: 150px;
  font-size: 15px;
  border-radius: 10px;
  margin-top: 2%;
}

input[type=submit]:hover{
  background-color: rgb(136, 136, 136);
}

.account{
  /* font-family: Arial; */
  background-color: black;
  color: white;
  text-decoration: none;
  border: 2px solid black;
  padding: 10px 20px;
  text-align: center;
  display: inline-block; 
  margin-left: auto;
  margin-right: auto;
  width: 150px;
  font-size: 15px;
  border-radius: 10px;
  margin-top: 1%;
  margin-bottom: 1%;
}

.account:hover{
  background-color: yellow;
  color: black;
}

footer{
  position: absolute;
  bottom: 20px;
  width: 100%;
  height: 2.5rem;
  font-size: 80%;
}

.copyright-text{
  color: black;
  padding-top: 10px;
  display: table;
  text-align: center;
  margin-left: auto;
  margin-right: auto;
}

li{
  text-align: left;
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

.big-container{
  display: flex;
  padding: 0rem;
  margin: 0;
  flex-wrap: wrap;
  align-content: space-around;
  flex-direction: row;
}

hr{
  margin-top: 10px;
}

.container{
  border: 1px black solid;
  font-size: 80%;
  background-color: white;
  font-size: 100%;
  padding: 1rem;
  margin-left: 1rem;
  margin-right: 1rem;
  margin-top: 0%;
  height: fit-content;
  border-radius: 15px;
  box-shadow: 0 4px 10px 0 rgba(0,0,0,0.2);
  width: 100%;
  margin-bottom: 5%;
}

h1{
  background-color: rgb(19,19,19);
  color: white;
  border-radius: 15px;
  text-align: center;
  font-size: 140%;
  padding: 8px 10px;
  margin-left: 2rem;
  margin-top: 2%;
}

@media screen and (max-width: 600px){
  .page-container{
    font-size:90%;
  }
  .account{
    padding: 8px 16px;
  }
  label{
    padding: 0.5rem;
  }
}

@media screen and (max-width: 400px){
  .page-container{
    font-size: 70%;
  }
  label{
   padding: 0.3rem;
  }
}

#myProgress{
  font-size: 48px;
  display:none;
}

/*#myBar{
  color: white;
  font: bold;
  width: 0%;
  height: 25px;
  background-color: rgb(50,154,255);
}*/
</style>
<div class="page-container">
 <div class="content-wrap">
  <div class="big-container">
   <h1>File Upload</h1>
     <div class="container">
       <h3><i class="fa fa-warning"></i> The max upload is 9 files at a time, no larger than 40 MB</h3>
	<h5>Only .gif, .pdf, .png, .jpg, and .txt are allowed.</h5>
	<form action="" method="post" enctype="multipart/form-data">
	  <div>
  	    <label for="fileToUpload"><strong>Click Here</strong>
		<input type="file" class="hideFile" name="fileToUpload[]" id="fileToUpload" multiple onchange="javascript:updateList()"/>
	    </label>
	  </div>
          <div id="myProgress"><div id="myBar"><i class="fa fa-refresh fa-spin"></i></div></div>
	  <hr>

	  <div id="fileList"></div>

	  <div>
	    <input id="submit" type="submit" value="Upload-Yes &#x2714;" name="submit" onclick="move()">
	  </div>
       </form>
     <div><a href="fileUpload1" class="account">Clear &#x274C;</a></div>
   </div>

    <h1 style="margin-top: 10px;">Hero Return</h1>
    <div class="container">
      <a href="manager" class="account">Account <i class="fa fa-user-circle"></i></a>
      <a href="index" class="account">Home <i class="fa fa-home"></i></a>
      <a href="myPictures" class="account">Gallery <i class="fa fa-image"></i></a>
      <a href="myPDFs" class="account">PDFs <i class="fa fa-file-pdf-o"></i></a>
   </div>
  </div>
 </div>
  <footer>
  <hr>
    <div>
       <p class="copyright-text">Copyright &copy; 2021 All Rights Reserved by <a style="color: black" href="#" target="_blank">Jerry D. Li</a></p>
    </div>
  </footer>
</div>
</body>
<script>
updateList = function(){
  var input = document.getElementById("fileToUpload");
  var output = document.getElementById("fileList");
  for (var i = 0; i < input.files.length; ++i){
    output.innerHTML += '<li style="margin-left:25%; padding:0.5rem; word-break: break-all;">' + input.files.item(i).name + '</li>';
  }
}
</script>

<script>
function move(){
  if(document.getElementById("myProgress").style.display="none"){
  document.getElementById("myProgress").style.display="block";
  }
}
</script>

<script>
$("#alert-box").fadeIn(2000).delay(1500).fadeOut(2000);
</script>
</html>
