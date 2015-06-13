/* ------------------------ Вкладки ---------------------- */
$(document).on("_exe_success_content", function(e, data, hash, method) 
{
	/* Вкладки */
	$("#_content .tab_button").click(function()
	{
		_tab($(this).attr("tab"));
	});
	
	if(_hash_parse(window.location.hash).after.substr(0, 5) === "#tab_")
	{
		_tab(_hash_parse(window.location.hash).after.substr(5));
	}
	
	if($("#_content .tab_button.active").length === 1)
	{
		_tab($(".tab_button.active").attr("tab"));
	}
});

/**
 * Показать вкладку
 */
function _tab(name)
{
	$("#_content .tab").hide();
	$("#_content .tab_button.active").removeClass("active");

	$("#tab_" + name).show();
	$("#_content .tab_button[tab=" + name + "]").addClass("active");
	
	var hash = _hash_parse(window.location.hash);
	window.location.hash = "#" + hash.mod + "/" + hash.act + hash.get +  "#tab_" + name;
}