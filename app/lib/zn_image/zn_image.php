<?php
class ZN_Image
{
	/**
	 * Проверка на рисунок
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function is_image($file)
	{
		if(!is_file($file))
		{throw new Exception("Файла \"{$file}\" не существует");}
		
		$settings = @getimagesize($file);
		if(empty($settings))
		{return false;}
		
		return true;
	}
	
	/**
	 * Проверка является ли файл иконкой
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function is_icon($file)
	{
		if(floatval(phpversion()) < 5.3)
		{return true;}
		
		$nastr = @getimagesize($file);
		if(empty($nastr))
		{return false;}
		
		if($nastr['mime'] != "image/vnd.microsoft.icon")
		{return false;}
		
		return true;
	}
	
	/**
	 * Получить настройки
	 *
	 * @param string $file
	 * @return array
	 */
	public static function get_settings($file)
	{
		if(!is_file($file))
		{throw new Exception("Файла \"{$file}\" не существует");}
		
		$nastr = @getimagesize($file);
		if(empty($nastr))
		{throw new Exception("Файл \"{$file}\" не является рисунком");}
		
		$settings = array();
		$settings['width'] = $nastr[0];
		$settings['height'] = $nastr[1];
		switch ($nastr[2])
		{
			case IMAGETYPE_GIF:
			{$settings['type'] = "gif";}
			break;
			
			case IMAGETYPE_JPEG:
			{$settings['type'] = "jpg";}
			break;
			
			case IMAGETYPE_PNG:
			{$settings['type'] = "png";}
			break;
			
			default:
			{$settings['type'] = "none";}
			break;
		}
		
		return $settings;
	}
	
	/**
	 * Проверка на соответствие размерам
	 *
	 * @param string $file
	 * @param string $width (>100, 100, <100)
	 * @param string $height (>100, 100, <100)
	 * @return bool
	 */
	public static function check($file, $width=0, $height=0)
	{
		$settings = self::get_settings($file);
		
		/* Щирина */
		if(intval(trim($width)) != 0)
		{
			$width = trim($width);
			if(!empty($width) and !preg_match("#^(>|>=|<|<=|=)?([0-9]+)$#isu", $width))
			{throw new Exception("Ширина \"{$width} px.\"  задана неверно");}
			
			if(!empty($sovpal[1]))
			{
				switch ($sovpal[1])
				{
					case ">":
					{
						if($settings['width'] <= $width)
						{throw new Exception("Ширина должна быть больше \"{$width} px.\".");}
					}
					break;
					
					case ">=":
					{
						if($settings['width'] < $width)
						{throw new Exception("Ширина должна быть больше, либо равна \"{$width} px.\".");}
					}
					break;
					
					case "<":
					{
						if($settings['width'] >= $width)
						{throw new Exception("Ширина должна быть меньше \"{$width} px.\".");}
					}
					break;
					
					case "<=":
					{
						if($settings['width'] > $width)
						{throw new Exception("Ширина должна быть меньше, либо равна \"{$width} px.\".");}
					}
					break;
					
					case "=":
					{
						if($settings['width'] != $width)
						{throw new Exception("Ширина должна быть равна \"{$width} px.\".");}
					}
					break;
				}
			}
			else 
			{
				if($settings['width'] != $width)
				{throw new Exception("Ширина должна быть равна \"{$width} px.\".");}
			}
		}
		
		/* Высота */
		if(intval(trim($height)) != 0)
		{
			$height = trim($height);
			if(!empty($height) and !preg_match("#^(>|>=|<|<=|=)?([0-9]+)$#isu", $height, $sovpal))
			{throw new Exception("Высота \"{$height}\" задана неверно");}
			$height = intval($sovpal[2]);
			
			if(!empty($sovpal[1]))
			{
				switch ($sovpal[1])
				{
					case ">":
					{
						if($settings['height'] <= $height)
						{throw new Exception("Высота должна быть больше \"{$height} px.\".");}
					}
					break;
					
					case ">=":
					{
						if($settings['height'] < $height)
						{throw new Exception("Высота должна быть больше, либо равна \"{$height} px.\".");}
					}
					break;
					
					case "<":
					{
						if($settings['height'] >= $height)
						{throw new Exception("Высота должна быть меньше \"{$height} px.\".");}
					}
					break;
					
					case "<=":
					{
						if($settings['height'] > $height)
						{throw new Exception("Высота должна быть меньше, либо равна \"{$height} px.\".");}
					}
					break;
					
					case "=":
					{
						if($settings['height'] != $height)
						{throw new Exception("Высота должна быть равна \"{$height} px.\".");}
					}
					break;
				}
			}
			else 
			{
				if($settings['height'] != $height)
				{throw new Exception("Высота должна быть равна \"{$height} px.\".");}
			}
		}
		
		return true;
	}
	
