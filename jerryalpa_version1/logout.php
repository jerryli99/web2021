<?php
   session_start();
   session_unset();
   session_destroy();

   // echo '<h1 style="text-align: center; border: 3px solid black; padding:center;">You are Logged out!</h1>';
   header('Location: index');
?>
