<?php
/**
 * Полезные функции по сео
 */
class ZN_Seo
{
	/**
	 * Подготовить текст для meta description
	 * 
	 * @param string $str
	 * @return string
	 */
	public static function meta_description_prepare($str)
	{
		$str = strip_tags($str);
		$str = trim($str);
		$str = str_replace(["\r", "\n", "\t"], " ", $str);
		$str = preg_replace("#[ ]{2,}#isu", " ", $str);
		$str = html_entity_decode($str, ENT_QUOTES, "UTF-8");
		$str = mb_substr($str, 0, 250);
		
		return $str;
	}
}
?>