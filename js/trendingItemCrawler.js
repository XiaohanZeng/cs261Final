
function populateTrendingPictures(){
	var url = "https://www.googleapis.com/customsearch/v1?cx=007306240209605891397%3Ah9qucp1qv8g&q=American+Idol&num=5&key=AIzaSyDqHSQNdJfxBFIvTmwHfpBR2HWo4FZ7aOI";
	temp(url);
}

function findValue(needle, array) { 
    var value = null;

    for (var key in array) {
		
		if (key == needle) {
            return array[key];
        }
		else if (typeof array[key] == "object"){	
			value = findValue(needle, array[key]);
			if (value !== null)
			{
				return value;
			}
		}
    }

    return value;    
} 

function temp(url){
	$.ajax({
	method: "GET",
    url: url,
    dataType: "json",
    success: function (returndata)
    {
        console.log(returndata);
		var item = findValue('cse_thumbnail', returndata);
		var imgUrl = findValue('src', item);
		var imgPageLink = findValue('link', returndata);
		console.log(imgPageLink);
    }  
});
}
