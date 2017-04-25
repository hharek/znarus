_packjs.tinymce = 
{
	/**
	 * Инициализация
	 */
	init: function ()
	{
		$("head").append('<script src="packjs/tinymce/tinymce.min.js"></script>');
	},
	
	/**
	 * Создание
	 */
	create: function (param)
	{
		/* Путь к textarea */
		var path;
		if (param.id !== undefined)
		{
			path = "textarea#" + param.id;
			delete(param.id);
		}
		else if (param.name !== undefined)
		{
			path = "textarea[name=" + param.name + "]";
			delete(param.name);
		}
		
		/* Подготавливаем конфиг */
		var config = 
		{
			language : "ru",
			selector: path,
			theme: "modern",
			height: 400,
			fontsize_formats: "8px 10px 12px 13px 14px 16px 18px 20px",
			plugins: ["code","media","table","link","anchor","fullscreen","zn_explorer"],
			content_css: _settings.css_default,
			toolbar: "undo redo | bold italic underline | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | inserttable | link unlink anchor | zn_explorer_image | fullscreen",
			relative_urls : false,
			body_class: "text-page",
			extended_valid_elements: "script[language|type|src]"
		};
		
		/* Добавляем дополнительные параметры */
		for (var i in param)
		{
			config[i] = param[i];
		}
		
		if (config["zn_explorer_image_url"] !== undefined)
		{
			config.plugins.push("zn_explorer");
		}
		
		/* Создаём */
		tinyMCE.init(config);
	},
	
	/**
	 * Освобождаем ресурсы
	 */
	clean: function ()
	{
		tinyMCE.remove();
	},
	
	/**
	 * Сохранить данные в html-элементе
	 */
	save: function ()
	{
		tinyMCE.triggerSave();
	},
	
	/**
	 * Назначить данные из html-элемента
	 */
	set: function ()
	{
		for(var i = 0; i < tinyMCE.editors.length; i ++)
		{
			var value = $(tinyMCE.editors[i].targetElm).val();
			tinyMCE.editors[i].setContent(value);
		}
	}
};