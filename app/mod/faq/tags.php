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
	"content" => $content,
	"tags" => "вопрос-ответ, чаво, часто задаваемые вопросы, faq, F.A.Q., ответы на вопросы, вопросы и ответы, ФАК"
];

return $data;
?>