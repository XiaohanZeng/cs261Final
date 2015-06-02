
function populateTrendingPictures(){
	
	$.ajax({
	method: "GET",
    url: "php/googleTrend.php",
    dataType: "json",
    success: function (returndata)
    {
		for (i=0; i<returndata.length; i++) {
			var url = "https://www.googleapis.com/customsearch/v1?cx=007306240209605891397%3Ah9qucp1qv8g&q=" + encodeURIComponent(returndata[i]) + "&num=3&key=AIzaSyDqHSQNdJfxBFIvTmwHfpBR2HWo4FZ7aOI";
			
			getImageLink(url, returndata[i], i);
		}
    }  
});
}

function getImageLink(url, title, index){
	$.ajax({
	method: "GET",
    url: url,
	title: title,
	index: index,
    dataType: "json",
    success: function (returndata)
    {
		var item = findValue('cse_thumbnail', returndata);
		var imgUrl = findValue('src', item);
		var imgPageLink = findValue('link', returndata);
		if (imgUrl !== null &&  imgPageLink!== null)
		{
			var item = constructNewPhotoItem(this.title, imgUrl, imgPageLink);
			var rows = document.getElementById('photoContainer').getElementsByClassName("row");
			var lastRow = rows[rows.length-1];
			if (lastRow == null || lastRow.children.length >= 3)
			{
				var new_row = document.createElement( 'div' );
				new_row.className = "row";
				document.getElementById('photoContainer').appendChild(new_row);
				lastRow = new_row
			}
			lastRow.appendChild(item);
			console.log(this.title);
			console.log(imgUrl);
			console.log(imgPageLink);
		}
    }  
});

// Construct a div that contains a photo
function constructNewPhotoItem(title, imgUrl, imgPageLink)
{
	var new_item = document.createElement( 'div' );
	new_item.className = "col-md-4 portfolio-item";
	
	var photo_div = document.createElement( 'a' );
	photo_div.href = imgPageLink;
	var img = document.createElement( 'img' );
	img.className = "img-responsive";
	img.src = imgUrl;
	photo_div.appendChild(img);
	new_item.appendChild(photo_div);
	
	var title_container = document.createElement( 'h3' );
	var title_link = document.createElement( 'a' );
	title_link.href = imgPageLink;
	title_link.title = title;
	var titleText = document.createTextNode(title);
	title_link.appendChild(titleText);
	title_container.appendChild(title_link);
	new_item.appendChild(title_container);
	
	var pin_link = document.createElement( 'a' );
	pin_link.className = "floatMenu";
	pin_link.onclick = "popWindow()";
	var pinText = document.createTextNode("click to Pin");
	pin_link.appendChild(pinText);
	pin_link.title = "click to Pin";
	new_item.appendChild(pin_link);
	
	return new_item;
	
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


}
