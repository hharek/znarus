/*--------------------- Окно с подтверждением и удалением ---------------------- */
var Okno =
{
	/* Окно подтверждения */
	Confirm:
	{
		/**
		 * Показать
		 * 
		 * @param {String} mess
		 * @param {String} url
		 */
		show: function(mess, url, type)
		{
			/* Другой тип */
			if(type === undefined)
			{type = "confirm";}
			
			switch(type)
			{
				case "confirm":
				{$("#zn_okno_confirm .down .confirm_ok").html('<div class="icon_active"></div>Да');}
				break;
				
				case "delete":
				{$("#zn_okno_confirm .down .confirm_ok").html('<div class="icon_delete"></div>Удалить');}
				break;
			}
			
			/* Отобразить */
			$("#zn_okno_confirm_str").text(mess);
			
			$("#zn_overlay").show();
			$("#zn_okno_confirm").show();
			
			$("#zn_okno_confirm form").off("submit");
			$("#zn_okno_confirm form").submit(function()
			{
//				zn(url, $(this).serializeArray());
				
				zn(url, new FormData(this));
				
				Okno.Confirm.hide();
				return false;
			});
		},
		
		/**
		 * Скрыть
		 */
		hide: function()
		{
			$("#zn_okno_confirm_str").text("");
			
			$("#zn_overlay").hide();
			$("#zn_okno_confirm").hide();
		}
	},
	
	/* Окно с предупреждением */
	Alert:
	{
		/**
		 * Показать
		 * 
		 * @param {String} mess
		 */
		show: function(mess)
		{
			/* Отобразить */
			$("#zn_okno_alert_str").html(mess);
			
			$("#zn_overlay").show();
			$("#zn_okno_alert").show();
		},
		
		/**
		 * Скрыть
		 */
		hide: function()
		{
			$("#zn_okno_alert_str").text("");
			
			$("#zn_overlay").hide();
			$("#zn_okno_alert").hide();
		}
	},
	
	/* Сообщение с текстовым полем */
	Prompt:
	{
		/**
		 * Показать
		 * 
		 * @param {String} mess
		 * @param {String} url
		 */
		show: function(mess, url, name, value, type)
		{
			/* Другой тип */
			if(type === undefined)
			{type = "edit";}
			
			switch(type)
			{
				case "edit":
				{$("#zn_okno_prompt .down .prompt_ok").html('<div class="icon_active"></div>Изменить');}
				break;
				
				case "add":
				{$("#zn_okno_prompt .down .prompt_ok").html('<div class="icon_active"></div>Добавить');}
				break;
			}
			
			/* Строка и параметры */
			$("#zn_okno_prompt_str").text(mess);
			$("#zn_okno_prompt_name").each(function()
			{
				$(this).attr("name", name);
				$(this).val(value);
			});
			
			/* Отобразить */
			$("#zn_overlay").show();
			$("#zn_okno_prompt").show();
			
			$("#zn_okno_prompt form").off("submit");
			$("#zn_okno_prompt form").submit(function()
			{
//				zn(url, $(this).serializeArray());
				zn(url, new FormData(this));
				Okno.Prompt.hide();
				return false;
			});
		},
		
		/**
		 * Скрыть
		 */
		hide: function()
		{
			/* Очистить строку и удалить параметры */
			$("#zn_okno_prompt_str").text("");
			$("#zn_okno_prompt_name").each(function()
			{
				$(this).removeAttr("name");
				$(this).val("");
			});
			
			/* Спрятать */
			$("#zn_overlay").hide();
			$("#zn_okno_prompt").hide();
		}
	}
};

$(function()
{
	/* Кнопки в окне подтвреждения */
	$("#zn_okno_confirm .down .confirm_back").click(function()
	{
		Okno.Confirm.hide();
	});
	
	$("#zn_okno_confirm .down .confirm_ok").click(function()
	{
		$("#zn_okno_confirm form").submit();
	});
	
	/* Кнопки в окне предупреждения */
	$("#zn_okno_alert .down .alert_back").click(function()
	{
		Okno.Alert.hide();
	});
	
	/* Кнопки в окне с текстовым полем */
	$("#zn_okno_prompt .down .prompt_back").click(function()
	{
		Okno.Prompt.hide();
	});
	
	$("#zn_okno_prompt .down .prompt_ok").click(function()
	{
		$("#zn_okno_prompt form").submit();
	});
});