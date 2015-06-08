
window.onload = function()
{
    makeRequest('action=init');
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
            var elem = document.getElementById('catchPhotos')
            elem.innerHTML = response;
          //  localStorage.setItem('localvideo',response);
            //addListeners();
        }

    }
    xmlhttp.open("GET",'videoTrack.php?' + statement,true);
    xmlhttp.send();
}