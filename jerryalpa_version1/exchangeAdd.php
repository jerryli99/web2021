<?php
//this file is in server 1: developer.jerryalpa.com
  date_default_timezone_set("America/New_York");
  session_start();
  session_regenerate_id();
  require_once('connectDB.php');
  if(!isset($_SESSION['this_user'])){
    header("Location: login");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php
    echo '<script src="https://cdn.tiny.cloud/1/mwixwaud0zgsik09qxt855ulfg62bsjm2u5q762apwkrg66b/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>';
  ?>
  <script>
    tinymce.init({
    selector: "#default",
    plugins: "advlist lists spellchecker autoresize wordcount",
    min_height: 200,
    fullpage_default_font_size: '11px',
    menubar: false,
    toolbar: "bold italic underline | bullist numlist | spellchecker"
    });
  </script>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/icon2.jpg">
  <link rel="apple-touch-icon" href="images/icon2.jpg">
  <link rel="stylesheet" href="stylePages/exchangeAdd.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Terrapin Exchange - Item/Skill Upload</title>
</head>

<body>
  <div class="page-container">
    <div class="content-wrap">
      <div class="big-container">
        <h2>Exchange Post</h2>
          <div class="container">
            <form enctype="multipart/form-data" method="POST" 
                  action=<?php 
                            echo "https://product.jerryalpa.com/productPost.php";
                          ?>>
              <h4 name="this_user"><?php echo "Hi~ " . $_SESSION['this_user']?></h4>
              <hr>
              
              <!-- the purpose of this is to send the user name to server 2. -->
              <input style="display: none;" type="text" name="this_user" 
                      value=<?php echo $_SESSION['this_user'] ?>>

              <!-- the purpose of this is to send the user's product_id (generated) to server 2. -->
              <input style="display: none;" type="text" name="productID" 
                      value=<?php echo $_SESSION['this_user'] . time(); ?>>

	      <div style="text-align:center;">
	      <h5><i class="fa fa-exclamation-circle"></i> File size needs to be < 10 MB </h5> 
              <h5> Only .gif, .png, and .jpg are allowed.</h5>
              <h5> Need to submit an image, else cannot post :)</h5></div>
              
              <div>
                <label for="file" class="fileUpload"> Upload One Image <i class="fa fa-upload"></i>
                <input type="file" name="file" id="file" class="file" onchange="javascript:updateList()"/></label>
              </div>

              <div style="text-align: center;"id="fileList"></div>

              <label>Category: 
              <select name="category" required>
                <option value="">Select...</option>
                <option value="Classnotes">Classnotes</option>
                <option value="Books">Books</option>
                <option value="Electronics">Electronics</option>
                <option value="Furnitures">Furnitures</option>
                <option value="Skills">Skills</option>
              </select>
              </label>

              <div class="exchangeLocation">
                <label>Location<i class='fa fa-map-marker'></i>:</label>
                  <input type="text" name="location" id="location" 
                         placeholder=" physical location or online?" required/>
              </div>
	      <h5><mark>For Efficient Exchange: Describe what you have and what you want.</mark></h5>
              <div>
                <textarea id='default' class="userInput" name="description">Description-></textarea>
              </div>

              <div>
                <input type="submit" value="Click Here to Submit" name="submit">
              </div>
            </form>
            <!-- end of container -->
          </div>
          <h2>Hero Return</h2>
          <div class="container">
            <a class="back" href="index">Home <i class="fa fa-home"></i></a>
            <a class="back" href="manager">Account <i class='fa fa-user-circle'></i></a>
          </div>
          <!-- end of big-container -->
        </div>
        <!-- end of content-wrap -->
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
        <!-- end of page-container -->
  </div>
</body>
<script>
updateList = function(){
  var input = document.getElementById("file");
  var output = document.getElementById("fileList");
  output.innerHTML = '<li style="text-align: center; word-break: break-all;">' + 'File Name is: ' + input.files.item(0).name + '</li>';
}
</script>
</html>
