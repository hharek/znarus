<?php
$faq = Faq::select_list();

Reg::title("ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ");
Reg::meta_title("ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ");
Reg::meta_description("Здесь вы можете ответы на часто задаваемые вопросы");
Reg::meta_keywords("вопрос-ответ, чаво, часто задаваемые вопросы, faq, F.A.Q., ответы на вопросы, вопросы и ответы, ФАК");

Reg::path
([
	"FAQ [/вопрос-ответ]"
]);
?>