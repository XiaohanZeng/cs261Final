$( document ).ready(function() {
		$("#dialog").dialog({
		autoOpen: false,
		show: 'slide',
		resizable: false,
		stack: true,
		height: '400',
		width: '400px',
		
	});		
	populateTrendingPictures();
});
					
function popWindow(element){
	

	var parent= element.parentElement;
	var imageUrl= parent.getElementsByTagName('img')[0].src;
	var h3Tag = parent.getElementsByTagName('h3')[0];
	var imgTitleTag = h3Tag.getElementsByTagName('a')[0];
	var title = imgTitleTag.textContent;
	var imagePageLink = imgTitleTag.href;
	
	var linkText = parent.getElementsByClassName('floatMenu')[0].textContent;
	if (linkText == "Click to Pin")
	{
		localStorage.setItem("imageUrl", imageUrl);
		localStorage.setItem("imagetitle",title);
		localStorage.setItem("imagePageLink", imagePageLink);	
		$("#dialog").dialog('open');
		makeRequest('action=updateFolderSelection');
		getSmallImg();
	}
	else
	{
		unPinPicture(title, imageUrl, imagePageLink);
		element.textContent = "Click to Pin";
	}
}

function unPinPicture(title, imageUrl, imagePageLink)
{
		makeRequest('action=unPinPicture&imageUrl='+imageUrl+'&title='+title+'&imagePageLink='+imagePageLink);
}

function getSmallImg()
{
	var imageUrl=localStorage.getItem("imageUrl");
	document.getElementsByClassName("pinImage")[0].src= imageUrl;
}

function addNew()
{
		var newFolderName=document.getElementById('addForm').elements['folderName'].value;
		

		if(newFolderName.length<255 && newFolderName.length>0 && checkUniqueName(newFolderName))
		{
			makeRequest('action=addNewFolder&Name=' + newFolderName);
		}
		else
		{
			var errorMessage1 ="";
			var errorMessage2 ="";
			var errorMessage3 ="";
			if(newFolderName.length >=225)
			{
				errorMessage1 ="invalid name input";
			}
			if(newFolderName.length<=0)
			{
				errorMessage2 = "name is a required field";
			}
			if(!checkUniqueName(newFolderName))
			{
				errorMessage3 = "not unique name";
			}
			
			window.alert(errorMessage1 + " " + errorMessage2 + " " + errorMessage3)		
	}

}

function addTag()
{
	var newTag=document.getElementById('addTags').elements['tagName'].value;
	//var folderGo=document.getElementById('dropDown')
	var res = newTag.split("#");
	var imageUrl=localStorage.getItem("imageUrl");
	var title=localStorage.getItem("imagetitle");
	var imagePageLink=localStorage.getItem("imagePageLink");
	var e = document.getElementById("dropDown");
	var selectedFolder = e.options[e.selectedIndex].value;
	
	makeRequest('action=addPicInfo&imageUrl='+imageUrl+'&title='+title+'&imagePageLink='+imagePageLink+'&selectedFolder='+selectedFolder);
	
	for(i=0; i<res.length; i++)
	{
		var temp = res[i];
		makeRequest('action=addTag&tag='+temp+
		'&imageUrl='+imageUrl+
		'&title='+title+
		'&imagePageLink='+imagePageLink+
		'&selectedFolder='+selectedFolder);
	}
	$("#dialog").dialog('close');
	changePinStatus();
	

}

function checkUniqueName(newName)
{
    var existName = document.getElementsByClassName("checkName");
    var length = existName.length;
    for(var i = 0; i < length; i++)
    {
        if (newName == existName[i].textContent)
            return false;
    }
    return true;
}
function makeRequest(statement)
{
	var xmlhttp;
	if(window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
	
    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var response = xmlhttp.responseText;
            var elem = document.getElementById('dropDown')
            elem.innerHTML = response;
        
        }

	}
	
    xmlhttp.open("GET",'php/getdata.php?' + statement,true);
    xmlhttp.send();
	
}

//change click to pin to unpin
function changePinStatus()
{
	$.ajax({
		method: "GET",
		url: "php/getdata.php",
		dataType: "json",
		data:{action:'upDatePinStatus'},
		success: function (returndata)
		{
			var onPageImages = document.getElementsByClassName('col-md-4 portfolio-item');
			for(var i = 0; i < returndata.length; i++)
			{
				for(var t = 0; t < onPageImages.length; t++)
				{
					var parent= onPageImages[t];
					var imageUrl= parent.getElementsByTagName('img')[0].src;
					var h3Tag = parent.getElementsByTagName('h3')[0];
					var imgTitleTag = h3Tag.getElementsByTagName('a')[0];
					var title = imgTitleTag.textContent;
					var imagePageLink = imgTitleTag.href;
					if(returndata[i].imgPageLink == imagePageLink && 
					returndata[i].imgUrl == imageUrl &&
					returndata[i].title == title)
					{
						parent.getElementsByClassName('floatMenu')[0].textContent = "unpin";
					}	
				}
			}
		}
	});	
}

