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
	try
	{
		/* URL */
		if(url === undefined)
		{throw new Error("url не задан.");}
		
		if(typeof(url) !== "string")
		{throw new Error("url не является строкой.");}
		
		if(hash_parse(url) === false)
		{throw new Error("url задан неверно.");}
		
		url = hash_parse(url);
		
		/* Метод */
		var method = "GET";
		if(post !== undefined)
		{
			method = "POST";
			
			/* Разбираем post и переделываем в FormData */
			if(post instanceof FormData === false)
			{
				var form_data = new FormData();
				for(var key in post)
				{
					form_data.append(key, post[key]);
				}
				
				post = form_data;
			}
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
		processData: false,
		contentType: false,
		cache: false,
		beforeSend: function()
		{
			$("#zn_overlay").show();
			$("#zn_load").show();
		},
		complete: function(jqXHR, textStatus)
		{
			$("#zn_load").hide();
			$("#zn_overlay").hide();
		},
		success: function(data, textStatus, jqXHR)
		{
			/* Исключения */
			if(data.exception !== undefined)
			{
				if(method === "GET")
				{
					$("#zn_exe").html
					(
						data.exe + 
						'<pre>' + data.exception + '</pre>'
					);
				}
				else if (method === "POST")
				{
					document.write("<pre>" + data.exception + "</pre>");
				}
				
				return false;
			}
			
			/* Исключения админские */
			if(data.exception_admin !== undefined)
			{
				if(method === "GET")
				{
					$("#zn_exe").html
					(
						data.exe + 
						'<div class="exception_admin">' + data.exception_admin + '</div>'
					);
				}
				else if (method === "POST")
				{
					Okno.Alert.show(data.exe + "<br/>" + data.exception_admin);
				}
				
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
					html += '<a class="path" href="' + data.path[i].url + '">' + data.path[i].name + '</a>';
					if(data.path.length-1 !== i)
					{
						html += '<hr class="join"/>';
					}
				}
				$("#zn_path").html(html);
			}

			/* Вывод */
			if(data.exe !== "")
			{
				$("#zn_exe_css").html(data.css);
				$("#zn_exe").html(data.exe);	
			}

			/* Стандартная обработка данных */
			after();

			/* JS */
			if(data.js !== "")
			{
				$("#zn_exe_js").remove();
				$("head").append(data.js);
			}

			/* JS error form */
			$("table.std_form input,textarea").css("border-color", "");
			$(".error_mess").remove();

			for(var key in data.form_error)
			{
				$("table.std_form [name='" + key + "']").css("border-color","#d90000");
				$("table.std_form [name='" + key + "']").after("<div class=\"error_mess\">" + data.form_error[key] + "</div>");
			}

			/* Сообщение об успешном выполнении */
			if(data.mess_ok !== "")
			{
				$("#zn_mess_text").text(data.mess_ok);
				$("#zn_mess").show();
				setTimeout(function()
				{
					$("#zn_mess").hide();
				}, 5000);
			}

			/* Перезагрузка */
			if(data.reload === true)
			{
				zn(hash);
				return true;
			}

			/* Редирект */
			if(data.redirect !== "")
			{
				setTimeout(function()
				{
					window.location.hash = data.redirect;
				}, 1000);
			}

			/* Админки по модулю */
			if(data.zn_admin_html !== "")
			{
				$("#zn_admin").html(data.zn_admin_html);
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