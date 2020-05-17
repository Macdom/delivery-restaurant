function Przekieruj(id){
	window.location="gotowanie.php?id="+id;
}
	
$(document).ready(function(){
	$("tr.element").mouseenter(function(){
		$(this).addClass("highlight");
	});
	$("tr.element").mouseleave(function(){
		$(this).removeClass("highlight");
	});
});