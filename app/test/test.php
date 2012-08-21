<?php
//header("Content-Type: text/plain");

require(Reg::path_app()."/creator/mod/pack/class/pack.php");
require(Reg::path_app()."/creator/mod/entity/class/entity.php");
require(Reg::path_app()."/creator/mod/field/class/field.php");
require(Reg::path_app()."/creator/mod/enum/class/enum.php");
require(Reg::path_app()."/creator/mod/field_type/class/field_type.php");


require(Reg::path_app()."/creator/sql/entity.php");


try
{
	

	$str = 
<<<TEXT
exception 'Exception_Creator' with message 'Типа поля с номером "45" не существует.' in /znt/site/znarus/app/creator/mod/field_type/class/field_type.php:134
Stack trace:
#0 /znt/site/znarus/app/creator/mod/field_type/class/field_type.php(214): ZN_Field_Type::is_id('45')
#1 /znt/site/znarus/app/creator/mod/field/class/field.php(189): ZN_Field_Type::get_type('45')
#2 /znt/site/znarus/app/creator/mod/field/act/edit_post.php(8): ZN_Field::edit('45', 'ID', 'ID', '3', '', '0', '', '41')
#3 [internal function]: _field_edit_post()
#4 /znt/site/znarus/app/creator/index.php(149): call_user_func('_field_edit_pos...')
#5 /znt/site/znarus/app/sys/main.php(63): require('/znt/site/znaru...')
#6 /znt/site/znarus/www/index.php(2): require('/znt/site/znaru...')
#7 {main}
TEXT;
	
	$str = nl2br($str);
	
	echo $str;

	
}
catch (Exception $e)
{
	echo $e->__toString();
	echo $e->getMessage();
}

?>
