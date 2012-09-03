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

/**
 * Замена текста для мультибайтовой кодировки utf-8 php.net
 *
 * @param string $needle
 * @param string $replacement
 * @param string $haystack
 * @return bool
 */
function mb_str_replace($needle, $replacement, $haystack)
{
    $needle_len = mb_strlen($needle, "UTF-8");
    $replacement_len = mb_strlen($replacement, "UTF-8");
    $pos = mb_stripos($haystack, $needle, null, "UTF-8");
    while ($pos !== false)
    {
        $haystack = mb_substr($haystack, 0, $pos, "UTF-8") . $replacement
                . mb_substr($haystack, $pos + $needle_len, mb_strlen($haystack, "UTF-8"), "UTF-8");
        $pos = mb_stripos($haystack, $needle, $pos + $replacement_len, "UTF-8");
    }
    return $haystack;
}

/**
 * Аналог функции substr_replace
 * 
 * @param string $string
 * @param string $replacement
 * @param int $start
 * @param int $length
 * @param string $encoding
 * @return string
 */
function mb_substr_replace($string, $replacement, $start, $length = null, $encoding = null)
{
	if (extension_loaded('mbstring') === true)
	{
		$string_length = (is_null($encoding) === true) ? mb_strlen($string) : mb_strlen($string, $encoding);

		if ($start < 0)
		{
			$start = max(0, $string_length + $start);
		}

		else if ($start > $string_length)
		{
			$start = $string_length;
		}

		if ($length < 0)
		{
			$length = max(0, $string_length - $start + $length);
		}

		else if ((is_null($length) === true) || ($length > $string_length))
		{
			$length = $string_length;
		}

		if (($start + $length) > $string_length)
		{
			$length = $string_length - $start;
		}

		if (is_null($encoding) === true)
		{
			return mb_substr($string, 0, $start) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length);
		}

		return mb_substr($string, 0, $start, $encoding) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length, $encoding);
	}

	return (is_null($length) === true) ? substr_replace($string, $replacement, $start) : substr_replace($string, $replacement, $start, $length);
}

?>
