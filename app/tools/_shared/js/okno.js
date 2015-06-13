/* ------------------------ Окно подтверждения ---------------------------- */
var _Confirm =
{
	/**
	 * Показать
	 * 
	 * @param {String} mess
	 * @param {String} url
	 * @param {String} type
	 */
	show: function(mess, url, type)
	{
		/* Тип по умолчанию */
		if(type === undefined)
		{
			type = "confirm";
		}

		/* Тип confirm или тип delete */
		switch(type)
		{
			case "confirm":
			{
				$("#_confirm ._down ._confirm_ok").html('<div class="icon_active"></div>Да');
			}
			break;

			case "delete":
			{
				$("#_confirm ._down ._confirm_ok").html('<div class="icon_delete"></div>Удалить');
			}
			break;
		}

		/* Отобразить */
		$("#_confirm ._str").text(mess);

		$("#_overlay").show();
		$("#_confirm").show();

		/* Вешаем submit на форму confirm */
		$("#_confirm form").off("submit");
		$("#_confirm form").submit(function()
		{
			_exe(url, new FormData(this));
			_Confirm.hide();
			
			return false;
		});
	},

	/**
	 * Скрыть
	 */
	hide: function()
	{
		$("#_confirm ._str").text("");

		$("#_overlay").hide();
		$("#_confirm").hide();
	}
};

/* Кнопки */
$(function()
{
	$("#_confirm ._confirm_back").click(function()
	{
		_Confirm.hide();
	});
	
	$("#_confirm ._confirm_ok").click(function()
	{
		$("#_confirm form").submit();
	});
});

/* Функция синоним */
var _confirm = _Confirm.show;

/* ------------------------------- Окно с предупреждением ---------------------------- */
var _Alert =
{
	/**
	 * Показать
	 * 
	 * @param {String} mess
	 */
	show: function(mess)
	{
		/* Отобразить */
		$("#_alert ._str").html(mess);

		$("#_overlay").show();
		$("#_alert").show();
	},

	/**
	 * Скрыть
	 */
	hide: function()
	{
		$("#_alert ._str").text("");

		$("#_overlay").hide();
		$("#_alert").hide();
	}
};

/* Кнопки */
$(function()
{
	$("#_alert ._alert_back").click(function()
	{
		_Alert.hide();
	});
});

/* Функция синоним */
var _alert = _Alert.show;

/* -------------------------- Сообщение с текстовым полем ------------------------- */
var _Prompt =
{
	/**
	 * Показать
	 * 
	 * @param {String} mess
	 * @param {String} url
	 */
	show: function(mess, url, name, value, type)
	{
		/* Тип по умолчанию edit */
		if(type === undefined)
		{
			type = "edit";
		}

		/* Тип edit или prompt */
		switch(type)
		{
			case "edit":
			{
				$("#_prompt ._down ._prompt_ok").html('<div class="icon_active"></div>Изменить');
			}
			break;

			case "add":
			{
				$("#_prompt ._down ._prompt_ok").html('<div class="icon_active"></div>Добавить');
			}
			break;
		}

		/* Строка и параметры */
		$("#_prompt ._str").text(mess);
		$("#_prompt input").each(function()
		{
			$(this).attr("name", name);
			$(this).val(value);
		});

		/* Отобразить */
		$("#_overlay").show();
		$("#_prompt").show();

		/* Вешаем submit */
		$("#_prompt form").off("submit");
		$("#_prompt form").submit(function()
		{
			_exe(url, new FormData(this));
			_Prompt.hide();
			
			return false;
		});
	},

	/**
	 * Скрыть
	 */
	hide: function()
	{
		/* Очистить строку и удалить параметры */
		$("#_prompt ._str").text("");
		$("#_prompt input").each(function()
		{
			$(this).removeAttr("name");
			$(this).val("");
		});

		/* Спрятать */
		$("#_overlay").hide();
		$("#_prompt").hide();
	}
};

/* Кнопки */
$(function()
{
	$("#_prompt ._prompt_back").click(function()
	{
		_Prompt.hide();
	});
	
	$("#_prompt ._prompt_ok").click(function()
	{
		$("#_prompt form").submit();
	});
});

/* Функция синоним */
var _prompt = _Prompt.show;