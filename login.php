<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
  </head>
  <body>
    <form id="loginForm" method="POST" action="home.html" onclick=homePageLogin()>
      <input type="text" name="username" value="Username">
      <input type="submit" value="Login">
    </form>


<?php
if(isset($_GET['logout']) && $_GET['logout']=='true')
{
	session_start();
	session_unset();
	session_destroy();
}
exit();
?>
  </body>
</html>