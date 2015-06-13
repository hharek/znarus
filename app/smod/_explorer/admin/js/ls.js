/* Текущий путь */
var path = $("#path").attr("path");

/* Реакция при выделинии одного файла */
$(".file").click(function ()
{
	if ($(".list .check input:checked").length === 0)
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
		act_show(type, $(this).find(".name").text());
	}
});

/* Реакция при выделинии одного файла */
$(".list .check").click(function ()
{
	$(this).parent().parent().find(".active").removeClass("active");
	
	if ($(".list .check input:checked").length > 0)
	{
		act_show("check");
	}
});

/* Переход при двойном нажатии на папку */
$(".list .dir").dblclick(function ()
{
	if (path === ".")
	{
		path = $(this).find(".name").text();
	}
	else
	{
		path += "/" + $(this).find(".name").text();
	}

	window.location.hash = "#_explorer/ls?path=" + path;
});

/* Вверх по иерархии */
$(".list .up").dblclick(function ()
{
	var path_ar = path.split("/");
	path_ar.pop();
	var path_up = path_ar.join("/");
	
	if (path_up === "")
	{
		path_up = ".";
	}

	window.location.hash = "#_explorer/ls?path=" + path_up;
});

/* На главную */
$(".panel .act_home").click(function ()
{
	window.location.hash = "#_explorer/ls?path=.";
});

/* Выделить все файлы */
$("#file_check_all").click(function ()
{
	if ($(this).is(":checked") === true)
	{
		$(".list .check input").prop("checked", true);
		act_show("check");
	}
	else
	{
		$(".list .check input").prop("checked", false);
		act_show("up");
	}
});

/* Закачать файлы */
$(".upload form input[type=file]").change(function()
{
	$(".upload form").submit();
});

/* Править */
$(".panel .act_put").click(function ()
{
	var file = $(".list .active .name").text();
	if (path !== ".")
	{
		file = path + "/" + file;
	}

	window.location.hash = "#_explorer/put?file=" + file;
});

/* Удалить */
$(".panel .act_rm").click(function ()
{
	if ($(".list .check input:checked").length === 0)
	{
		var file_name = $(".list .active .name").text();
		var file_path = file_name;
		if (path !== ".")
		{
			file_path = path + "/" + file_name;
		}

		_confirm("Вы действительно хотите удалить файл «" + file_name + "»?", "#_explorer/rm?file=" + file_path, "delete");
	}
	else if ($(".list .check input:checked").length !== 0)
	{
		_confirm("Вы действительно хотите удалить выбранные файлы?", "#_explorer/rm?path=" + path);
		$("#_confirm form").off("submit");
		$("#_confirm form").submit(function ()
		{
			_exe("#_explorer/rm?path=" + path, new FormData($("#file")[0]));

			_Confirm.hide();
			return false;
		});
	}
});

/* Переименовать */
$(".panel .act_rename").click(function ()
{
	var file_name = $(".list .active .name").text();
	var file_path;
	if (path === ".")
	{
		file_path = file_name;
	}
	else
	{
		file_path = path + "/" + file_name;
	}

	_prompt("Укажите новое имя файла", "#_explorer/rename?file=" + file_path, "name", file_name);
});

/* Скачать */
$(".panel .act_download").click(function ()
{
	if ($(".list .check input:checked").length === 0)
	{
		var file_name = $(".list .active .name").text();
		var file_path;
		if (path === ".")
		{
			file_path = file_name;
		}
		else
		{
			file_path = path + "/" + file_name;
		}

		$("#file").attr("action", "exe/_explorer/download?_token=" + $.cookie("_sid"));
		$("#file").attr("method", "post");
		$("#file").append('<input type="hidden" name="file_one" value="' + file_path + '"/>');
		$("#file").off("submit");
		$("#file").submit();

	}
	else if ($(".file .check input:checked").length !== 0)
	{
		$("#file").attr("action", "exe/_explorer/download?path=" + path + "&_token=" + $.cookie("_sid"));
		$("#file").attr("method", "post");
		$("#file").off("submit");
		$("#file").submit();
	}
});

/* Добавить */
$(".panel .act_add").click(function ()
{
	window.location.hash = "#_explorer/add?path=" + path;
});

/* Создать папку */
$(".panel .act_mkdir").click(function ()
{
	_prompt("Имя папки", "#_explorer/mkdir?path=" + path, "name", "", "add");
});

/**
 * Показать доступные операции над элементом
 */
function act_show(type, file_name)
{
	/* Все действия */
	var act_all = ["put","rename","rm","download"];
	
	/* Спрятать все */
	for (var i = 0; i < act_all.length; i ++)
	{
		$(".panel").find(".act_" + act_all[i]).hide();
	}
	
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
			$(".panel").find(".act_download").show();
		}
		break;
		
		case "file":
		{
			/* Доступные расширения */
			var ext = "";
			var file_name_ar = file_name.split(".");
			if (file_name_ar.length > 1)
			{
				ext = file_name_ar[file_name_ar.length - 1];
			}
			
			if ($.inArray(ext, ["txt","css","html","htm","js","php","xml"]) !== -1)
			{
				$(".panel").find(".act_put").show();
			}
			
			$(".panel").find(".act_rename").show();
			$(".panel").find(".act_rm").show();
			$(".panel").find(".act_download").show();
		}
		break;
		
		case "check":
		{
			$(".panel").find(".act_rm").show();
			$(".panel").find(".act_download").show();
		}
	}
}