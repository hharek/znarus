<?php
/**
 * Редирект
 *
 * @param string $url
 * @return bool
 */
function redirect($url)
{
    header("Location: {$url}");
    exit;
    
    return true;
}

/**
 * Кодирование строки алгоритмом base64 с поддержкой URL
 *
 * @param string $input
 * @return string
 */
function base64_url_encode($input) 
{
	return strtr(base64_encode($input), '+/=', '-_,');
}

/**
 * Декодирование строки закодированной алгоритмом base64 с поддержкой URL
 *
 * @param string $input
 * @return string
 */
function base64_url_decode($input) 
{
	return base64_decode(strtr($input, '-_,', '+/='));
}


?>
