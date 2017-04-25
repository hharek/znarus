$("#search .std_button").click(function()
{
	window.location.hash = "#_seo/redirect?from=" + $("#search input[name=from]").val();
});

$("#search input").keyup(function(e)
{
	if(e.keyCode === 13)
	{
		window.location.hash = "#_seo/redirect?from=" + $("#search input[name=from]").val();
	}
});