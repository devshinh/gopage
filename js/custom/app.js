$(document).ready(function() {
   $("header .login a.username").click(function () {
   	$(this).siblings('a').toggleClass('hide');
   	return false;
   });
});

