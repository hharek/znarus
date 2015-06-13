/* ------------------------ Версионность ---------------------- */
$(function()
{
	/* Отобразить даты */
	$("#_version > .icon_version").click(function()
	{
		if($("#_version > ._list").is(":visible") === false)
		{
			$("#_version > ._list").show();
		}
		else
		{
			$("#_version > ._list").hide();
		}	
	});
});

/* После выполнения exe */
$(document).on("_exe_success_content", function(e, data, hash, method) 
{
	/* Чистим иконку с версиями */
	$("#_version").removeAttr("identified");
	$("#_version .list").empty();
	$("#_version").hide();

	/* Создаём новую */
	if(data.version !== undefined && data.version !== "")
	{
		_version_get_date_all(data.version);
	}
});

/* Если выполнен POST запрос обновляем версии */
$(document).on("_exe_success", function(e, data, hash, method) 
{
	if
	(
		method === "POST" &&
		(data.content === undefined || data.content === "") && 
		$("#_version").attr("identified") !== undefined &&
		hash.get.search("_autosave") === -1
	)
	{
		_version_get_date_all($("#_version").attr("identified"));
	}
});


/**
 * Получить все даты версий по объекту
 */
function _version_get_date_all(identified)
{
	$.ajax
	({
		url: "version",
		type: "POST",
		dataType: "json",
		data: 
		{
			type: "date_all",
			identified: identified
		},
		success: function(data)
		{
			/* Ошибка */
			if(data.error !== undefined)
			{
				alert(data.error);
			}
			/* Версии есть */
			else if(data.date_all !== null)
			{
				/* Создаём список с датами */
				$("#_version").attr("identified", identified);
				$("#_version ._list").empty();
				var html = "";
				for(var i = 0; i < data.date_all.length; i++)
				{
					html += "<div>" + data.date_all[i] + "</div>";
				}
				$("#_version ._list").html(html);
				$("#_version").show();
				
				/* Первый элемент выделяем */
				$("#_version ._list > div").first().addClass("_active");
				
				/* Вешаем событие */
				$("#_version ._list > div").click(function()
				{
					$("#_version ._list > div._active").removeClass("_active");
					$(this).addClass("_active");
					$("#_version ._list").hide();
					
					_version_get($("#_version").attr("identified"), $(this).text());
				});
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus);
		}
	});
}

/**
 * Получить версию по дате
 */
function _version_get(identified, date)
{
	$.ajax
	({
		url: "version",
		type: "POST",
		dataType: "json",
		data: 
		{
			type: "data",
			identified: identified,
			date: date
		},
		beforeSend: function()
		{
			$("#_version > ._load").show();
		},
		complete: function(jqXHR, textStatus)
		{
			$("#_version > ._load").hide();
		},
		success: function(data)
		{
			/* Ошибка */
			if(data.error !== undefined)
			{
				alert(data.error);
			}
			/* Добавить в поля формы */
			else if (data.data !== null)
			{
				/* Определить форму */
				var form = _get_form_active("version");
				
				/* Заполняем */
				for(var key in data.data)
				{
					$(form).find("[name='" + key + "']").val(data.data[key]);
				}
				
				/* Вызываем событие */
				$(document).trigger("_version_get_after");
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus);
		}
	});
}