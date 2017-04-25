_packjs.datepick = 
{
	/**
	 * Инициализация
	 */
	init: function ()
	{
		$("head").append
		(
			'<link rel="stylesheet" type="text/css" href="packjs/datepick/redmond.datepick.css">' +
			'<script src="packjs/datepick/jquery.plugin.js"></script>' +
			'<script src="packjs/datepick/jquery.datepick.js"></script>' +
			'<script src="packjs/datepick/jquery.datepick-ru.js"></script>'
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
			path = "input#" + param.id;
		}
		else if (param.name !== undefined)
		{
			path = "input[name=" + param.name + "]";
		}
		
		/* Формат */
		var format = "dd.mm.yyyy";
		if (param.format !== undefined)
		{
			format = param.format;
		}
		
		/* Создание */
		$(path).datepick
		({
			dateFormat: format
		});
	},
	
	/**
	 * Освобождаем ресурсы
	 */
	clean: function ()
	{
		$(".is-datepick").datepick("destroy");
	}
};