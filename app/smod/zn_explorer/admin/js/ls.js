var path = $("#form_upload input[name=path]").val();

/* Реакция при выделинии одного файла */
$(".explorer_file .file").click(function()
{
	$(".explorer_file .active").removeClass("active");
	$(this).addClass("active");
	
	/* Править */
	if($(this).attr("class").indexOf("folder") === -1 && $(this).find(".name").text() !== "..")
	{$(".explorer_panel .icon_list").show();}
	else
	{$(".explorer_panel .icon_list").hide();}
	
	/* Переименовать, Удалить, Скачать */
	if($(this).find(".name").text() !== "..")
	{
		$(".explorer_panel .icon_rename").show();
		$(".explorer_panel .icon_delete").show();
		$(".explorer_panel .icon_download").show();
	}
	else
	{
		$(".explorer_panel .icon_rename").hide();
		$(".explorer_panel .icon_delete").hide();
		$(".explorer_panel .icon_hide").show();
	}
});

/* Реакция при выделинии одного файла */
$(".explorer_file .check").click(function()
{
	if($(".explorer_file .check input:checked").length > 0)
	{
		$(".explorer_panel .icon_download").show();
	}
});

/* Переход при двойном нажатии на папку */
$(".explorer_file .folder").dblclick(function()
{
	var path = "";
	if($("#explorer_path").attr("path") !== ".")
	{
		path += $("#explorer_path").attr("path") + "/";
	}
	
	path += $.trim($(this).find(".name").text());
	
	window.location.hash = "#zn_explorer/ls?path=" + path;
});

/* Вверх по иерархии */
$(".explorer_file .up").dblclick(function()
{
	var path_ar = $("#explorer_path").attr("path").split("/");
	path_ar.pop();
	var path = path_ar.join("/");
	if(path === "")
	{path = ".";}
	
	window.location.hash = "#zn_explorer/ls?path=" + path;
});

/* На главную */
$(".explorer_panel .icon_house").click(function()
{
	window.location.hash = "#zn_explorer/ls?path=.";
});

/* Править */
$(".explorer_panel .icon_list").click(function()
{
	var file = $(".explorer_file .active .name").text();
	if(path !== ".")
	{file = path + "/" + file;}

	window.location.hash = "#zn_explorer/put?file=" + file;
});

/* Переименовать */
$(".explorer_panel .icon_rename").click(function()
{
	var file_name = $(".explorer_file .active .name").text();
	var file = file_name;
	if(path !== ".")
	{file = path + "/" + file;}

	Okno.Prompt.show("Укажите новое имя файла", "#zn_explorer/rename_post?file=" + file, "name", file_name);
});

/* Удалить */
$(".explorer_panel .icon_delete").click(function()
{
	if($(".explorer_file .check input:checked").length === 0)
	{
		var file_name = $(".explorer_file .active .name").text();
		var file = file_name;
		if(path !== ".")
		{file = path + "/" + file;}

		Okno.Confirm.show("Вы действительно хотите удалить файл «" + file_name + "»?", "#zn_explorer/rm_post?file=" + file, "delete");
	}
	else
	{
		Okno.Confirm.show("Вы действительно хотите удалить выбранные файлы?", "#zn_explorer/rm_post?path=" + path);
		$("#zn_okno_confirm form").off("submit");
		$("#zn_okno_confirm form").submit(function()
		{
			zn("#zn_explorer/rm_post?path=" + path, new FormData($("#explorer_file")[0]));

			Okno.Confirm.hide();
			return false;
		});
	}
});

/* Выделить все файлы */
$("#explorer_file_check_all").click(function()
{
	if($(this).is(":checked") === true)
	{
		$("#explorer_file input[type=checkbox]").prop("checked", true);
	}
	else
	{
		$("#explorer_file input[type=checkbox]").prop("checked", false);
	}
});

/* Скачать */
$(".explorer_panel .icon_download").click(function()
{
	if($(".explorer_file .check input:checked").length === 0)
	{
		var file = $(".explorer_file .active .name").text();
		if(path !== ".")
		{file = path + "/" + file;}
		
		$("#explorer_file").attr("action", "ajax/zn_explorer/download_post?token=" + $.cookie("token"));
		$("#explorer_file").attr("method", "post");
		$("#explorer_file").append('<input type="hidden" name="file_one" value="' + file + '"/>');
		$("#explorer_file").submit();
		
	}
	else
	{
		$("#explorer_file").attr("action", "ajax/zn_explorer/download_post?path=" + path + "&token=" + $.cookie("token"));
		$("#explorer_file").attr("method", "post");
		$("#explorer_file").submit();
	}
});

/* Добавить */
$(".explorer_panel .icon_add").click(function()
{
	window.location.hash = "#zn_explorer/add?path=" + path;
});

/* Переименовать */
$(".explorer_panel .icon_folder").click(function()
{
	Okno.Prompt.show("Имя папки", "#zn_explorer/mkdir_post?path=" + path, "name", "", "add");
});