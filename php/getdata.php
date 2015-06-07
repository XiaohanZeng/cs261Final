<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');


$user ='root';
$pass='';
$db='340final';
session_start();

$mysqli=new mysqli('localhost',$user,$pass,$db) or die("Unable to connect");



if(isset($_REQUEST['action']))
{
	$action_value = $_REQUEST['action'];
	if($action_value == "addNewFolder")
	{
		$folderName = addNewFolder();
		buildSelection($folderName);
	}
	if($action_value == 'updateSelection')
	{
		buildSelection();
	}
	
	if($action_value == 'addUser')
	{		
		addNewUser();
	}
	if($action_value == 'init')
	{		
		init();
	}
	if($action_value == 'addPicInfo')
	{		
		addPicInfo();
		buildSelection();
	}
	if($action_value == 'addTag')
	{
		addTag();
		buildSelection();
	}
	if($action_value =='upDatePinStatus')
	{
		upDatePinStatus();
	}
	if($action_value == 'unPinPicture')
	{
		unPinPicture();
	}
	if($action_value == 'updateFolderSelection')
	{
		buildSelection();
	}
}

function buildSelection($addFolderName = NULL )
{
	global $mysqli,$table;
	$userId = getUserId();
	$all = $mysqli->prepare("SELECT Folders_Name FROM folders WHERE Users_Id LIKE '$userId'");
	$all->execute();
	$res = $all->get_result();
	echo '<br>';
	echo '<select id="dropDown">';
	
	while($row = $res->fetch_assoc()) //get it one by one
	{	
		if($row['Folders_Name'] == $addFolderName)
		{
			echo '<option value="'.$row['Folders_Name'].'" selected="selected">'.$row['Folders_Name'].'</option>';
		}
		else
		{
			echo '<option value="'.$row['Folders_Name'].'">'.$row['Folders_Name'].'</option>';
		}
	}
	echo '</select>';
	
	echo '<button type="button" onclick="addNew()" id="addNewFolder">Add</button>';
	echo '<br>';
}

function addNewFolder()
{
	global $mysqli;
	$name = $_GET['Name'];
	$userID = getUserId();
	//check if there is same folder name under this ID
	$all = $mysqli->prepare("SELECT Folders_Id FROM folders WHERE Folders_Name LIKE '$name' AND Users_Id LIKE '$userID'");
	$all->execute();
	$result = $all->get_result();
	if($result->num_rows == 0)
	{
		$all = $mysqli->prepare("INSERT INTO folders (Folders_Name,Users_Id) VALUES('$name','$userID')");
		$all->execute();
	}
	return $name;
	
}
function addTag()
{
	global $mysqli;
	$tag = $_GET['tag'];
	$userID = getUserId();
	$all = $mysqli->prepare("INSERT INTO tags (Tags_Name, Users_Id) VALUES('$tag','$userID')");
	$all->execute();
	$tagId = $all->insert_id;
	
	//set tagpictures table. get the tag id and picture id. since we know users id for each tag, we can find picturers under a users' tags.
	$imageUrl=$_REQUEST['imageUrl'];
	$title=$_REQUEST['title'];
	$imagePageLink=$_REQUEST['imagePageLink'];
	$imgId = getPictureId($title,$imageUrl,$imagePageLink);
	$all = $mysqli->prepare("INSERT INTO tagpictures (Tags_Id, P_Id) VALUES('$tagId','$imgId')");
	$all->execute();
	
}

