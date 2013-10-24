/**
 * Jquery Cookie
 * Меню
 * Хэш
 * Функция zn()
 * Функция after()
 * Объект Okno.Delete
 * Сортировка
 * Другое
 */

/* ------------------------- Jquery Cookie ------------------------ */
/*!
 * jQuery Cookie Plugin v1.3
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function ($, document, undefined) {

	var pluses = /\+/g;

	function raw(s) {
		return s;
	}

	function decoded(s) {
		return decodeURIComponent(s.replace(pluses, ' '));
	}

	var config = $.cookie = function (key, value, options) {

		// write
		if (value !== undefined) {
			options = $.extend({}, config.defaults, options);

			if (value === null) {
				options.expires = -1;
			}

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}

			value = config.json ? JSON.stringify(value) : String(value);

			return (document.cookie = [
				encodeURIComponent(key), '=', config.raw ? value : encodeURIComponent(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// read
		var decode = config.raw ? raw : decoded;
		var cookies = document.cookie.split('; ');
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			if (decode(parts.shift()) === key) {
				var cookie = decode(parts.join('='));
				return config.json ? JSON.parse(cookie) : cookie;
			}
		}

		return null;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) !== null) {
			$.cookie(key, null, options);
			return true;
		}
		return false;
	};

})(jQuery, document);

/* ---------------------------- Хэш ------------------------------ */
/**
 * Разобрать урл
 * @param {String} url
 * @returns {Object|Boolean}
 */
