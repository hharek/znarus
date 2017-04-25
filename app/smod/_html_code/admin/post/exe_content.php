<?php
$exe = _Exe::get($_GET['id']);
$module = _Module::get($exe['Module_ID']);

G::file_app()->put("{$module['Type']}/{$module['Identified']}/exe/html/{$exe['Identified']}.html", $_POST['Content']);
_Cache_Front::delete(["exe" => $module['Identified'] . "_" . $exe['Identified']]);

G::version()->set
(
	"_html_code/exe_{$exe['ID']}", 
	[
		"Content" => $_POST['Content']
	]
);

mess_ok("Exe «{$module['Identified']}_{$exe['Identified']} ({$module['Name']} / {$exe['Name']}) сохранён.»");
?>