/* -------------------- Авторизация --------------------*/
$(function()
{
	/* Повесить submit на форму */
	$("form").find("._button").click(function()
	{
		$(this).submit();
	});

	/* Отправка файла */
	$("form").submit(function()
	{
		var form_data;
		
		/* Шифровать данные */
		if ($(this).find("input[name=public_key]").length !== 0)
		{
			var crypt = new JSEncrypt();
			crypt.setPublicKey($(this).find("input[name=public_key]").val());

			form_data = new FormData();
			form_data.append("token", $(this).find("input[name=token]").val());
			form_data.append("Email", crypt.encrypt($(this).find("input[name=Email]").val()));
			form_data.append("Password", crypt.encrypt($(this).find("input[name=Password]").val()));
		}
		/* Шифровать не надо */
		else
		{
			form_data = new FormData($(this)[0]);
		}
		
		/* Отправка */
		$.ajax
		({
			url: "auth",
			type: "POST",
			data: form_data,
			dataType: "json",
			processData: false,
			contentType: false,
			cache: false,
			beforeSend: function()
			{
				$("#_overlay").show();
				$("#_loader").show();
			},
			success: function(data, textStatus, jqXHR)
			{
				if (data.error === undefined)
				{
//					if (data.visit_last !== "")
//					{
//						window.location.hash = data.visit_last;
//						window.location.reload(true);
//					}

					window.location.reload(true);
				}
				else if (data.error !== undefined)
				{
					_alert(data.error);
				}
			},
			complete: function(jqXHR, textStatus)
			{
				$("#_loader").hide();
				$("#_overlay").hide();
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				alert(textStatus + ": " + errorThrown);
			}
		});
		
		return false;
	});
});