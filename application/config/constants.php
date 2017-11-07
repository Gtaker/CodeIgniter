<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 显示调试追踪
|--------------------------------------------------------------------------
|
| 如果设置为 TRUE ，当 php 报错时将会显示一个错误追踪。
| 如果 error_reporting 设置为不报错，那么无论该值设置为什么，都不会显示错误追踪。
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| 文件和目录模式
|--------------------------------------------------------------------------
|
| 这些设置将会在查看或设置文件系统时被使用，
| 默认设置将会适当的保护服务器的安全，
| 但是在某个环境下，你或许希望（甚至是需要）改变默认值
| （ Apache 为每个用户分配一个单独的进程，在 Apache suEXEC 处理的 CGI 请求下运行 PHP,等等）。
| 总是应该使用八进制来正确的设置这些模式。
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| 文件流模式
|--------------------------------------------------------------------------
|
| 这些模式可以在调用 fopen()/popen() 函数时使用
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // 截断现有的文件数据, 小心使用
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // 截断现有的文件数据, 小心使用
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| 退出状态码
|--------------------------------------------------------------------------
|
| 当脚本调用 exit() 函数时，使用这些模式来表示状态。
| 虽然这里没有通用标准的错误码，但是有一些宽泛的约定。
| 有三个类似的约定将在下面被提到，以供想要使用的人选择。
| 虽然CodeIgniter默认使用这些规范的最小叠加（注：交集）,
| 但仍为未来的版本和用户应用留有余地。
|
| 指定退出状态码的三个主要约定如下：
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
        (这个链接同样包含其他的 GNU-specific 规范)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // 没有错误
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // 一般错误
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // 配置错误
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // 文件没有找到
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // 类不存在
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // 类中的方法不存在
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // 无效的用户输入
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // 数据库错误
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // 可分配的最小错误代码
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // 可分配的最大错误代码
