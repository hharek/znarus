<?php
/* Исключения в конструкторе */
class Exception_Constr extends Exception {}

/* Исключения в админке */
class Exception_Admin extends Exception {}

/* Исключения срабатываемые при ошибке пользователя */
class Exception_User extends Exception {}

/* Исключения срабатываемые при ошибке в форме */
class Exception_Form extends Exception {}

/* Исключения для 404 Not Found */
class Exception_404 extends Exception {}

/* Исключения для 403 Forbidden  */
class Exception_403 extends Exception {}
?>