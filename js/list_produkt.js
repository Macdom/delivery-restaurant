function Przekieruj(id){
	window.location="../update/produkt.php?id="+id;
}
	
$(document).ready(function(){
	$("tr.element").mouseenter(function(){
		$(this).addClass("highlight");
	});
	$("tr.element").mouseleave(function(){
		$(this).removeClass("highlight");
	});
});