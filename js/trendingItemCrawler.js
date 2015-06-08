
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



