/* ---------------------------- Пакеты Javascript ----------------------------- */
var _packjs = {};												/* Основная функциональность */
var _packjs_init = [];											/* Проиницированные */
var _packjs_load = [];											/* Загруженные */

/* Получить список установленных пакетов (append для синхроного запуска) */
$("title").append('<script src="packjs"></script>');

/* Освобождаем старые ресурсы */
$(document).on("_exe_success_content_before", function(e, data)
{
	if (_packjs_load.length !== 0)
	{
		for (var i = 0; i < _packjs_load.length; i ++)
		{
			var identified = _packjs_load[i];
			
			if (_packjs[identified].clean === undefined)		/* Нет функции clean() */
			{
				continue;
			}
			
			_packjs[identified].clean();						/* Запускаем функции clean() */
		}
		
		_packjs_load = [];
	}
});

/* Инициализация и создание объектов */
$(document).on("_exe_success_content", function(e, data)
{
	/* Пакеты не заданы */
	if (data.packjs === undefined)
	{
		return;
	}
	
	/* Инициализируем */
	for (var i = 0; i < data.packjs_init.length; i ++)
	{
		var identified = data.packjs_init[i];					/* Идентификатор */
		
		if ($.inArray(identified, _packjs_init) !== -1)			/* Уже проинициализированы */
		{
			continue;
		}
		
		if (_packjs[identified].init === undefined)				/* Нет функции init */
		{
			continue;
		}
		
		_packjs[identified].init();								/* Инициализация */
		_packjs_init.push(identified);							/* Помещаяем в массив инициализированных */
	}
	
	/* Создаём объекты */
	for (var i = 0; i < data.packjs.length; i ++)
	{
		var identified = data.packjs[i].identified;
		var param = data.packjs[i].param;
		
		if (_packjs[identified].create === undefined)			/* Нет функции create */
		{
			continue;
		}
		
		_packjs[identified].create(param);						/* Создаём */
		
		
		if ($.inArray(identified, _packjs_load) === -1)			/* Помещаем в массив загруженных */
		{
			_packjs_load.push(identified);
		}
	}
});

/* Помещаем данные из объекта в html-элемент если submit */
$(document).on("_submit_prepare", function(e)
{
	if (_packjs_load.length !== 0)
	{
		for (var i = 0; i < _packjs_load.length; i ++)
		{
			var identified = _packjs_load[i];
			
			if (_packjs[identified].save === undefined)			/* Нет функции save() */
			{
				continue;
			}
			
			_packjs[identified].save();							/* Запускаем функции save() */
		}
	}
});

/* Назначить новые данные объекту из html-элемента */
$(document).on("_version_get_after _draft_get_after", function(e)
{
	if (_packjs_load.length !== 0)
	{
		for (var i = 0; i < _packjs_load.length; i ++)
		{
			var identified = _packjs_load[i];
			
			if (_packjs[identified].set === undefined)			/* Нет функции set() */
			{
				continue;
			}
			
			_packjs[identified].set();							/* Запускаем функции set() */
		}
	}
});