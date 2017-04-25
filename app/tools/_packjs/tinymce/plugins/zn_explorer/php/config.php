<?php
const ZN_EXPLORER_DIR_HOME = DIR_PUBLIC;							/* Абсолютный путь к корневой папке (Пример: /home/example/public_html/upload) */
const ZN_EXPLORER_DIR_HOME_URL = "/";							/* Абсолютный урл корневой папки (Пример: /upload) */
const ZN_EXPLORER_DIR_ALLOW	=									/* Разрещённые под папки (array) (По умолчанию: []. Пример: ["news, articles/new"]) */
[
	"upload",
	"images"
];
const ZN_EXPLORER_DIR_HIDDEN_SHOW = false;						/* Разрешить работать с папками начинающиеся на «.» (По умолчанию: false)*/
const ZN_EXPLORER_FILE_HIDDEN_SHOW = false;						/* Разрешить работать с файлами начинающиеся на «.» (По умолчанию: false) */
const ZN_EXPLORER_DENY_FILE_EXTENSION = ["php", "htaccess"];	/* Запрещёные расширения для файлов (array) (По умолчанию: ["php","htaccess"]) */
const ZN_EXPLORER_IMAGE_EXTENSION = ["jpg", "png", "gif"];		/* Расширения для рисунков (array) (По умолчанию: ["jpg", "png", "gif"]) */
const ZN_EXPLORER_ASCII_ONLY = true;							/* Строгое именование файлов и папок (только ASCII символы) (По умолчанию: true) */
const ZN_EXPLORER_UPLOAD_FILE_SIZE_MAX = 2;						/* Максимальный размер файла при закачке в мегабайтах (MB) (int) (По умолчанию: 2) */
const ZN_EXPLORER_LOWER_CASE = true;							/* Преобразовывать имена файлов и папок в нижний регистр (По умолчанию: true) */
?>