/* ------------------------ Общее ---------------------- */
$(function()
{
	/* Сообщение */
	$("#_mess_ok ._close").click(function()
	{
		$("#_mess_ok").hide();
	});
	
	/* Токен на выход */
	$("#_exit").attr("href", "exit?_token=" + $.cookie("_sid"));
});


/**
 * Получить активную форму (для autosave, draft, version)
 */
function _get_form_active(type)
{
	var form;
	if($("#_content form").length === 1)
	{
		form = $("#_content form")[0];
	}
	else if($("#_content form").length > 1)
	{
		form = $("#_content form[" + type + "]")[0];
	}
	else
	{
		throw new Error("Отсутствует форма");
	}
	
	if(form === undefined)
	{
		throw new Error("Отсутствует форма «" + type + "»");
	}
	
	return form;
}