$(function()
{
	/* Подсветка для таблиц типа list */
	$("table.list tbody tr").hover
	(
		function()
		{
			$(this).css("background-color","#f9fac5");
		},
		function()
		{
			$(this).css("background-color","#fefffa");
		}
	);
	
	/* Меню */
	$("div.div_menu div.div_menu_title").click
	(
		function()
		{
			$(this).parent().find(".div_menu_podcat").slideToggle("fast");
		}
	);
		
	/* Подробнее об ошибке */
	$("#knopka_error_more").click
	(
		function()
		{
			$("#div_error_more").slideToggle("fast");
		}
	);
	
});

/**
 * Выводит строку с большой буквы
 */
function ucfirst (str) 
{
    str += '';
    var f = str.charAt(0).toUpperCase();
    return f + str.substr(1);
}