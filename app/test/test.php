<?php
header("Content-Type: text/plain");

require(Reg::path_app()."/creator/mod/pack/class/pack.php");
require(Reg::path_app()."/creator/mod/entity/class/entity.php");
require(Reg::path_app()."/creator/mod/field/class/field.php");
require(Reg::path_app()."/creator/mod/enum/class/enum.php");
require(Reg::path_app()."/creator/mod/field_type/class/field_type.php");
require(Reg::path_app()."/creator/mod/unique/class/unique.php");

require (Reg::path_app()."/creator/mod/pack/sql/pack.php");
require (Reg::path_app()."/creator/mod/entity/sql/entity.php");
require (Reg::path_app()."/creator/mod/field/sql/field.php");
require (Reg::path_app()."/creator/mod/enum/sql/enum.php");
require (Reg::path_app()."/creator/mod/unique/sql/unique.php");
require (Reg::path_app()."/creator/mod/constraint/sql/constraint.php");

//Reg::file_app()->set_path(Reg::file_app()->get_path()."/constr");
require (Reg::path_app()."/creator/mod/pack/code/pack.php");
require (Reg::path_app()."/creator/mod/entity/code/entity.php");
require (Reg::path_app()."/creator/mod/entity/data/entity.php");


try
{
	Reg::db()->cache_truncate();
	

}
catch (Exception $e)
{
	echo $e->__toString();
	echo $e->getMessage();
}

?>

