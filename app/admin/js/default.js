/* Хэш параметры */
var hash = "";
var hash_url = "";
var hash_hash = "";

/* --------------------------- Меню ---------------------------*/
$(function()
{
	/* Меню */
	var timeout; var menu;
	
	$(".zn_menu .menu").hover
	(
		function()
		{
			menu = this;
			timeout = setTimeout(function()
			{
				$(menu).find(".podmenu").show();
			}, 300);
		},
				
		function()
		{
			clearTimeout(timeout);
			$(menu).find(".podmenu").hide();
		}
	);
		
		
	/* Меню модули */
	$(".zn_menu .menu:nth-child(1)").hover
	(
		function()
		{
			menu = this;
			timeout = setTimeout(function()
			{
				$(menu).find(".module").show();
			}, 300);
		},
				
		function()
		{
			clearTimeout(timeout);
			$(menu).find(".module").hide();
		}
	);
		
	$(".zn_menu .mod").hover
	(
		function()
		{
			$(this).find(".admin").show();
		},
				
		function()
		{
			$(this).find(".admin").hide();
		}	
	);
		
	/* Сообщение */
	$("#zn_mess .close").click(function()
	{
		$("#zn_mess").hide();
	});
});

/* ---------------------------- Хэш ------------------------------ */
/**
 * Разобрать урл
 * @param {String} url
 * @returns {Object|Boolean}
 */
function hash_parse(url)
{
	var obj = {};
	var reg = /^#([0-9a-z_]+)\/([0-9a-z_]+)(\??[^#]*)(#?.*)/;
	
	if(!reg.test(url))
	{return false;}
	
	var match = url.match(reg);
	obj.mod = match[1];
	obj.act = match[2];
	obj.get = match[3];
	obj.after = match[4];
	obj.url = "#" + obj.mod + "/" + obj.act + obj.get;
	
	return obj;
}

/* Разбор хэша */
$(function()
{
	setInterval(function()
	{
		if(window.location.hash === "")
		{
			window.location.hash = "#zn_service/module";
		}
		
		if(hash !== window.location.hash)
		{
			var url = hash_parse(window.location.hash);
//			if(url !== false)
			if(url !== false && hash_url !== url.mod + "/" + url.act + url.get)
			{
				hash_url = url.mod + "/" + url.act + url.get;
				hash_hash = url.after;

				zn(url.url);
			}
			
			hash = window.location.hash;
		}
	}, 100);
});

/*-------------- Функция выполняемая до загрузки html -------------------*/
/**
 * Функция выполняемая до загрузки html
 */
function before_html()
{
	/* TinyMCE */
	if(typeof tinymce === "object")
	{tinymce.remove();}
}

/*-------------- Функция выполняемая после удачной загрузки аякс -------------------*/
/**
 * Функция выполняемая после загрузки ajax
 */
function after()
{
	/* Ссылка на строки таблицы list */
	$(".std_list tbody tr,td").each(function()
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
	
	/* Формирование формы для submit-а */
	$("#zn_exe form[action]").each(function()
	{
		var form = this;
		var url = hash_parse($(form).attr("action"));
		if(url !== false)
		{
			/* Добавляем кнопку type=submit чтобы submit проходил */
			if($(form).find("input[type=submit]").length === 0)
			{
				$(form).append("<input type=\"submit\" style=\"display: none;\" />");
			}

			/* Вешаем событие на кнопку .submit */
			$(form).find(".submit").off("click");
			$(form).find(".submit").click(function()
			{
				$(form).submit();
			});

			/* Вешаем на submit формы функцию */
			$(form).off("submit");
			$(form).submit(function()
			{
				/* Перед отправкой поместить данные из tinyMce в textarea */
				if(typeof tinymce === "object")
				{tinymce.triggerSave();}
				
				/* Перед отправкой поместить данные из CodeMirror в textarea */
				if(typeof cm === "object")
				{cm.save();}

				zn(url.url, new FormData(form));
				
				return false;
			});
		}
	});
	
	/* Окно удаления */
	$("#zn_exe .delete").each(function()
	{
		if
		(
			$(this).attr("url") !== undefined && 
			$.trim($(this).text()) !== "" &&
			hash_parse($(this).attr("url")) !== false
		)
		{
			var url = hash_parse($(this).attr("url"));
			var mess = $(this).text();
			$(this).text("");
			
			$(this).click(function()
			{
				Okno.Confirm.show(mess, url.url, "delete");
			});
		}
	});
	
	/* Курсор в первое поле формы */
	$(".std_form input:first").focus();
	
	/* Для сортировки */
	$(".std_list .up").off("click");
	$(".std_list .up").click(function()
	{
		sort("up", this);
	});
	
	$(".std_list .down").off("click");
	$(".std_list .down").click(function()
	{
		sort("down", this);
	});
	
	$(".std_list").find("tbody").find(".up:first").hide();
	$(".std_list").find("tbody").find(".down:last").hide();
	
	/* Класс token */
	$("a.token").each(function()
	{
		$(this).attr("href", $(this).attr("href") + "?token=" + $.cookie("token"));
	});
	
	/* Вкладки */
	$(".tab_button").click(function()
	{
		tab_show($(this).attr("tab"));
	});
	
	if(hash_hash.substr(0, 5) === "#tab_")
	{
		tab_show(hash_hash.substr(5));
	}
	
	return true;
}

/**
 * Показать вкладку
 * 
 * @param {String} name
 */
function tab_show(name)
{
	$(".tab").hide();
	$(".tab_button.active").removeClass("active");

	$("#tab_" + name).show();
	$(".tab_button[tab=" + name + "]").addClass("active");
	
	hash_hash = "#tab_" + name;
	hash = window.location.hash = "#" + hash_url + hash_hash;
	
}

/*---------------------------- Сортировка -------------------------- */
/**
 * Сортировка
 * 
 * @param {String} sort
 * @param {Object} obj
 */
function sort(sort, obj)
{
	var tr = $(obj).parents(".std_list tbody tr"); 
	
	/* Перенести */
	if(sort === "up")
	{
		$(tr).prev().before($(tr).remove());		
	}
	else if(sort === "down")
	{
		$(tr).next().after($(tr).remove());
	}
	
	/* Заново вешаем событие клик*/
	$(tr).find(".up").click(function()
	{
		window.sort("up", this);
	});

	$(tr).find(".down").click(function()
	{
		window.sort("down", this);
	});
	
	/* Скрыть и показать кнопки */
	$(tr).parents(".std_list tbody").find(".up,.down").show();
	$(tr).parents(".std_list tbody").find(".down:last").hide();
	$(tr).parents(".std_list tbody").find(".up:first").hide();
}

/* ----------------------------- Другое ---------------------------- */
$(function()
{
	/* Токен на выход */
	$("#zn_exit").attr("href", "exit?token=" + $.cookie("token"));
});