	/**
	 * Конвертировать рисунок из одного расширения в другой
	 *
	 * @param string $file_in
	 * @param string $file_out
	 * @return bool
	 */
	public static function convert($file_in, $file_out)
	{
		/* Проверка */
		$settings = self::get_settings($file_in);
		
		if(!is_file($file_in))
		{throw new Exception("Файла \"{$file_in}\" не существует");}
		
		if(empty($file_out) or !preg_match("#\.(jpg|png|gif)$#isu", $file_out, $sovpal))
		{throw new Exception("Имя исходящего файла задано неверно");}
		$ext = $sovpal[1];
		
		if($settings['type'] == $ext)
		{ 
			if(!@copy($file_in, $file_out))
			{
				$error = error_get_last();
				throw new Exception($error['message']);
			}
		}
		
		/* Входящий файл */
		switch ($settings['type'])
		{
			case "jpg":
			{$im = imagecreatefromjpeg($file_in);}
			break;
			
			case "png":
			{$im = imagecreatefrompng($file_in);}
			break;
			
			case "gif":
			{$im = imagecreatefromgif($file_in);}
			break;
		}
		
		/* Исходящий файл */
		if($file_in == $file_out)
		{@unlink($file_in);}
		
		switch ($ext)
		{
			case "jpg":
			{ 
				if(!@imagejpeg($im, $file_out, 100))
				{
					$error = error_get_last();
					throw new Exception($error['message']);
				}
			}
			break;
			
			case "png":
			{
				if(!@imagepng($im, $file_out))
				{
					$error = error_get_last();
					throw new Exception($error['message']);
				}
			}
			break;
			
			case "gif":
			{
				if(!@imagegif($im, $file_out))
				{
					$error = error_get_last();
					throw new Exception($error['message']);
				}
			}
			break;
		}
		
		/* Очистим память */
		imagedestroy($im);
		
		return true;
	}
	