function hash_parse(url)
{
	var obj = {};
	var reg = /^#([a-z_]+)\/([a-z_]+)(\??[^#]*)(#?.*)/;
	
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

/* Хэш параметры */
var hash = "";
var hash_url = "";
var hash_hash = "";

/* Разбор хэша */
$(function()
{
	setInterval(function()
	{
		if(window.location.hash === "")
		{
			window.location.hash = "#module/list";
		}
		
		if(hash !== window.location.hash)
		{
			var url = hash_parse(window.location.hash);
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

/*----------------------------- Функция zn() ----------------------------*/
/**
 * Основная функция аякс запросов
 * 
 * @param {String} url
 * @param {Object} post
 * @returns {Boolean}
 */
function zn(url, post)
{
	/* Лог */
	console.log("zn(«" + url + "»);");
	
	if(typeof(post) === "object" && $.isEmptyObject === false)
	{
		console.log("POST данные:");
		console.log(post);
	}
	
	try
	{
		/* URL */
		if(typeof(url) === "undefined")
		{throw new Error("url не задан.");}
		
		if(typeof(url) !== "string")
		{throw new Error("url не является строкой.");}
		
		if(hash_parse(url) === false)
		{throw new Error("url задан неверно.");}
		
		url = hash_parse(url);
		
		/* POST */
		if(typeof(post) === "undefined")
		{post = {};}
		
		if(typeof(post) !== "object")
		{throw new Error("post данные заданы неверно.");}
		
		/* Метод */
		var method = "GET";
		if($.isEmptyObject(post) === false)
		{
			method = "POST";
		}
		
		/* Токен */
		var token = "";
		if(url.get !== "")
		{
			token = "&token=" + $.cookie("token");
		}
		else
		{
			token = "?token=" + $.cookie("token");
		}
	}
	catch(e)
	{
		alert(e.message);
		return false;
	}
		
	/* Запрос */
	$.ajax
	({
		url: "ajax/" + url.mod + "/" + url.act + url.get + token,
		type: method,
		dataType: "json",
		data: post,
		beforeSend: function()
		{
			$("#overlay").show();
			$("#load").show();
		},
		complete: function(jqXHR, textStatus)
		{
			$("#load").hide();
			$("#overlay").hide();
		},
		success: function(data, textStatus, jqXHR)
		{
			/* Исключения */
			if(data.exception !== "")
			{
				alert(data.exception);
				return false;
			}

			/* Заголовок */
			if(data.title !== "")
			{
				document.title = data.title;
			}

			/* Путь */
			if(data.path.length !== 0)
			{
				var html = "";
				for(var i = 0; i < data.path.length; i++)
				{
					html += '<a class="trace" href="' + data.path[i].url + '">' + data.path[i].name + '</a>';
					if(data.path.length-1 !== i)
					{
						html += '<hr class="join"/>';
					}
				}
				$("#path").html(html);
			}

			/* Вывод */
			if(data.exe !== "")
			{
				$("#exe_css").html(data.css);
				$("#exe").html(data.exe);
			}

			/* Стандартная обработка данных */
			after();

			/* JS */
			if(data.js !== "")
			{
				$("#exe_js").remove();
				$("head").append(data.js);
			}

			/* JS error form */
			$("table.forma input,textarea").css("border-color", "");
			$(".error_mess").remove();
			
			$("table.forma input[type=checkbox]") /* checkbox */
				.css("border", "")
				.removeAttr("title");	

			for(var key in data.form_error)
			{
				$("table.forma [name='" + key + "']:not(input[type=hidden],input[type=checkbox])")
					.css("border-color","#d90000")
					.after("<div class=\"error_mess\">" + data.form_error[key] + "</div>");
				
				$("table.forma input[type=checkbox][name='" + key + "']")  /* checkbox */
					.css("border","1px solid #d90000")
					.attr("title", data.form_error[key]);		
			}

			/* Обновить меню */
			if(data.menu_top !== "")
			{
				menu_top(data.menu_top);
			}

			/* Сообщение об успешном выполнении */
			if(data.mess_ok !== "")
			{
				$("#mess_ok_text").text(data.mess_ok);
				$("#mess_ok").show();
				setTimeout(function()
				{
					$("#mess_ok").hide();
				}, 5000);
			}

			/* Редирект */
			if(data.redirect !== "")
			{
				setTimeout(function()
				{
					window.location.hash = data.redirect;
				}, 1000);
			}

			/* Сменить урл */
			if(data.exe !== "")
			{
				hash = window.location.hash = "#" + url.mod + "/" + url.act + url.get + hash_hash;
			}

		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus);
		}
	});
	
	return true;
}

/*-------------- Функция выполняемая после удачной загрузки аякс -------------------*/
/**
 * Функция выполняемая после загрузки аякс
 * @returns Boolean
 */
function after()
{
	/* Ссылка на строки таблицы list */
	$(".list tbody tr,td").each(function()
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
	$("#exe form[action]").each(function()
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
				zn(url.url, $(form).serializeArray());
				return false;
			});
		}
	});
	
	/* Окно удаления */
	$("#exe .delete").each(function()
	{
		if
		(
			$(this).attr("url") !== undefined && 
			$(this).attr("mess") !== undefined &&
			hash_parse($(this).attr("url")) !== false
		)
		{
			var url = hash_parse($(this).attr("url"));
			var mess = $(this).attr("mess");
			
			$(this).click(function()
			{
				Okno.Delete.show(mess, url);
			});
		}
	});
	
	/* Курсор в первое поле формы */
	$(".forma input:first").focus();
	
	/* Для сортировки */
	$(".list .up").off("click");
	$(".list .up").click(function()
	{
		sort("up", this);
	});
	
	$(".list .down").off("click");
	$(".list .down").click(function()
	{
		sort("down", this);
	});
	
	$(".list").find("tbody").find(".up:first").hide();
	$(".list").find("tbody").find(".down:last").hide();
	
	return true;
}

/*--------------------- Окно с подтверждением и удалением ---------------------- */
var Okno =
{
	/* Окно с предупреждением об удалении */
	Delete:
	{
		/**
		 * Показать
		 * 
		 * @param {String} mess
		 * @param {Object} url
		 * @returns {Boolean}
		 */
		show: function(mess, url)
		{
			$("#okno_delete .str").text(mess);
			
			$("#overlay").show();
			$("#okno_delete").show();
			
			$("#okno_delete form").off("submit");
			$("#okno_delete form").submit(function()
			{
				zn(url.url, $(this).serializeArray());
				Okno.Delete.hide();
				return false;
			});
			
			return true;
		},
		
		/**
		 * Скрыть
		 * 
		 * @returns {Boolean}
		 */
		hide: function()
		{
			$("#okno_delete .str").text("");
			
			$("#overlay").hide();
			$("#okno_delete").hide();
			
			return true;
		}
	}
};

$(function()
{
	/* Вешаем на кнопку "Отмена" скрыть окно */
	$("#okno_delete .knopka:nth-child(2)").click(function()
	{
		Okno.Delete.hide();
	});
	
	/* Вешаем на кнопку "Удалить" submit формы */
	$("#okno_delete .knopka:nth-child(1)").click(function()
	{
		$("#okno_delete form").submit();
	});
});

/* ---------------------------- Меню ------------------------------ */
$(function()
{
	var timeout; var menu;
	
	$("#menu_top .menu").hover
	(
		function()
		{
			menu = this;
			timeout = setTimeout(function()
			{
				$(menu).find(".podcat").show();
			}, 300);
		},
				
		function()
		{
			clearTimeout(timeout);
			$(menu).find(".podcat").hide();
		}
	);
		
	menu_top();
});

/**
 * Функция отображет меню через ajax
 * 
 * @param {String} type
 * @returns Boolean
 */
function menu_top(type)
{
	/* Проверка */
	if(typeof(type) === "undefined")
	{type = "all";}
	
	var type_all = ["module","html","user","packjs","lib"];
	if($.inArray(type, type_all) === -1 && type !== "all")
	{
		alert("Тип для меню задан неверно.");
		return false;
	}
	
	/* Аякс запрос */
	$.ajax
	({
		url: "menu/?token=" + $.cookie("token"),
		type: "POST",
		dataType: "json",
		data: {type: type},
		beforeSend: function()
		{
			$("#menu_" + type).css("border", "1px solid red");
		},
		complete: function(jqXHR, textStatus)
		{
			$("#menu_" + type).css("border", "");
		},
		success: function(data, textStatus, jqXHR)
		{
			if(type !== "all")
			{
				menu_create(type, data.result);
			}
			else
			{
				for(var i=0; i<type_all.length; i++)
				{
					menu_create(type_all[i], data.result[type_all[i]]);
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			alert(textStatus);
		}
	});
	
	return true;
}

/**
 * Построить меню
 * 
 * @param {String} type
 * @param {Object} data
 * @returns {Boolean}
 */
function menu_create(type, data)
{
	var html = "";
	switch(type)
	{
		/* ---- Модули ----*/
		case "module":
		{
			for(var i=0; i<data.mod.length; i++)
			{
				html += "<a href=\"#module/edit?id=" + data.mod[i].ID + "\">" + data.mod[i].Identified + " - " + data.mod[i].Name + "</a>";
			}
			
			html += "<hr style=\"border: 1px dashed #ccc;\"/>";
			
			for(var i=0; i<data.smod.length; i++)
			{
				html += "<a href=\"#module/edit?id=" + data.smod[i].ID + "\">" + data.smod[i].Identified + " - " + data.smod[i].Name + "</a>";
			}
		}
		break;
		
		/* ---- Шаблоны ----*/
		case "html":
		{
			for(var i=0; i<data.length; i++)
			{
				html += "<a href=\"#html/edit?id=" + data[i].ID + "\">" + data[i].Identified + " - " + data[i].Name + "</a>";
			}
		}
		break;
		
		/* ---- Пользователи ----*/
		case "user":
		{
			for(var i = 0; i < data.length; i++)
			{
				html += "<a href=\"#user/group_edit?id=" + data[i].ID + "\">" + data[i].Name + "</a>";
				for(var u = 0; u < data[i].user.length; u++)
				{
					if(data[i].user[u].Active === "1")
					{
						html += "<a href=\"#user/user_edit?id=" + data[i].user[u].ID + "\" style=\"margin-left: 10px;\">" + data[i].user[u].Email + "</a>";
					}
					else
					{
						html += "<a href=\"#user/user_edit?id=" + data[i].user[u].ID + "\" style=\"margin-left: 10px; color: #ccc;\">" + data[i].user[u].Email + "</a>";
					}
				}
			}
			
			html += "<hr style=\"border: 1px dashed #ccc;\"/>"+
					"<a href=\"#user/priv\">Привилегии</a>";
		}
		break;
		
		/* ---- Javascript ----*/
		case "packjs":
		{

		}
		break;
		
		/* ---- Библиотеки ----*/
		case "lib":
		{

		}
		break;
	}
	
	if(html !== "")
	{
		$("#menu_" + type + " .podcat").html(html);
	}
	
	return true;
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
	var tr = $(obj).parents(".list tbody tr"); 
	
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
	$(tr).parents(".list tbody").find(".up,.down").show();
	$(tr).parents(".list tbody").find(".down:last").hide();
	$(tr).parents(".list tbody").find(".up:first").hide();
}

/* ----------------------------- Другое ---------------------------- */
$(function()
{
	/* Токен на выход */
	$("#right a.icon_exit").attr("href", "exit?token=" + $.cookie("token"));
	
});
