/**
 * Авторизация
 */

/* -------------------------------- Авторизация -------------------------------- */
$(function()
{
	/* Submit на кнопку */
	$(".knopka").click(function()
	{
		$("form").submit();
	});
	
	/* Submit */
	$("form").submit(function()
	{
		$(".load").show();
		$(".error").remove();
		
		var crypt = new JSEncrypt();
		crypt.setPublicKey($("#public_key").val());
		
		$.ajax
		({
			url: $("input[name=url]").val(),
			type: "POST",
			dataType: "json",
			data: 
			{
				name : crypt.encrypt($("form input[name=name]").val()),
				password: crypt.encrypt($("form input[name=password]").val()),
				token: $("input[name=token]").val()
			},
			success: function(data)
			{
				$(".load").hide();
				if(!data.status)
				{
					$(".container").prepend("<div class=\"error\">" + data.error + "</div>");
				}
				else
				{
					window.location = "";
				}
			}
		});
		return false;
	});
	
	/* Фокус на Имя */
	$("input[name=name]").focus();
});