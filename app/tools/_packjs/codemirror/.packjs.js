_packjs.codemirror = 
{
	/**
	 * Инициализация
	 */
	init: function ()
	{
		$("head").append
		(
			'<link rel="stylesheet" href="packjs/codemirror/lib/codemirror.css">' +
			'<link rel="stylesheet" href="packjs/codemirror/addon/display/fullscreen.css">' +
			'<script src="packjs/codemirror/lib/codemirror.js"></script>' + 
			'<script src="packjs/codemirror/addon/edit/matchbrackets.js"></script>' + 
			'<script src="packjs/codemirror/addon/display/fullscreen.js"></script>' + 
			'<script src="packjs/codemirror/mode/xml/xml.js"></script>' + 
			'<script src="packjs/codemirror/mode/javascript/javascript.js"></script>' + 
			'<script src="packjs/codemirror/mode/css/css.js"></script>' + 
			'<script src="packjs/codemirror/mode/php/php.js"></script>' + 
			'<script src="packjs/codemirror/mode/clike/clike.js"></script>' +
			'<script src="packjs/codemirror/addon/display/fullscreen.js"></script>'
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
			path = "textarea#" + param.id;
		}
		else if (param.name !== undefined)
		{
			path = "textarea[name=" + param.name + "]";
		}
		
		/* Mime */
		var mime = "application/x-httpd-php";
		if (param.mime !== undefined)
		{
			mime = param.mime;
		}
		
		/* Создаем */
		CodeMirror.fromTextArea
		( 
			$(path)[0], 
			{
				lineNumbers: true,
				matchBrackets: true,
				mode: mime,
				indentUnit: 4,
				indentWithTabs: true,
				enterMode: "keep",
				tabMode: "shift",
				lineWrapping: true,
				extraKeys: 
				{
					"F11": function(cm) 
					{
						cm.setOption("fullScreen", !cm.getOption("fullScreen"));
						cm.setOption("lineWrapping", !cm.getOption("lineWrapping"));
					},
					"Esc": function(cm) 
					{
						if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
						if (cm.getOption("lineWrapping")) cm.setOption("lineWrapping", false);
					}
				}
			}
		);

		/* Отображать когда на закрытой вкладке */
		if ($(path).parents(".tab").length > 0)
		{
			var tab = $(path).parents(".tab").attr("id").substr(4);
			$(".tab_button[tab=" + tab + "]").click(function()
			{
				setTimeout(function()
				{
					$("#tab_" + tab + " .CodeMirror")[0].CodeMirror.refresh();
				}, 100);
			});
		}
	},
	
	/**
	 * Освобождаем ресурсы
	 */
	clean: function ()
	{
		$(".CodeMirror").each(function()
		{
			this.CodeMirror.toTextArea();
		});
	},
	
	/**
	 * Сохранить данные в html-элементе
	 */
	save: function ()
	{
		$(".CodeMirror").each(function()
		{
			this.CodeMirror.save();
		});
	},
	
	/**
	 * Назначить новые данные
	 */
	set: function ()
	{
		$(".CodeMirror").each(function()
		{
			var value = $(this.CodeMirror.getTextArea()).val();
			this.CodeMirror.setValue( value );
		});
	}
};