/* ------------------------ Меню ---------------------- */
$(function()
{
	/* Меню */
	var timeout; var menu;
	
	$("#_menu ._item").hover
	(
		function()
		{
			menu = this;
			timeout = setTimeout(function()
			{
				$(menu).find("._podmenu").show();
				$(menu).find("._module").show();
			}, 300);
		},
				
		function()
		{
			clearTimeout(timeout);
			$(menu).find("._podmenu").hide();
			$(menu).find("._module").hide();
		}
	);
		
	$("#_menu ._mod").hover
	(
		function()
		{
			$(this).find("._admin").show();
		},
				
		function()
		{
			$(this).find("._admin").hide();
		}	
	);	
});