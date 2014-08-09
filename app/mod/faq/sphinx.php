<?php
$data = [];

/* Вопросы */
$faq = Faq::select_list();
$content = "Часто задаваемые вопросы. FAQ. ЧАВО \n";
foreach ($faq as $val)
{
	$content .= "\n\n". $val['Question'] . " " . $val['Answer'];
}

$data[] = 
[
	"url" => "/вопрос-ответ",
	"name" => "Вопрос-ответ",
	"content" => $content
];

return $data;
?>