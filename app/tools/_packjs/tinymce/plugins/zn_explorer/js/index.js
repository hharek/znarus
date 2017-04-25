/* При изменении хэша показывать список файлов */
$(window).bind("hashchange", function()
{
	ls();
});

$(function()
{	
	/* Показать список файлов */
	ls();
	
	/* Выделить все файлы */
	$("#file_check_all").click(function ()
	{
		if ($(this).is(":checked") === true)
		{
			$("#file .check input").prop("checked", true);
			act_show("check");
		}
		else
		{
			$("#file .check input").prop("checked", false);
			act_show("up");
		}
	});

	/* Закачать файлы */
	$("#upload input[type=file]").change(function()
	{
		$("#upload").submit();
	});

	$("#upload").submit(upload);

	/* Удалить */
	$(".panel .act_rm").click(function ()
	{
		if ($("#file .check input:checked").length === 0)
		{
			_confirm("Вы действительно хотите удалить файл «" + $("#file .active .name").text() + "»?", "delete", rm);
		}
		else if ($("#file .check input:checked").length !== 0)
		{
			_confirm("Вы действительно хотите удалить выбранные файлы?", "delete", rm);
		}
	});

	/* Переименовать */
	$(".panel .act_rename").click(function ()
	{
		_prompt("Укажите новое имя файла", "name", $("#file .active .name").text(), "edit", rename);
	});

	/* Создать папку */
	$(".panel .act_mkdir").click(function ()
	{
		_prompt("Имя папки", "name", "", "add", mkdir);
	});
	
	
});

/**
 * Показать доступные операции над элементом
 */
function act_show(type)
{
	/* Все действия */
	var act_all = ["put","rename","rm","show"];
	
	/* Спрятать все */
	for (var i = 0; i < act_all.length; i ++)
	{
		$(".panel").find(".act_" + act_all[i]).hide();
	}
	$(".panel").find(".act_show").attr("href", "#");
	
	/* Показать в зависемости от типа */
	switch (type)
	{
		case "up":
		{}
		break;
		
		case "dir":
		{
			$(".panel").find(".act_rename").show();
			$(".panel").find(".act_rm").show();
		}
		break;
		
		case "file":
		{
			$(".panel").find(".act_rename").show();
			$(".panel").find(".act_rm").show();
			
			/* Посмотреть */
			$(".panel").find(".act_show").show();
			$(".panel").find(".act_show").attr("href", $("#url_dir").text() + "/" + $("#file .active .name").text());
		}
		break;
		
		case "check":
		{
			$(".panel").find(".act_rm").show();
		}
	}
}

/**
 * После показа списка файлов файлов
 */
function ls_after(data)
{
	/* Очищаем старый список файлов */
	$("#file").empty();
	
	/* Указываем урл папки */
	$("#url_dir").text(data.url_dir);
	
	/* Указываем формам url и type */
	$("#upload input[name=url]").val(data.url_dir);
	$("#upload input[name=type]").val(data.type);
	
	$("#_prompt form input[name=url]").val(data.url_dir);
	$("#_prompt form input[name=type]").val(data.type);
	
	$("#file").append
	(
		'<input name="url" type="hidden" value="' + data.url_dir + '"/>' +
		'<input name="type" type="hidden" value="' + data.type + '"/>'
	);
	
	/* Папка «..» */
	if (data.url_dir_top !== null)
	{
		$("#file").append
		(
			'<div class="file up">' +
				'<div class="name">..</div>' +
			'</div>'
		);
	}
	
	/* Размещаем файлы и папки */
	for (var i=0; i < data.ls.length; i++)
	{
		if (data.ls[i].type === "dir")
		{
			$("#file").append
			(
				'<div class="file dir">' +
					'<div class="check">' +
						'<input name="file[]" value="' + data.ls[i].name + '" type="checkbox">' +
					'</div>' +
					'<div class="icon_dir"></div>' +
					'<div class="name">' + data.ls[i].name + '</div>' +
				'</div>'
			);
		}
		else if (data.ls[i].type === "file")
		{
			$("#file").append
			(
				'<div class="file">' +
					'<div class="check">' +
						'<input name="file[]" value="' + data.ls[i].name + '" type="checkbox">' +
					'</div>' +
					'<div class="name">' + data.ls[i].name + '</div>' +
				'</div>'
			);
		}
	}
	
	/* Реакция при выделинии одного файла */
	$(".file").click(function ()
	{
		if ($("#file .check input:checked").length === 0)
		{
			/* Выделить */
			$(this).parent().find(".active").removeClass("active");
			$(this).addClass("active");
			
			/* Тип */
			var type = "file";
			if ($(this).hasClass("up"))
			{
				type = "up";
			}
			else if ($(this).hasClass("dir"))
			{
				type = "dir";
			}

			/* Показать доступные действия */
			act_show(type);
		}
	});

	/* Выделить checkbox */
	$("#file .check").click(function ()
	{
		$(this).parent().parent().find(".active").removeClass("active");

		if ($("#file .check input:checked").length > 0)
		{
			act_show("check");
		}
	});

	/* Переход при двойном нажатии на папку «..» */
	$("#file .up").dblclick(function ()
	{
		window.location.hash = "#type=" + data.type + "&url=" + data.url_dir_top;
	});

	/* Переход при двойном нажатии на папку */
	$("#file .dir").dblclick(function ()
	{
		window.location.hash = "#type=" + data.type + "&url=" + data.url_dir + "/" + $(this).find(".name").text();
	});
	
	/* Выделить файл и отобразить иконки */
	if (data.file !== null)
	{
		$("#file .file").each(function()
		{	
			if ($(this).find(".name").text() === data.file)
			{
				$(this).addClass("active");
				act_show("file");
			}
		});
	}
	else
	{
		act_show("up");
	}
	
	/* Выбрать файл (TinyMCE) */
	if (top.tinymce.activeEditor.windowManager !== undefined)
	{
		
		
		if (data.type === "image")
		{
			$("#file .file").not(".dir, .up").dblclick(tinymce_enter_image);
		}
		else if (data.type === "all")
		{
			$("#file .file").not(".dir, .up").dblclick(tinymce_enter_file);
		}
	}
}

/**
 * Выбрать рисунок
 */
function tinymce_enter_image()
{
	/* Назначить рисунок */
	var window_form = top.tinymce.activeEditor.windowManager.getWindows()[0];
	var src = $("#url_dir").text() + "/" + $("#file .active .name").text();
	window_form.find("#src")[0].value(src);
	
	/* Определить ширину и высоту */
	var img = new Image();
	img.onload = function() 
	{
		window_form.find("#width")[0].value(img.width);
		window_form.find("#height")[0].value(img.height);
		
		/* Закрыть окно */
		var window_list = top.tinymce.activeEditor.windowManager.getWindows()[1];
		window_list.close();	
	};
	img.src = src;
}

/**
 * Выбрать файл
 */
function tinymce_enter_file()
{
	/* Назначить рисунок */
	var window_form = top.tinymce.activeEditor.windowManager.getWindows()[0];
	var href = $("#url_dir").text() + "/" + $("#file .active .name").text();
	window_form.find("#href")[0].value(href);
	
	/* Закрыть окно */
	var window_list = top.tinymce.activeEditor.windowManager.getWindows()[1];
	window_list.close();
}