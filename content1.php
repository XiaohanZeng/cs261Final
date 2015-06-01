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
    if(isset($_REQUEST['username'])) 
	{
		$newName = $_REQUEST['username'];
		if(!isset($_SESSION['username']))  
		{
			$_SESSION['username'] = $newName;
			$_SESSION['count'] = 0;
			echo "Hello ".$newName. " you have visited this page ".$_SESSION['count']. " times".".<br/>";
			echo "Click here to logout ";
			echo '<a href="login.php?logout=true">here</a>.';
			$_SESSION['count'] = $_SESSION['count']+1;
			echo "<br>"." click  "."<a href=\"content2.php\">here</a>"." to go to content2";  
		}
		elseif($_SESSION['username'] != $_REQUEST['username'])
		{
			echo $_SESSION['username']." has logged in";
			echo "<br>Click ";
			echo '<a href="login.php?logout=true">here</a>  to logout.';
		}
		else
		{
			echo "Hello ".$newName. " you have visited this page ".$_SESSION['count']. " times".".<br/>";
			echo "Click here to logout ";
			echo '<a href="login.php?logout=true">here</a>.';
			$_SESSION['count'] = $_SESSION['count']+1;
			echo "<br>"." click  "."<a href=\"content2.php\">here</a>"." to go to content2";  
		}
    }
	else 
	{
		// check if user has logged in
		if (isset($_SESSION['username']))
		{
			echo "Hello ".$_SESSION['username']. " you have visited this page ".$_SESSION['count']. " times".".<br/>";
			echo "Click here to logout ";
			echo '<a href="login.php?logout=true">here</a>.';
			$_SESSION['count'] = $_SESSION['count']+1;	
			echo "<br>"." click  "."<a href=\"content2.php\">here</a>"." to go to content2"; 
		}
		else
		{
			echo "A username must be entered. Click here to return to the login screen ";
			echo "<a href=\"login.php\">here</a>";
		}
     
    }
	
    ?>
  </body>
</html>