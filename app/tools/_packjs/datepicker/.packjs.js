_packjs.datepicker = 
{
	/**
	 * Инициализация
	 */
	init: function ()
	{
		$("head").append
		(
			'<script src="packjs/datepicker/i18n/datepicker-ru.js"></script>'
		);
	},
	
	/**
	 * Создание
	 */
	create: function (param)
	{
		/* Jquery путь */
		var path;
		if (param.id !== undefined)
		{
			path = "#" + param.id;
		}
		else if (param.name !== undefined)
		{
			path = "[name=" + param.name + "]";
		}
		
		/* Формат */
		var format = "dd.mm.yy";
		if (param.format !== undefined)
		{
			format = param.format;
		}
		
		/* Создание */
		$(path).datepicker
		({
			dateFormat: format
		});
	},
	
	/**
	 * Освобождаем ресурсы
	 */
	clean: function ()
	{
		$("#ui-datepicker-div").remove();
	}
};