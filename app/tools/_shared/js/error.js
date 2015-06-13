/* Системные ошибки */
$(document).on("_exe_error_sys", function(e, error, content)
{
	$("#_content").html('<div class="exception_admin">' + error + '</div>');	
});

/* Пользовательские ошибки */
$(document).on("_exe_error", function(e, error, content)
{
	if(content !== undefined && content !== "")
	{
		$("#_content").html(content);
	}
	
	_alert(error);
});

/* Ошибка при заполнении в форме */
$(document).on("_exe_error_form", function(e, error)
{
	for(var key in error)
	{
		$("table.std_form [name='" + key + "']").each(function()
		{
			/* Пометить красным цветом */
			if ($(this).attr("type") !== "hidden")
			{
				$(this).css("border-color","#d90000");
				$(this).css("box-shadow","0px 0px 6px #d94343");
			}
			
			/* Повесить сообщение */
			if ($.inArray($(this).attr("type"), ["checkbox", "radio"]) !== -1)
			{
				$(this).attr("title", error[key]);;
			}
			else if ($.inArray($(this).attr("type"), ["hidden"]) !== -1)
			{}
			else
			{
				$(this).after('<div class="error_mess">' + error[key] + '</div>');
			}
		});
	}
});

/* Удаление сообщений об ошибке перед exe */
$(document).on("_exe_before", function()
{
	$("#_content table.std_form").find("input, textarea").css("border-color", "");
	$("#_content table.std_form").find("input, textarea").css("box-shadow", "");
	$("#_content table.std_form").find("input[type=checkbox], input[type=radio]").removeAttr("title");
	$("#_content .error_mess").remove();
});