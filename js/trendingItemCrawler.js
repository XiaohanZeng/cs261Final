
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

