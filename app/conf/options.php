<?php
/**
 * Опции
 */

/* Версия CMS */
const CMS_NAME = "znarus";
const CMS_VERSION = "0.8a";
const CMS_URL = "https://github.com/hharek/znarus";

/* Прописывать заголовок UTF-8 */
const HEADER_UTF8 = false;

/* Отображение ошибок */
const ERROR_REPORTING = true;

/* Режим работы с файлами (sys|ftp) */
const FILE_MANAGER = "sys";

/* Данные для подключения по FTP, если FILE_MANAGER = ftp */
const FILE_FTP_HOST = "127.0.0.1";
const FILE_FTP_USER = "";
const FILE_FTP_PASS = "";
const FILE_FTP_PATH_APP = "";
const FILE_FTP_PATH_WWW = "";
const FILE_FTP_PORT = 21;
const FILE_FTP_SSL = false;

/* Данные для поключиния к PostgreSQL */
const DB_PORT = "5432";							/* Порт */
const DB_PERSISTENT = false;					/* Постоянное соединение */
const DB_SSL = "disable";						/* Использовать SSL (disable | prefer | require) */
const DB_LOG = false;							/* Ввести отчёт запросов к БД */
const DB_LOG_FILE = DIR_VAR . "/db.log";		/* Файл отчётов */

/* Урлы */
const URL_CONSTR = "constr";					/* Урл конструктора */
const URL_ADMIN = "admin";						/* Урл админки */
const URL_AJAX = "ajax";						/* Урл аякса */
const URL_TEST = "test";						/* Урл для тестирования */
const URL_END = "";								/* Окончание урла */

/* Пароли */
const PASSWORD_BCRYPT_COST = 10;				/* Цена хэша пароля. От 04 до 12. Чем больше тем сложнее и дольше получать хэш через bcrypt */
const PASSWORD_LENGTH_MIN = 3;					/* Минимальное длина пароля */
const PASSWORD_LENGTH_MAX = 30;					/* Максимальная длина пароля */

/* Данные по администратору (root) */
const ROOT_NAME = "root";						/* Имя */
const ROOT_NAME_FULL = "Администратор";			/* Полное имя */

/* Данные по отправителю писем. SMTP */
const SENDER = true;							/* Отправлять письма */
const SENDER_SMTP = false;						/* Использовать SMTP */
const SENDER_SMTP_HOST = "127.0.0.1";			/* SMTP. Хост */
const SENDER_SMTP_PORT = 587;					/* SMTP. Порт (587|25|465) */
const SENDER_SMTP_SECURE = false;				/* SMTP. Использовать ли безопасное соединение */
const SENDER_SMTP_SECURE_TYPE = "ssl";			/* SMTP. Тип безопасного соединения (ssl|tls) */
const SENDER_SMTP_AUTH = false;					/* SMTP. Использовать авторизацию */
const SENDER_SMTP_AUTH_USERNAME = "";			/* SMTP авторизация. Имя */
const SENDER_SMTP_AUTH_PASSWORD = "";			/* SMTP авторизация. Пароль */

/* Версии данных */
const VERSION_TYPE = "dba";						/* Тип хранения версий */
const VERSION_FILE_DIR = DIR_VAR . "/version";	/* Папка для хранения файлов с версиями (если тип file)*/
const VERSION_DBA = DIR_VAR . "/version.dba";	/* Файл dba (если тип dba) */
const VERSION_DBA_HANDLER = "db4";				/* Тип dba-файла (если тип dba) */

/* Черновик */
const DRAFT_TYPE = "dba";						/* Тип хранения черновиков */
const DRAFT_FILE_DIR = DIR_VAR . "/draft";		/* Папка для хранения файлов с черновиками (если тип dba) */
const DRAFT_DBA = DIR_VAR . "/draft.dba";		/* Файл dba (если тип dba) */
const DRAFT_DBA_HANDLER = "db4";				/* Тип dba-файла (если тип dba) */

