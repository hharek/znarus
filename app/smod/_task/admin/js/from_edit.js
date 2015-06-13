date_require_show();

$("form input[name=Date_Require_Select]").click(date_require_show);

function date_require_show()
{
	if($("form input[name=Date_Require_Select]").is(":checked") === true)
	{
		
		$("form input[name=Date_Require_Date]").prop("disabled", false);
		$("form input[name=Date_Require_Time]").prop("disabled", false);
	}
	else
	{
		$("form input[name=Date_Require_Date]").prop("disabled", true);
		$("form input[name=Date_Require_Time]").prop("disabled", true);
	}
}