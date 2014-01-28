/* Выделить все админки модуля для текущей группы */
$("input[name^=mod]").click(function()
{
	var ar = $(this).attr("name").split("_");
	mod_group(ar[1], ar[2], $(this).is(":checked"));
});

function mod_group(mod, group, checked)
{
	$(".priv tbody tr[mod=" + mod + "] input").each(function()
	{
		if($(this).attr("name").split("_")[2] === group)
		{	
			if(checked === true)
			{
				$(this).prop("checked", true);
			}
			else if(checked === false)
			{
				$(this).prop("checked", false);
			}
		}
	});
}

/* Спрятать админки */
$(".priv tbody tr").each(function()
{
	if($(this).hasClass("priv_title") === false)
	{
		$(this).hide();
	}
});

$("tr.priv_title td[id^=priv_mod_]").click
(
	function()
	{
		$(".priv tbody tr[mod=" + $(this).attr("id").split("_")[2] + "]").toggle();
		
		if($(this).find("span").html() === "+")
		{$(this).find("span").html("-");}
		else
		{$(this).find("span").html("+");}
	}
);