/* Аякс */
const AJAX_TOKEN_GET = "_token";				/* Наименование GET переменной, необходимой для запуска аякса */
const AJAX_TOKEN_COOKIE = "_token";				/* Наименование COOKIE переменной, необходимой для запуска аякса */

/* Кэширование */
const CACHE_ENABLE = true;						/* Использовать кэширование */
const CACHE_DB_CORE_ENABLE = true;				/* Использовать кэширование БД core */
const CACHE_DB_ENABLE = true;					/* Использовать кэширование БД public */
const CACHE_ROUTE_ENABLE = true;				/* Использовать кэширование маршрутов */
const CACHE_PAGE_ENABLE = true;					/* Использовать кэширование страниц */

/* Тип кэширования (file|memcache|dba|mixed) и опции для подключения */
const CACHE_DB_CORE_TYPE = "dba";						/* Тип кэширования для БД core */
const CACHE_DB_TYPE = "dba";							/* Тип кэширования для БД public */
const CACHE_ROUTE_TYPE = "dba";							/* Тип кэширования для маршрутов */	
const CACHE_PAGE_TYPE = "dba";							/* Тип кэширования для страниц */
const CACHE_FILE_DIR = DIR_VAR . "/cache";				/* Папка для кэширования (если тип file) */
const CACHE_MEMCACHE_SOCKET = "/tmp/memcached.sock";	/* Файл-сокет подключения к Memcache (если тип memcache) */
const CACHE_MEMCACHE_HOST = "127.0.0.1";				/* Хост для подключения к Memcache (если тип memcache) */
const CACHE_MEMCACHE_PORT = 11211;						/* Порт для подключения к Memcache (если тип memcache) */
const CACHE_DBA_HANDLER = "db4";						/* Тип dba (если тип dba) */

/* Папка с инструментами (конструктор, админка) */
const DIR_TOOLS = DIR_APP . "/tools";

/* Конструктор */
const CONSTR_ENABLE = true;							/* Включить конструктор */
const CONSTR_EXE_USLEEP = 100000;					/* Задержка выполнения exe в микросекундах */
const CONSTR_HASH_DEFAULT = "#module/list";			/* Хэш по умолчанию */
const CONSTR_SESSION_TIME = 60 * 60 * 24 * 2;		/* Время хранения сессии в секундах (2 дня) */
const CONSTR_AUTH_SLEEP = 1;						/* Задержка в секундах перед авторизацией */
const CONSTR_IP_ACCESS_MODE = "deny_all";			/* Режим доступа по IP (allow_all, allow_all_except, deny_all, deny_all_except) */
const CONSTR_IP_ACCESS = [];						/* IP-адреса связанные с доступом по IP */

/* Админка */
const ADMIN_ENABLE = true;							/* Включить админку */
const ADMIN_EXE_USLEEP = 100000;					/* Задержка выполнения exe в микросекундах */
const ADMIN_HASH_DEFAULT = "#_service/module";		/* Хэш по умолчанию */
const ADMIN_SESSION_TIME = 60 * 60 * 24 * 2;		/* Время хранения сессии в секундах */
const ADMIN_AUTH_SLEEP = 1;							/* Задержка в секундах перед авторизацией */
const ADMIN_IP_ACCESS_MODE = "allow_all";			/* Режим доступа по IP (allow_all, allow_all_except, deny_all, deny_all_except) */
const ADMIN_IP_ACCESS = [];							/* IP-адреса связанные с доступом по IP */

/* JSEncrypt */
const JSENCRYPT_AUTH = true;									/* Шифровать поля перед отправкой открытым ключём */
const JSENCRYPT_PRIVATE_KEY = DIR_APP . "/conf/private.pem";	/* Файл с закрытым ключом */
const JSENCRYPT_PUBLIC_KEY = DIR_APP . "/conf/public.pem";		/* Файл с открытым ключом */

/* Другое */
const LAST_MODIFIED_FORMAT_DATE = "D, d M Y H:i:s T";	/* Формат даты заголовка Last-Modified (для функции gmdate) */
const FRONT_INFO_GET = "_front_info";					/* Наименование GET переменной для получения информации по странице */
?>