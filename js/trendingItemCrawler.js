
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



