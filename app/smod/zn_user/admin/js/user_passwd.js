$("#icon_eye").click(function()
{
	var input = $(".std_form input[name=Password]");
	if(input.attr("type") === "password")
	{
		input.attr("type","text");
	}
	else
	{
		input.attr("type","password");
	}
});