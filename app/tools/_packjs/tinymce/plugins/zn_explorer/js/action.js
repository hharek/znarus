/**
 * Список файлов
 */
function ls()
{
	/* Запрос */
	$.ajax
	({
		url: "php/ls.php?" + window.location.hash.substr(1),
		type: "GET",
		dataType: "json",
		beforeSend: function()
		{
			$("#_overlay").show();
			$("#_loader").show();
		},
		success: function(data, textStatus, jqXHR)
		{
			/* Ошибка */
			if (data.error !== undefined)
			{
				_alert(data.error);
				return;
			}
			
			/* Операции после получения списка файлов */
			ls_after(data);
		},
		complete: function(jqXHR, textStatus)
		{
			$("#_loader").hide();
			$("#_overlay").hide();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus + ": " + errorThrown);
		}
	});
}


/**
 * Закачать файлы
 */
function upload()
{
	$.ajax
	({
		url: "php/action.php?action=upload",
		type: "POST",
		data: new FormData($("#upload")[0]),
		dataType: "json",
		processData: false,
		contentType: false,
		cache: false,
		beforeSend: function()
		{
			$("#_overlay").show();
			$("#_loader").show();
		},
		success: function(data, textStatus, jqXHR)
		{
			/* Ошибка */
			if (data.error !== undefined)
			{
				_alert(data.error);
				return;
			}
			
			/* Файлы закачаны */
			_alert("Файлы «" + data.join("», «") + "» успешно закачаны.");
			
			/* Обновить список */
			ls();
		},
		complete: function(jqXHR, textStatus)
		{
			$("#_loader").hide();
			$("#_overlay").hide();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus + ": " + errorThrown);
		}
	});
	
	return false;
}

/**
 * Создать папку
 */
function mkdir()
{
	$.ajax
	({
		url: "php/action.php?action=mkdir",
		type: "POST",
		data: new FormData($("#_prompt form")[0]),
		dataType: "json",
		processData: false,
		contentType: false,
		cache: false,
		beforeSend: function()
		{
			$("#_overlay").show();
			$("#_loader").show();
		},
		success: function(data, textStatus, jqXHR)
		{
			/* Ошибка */
			if (data.error !== undefined)
			{
				_alert(data.error);
				return;
			}
			
			/* Папка создана */
			_alert("Папка «" + data.dir + "» создана.");
			
			/* Обновить список */
			ls();
		},
		complete: function(jqXHR, textStatus)
		{
			$("#_loader").hide();
			$("#_overlay").hide();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus + ": " + errorThrown);
		}
	});
}

/**
 * Переименовать файл или папку
 */
function rename()
{
	var form_data = new FormData($("#_prompt form")[0]);
	form_data.append("old", $("#file .active .name").text());
	
	$.ajax
	({
		url: "php/action.php?&action=rename",
		type: "POST",
		data: form_data,
		dataType: "json",
		processData: false,
		contentType: false,
		cache: false,
		beforeSend: function()
		{
			$("#_overlay").show();
			$("#_loader").show();
		},
		success: function(data, textStatus, jqXHR)
		{
			/* Ошибка */
			if (data.error !== undefined)
			{
				_alert(data.error);
				return;
			}
			
			/* Файл переименован */
			_alert("Файл переименован.");
			
			/* Обновить список */
			ls();
		},
		complete: function(jqXHR, textStatus)
		{
			$("#_loader").hide();
			$("#_overlay").hide();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus + ": " + errorThrown);
		}
	});
}

/**
 * Удалить файлы
 */
function rm()
{
	/* Сформировать данные для формы */
	var form_data = new FormData($("#file")[0]);
	if ($("#file .check input:checked").length === 0)
	{
		form_data.append("file[]", $("#file .active .name").text());
	}
	
	/* Запрос */
	$.ajax
	({
		url: "php/action.php?action=rm",
		type: "POST",
		data: form_data,
		dataType: "json",
		processData: false,
		contentType: false,
		beforeSend: function()
		{
			$("#_overlay").show();
			$("#_loader").show();
		},
		success: function(data, textStatus, jqXHR)
		{
			/* Ошибка */
			if (data.error !== undefined)
			{
				_alert(data.error);
				return;
			}
			
			/* Файлы удалены */
			_alert("Файлы «" + data.join("», «") + "» удалены.");
			
			/* Обновить список */
			ls();
		},
		complete: function(jqXHR, textStatus)
		{
			$("#_loader").hide();
			$("#_overlay").hide();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus + ": " + errorThrown);
		}
	});
}