<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
| 显示调试追踪
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
| 如果设置为 TRUE ，当 php 报错时将会显示一个错误追踪。
| 如果 error_reporting 设置为失效，那么无论该值设置为什么，都不会显示错误追踪。
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
| 文件和目录模式
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
| 这些设置将会在查看或设置文件系统时被使用，
| 默认设置将会适当的保护服务器的安全，
| 但是在某个环境下，你或许希望（甚至是需要）改变默认值
| （ Apache 为每个用户分配一个单独的进程，在 Apache suEXEC 处理的 CGI 请求下运行 PHP,等等）
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
| 文件流模式
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
| 这些模式可以在调用 fopen()/popen() 函数时使用
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
| 退出状态码
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| 当脚本调用 exit() 函数时，使用这些模式来表示状态。
| 虽然这里没有通用标准的错误码，但是有一些宽泛的约定。
| 有三个类似的约定将在下面被提到，以供想要使用的人选择。
| 虽然CodeIgniter默认使用这些规范的最小叠加（注：交集）,
| 但在未来的版本和用户应用中仍留有余地。
|
| The three main conventions used for determining exit status codes
| are as follows:
| 确定退出状态码的三个主要约定如下：
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
        //（这个链接同样包含其他的 GNU-specific 规范）
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
//没有错误
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
//一般错误
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
//配置错误
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
//文件没有找到
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
//类不存在
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
//类中的方法不存在
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
//无效的用户输入
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
//数据库错误
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
//最小可分配错误代码
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
//最大可分配错误代码
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
