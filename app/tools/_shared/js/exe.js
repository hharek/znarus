/**
 * Основная функция аякс запросов
 */
function _exe(hash, post_data)
{
	/* Разобрать хэш */
	hash = _hash_parse(hash);

	/* Токен */
	var token = "";
	if (hash.get !== "")
	{
		token = "&_token=" + $.cookie("_sid");
	}
	else
	{
		token = "?_token=" + $.cookie("_sid");
	}
	
	/* Метод */
	var method = "GET";
	
	/* POST данные */
	if (post_data !== undefined)
	{
		method = "POST";
		
		/* Переделываем POST-данные в FormData */
		if(post_data instanceof FormData === false)
		{
			var form_data = new FormData();
			for(var key in post_data)
			{
				form_data.append(key, post_data[key]);
			}

			post_data = form_data;
		}
	}
	
	/* Запрос */
	$.ajax
	({
		url: "exe/" + hash.mod + "/" + hash.act + hash.get + token,
		type: method,
		data: post_data,
		dataType: "json",
		processData: false,
		contentType: false,
		cache: false,
		beforeSend: function()
		{
			$(document).trigger("_exe_before", hash.get, method);
		},
		success: function(data, textStatus, jqXHR)
		{
			if (data.error === undefined && data.error_form === undefined && data.error_sys === undefined)
			{
				if (data.content !== undefined && data.content !== "")
				{
					$(document).trigger("_exe_success_content_before", [data, hash, method]);	/* Событие. До загрузки нового содержимого */
					$("#_content").html(data.content);											/* Содержимое */
					$(document).trigger("_exe_success_content", [data, hash, method]);			/* Событие. После загрузки нового содержимого */
				}
				
				$(document).trigger("_exe_success", [data, hash, method]);
			}
			else if (data.error_sys !== undefined)
			{
				$(document).trigger("_exe_error_sys", [data.error_sys, data.content]);
			}
			else if (data.error !== undefined)
			{
				$(document).trigger("_exe_error", [data.error, data.content]);
			}
			else if (data.error_form !== undefined)
			{
				$(document).trigger("_exe_error_form", data.error_form);
			}
		},
		complete: function(jqXHR, textStatus)
		{
			$(document).trigger("_exe_complete", hash.get);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus + ": " + errorThrown);
		}
	});
}

/* Страница загружается */
$(document).on("_exe_before", function(e, get, method)
{
	if(get.search("autosave") === -1)
	{
		$("#_overlay").show();
		$("#_loader").show();
	}
});

/* Страница загружена */
$(document).on("_exe_complete", function(e, get)
{
	if(get.search("autosave") === -1)
	{
		$("#_loader").hide();
		$("#_overlay").hide();
	}
});