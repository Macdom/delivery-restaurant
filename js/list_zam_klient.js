function Przekieruj(idZ){
	window.location="../main/kl_sz.php?idZ="+idZ;
}
	
$(document).ready(function(){
	$("tr.element").mouseenter(function(){
		$(this).addClass("highlight");
	});
	$("tr.element").mouseleave(function(){
		$(this).removeClass("highlight");
	});
});