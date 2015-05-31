<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
session_start();
?>
<!DOCTYPE html>
<html>
  <head><title>Login Result</title></head>
  <body>
	<?php
		if (isset($_SESSION['username']))
		{
			$newName = $_SESSION['username'];
			echo "Hello ".$newName;
			echo "<br>"." click  "."<a href=\"content1.php\">here</a>"." to go to content1";  
		}
		else
		{
			echo "A username must be entered. Click here to return to the login screen ";
			echo "<a href=\"login.php\">here</a>";
		}
		
     ?>
  </body>
</html>