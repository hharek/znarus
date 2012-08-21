foreign_view();
default_view();
null_view();
type_disable();

$("#type_id").change(foreign_view);
$("#type_id").change(default_view);
$("#type_id").change(null_view);


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

function type_disable()
{
	if($.inArray($("#type_id :selected").html().trim(), ['id','sort','enum','foreign']) > -1)
	{
		$("#type_id").attr("disabled","disabled");
	}
}