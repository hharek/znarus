/* ------------------------ Сортировка ---------------------- */
$(document).on("_exe_success_content", function(e, data, hash, method) 
{
	$("#_content .std_list .up").off("click");
	$("#_content .std_list .up").click(function()
	{
		_sort("up", this);
	});
	
	$("#_content .std_list .down").off("click");
	$("#_content .std_list .down").click(function()
	{
		_sort("down", this);
	});
	
	_sort_btn_reload();
});

/**
 * Сортировка
 */
function _sort(sort, obj)
{
	var tr = $(obj).parents(".std_list tbody tr"); 
	
	/* Перенести */
	if(sort === "up")
	{
		$(tr).prev().before($(tr).remove());		
	}
	else if(sort === "down")
	{
		$(tr).next().after($(tr).remove());
	}
	
	/* Заново вешаем событие клик*/
	$(tr).find(".up").click(function()
	{
		_sort("up", this);
	});

	$(tr).find(".down").click(function()
	{
		_sort("down", this);
	});
	
	/* Скрыть и показать кнопки */
	$(tr).parents(".std_list tbody").find(".up,.down").show();
	_sort_btn_reload();
}

function _sort_btn_reload ()
{
	$("#_content .std_list").find("tbody").find(".up, .down").each(function()
	{
		var parent = 0;
		if ($(this).data("parent") !== undefined)
		{
			parent = $(this).data("parent");
		}
		
		var selector = ":first";
		if ($(this).hasClass("down"))
		{
			selector = ":last";
		}
		
		if ($(this).is("[data-parent=" + parent + "]" + selector))
		{
			$(this).hide();
		}
	});
}