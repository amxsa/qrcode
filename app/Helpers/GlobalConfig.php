<?php


/**
 * 后台身份校验错误代码
 */
define('ERROR_BACKEND_NEED_LOGIN', 98);
define('ERROR_BACKEND_NEED_LOGIN_DESC', '身份校验失败，请重新登录');

//后台缓存
define('REDIS_EFEES_BACKEND_TOKEN', 'EFEES_BACKEND_TOKEN');

//极验验证码id和key
define("CAPTCHA_ID", "3c01a34409ec6acbc346922405c3bf71");
define("PRIVATE_KEY", "19531232dc772bbe1d8f080c89379fa8");

/**
 * api身份校验错误代码
 */
define('ERROR_NO_ACCESS_TOKEN', 100);
define ('ERROR_NO_ACCESS_TOKEN_DESC', '缺少access_token');

define ('ERROR_WROING_ACCESS_PRIVILEGE', 101);
define ('ERROR_WROING_ACCESS_PRIVILEGE_DESC', 'access_token已过期');

define ('ERROR_NO_ACCESS_PRIVILEGE', 102);
define ('ERROR_NO_ACCESS_PRIVILEGE_DESC', '缺少该接口的权限访问');

define ('ERROR_NO_ACCESS_APPID', 103);
define ('ERROR_NO_ACCESS_APPID_DESC', '缺少相关的动态表：');
