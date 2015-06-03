
function populateTrendingPictures(){
	
	$.ajax({
	method: "GET",
    url: "php/googleTrend.php",
    dataType: "html",
    success: function (returndata)
    {
		document.getElementById('photoContainer').innerHTML = returndata;
    }  
});
}