	/**
	 * Изменить размер
	 *
	 * @param string $file_in
	 * @param int $width
	 * @param int $height
	 * @param string $file_out	
	 * @param string $method (>,<,=)
	 * @param bool $enlarge
	 * @return bool
	 */
	public static function resize($file_in, $width, $height, $file_out, $method=">", $enlarge=false)
	{
		/* Проверка */
		$settings = self::get_settings($file_in);
		$width_in = $settings['width'];
		$height_in = $settings['height'];
		
		$width = intval($width);
		$height = intval($height);
		
		if(empty($file_out))
		{throw new Exception("Не задано имя исходящего файла");}
		
		if(preg_match("#\.(jpg|png|gif)$#isu", $file_out, $sovpal))
		{$ext = $sovpal[1];}
		else 
		{$ext = $settings['type'];}
		
		/* Метод */
		if(!in_array($method, array(">", "<", "=")))
		{throw new Exception("Метод задан неверно");}
		
		/* Можно ли увеличивать */
		settype($enlarge, "boolean");
		
		/* Увеличивать не надо */
		if($enlarge == false and $width > $width_in and $height > $height_in)
		{
			$width_out = $width_in;
			$height_out = $height_in;
			if($file_in != $file_out)
			{
				if(!@copy($file_in, $file_out))
				{throw new Exception("Не удалось создать рисунок \"{$file_out}\"");}
			}
			
			return true;
		}
		
		/* Уменьшить размер */
		switch ($method)
		{
			/* Не больше заданного размера */
			case ">":
			{
				if(($width/$width_in) <= ($height/$height_in))
				{
					$width_out = $width;
					$height_out = $height_in * ($width/$width_in);
				}
				else 
				{
					$height_out = $height;
					$width_out = $width_in * ($height/$height_in);
				}
			}
			break;
			
			/* Не меньше заданного размера */
			case "<":
			{
				if(($width/$width_in) >= ($height/$height_in))
				{
					$width_out = $width;
					$height_out = $height_in * ($width/$width_in);
					$y = ($height_out / 2) - ($height / 2);
					$x = 0;
				}
				else 
				{
					$height_out = $height;
					$width_out = $width_in * ($height/$height_in);
					$x = ($width_out / 2) - ($width / 2);
					$y = 0;
				}
			}
			break;
			
			/* В точности заданный размер */
			case "=":
			{
				$width_out = $width;
				$height_out = $height;
			}
			break;
		}
		
		/* Входящий файл */
		switch ($settings['type'])
		{
			case "jpg":
			{$im = imagecreatefromjpeg($file_in);}
			break;
			
			case "png":
			{$im = imagecreatefrompng($file_in);}
			break;
			
			case "gif":
			{$im = imagecreatefromgif($file_in);}
			break;
		}
		
		/* Изменить размер если метод >, = */
		if($method == ">" or $method == "=")
		{
			$im_out = imagecreatetruecolor($width_out, $height_out);
			imagecopyresampled($im_out, $im, 0, 0, 0, 0, $width_out, $height_out, imagesx($im), imagesy($im));
			imagedestroy($im);
		}
		/* Изменить размер если метод < */
		else 
		{
			/* Поготовим к срезу */
			$im_crop = imagecreatetruecolor($width_out, $height_out);
			imagecopyresampled($im_crop, $im, 0, 0, 0, 0, $width_out, $height_out, imagesx($im), imagesy($im));
			imagedestroy($im);
			
			/* Срез */
			$im_out = imagecreatetruecolor($width, $height);
			imagecopy($im_out, $im_crop, 0, 0, $x, $y, imagesx($im_crop), imagesy($im_crop));
			imagedestroy($im_crop);
		}
		
		/* Исходящий */
		if($file_in == $file_out)
		{@unlink($file_in);}
		
		switch ($ext)
		{
			case "jpg":
			{
				if(!@imagejpeg($im_out, $file_out, 100))
				{throw new Exception("Не удалось создать файл \"{$file_out}\"");}
			}
			break;
			
			case "png":
			{
				if(!@imagepng($im_out, $file_out))
				{throw new Exception("Не удалось создать файл \"{$file_out}\"");}
			}
			break;
			
			case "gif":
			{
				if(!imagegif($im_out, $file_out))
				{throw new Exception("Не удалось создать файл \"{$file_out}\"");}
			}
			break;
		}
		imagedestroy($im_out);
		
		return true;
	}
	
