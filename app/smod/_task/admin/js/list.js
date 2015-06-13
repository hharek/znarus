$(".filter .std_button").click(function()
{
	var from = $(".filter select[name=from]").val();
	var to = $(".filter select[name=to]").val();
	_exe("#_task/list?from=" + from + "&to=" + to);
});