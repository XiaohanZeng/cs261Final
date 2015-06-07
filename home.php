<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include './php/getdata.php';

$user ='root';
$pass='';
$db='340final';


$mysqli=new mysqli('localhost',$user,$pass,$db) or die("Unable to connect");
//session_start();

    if(isset($_REQUEST['username'])) 
	{
		$newName = $_REQUEST['username'];
		if(!isset($_SESSION['username']))  
		{
			$_SESSION['username'] = $newName;
		}
		elseif($_SESSION['username'] != $_REQUEST['username'])
		{
			
			session_unset();
			session_destroy();
			$_SESSION['username'] = $newName;		
		}
    }
	else 
	{
		// check if user has logged in
		if (!isset($_SESSION['username']))
		{
			header("Location: login.php"); /* Redirect browser */
			exit();
		}

     
    }
	addNewUser();

	function addNewUser()
	{
		global $mysqli;
		$name = $_SESSION['username'];
		$all = $mysqli->prepare("SELECT Users_Id FROM users WHERE user_name LIKE '$name' ");
		$all->execute();
		$result = $all->get_result();
		if($result->num_rows == 0)
		{
			$all = $mysqli->prepare("INSERT INTO users (user_name) VALUES('$name')");
			$all->execute();
			setDefaultFolder();
		}
	}
	function setDefaultFolder()
	{
		global $mysqli;
		$name = 'default Folder';
		$userID = getUserId();	
		$all = $mysqli->prepare("INSERT INTO folders (Folders_Name,Users_Id) VALUES('$name','$userID')");
		$all->execute();

	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/3-col-portfolio.css" rel="stylesheet">
	 <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" href="css/jquery-ui.min.css">
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/trendingItemCrawler.js"></script>
	<script src="js/finalScript.js"></script>

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              
                <a class="navbar-brand" href="#">Pin Pictures</a>
				
            </div>
			<div id='linkhome'>
			<a href="folderPage.html">
			<?php
				echo "Go to".$_SESSION['username']."'s folder";
			?>
			</a>
			<a href="logout.php"> logout </a>
            <!-- Collect the nav links, forms, and other content for toggling -->
            
            <!-- /.navbar-collapse -->
			</div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">All pictures in DataBase
				<div id='intro'>
                    <p>you can pin/save any of these pictures into your folder or add tags to it</p>
					<div>
                </h1>
            </div>
        </div>
		<!--popWindow-->
		<div id="dialog" style="display:none">
			<script type="text/javascript">
			makeRequest('action=updateSelection');
			</script>
			<div  id="myDiv" >
			
				<img class="pinImage" ><br>
				<P> create a new folder to save it.</p>
				<form id="addForm">
				New Folder Name:<input type="text" name="folderName"><br>
				</form>
				<button onclick="addNew()" value="Submit" id="addNewFolder"> Add</button><br>
				<p>Please choose the folder here.</p>
				<select id="dropDown">
				</select>
				<br>
				
				<form id="addTags">
				Add Tags:<input type="text" name="tagName"><br> 
				</form>
				<button onclick="addTag()" value="Submit" id="addNewTags"> Pin</button><br>
				
			
			</div>
			<!--<div value='tag'>
			<p>please add the tag you need. Using"#"to divide different tags.</p>
			<form action='#'>
			Tags:<input type="text" name="TagName"> 
			<input type="submit" value="Submit"><br>
			</form>
			</div>-->
		</div>
        <!-- /.row -->
		
		<div  id="photoContainer">
			<div id='loadingmessage' style='display:none'>
			  <img src='./pictures/loading.gif'/>
			</div>
		</div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

   
</body>