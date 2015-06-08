
function populateTrendingPictures(){
	
	$('#loadingmessage').show(); 
	$.ajax({
	method: "GET",
    url: "php/googleTrend.php",
    dataType: "html",
    success: function (returndata)
    {
		$('#photoContainer').html(returndata);
		$('#loadingmessage').hide();
		changePinStatus();
    }  
});
}

function populateUserPictures(){
	
	$('#loadingmessage').show(); 
	$.ajax({
	method: "GET",
    url: "php/getdata.php",
    dataType: "html",
	data:{'action':'getUserPin'},
    success: function (returndata)
    {
		$('#UserPhotoContainer').html(returndata);
		$('#loadingmessage').hide();
    }  
});
}

function unPin(element){
	var parent= element.parentElement;
	var imageUrl= parent.getElementsByTagName('img')[0].src;
	var h3Tag = parent.getElementsByTagName('h3')[0];
	var imgTitleTag = h3Tag.getElementsByTagName('a')[0];
	var title = imgTitleTag.textContent;
	var imagePageLink = imgTitleTag.href;
	
	$.ajax({
	method: "GET",
    url: "php/getdata.php",
    dataType: "html",
	data:{'action':'unPinPicture', 'imageUrl': imageUrl, 'title': title, 'imagePageLink':imagePageLink},
    success: function (returndata)
    {
		populateUserPictures();
    }  
	});
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



