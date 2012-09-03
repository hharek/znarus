/* Отображение поля для внешнего ключа */
foreign_view();
$("#type_id").change(foreign_view);
function foreign_view()
{
	if($("#type_id :selected").html().trim() == "foreign")
	{
		$("#tr_foreign").show();
		$("#tr_foreign_all").show();
		$("#tr_foreign_change").show();
		foreign_auto_fill();
	}
	else
	{
		$("#tr_foreign").hide();
		$("#tr_foreign_all").hide();
		$("#tr_foreign_change").hide();
	}
}

/* Автозаполнение для Foreign */
$("#foreign_id").change(foreign_auto_fill);
$("#foreign_id_all").change(foreign_auto_fill);
function foreign_auto_fill()
{
	if($("#foreign_all_view").val() == 1)
	{var stolb = "foreign_id_all";}
	else
	{var stolb = "foreign_id";}
	
	var entity_identified = $("#"+stolb+" :selected").attr("entity_identified");
	var field_identified = $("#"+stolb+" :selected").attr("field_identified");
	var identified = ucfirst(entity_identified) + '_' + field_identified;
	
	$("input:text[name=name]").val('Привязка к '+$("#"+stolb+" :selected").attr("entity_name").toLowerCase());
	$("input:text[name=identified]").val(identified);
}

/* Отображение всех Foreign */
$("#button_foreign_all").toggle
(
	function()
	{
		$("#foreign_id").attr("disabled","disabled");
		$("#foreign_id_all").removeAttr("disabled");
		$("#foreign_all_view").val(1);
		foreign_auto_fill();
	},
	function()
	{
		$("#foreign_id").removeAttr("disabled");
		$("#foreign_id_all").attr("disabled","disabled");
		$("#foreign_all_view").val(0);
		foreign_auto_fill();
	}
);
if($("#foreign_all_view").val() == 1)
{$("#button_foreign_all").click();}

/* Отображение поля Default */
default_view();
$("#type_id").change(default_view);
function default_view()
{	
	if($.inArray($("#type_id :selected").html().trim(), ['id','sort','enum']) > -1)
	{
		$("#tr_default").hide();
	}
	else
	{
		$("#tr_default").show();
	}
}

/* Отображение поля NULL */
null_view();
$("#type_id").change(null_view);
function null_view()
{
	if($.inArray($("#type_id :selected").html().trim(), ['id','sort','enum']) > -1)
	{
		$("#tr_null").hide();
	}
	else
	{
		$("#tr_null").show();
	}
}

/* Автозаполнение для полей */
$("#type_id").change(auto_fill);
function auto_fill()
{
	switch($("#type_id :selected").html().trim())
	{
		case "date":
		{
			$("input:text[name=name]").val('Дата');
			$("input:text[name=identified]").val('Date');
		}
		break;
		
		case "email":
		{
			$("input:text[name=name]").val('Почтовый ящик');
			$("input:text[name=identified]").val('Email');
		}
		break;
		
		case "file":
		{
			$("input:text[name=name]").val('Файл');
			$("input:text[name=identified]").val('File');
		}
		break;
		
		case "html":
		{
			$("input:text[name=name]").val('Html');
			$("input:text[name=identified]").val('Html');
		}
		break;
		
		case "id":
		{
			$("input:text[name=name]").val('ID');
			$("input:text[name=identified]").val('ID');
		}
		break;
		
		case "identified":
		{
			$("input:text[name=name]").val('Идентификатор');
			$("input:text[name=identified]").val('Identified');
		}
		break;
		
		case "image":
		{
			$("input:text[name=name]").val('Рисунок');
			$("input:text[name=identified]").val('Image');
		}
		break;
		
		case "md5":
		{
			$("input:text[name=name]").val('Пароль');
			$("input:text[name=identified]").val('Pass');
		}
		break;
		
		case "price":
		{
			$("input:text[name=name]").val('Цена');
			$("input:text[name=identified]").val('Price');
		}
		break;

		case "sort":
		{
			$("input:text[name=name]").val('Сортировка');
			$("input:text[name=identified]").val('Sort');
		}
		break;
		
		case "url":
		{
			$("input:text[name=name]").val('Урл');
			$("input:text[name=identified]").val('Url');
		}
		break;
	}	
}

/* С большой буквы поле Идентификатор и Наименование */
$("#identified").change
(
	function()
	{
		$("#identified").val(ucfirst($("#identified").val()));
	}
);
	
$("#identified").keypress
(
	function()
	{
		$("#identified").val(ucfirst($("#identified").val()));
	}
);

$("#name").change
(
	function()
	{
		$("#name").val(ucfirst($("#name").val()));
	}
);
	
$("#name").keypress
(
	function()
	{
		$("#name").val(ucfirst($("#name").val()));
	}
);

/* Отображение поля "Использовать в сортировке" */
is_order_view();
$("#type_id").change(is_order_view);
function is_order_view()
{
	if($.inArray($("#type_id :selected").html().trim(), ['blob','bool','enum','file','foreign','html','id','image','md5']) > -1)
	{
		$("#tr_is_order").hide();
	}
	else
	{
		$("#tr_is_order").show();
	}
}

/* Поле "Использовать в сортировке" выбирать если тип поля sort */
$("#type_id").change
(
	function()
	{
		if($("#type_id :selected").html().trim() == "sort")
		{
			$("#is_order").attr("checked", "checked");
		}
	}
);
	
/* Отображать подсказку для типа */
type_about();
$("#type_id").change(type_about);
function type_about()
{
	$("#img_type_about").attr("title", $("#type_id :selected").attr("about"));
}
