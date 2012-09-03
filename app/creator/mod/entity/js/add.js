/* Наименование c большой буквы */
$("#name").change
(
	function()
	{
		$("#name").val(ucfirst($("#name").val()));	
	}
);
	
$("#name").keypress
(
	
	function()
	{
		$("#name").val(ucfirst($("#name").val()));
	}
);

/* Идентификатор маленькими буквами */
$("#identified").change
(
	function()
	{
		$("#identified").val($("#identified").val().toLowerCase());
	}
);
	
$("#identified").keypress
(
	
	function()
	{
		$("#identified").val($("#identified").val().toLowerCase());
	}
);