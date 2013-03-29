$(document).ready(function(){
	$("#loginform").submit(function(form){
		var user = $("input[name='user']").val();
		var pass = $("input[name='pass']").val();
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    	if (!re.test(user)) {
    		alert("E-mail введен не корректно");
    		return false;
    	}
		if (pass.length == 0) {
			alert("Вы не ввели пароль");
			return false;
		}
	});
	checkpage();
});
function checkpage() {
	$("nav a").each(function(){
		var link = $(this).attr("href");
		if (location.pathname == link || (location.pathname == '/index.php' && link == '/')) {
			$(this).addClass("active");
		}
	});
}
function showhide (id){
	if ($('#'+id).is(':hidden')) {
		$('#'+id).show();
	} else {
		$('#'+id).hide();
	}
}