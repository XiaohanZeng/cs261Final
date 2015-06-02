<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');


$user ='root';
$pass='';
$db='340final';


$mysqli=new mysqli('localhost',$user,$pass,$db) or die("Unable to connect");

if(isset($_REQUEST['action']))
{
	$action_value = $_REQUEST['action'];
	if($action_value == "addNewFolder")
	{
		addNewFolder();
		buildSelection();
	}
	if($action_value == 'updateSelection')
	{
		buildSelection();
	}
	if($action_value == 'addTag')
	{
		echo "HIHI";
		addTag();
		buildSelection();
	}
	
}

function buildSelection()
{
	global $mysqli,$table;
	$all = $mysqli->prepare("SELECT DISTINCT Folders_Name FROM folders");
	$all->execute();
	$res = $all->get_result();
	echo '<br>';
	echo '<select id="dropDown">';
	
	while($row = $res->fetch_assoc()) //get it one by one
	{	
		echo '<option value="'.$row['Folders_Name'].'">'.$row['Folders_Name'].'</option>';
	}
	echo '</select>';
	
	echo '<button type="button" onclick="addNew()" id="addNewFolder">Add</button>';
	echo '<br>';
}

function addNewFolder()
{
	global $mysqli;
	$name = $_GET['Name'];
	
	$all = $mysqli->prepare("INSERT INTO folders (Folders_Name) VALUES('$name')");
	$all->execute();
	
}
function addTag()
{
	global $mysqli;
	$tag = $_GET['tag'];
	
	$all = $mysqli->prepare("INSERT INTO tags (Tags_Name) VALUES('$tag')");
	$all->execute();
	
}

?>