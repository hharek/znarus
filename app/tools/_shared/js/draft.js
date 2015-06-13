/* ------------------------ Черновик ---------------------- */
var _draft_interval;	/* Ссылка на setInterval */
var _draft_time = 30;	/* Время для создания черновика */

/* После выполнения exe */
$(document).on("_exe_success_content", function(e, data, hash, method)
{
	/* Зачищаем старый */
	$("#_content").removeAttr("draft");
	clearInterval(_draft_interval);

	/* Берём данные из черновика если есть */
	if(data.draft !== undefined && data.draft !== "")
	{
		_draft_get(data.draft);
		$("#_content").attr("draft", data.draft);

		/* Записываем данные в черновик */
		_draft_interval = setInterval(function()
		{
			_draft_set($("#_content").attr("draft"));
		}, _draft_time * 1000);
	}	
});

/**
 * Получить черновик
 */
function _draft_get(identified)
{
	$.ajax
	({
		url: "draft_get",
		type: "POST",
		dataType: "json",
		data: 
		{
			identified: identified
		},
		success: function(data)
		{
			/* Ошибка */
			if(data.error !== undefined)
			{
				alert(data.error);
			}
			/* Черновик */
			else if(data.draft !== null)
			{
				/* Определить форму */
				var form = _get_form_active("draft");

				/* Добавить в поля формы */
				for(var key in data.draft)
				{
					$(form).find("[name='" + key + "']").val(data.draft[key]);
				}
				
				/* Вызываем событие */
				$(document).trigger("_draft_get_after");
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus);
		}
	});
}

/**
 * Сохранить черновик
 */
function _draft_set(identified)
{
	/* Вызываем событие перед обработкой формы */
	$(document).trigger("_submit_prepare");
	
	/* Определить форму */
	var form = _get_form_active("draft");
	
	/* Отправить запрос на запись */
	$.ajax
	({
		url: "draft_set?identified=" + identified,
		type: "POST",
		dataType: "json",
		data: new FormData(form),
		processData: false,
		contentType: false,
		cache: false,
		success: function(data)
		{
			if(data.error !== undefined)
			{
				alert(data.error);
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus);
		}
	});
}