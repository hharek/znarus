/* ------------------------ Автосохранение ---------------------- */
var _autosave_interval;		/* Ссылка на setInterval */
var _autosave_time = 30;	/* Время автосохранения */

/* Включение и отключения автосохранения */
$(function()
{
	$("#_autosave > div").click(function()
	{
		if($(this).attr("class") === "icon_autosave_disable")
		{
			_autosave_enable();
		}
		else
		{
			_autosave_disable();
		}
	});
});

/* После выполнения exe */
$(document).on("_exe_success_content", function(e, data, hash, method) 
{
	/* Зачищаем старое */
	$("#_autosave").hide();
	_autosave_disable();

	/* По умолчанию включаем автосохранение */
	if(data.autosave !== undefined && data.autosave === true)
	{
		$("#_autosave").show();
		_autosave_enable();
	}	
});

/**
 * Включить автосохранение
 */
function _autosave_enable()
{
	$("#_autosave > div").attr("class", "icon_autosave");
	$("#_autosave > div").attr("title", "Автосохранение включено");	
	
	/* Определить форму */
	var form = _get_form_active("autosave");
	
	/* Данные по форме */
	var url = "";
	if($(form).attr("action") === undefined)
	{
		url = window.location.hash;
	}
	else
	{
		url = $(form).attr("action");
	}
	
	if(url.search("\\?") === -1)
	{
		url += "?_autosave";
	}
	else
	{
		url += "&_autosave";
	}
	
	/* Ставим интервал */
	_autosave_interval = setInterval(function()
	{
		_exe(url, new FormData(form));	
	}, _autosave_time * 1000);
}

/**
 * Отключить автосохранение
 */
function _autosave_disable()
{
	$("#_autosave > div").attr("class", "icon_autosave_disable");
	$("#_autosave > div").attr("title", "Автосохранение выключено");
	
	clearInterval(_autosave_interval);
}