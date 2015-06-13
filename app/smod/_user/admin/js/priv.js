/* Флаги для модулей */
$("#priv .module input").each(function()
{
	module_checkbox($(this));
});

$("#priv .admin input").click(function()
{
	var col = $(this).parent()[0].cellIndex + 1;
	var tr = $(this).parents(".admin");
	while (true)
	{
		tr = tr.prev();
		if (tr.attr("class") === "module")
		{
			module_checkbox(tr.find("td:nth-child(" + col + ") input"));
			break;
		}
	}
});

/* Показать админки по модулю */
$("#priv .module td:nth-child(1)").click(function()
{
	var tr = $(this).parent();
	
	if (tr.find("span").text() === "+")
	{
		tr.find("span").text("-");
	}
	else
	{
		tr.find("span").text("+");
	}
	
	while (true)
	{
		tr = tr.next();
		if (tr.attr("class") === "admin")
		{
			tr.toggle();
		}
		else
		{
			break;
		}
	}
});

/* Выделить все админки для модуля и группы */
$("#priv .module input").click(function ()
{
	var col = $(this).parent()[0].cellIndex + 1;
	var tr = $(this).parents(".module");
	while (true)
	{
		tr = tr.next();
		if (tr.attr("class") === "admin")
		{
			if ($(this).prop("checked") === true)
			{
				tr.find("td:nth-child(" + col + ") input").prop("checked", true);
			}
			else
			{
				tr.find("td:nth-child(" + col + ") input").prop("checked", false);
			}
		}
		else
		{
			break;
		}
	}
});

/**
 * Флаг для модуля
 */
function module_checkbox(input)
{
	var col = $(input).parent()[0].cellIndex + 1;
	var tr = $(input).parents(".module");
	
	var input_count = 0;
	var input_checked_count = 0;
	
	while (true)
	{
		tr = tr.next();
		if (tr.attr("class") === "admin")
		{
			input_count ++;
			if (tr.find("td:nth-child(" + col + ") input").prop("checked") === true)
			{
				input_checked_count ++;
			}
		}
		else
		{
			break;
		}
	}
	
	/* Не помечать */
	if (input_checked_count === 0)
	{
		$(input).prop("checked", false);
		$(input).prop("indeterminate", false);
	}
	/* Пометить */
	else if (input_checked_count === input_count)
	{
		$(input).prop("checked", true);
		$(input).prop("indeterminate", false);
	}
	/* Поменить как неопределённо */
	else if (input_checked_count !== 0 && input_checked_count !== input_count)
	{
		$(input).prop("checked", true);
		$(input).prop("indeterminate", true);
	}
}