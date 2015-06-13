/*---------------------Обработка содержимого после exe -------------------------*/
$(document).on("_exe_success_content", function(e, data, hash, method)
{
	/* Ссылка на строки таблицы list */
	$("#_content .std_list tbody tr, #_content .std_list tbody td").each(function()
	{
		if($(this).attr("url") !== undefined)
		{
			$(this).css("cursor","pointer");
			$(this).click(function()
			{
				window.location.hash = $(this).attr("url");
			});
		}
	});
	
	/* Работа с формами */
	$("#_content form").each(function()
	{
		var form = this;
		
		/* Вешаем на кнопку .submit */
		$(form).find(".submit").off("click");
		$(form).find(".submit").click(function()
		{
			$(form).submit();
		});
		
		/* Добавить submit к формам */
		$(form).submit(function()
		{
			/* Операции над формой перед отправкой */
			$(document).trigger("_submit_prepare");		
			
			/* Урл запроса */
			var action = "";
			if($(this).attr("action") === undefined)
			{	
				action = window.location.hash;
			}
			else
			{
				action = $(this).attr("action");
			}
			
			/* Exe */
			_exe(action, new FormData($(this)[0]));

			return false;
		});
		
		/* Добавим кнопку type=submit чтобы enter срабатывал */
		$(this).append('<input type="submit" style="display:none;"/>');
	});
	
	/* Окно удаления */
	$("#_content .delete").each(function()
	{
		$(this).click(function()
		{
			_confirm($(this).attr("mess"), $(this).attr("url"), "delete");
		});

		$(this).text("");
	});
	
	/* Класс token */
	$("a.token").each(function()
	{
		if ($(this).attr("href").search("\\?") === -1)
		{
			$(this).attr("href", $(this).attr("href") + "?_token=" + $.cookie("_sid"));
		}
		else
		{
			$(this).attr("href", $(this).attr("href") + "&_token=" + $.cookie("_sid"));
		}
	});
});

/*--------------------------- После exe ----------------------------*/
$(document).on("_exe_success", function(e, data, hash, method)
{
	/* Заголовок */
	if (data.title !== undefined)
	{
		document.title = data.title;
		$("#_title div").text(data.title);
	}
	
	/* CSS */
	if (data.css !== undefined)
	{
		$("#_content").prepend("<style>" + data.css + "</style>");
	}
	
	/* Javascript */
	if (data.js !== undefined)
	{
		$("#_content").append("<script>" + data.js + "</script>");
	}
	
	/* Сообщение об успешном выполнении */
	if (data.mess_ok !== undefined && data.mess_ok !== "" && hash.get.search("_autosave") === -1)
	{
		$("#_mess_ok ._text ").text(data.mess_ok);
		$("#_mess_ok").show();
		setTimeout(function()
		{
			$("#_mess_ok").hide();
		}, 5000);
	}
	
	/* Переход на другую страницу */
	if (data.redirect !== undefined && data.redirect !== "")
	{
		setTimeout(function()
		{
			window.location.assign(data.redirect);
		}, 1000);
	}
	
	/* Перезагрузка */
	if (data.reload !== undefined && data.reload === true)
	{
		setTimeout(function()
		{
			_exe(window.location.hash);
		}, 1000);
	}
	
	/* Доступные видимые админки */
	if (data.module_admin !== undefined)
	{
		var module = data.module_admin;
		
		$("#_admin ._module ._icon img").attr("src", "mod_icon/" + module.Identified + ".png");
		$("#_admin ._module span").text(module.Name);
		
		$("#_admin a").remove();
		if (module.Admin !== undefined)
		{
			for (var i = 0; i < module.Admin.length; i++)
			{
				var admin = module.Admin[i];
				$("#_admin").append
				(
					'<a class="_adm" href="#' + data.module_admin.Identified + '/' + admin.Identified  + '">' +
						admin.Name +
					'</a>'
				);
			}
		}
	}
});