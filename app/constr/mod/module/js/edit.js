/* Клики на вкладке */
$("#knopka_data" ).click(function()
{
	show_type("data");
});

$("#knopka_creator" ).click(function()
{
	show_type("creator");
});

$("#knopka_structure" ).click(function()
{
	show_type("structure");
});

/* Открытие вкладки */
if($.inArray(hash_hash, ["#data","#creator","#structure"]) !== -1)
{
	show_type(hash_hash.substr(1));
}

/* Переход к элементам */
if(hash_hash.substr(1, 10) === "structure_")
{
	show_type("structure");
	structure_element(hash_hash.substr(11));
}

/* Событие на кнопки элементов структуры */
$("#panel .knopka").click(function()
{
	structure_element($(this).attr("id").substr(7));
});

/* Показать вкладку */
function show_type(type)
{
	var type_all = ["data","creator","structure"];
	for(var i=0; i<type_all.length; i++)
	{
		if(type === type_all[i])
		{
			$("#knopka_" + type).css("background-color", "#f6e8b6");
			$("#okno_" + type).show();
		}
		else
		{
			$("#knopka_" + type_all[i]).css("background-color", "");
			$("#okno_" + type_all[i]).hide();
		}
	}
	
	if(type === "structure")
	{
		$(".structure_element .knopka").hide();
	}
	
	window.location.hash = "#" + hash_url + "#" + type;
}

/* Показать элемент структуры */
function structure_element(type)
{
	var element_all = ["phpclass","param","admin","exe","inc","text"];
	
	if(type !== "all")
	{
		if($("#structure_" + type).length === 0)
		{return false;}
	
		for(var i = 0; i < element_all.length; i++)
		{
			$("#structure_" + element_all[i]).hide();
		}
		$("#structure_" + type).show();
		$("#structure_" + type + " .knopka").show();
		window.location.hash = "#" + hash_url + "#structure_" + type;
	}
	else
	{
		for(var i = 0; i < element_all.length; i++)
		{
			$("#structure_" + element_all[i]).show();
		}
		
		$(".structure_element .knopka").hide();
	}
}


