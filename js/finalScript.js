$( document ).ready(function() {
		$("#dialog").dialog({
		autoOpen: false,
		show: 'slide',
		resizable: false,
		stack: true,
		height: '400',
		width: '400px',
		
	});		
});
					
function popWindow(){
	
	$("#dialog").dialog('open');
}
