/* Отобразить вкладку */
if (_hash_parse(window.location.hash).after.substr(1, 14) === "tab_structure_")
{
	var type = _hash_parse(window.location.hash).after.substr(15);
	
	_tab("structure");
	structure_element(type);
}

if (_hash_parse(window.location.hash).after.substr(1) === "tab_structure")
{
	_tab("structure");
	structure_element("all");
}

/* Событие на кнопки элементов структуры */
$("#panel .std_button").click(function()
{
	structure_element($(this).attr("id").substr(7));
});

/* Показать элемент структуры */
function structure_element(type)
{
	var element_all = ["bin","param","admin","exe","inc","proc","text","ajax"];
	
	if(type !== "all")
	{
		if($("#structure_" + type).length === 0)
		{
			return false;
		}
	
		for(var i = 0; i < element_all.length; i++)
		{
			$("#structure_" + element_all[i]).hide();
		}
		$("#structure_" + type).show();
		$("#structure_" + type + " .std_button").show();
		window.location.hash = _hash_parse(window.location.hash).url + "#tab_structure_" + type;
	}
	else
	{
		for(var i = 0; i < element_all.length; i++)
		{
			$("#structure_" + element_all[i]).show();
		}
		
		$(".structure_element .std_button").hide();
		window.location.hash = _hash_parse(window.location.hash).url + "#tab_structure";
	}
}

/* Влияние на доступ */
show_access_type();

$("input[type=checkbox][name=Access_Enable]").click(show_access_type);

function show_access_type()
{
	if ($("input[type=checkbox][name=Access_Enable]").prop("checked") === true)
	{
		$("#tr_access_type").show();
	}
	else
	{
		$("#tr_access_type").hide();
	}
}
