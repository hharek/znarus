date_show("Require");
$("form input[name=Date_Require_Select]").click(function()
{
	date_show("Require");
});

date_show("Done");
$("form input[name=Date_Done_Select]").click(function()
{
	date_show("Done");
});

date_show("Fail");
$("form input[name=Date_Fail_Select]").click(function()
{
	date_show("Fail");
});

function date_show(type)
{
	if($("form input[name=Date_" + type + "_Select]").is(":checked") === true)
	{
		
		$("form input[name=Date_" + type + "_Date]").prop("disabled", false);
		$("form input[name=Date_" + type + "_Time]").prop("disabled", false);
	}
	else
	{
		$("form input[name=Date_" + type + "_Date]").prop("disabled", true);
		$("form input[name=Date_" + type + "_Time]").prop("disabled", true);
	}
}