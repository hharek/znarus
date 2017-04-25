<?php
$inc = _Inc::get($_GET['id']);
$module = _Module::get($inc['Module_ID']);

G::file_app()->put("{$module['Type']}/{$module['Identified']}/inc/html/{$inc['Identified']}.html", $_POST['Content']);
_Cache_Front::delete(["inc" => $module['Identified'] . "_" . $inc['Identified']]);

G::version()->set
(
	"_html_code/inc_{$inc['ID']}", 
	[
		"Content" => $_POST['Content']
	]
);

mess_ok("Inc «{$module['Identified']}_{$inc['Identified']} ({$module['Name']} / {$inc['Name']}) сохранён.»");
?>