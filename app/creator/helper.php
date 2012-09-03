<?php
class Helper
{
	public static $zn_error;
	
	/**
	 * Выполение функций
	 * 
	 * @param type $func
	 * @param type $args 
	 */
	public static function __callStatic($func, $args) 
	{
		if(method_exists(__CLASS__, "_".$func))
		{
			return	call_user_func_array("self::_".$func, $args);
		}
	}
		
	/**
	 * Добавить предупреждение об ошибке в форме
	 * 
	 * @param string $name 
	 * @return string
	 */
	private static function _warn($name)
	{
		if(!Reg::_isset("zn_error"))
		{return "";}
		
		$zn_error = Reg::zn_error();
		if(!isset ($zn_error[$name]))
		{return "";}
		
		$zn_error[$name] = htmlentities($zn_error[$name], ENT_COMPAT, "UTF-8");
		
		ob_start();
		require(Reg::path_app()."/creator/tpl/warn.phtml");
		$str = ob_get_contents();
		ob_end_clean();
		
		return $str;
	}
}
?>
