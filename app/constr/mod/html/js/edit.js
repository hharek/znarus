$(".inc_delete").click(function()
{
	zn
	(
		"#html/inc_delete_post", 
		{
			"html_id" : $(this).attr("html_id"),
			"inc_id"  : $(this).attr("inc_id")
		}
	);
});