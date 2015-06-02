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
					
function popWindow(){
	
	$("#dialog").dialog('open');
	
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
	for(i=0; i<res.length; i++)
	{
		var temp = res[i];
		makeRequest('action=addTag&tag='+temp);
	}
	
	
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
	
    xmlhttp.open("GET",'php/getData.php?' + statement,true);
    xmlhttp.send();
	
}