	/**
	 * Наложить рисунок на рисунок
	 *
	 * @param string $file_in
	 * @param string $file_apply
	 * @param string $file_out
	 * @param int size
	 * @param string align (left,center,right)
	 * @param string valign (top,middle,bottom)
	 * @param int padding
	 * @return bool
	 */
	public static function apply_image($file_in, $file_apply, $file_out, $size=100, $align="center", $valign="middle", $padding=0)
	{
		/* Проверка */
		$in_settings = self::get_settings($file_in);
		$apply_settings = self::get_settings($file_apply);
		
		if(empty($file_out))
		{throw new Exception("Не задано имя исходящего файла");}
		
		if(preg_match("#\.(jpg|png|gif)$#isu", $file_out, $sovpal))
		{$ext = $sovpal[1];}
		else 
		{$ext = $in_settings['type'];}
		
		$size = intval($size);
		if($size < 0 or $size > 100)
		{throw new Exception("Размер \"{$size}\" задан неверно.");}
		
		if(!in_array($align, array("left", "center", "right")))
		{throw new Exception("Горизонтальное выравнивание \"{$align}\" задано неверно.");}
		
		if(!in_array($valign, array("top","middle","bottom")))
		{throw new Exception("Вертикальное выравнивание \"{$valign}\" задано неверно.");}
		
		$padding = abs(intval($padding));
		
		/* Размер применяемого рисунка */
		$width = $in_settings['width'];
		$height = $in_settings['height'];
		if($size < 100)
		{
			$width = $width * ($size / 100);
			$height = $height * ($size / 100);
		}
		
		$width_apply = $apply_settings['width'];
		$height_apply = $apply_settings['height'];
		
		if(($width/$width_apply) <= ($height/$height_apply))
		{
			$width_out = $width;
			$height_out = $height_apply * ($width/$width_apply);
		}
		else 
		{
			$height_out = $height;
			$width_out = $width_apply * ($height/$height_apply);
		}
		
		/* Координаты применяемого рисунка на основном */
		$x = 0; $y = 0;
		
		switch ($align)
		{
			case "left":
			{$x = $padding;}
			break;
			
			case "center":
			{$x = ($in_settings['width'] / 2) - ($width_out / 2);}
			break;
			
			case "right":
			{$x = ($in_settings['width'] - $width_out) - $padding;}
			break;
		}
		
		switch ($valign)
		{
			case "top":
			{$y = $padding;}
			break;
			
			case "middle":
			{$y = ($in_settings['height'] / 2) - ($height_out / 2);}
			break;
			
			case "bottom":
			{$y = ($in_settings['height'] - $height_out) - $padding;}
			break;
		}
		
		/* Входящий файл */
		switch ($in_settings['type'])
		{
			case "jpg":
			{$im_in = imagecreatefromjpeg($file_in);}
			break;
			
			case "png":
			{$im_in = imagecreatefrompng($file_in);}
			break;
			
			case "gif":
			{$im_in = imagecreatefromgif($file_in);}
			break;
		}
		
		/* Накладываемый файл */
		switch ($apply_settings['type'])
		{
			case "jpg":
			{$im_apply = imagecreatefromjpeg($file_apply);}
			break;
			
			case "png":
			{$im_apply = imagecreatefrompng($file_apply);}
			break;
			
			case "gif":
			{$im_apply = imagecreatefromgif($file_apply);}
			break;
		}
		
		/* Наложить */
		imagecopyresampled($im_in, $im_apply, $x, $y, 0, 0, $width_out, $height_out, imagesx($im_apply), imagesy($im_apply));
		imagedestroy($im_apply);
		
		/* Сохранить */
		if($file_in == $file_out)
		{@unlink($file_in);}
		switch ($ext)
		{
			case "jpg":
			{
				if(!@imagejpeg($im_in, $file_out, 100))
				{throw new Exception("Не удалось создать файл \"{$file_out}\"");}
			}
			break;
			
			case "png":
			{
				if(!@imagepng($im_in, $file_out))
				{throw new Exception("Не удалось создать файл \"{$file_out}\"");}
			}
			break;
			
			case "gif":
			{
				if(!imagegif($im_in, $file_out))
				{throw new Exception("Не удалось создать файл \"{$file_out}\"");}
			}
			break;
		}
		imagedestroy($im_in);
		
		return true;
	}
	
