$("#route_std_home").click(function()
{
	$("input[name=Home_Module]").val("_service");
	$("input[name=Home_Exe]").val("home");
	$("input[name=Home_Admin_Url]").val("#_service/home");
});

$("#route_std_404").click(function()
{
	$("input[name=404_Module]").val("_service");
	$("input[name=404_Exe]").val("404");
	$("input[name=404_Admin_Url]").val("#_service/404");
});

$("#route_std_403").click(function()
{
	$("input[name=403_Module]").val("_service");
	$("input[name=403_Exe]").val("403");
	$("input[name=403_Admin_Url]").val("#_service/403");
});

