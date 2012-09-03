/* Отображение поля Default */
default_view();
$("#type_id").change(default_view);
function default_view()
{	
	var type = $("#type_id :selected").html().trim();
	
	if(type == "id" || type == "sort")
	{
		$("#tr_default").hide();
		$("#tr_default_enum").hide();
	}
	else if(type == "enum")
	{
		$("#tr_default").hide();
		$("#tr_default_enum").show();
	}
	else
	{
		$("#tr_default").show();
		$("#tr_default_enum").hide();
	}
}

/* Отображение поля NULL */
null_view();
$("#type_id").change(null_view);
function null_view()
{
	if($.inArray($("#type_id :selected").html().trim(), ['id','sort']) > -1)
	{
		$("#tr_null").hide();
	}
	else
	{
		$("#tr_null").show();
	}
}

/* Запрет на изменение сложного типа */
type_disable();
function type_disable()
{
	if($.inArray($("#type_id :selected").html().trim(), ['id','sort','enum','foreign']) > -1)
	{
		$("#type_id").attr("disabled","disabled");
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

/* Отображать подсказку для типа */
type_about();
$("#type_id").change(type_about);
function type_about()
{
	$("#img_type_about").attr("title", $("#type_id :selected").attr("about"));
}