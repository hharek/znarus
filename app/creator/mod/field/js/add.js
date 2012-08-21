foreign_view();
default_view();
null_view();

$("#type_id").change(foreign_view);
$("#type_id").change(default_view);
$("#type_id").change(null_view);
$("#type_id").change(auto_fill);


function foreign_view()
{
	if($("#type_id :selected").html().trim() == "foreign")
	{
		$("#tr_foreign").show();
	}
	else
	{
		$("#tr_foreign").hide();
	}
}

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