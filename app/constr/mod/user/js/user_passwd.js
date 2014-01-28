$("#icon_eye").click(function()
{
	var input = $(".forma input[name=Password]");
	if(input.attr("type") === "password")
	{
		input.attr("type","text");
	}
	else
	{
		input.attr("type","password");
	}
});