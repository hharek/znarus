/* ------------------------ Путь ---------------------- */
$(document).on("_exe_success", function(e, data, hash, method) 
{
	/* Создать новый путь */
	if(data.path !== undefined && data.path.length !== 0)
	{
		var html = "";
		for(var i = 0; i < data.path.length; i++)
		{
			if (data.path[i].url !== null)
			{
				html += '<a class="_item" href="' + data.path[i].url + '">' + data.path[i].name + '</a>\n';
			}
			else
			{
				html += '<a class="_item">' + data.path[i].name + '</a>\n';
			}
			
			
			if(data.path.length-1 !== i)
			{
				html += '<div class="_join">--></div>\n';
			}
		}
		$("#_path").html(html);
	}
	
	/* Выравнить путь */
	_path_align();
	$(window).resize(function()
	{
		_path_align();
	});
});

/**
 * Выравнить путь
 */
function _path_align()
{
	var path_element_width = 0;
	var path_width = parseInt($("#_path").css("width"));
	
	$("#_path > ._item, #_path > ._join").each(function()
	{
		path_element_width += parseInt($(this).css("width"));
		path_element_width += parseInt($(this).css("padding-left"));
		path_element_width += parseInt($(this).css("padding-right"));
	});
	path_element_width += 2;
	
	if(path_width < path_element_width)
	{
		$("#_path > ._item").first().css("margin-left", path_width - path_element_width);
	}
}




