$(function()
{
	$(".slider_button > div").click(function()
	{
		var left = (parseInt( $(this).attr("number") ) - 1) * 300;
		$("#slider_all").animate({"margin-left" : -left});
		
		$(".slider_button > div.active").removeClass("active");
		$(this).addClass("active");
	});
});