function getUserId()
{
	global $mysqli;
	$userName = $_SESSION['username'];
	$all = $mysqli->prepare("SELECT Users_Id FROM `users` WHERE `user_name` LIKE '$userName' ");
	$all->execute();
	$result = $all->get_result();
	while ($row = $result->fetch_array(MYSQLI_NUM))
	{
		foreach ($row as $r);
		
	}
	return "$r";
}
function init()
{
	global $mysqli;
	$userId =getUserId();
	
	$all = $mysqli->prepare("SELECT Folders_Name FROM folders WHERE Users_Id LIKE '$userId'");//prepared statment
	$all->execute();// run the prepared statment,all save object
	$res = $all->get_result();
	while($row = $result->fetch_array(MYSQLI_NUM))	
	{
		echo'<p>'.$row.'</p>';
	}
}
function addPicInfo()
{
	global $mysqli;
	//PUT PICTURES INTO PICTURES FOLDER
	$imageUrl=$_REQUEST['imageUrl'];
	$title=$_REQUEST['title'];
	$imagePageLink=$_REQUEST['imagePageLink'];
	
	//check if there is a same pic in pictures
	$all = $mysqli->prepare("SELECT P_Id FROM pictures WHERE P_name LIKE '$title' AND imgUrl LIKE '$imageUrl' AND imgPageLink LIKE '$imagePageLink'");
	$all->execute();
	$result = $all->get_result();
	if($result->num_rows == 0)
	{
		$all = $mysqli->prepare("INSERT INTO pictures (imgUrl,P_name,imgPageLink) VALUES('$imageUrl','$title','$imagePageLink')");
		$all->execute();
		
	}
	
	//set pinPictures TABLE. CONNECT USER WITH PICTURES
	$userId =getUserId();
	$imgId = getPictureId($title,$imageUrl,$imagePageLink);
	$all = $mysqli->prepare("INSERT INTO pinpictures (Users_Id,P_Id) VALUES('$userId','$imgId')");
	$all->execute();
	
	// set pictures in folders relation inFolder.
	$folderName=$_REQUEST['selectedFolder'];
	echo $folderName;
	$folderId = getFolderId($folderName,$userId);
	echo $userId;
	$all = $mysqli->prepare("INSERT INTO infolder (Folders_Id,P_Id) VALUES('$folderId','$imgId')");
	$all->execute();	
	
}
function getPictureId($title, $imageUrl, $imagePageLink)
{
	global $mysqli;
	$all = $mysqli->prepare("SELECT P_Id FROM pictures WHERE P_name LIKE '$title' AND imgUrl LIKE '$imageUrl' AND imgPageLink LIKE '$imagePageLink'");
	$all->execute();
	$result = $all->get_result();
	if($result->num_rows == 0)
	{
		return -1;
	}
	while ($row = $result->fetch_array(MYSQLI_NUM))
	{
		foreach ($row as $r);
		
	}
	return "$r";
}

function getFolderId($folderName,$userID)
{
	global $mysqli;
	$all = $mysqli->prepare("SELECT Folders_Id FROM folders WHERE Folders_Name LIKE '$folderName' AND Users_Id LIKE '$userID'");
	echo $folderName;
	echo $userID;
	$all->execute();
	$result = $all->get_result();
	if($result->num_rows == 0)
	{
		return -1;
	}
	while ($row = $result->fetch_array(MYSQLI_NUM))
	{
		foreach ($row as $r);
		
	}
	return "$r";
	
}

function upDatePinStatus()
{
	global $mysqli;
	$userName = $_SESSION['username'];
	$all = $mysqli->prepare("SELECT P_name, imgUrl,imgPageLink
	FROM users 
	JOIN pinpictures ON users.Users_Id = pinpictures.Users_Id 
	JOIN pictures ON pinpictures.P_Id = pictures.P_Id 
	WHERE user_name = '$userName'");
	$all->execute();
	$result = $all->get_result();
	$imageItemArray = [];
	while ($row = $result->fetch_array(MYSQLI_NUM))
	{
			$P_name = $row[0];
			$imgUrl = $row[1];
			$imgPageLink = $row[2];
			$imageItem = (object) array('title' => $P_name,
										'imgUrl' => $imgUrl,
										'imgPageLink' => $imgPageLink);
			$imageItemArray[] = $imageItem;		
	}
	echo json_encode($imageItemArray);
}

function unPinPicture()
{
	global $mysqli;
	$userId = getUserId();
	$imageUrl=$_REQUEST['imageUrl'];
	$title=$_REQUEST['title'];
	$imagePageLink=$_REQUEST['imagePageLink'];
	$pictureId = getPictureId($title, $imageUrl, $imagePageLink);
	$all = $mysqli->prepare("DELETE FROM pinpictures WHERE Users_Id = '$userId' AND P_Id = '$pictureId'");
	$all->execute();
}

?>