	/**
	 * Вывод рисунка с новыми размерами
	 *
	 * @param string $file_in
	 * @param int $width
	 * @param int $height
	 * @param string $file_name_output
	 * @param bool $download_window
	 * @param string $method (>,<,=)
	 * @return bool
	 */
	public static function output($file_in, $width, $height, $file_name_output="", $download_window=true, $method=">")
	{
		/* Проверка */
		$settings = self::get_settings($file_in);
		$width_in = $settings['width'];
		$height_in = $settings['height'];
		
		$width = intval($width);
		$height = intval($height);
		
		if(empty($file_name_output))
		{$file_name_output = basename($file_in);}
		
		if(preg_match("#\.(jpg|png|gif)$#isu", $file_name_output, $sovpal))
		{$ext = $sovpal[1];}
		else 
		{$ext = $settings['type'];}
		
		/* Метод */
		if(!in_array($method, array(">", "<", "=")))
		{throw new Exception("Метод задан неверно");}
		
		/* Уменьшить размер */
		switch ($method)
		{
			/* Не больше заданного размера */
			case ">":
			{
				if(($width/$width_in) <= ($height/$height_in))
				{
					$width_out = $width;
					$height_out = $height_in * ($width/$width_in);
				}
				else 
				{
					$height_out = $height;
					$width_out = $width_in * ($height/$height_in);
				}
			}
			break;
			
			/* Не меньше заданного размера */
			case "<":
			{
				if(($width/$width_in) >= ($height/$height_in))
				{
					$width_out = $width;
					$height_out = $height_in * ($width/$width_in);
					$y = ($height_out / 2) - ($height / 2);
					$x = 0;
				}
				else 
				{
					$height_out = $height;
					$width_out = $width_in * ($height/$height_in);
					$x = ($width_out / 2) - ($width / 2);
					$y = 0;
				}
			}
			break;
			
			/* В точности заданный размер */
			case "=":
			{
				$width_out = $width;
				$height_out = $height;
			}
			break;
		}
		
		/* Входящий файл */
		switch ($settings['type'])
		{
			case "jpg":
			{$im = imagecreatefromjpeg($file_in);}
			break;
			
			case "png":
			{$im = imagecreatefrompng($file_in);}
			break;
			
			case "gif":
			{$im = imagecreatefromgif($file_in);}
			break;
		}
		
		/* Изменить размер если метод >, = */
		if($method == ">" or $method == "=")
		{
			$im_out = imagecreatetruecolor($width_out, $height_out);
			imagecopyresampled($im_out, $im, 0, 0, 0, 0, $width_out, $height_out, imagesx($im), imagesy($im));
			imagedestroy($im);
		}
		/* Изменить размер если метод < */
		else 
		{
			/* Поготовим к срезу */
			$im_crop = imagecreatetruecolor($width_out, $height_out);
			imagecopyresampled($im_crop, $im, 0, 0, 0, 0, $width_out, $height_out, imagesx($im), imagesy($im));
			imagedestroy($im);
			
			/* Срез */
			$im_out = imagecreatetruecolor($width, $height);
			imagecopy($im_out, $im_crop, 0, 0, $x, $y, imagesx($im_crop), imagesy($im_crop));
			imagedestroy($im_crop);
		}
		
		/* Заголовок */
		if(!$download_window)
		{
			switch ($ext)
			{
				case "jpg";
				{header('Content-type: image/jpeg');}
				break;
			
				case "png";
				{header('Content-type: image/png'); }
				break;
			
				case "gif";
				{header('Content-type: image/gif');}
				break;
			}
		}
		else
		{ 
			header("Content-Type: application/octet-stream;");
			header("Content-Disposition: attachment; filename=\"{$file_name_output}\"");
		}
		
		/* Исходящий */
		switch ($ext)
		{
			case "jpg":
			{
				if(!@imagejpeg($im_out, null, 100))
				{throw new Exception("Не удалось создать файл \"{$file_name_output}\"");}
			}
			break;
			
			case "png":
			{
				if(!@imagepng($im_out))
				{throw new Exception("Не удалось создать файл \"{$file_name_output}\"");}
			}
			break;
			
			case "gif":
			{
				if(!imagegif($im_out))
				{throw new Exception("Не удалось создать файл \"{$file_name_output}\"");}
			}
			break;
		}
		
		imagedestroy($im_out);
		
		return true;
	}
}
?>