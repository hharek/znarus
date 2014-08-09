<?php
/**
 * Tinymce
 * 
 * @param string $name
 */
function packjs_tinymce4($name)
{
?>

<script src="packjs/tinymce4/tinymce.min.js"></script>
<script>
tinymce.init
({
	language : "ru",
	selector: "textarea[name=<?php echo $name; ?>]",
	theme: "modern",
	height: 400,
	plugins: ["code","image","media","table","codemirror","link"],
	content_css: "<?php echo P::get("css_default"); ?>",
	toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | inserttable | image | link",
	relative_urls : false,
	codemirror: 
	{
		indentOnInit: true,
		path: "/<?php echo Reg::url_admin(); ?>/packjs/codemirror",
		config:
		{
			lineNumbers: true,
			matchBrackets: true,
			mode: "application/x-httpd-php",
			indentUnit: 4,
			indentWithTabs: true,
			enterMode: "keep",
			tabMode: "shift"
		},
		jsFiles: 
		[
			"addon/edit/matchbrackets.js",
			"addon/display/fullscreen.js",
			"mode/xml/xml.js",
			"mode/javascript/javascript.js",
			"mode/css/css.js",
			"mode/php/php.js",
			"mode/clike/clike.js"
		]
	}
});
</script>

<?php
}

/**
 * CodeMirror
 * 
 * @param string $name
 * @param string $mime
 */
function packjs_codemirror($name, $mime="application/x-httpd-php")
{
?>

<!-- CodeMirror -->
<link rel="stylesheet" href="packjs/codemirror/lib/codemirror.css">
<script src="packjs/codemirror/lib/codemirror.js"></script>
<script src="packjs/codemirror/addon/edit/matchbrackets.js"></script>
<script src="packjs/codemirror/addon/display/fullscreen.js"></script>
<script src="packjs/codemirror/mode/xml/xml.js"></script>
<script src="packjs/codemirror/mode/javascript/javascript.js"></script>
<script src="packjs/codemirror/mode/css/css.js"></script>
<script src="packjs/codemirror/mode/php/php.js"></script>
<script src="packjs/codemirror/mode/clike/clike.js"></script>

<script>
var cm = CodeMirror.fromTextArea ( $("textarea[name=<?php echo $name; ?>]")[0], 
{
	lineNumbers: true,
	matchBrackets: true,
	mode: "<?php echo $mime; ?>",
	indentUnit: 4,
	indentWithTabs: true,
	enterMode: "keep",
	tabMode: "shift"
});
cm.setSize("100%", "500px");
</script>

<style>
.CodeMirror *
{
	font-family: monospace;
	font-size: 14px;
}
</style>

<?php
}

/**
 * Datepick
 * 
 * @param string $name
 * @param string $format
 */
function packjs_datepick($name, $format="dd.mm.yyyy")
{
?>

<!-- Календарь -->
<link rel="stylesheet" type="text/css" href="packjs/datepick/redmond.datepick.css">
<script src="packjs/datepick/jquery.datepick.js"></script>
<script src="packjs/datepick/jquery.datepick-ru.js"></script>
<script>
$("input[name=<?php echo $name; ?>]").datepick
({
	dateFormat: "<?php echo $format; ?>"
});
</script>

<?php
}
?>