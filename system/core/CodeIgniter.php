<?php
/**
 * CodeIgniter
 *
 * 一款开源的PHP应用开发框架
 *
 * （注：以下为 MIT协议声明 原文）
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 系统初始化文件
 *
 * 载入基础类并处理请求
 *
 * @package		CodeIgniter
 * @subpackage	CodeIgniter
 * @category	Front-controller
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/
 */

/**
 * CodeIgniter 版本
 *
 * @var	string
 *
 */
	const CI_VERSION = '3.1.6';

/*
 * ------------------------------------------------------
 *  载入框架常量
 * ------------------------------------------------------
 */
    // 注：如果 config 目录中存在与当前开发环境同名的目录，
    // 那么载入该目录下的 constants.php文件
	if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/constants.php'))
	{
		require_once(APPPATH.'config/'.ENVIRONMENT.'/constants.php');
	}

	if (file_exists(APPPATH.'config/constants.php'))
	{
		require_once(APPPATH.'config/constants.php');
	}

/*
 * ------------------------------------------------------
 *  载入全局函数
 * ------------------------------------------------------
 */
	require_once(BASEPATH.'core/Common.php');


/*
 * ------------------------------------------------------
 * 安全程序
 * ------------------------------------------------------
 */

if ( ! is_php('5.4'))
{
	ini_set('magic_quotes_runtime', 0);

	if ((bool) ini_get('register_globals'))
	{
		$_protected = array(
			'_SERVER',
			'_GET',
			'_POST',
			'_FILES',
			'_REQUEST',
			'_SESSION',
			'_ENV',
			'_COOKIE',
			'GLOBALS',
			'HTTP_RAW_POST_DATA',
			'system_path',
			'application_folder',
			'view_folder',
			'_protected',
			'_registered'
		);

		$_registered = ini_get('variables_order');
		foreach (array('E' => '_ENV', 'G' => '_GET', 'P' => '_POST', 'C' => '_COOKIE', 'S' => '_SERVER') as $key => $superglobal)
		{
			if (strpos($_registered, $key) === FALSE)
			{
				continue;
			}

			foreach (array_keys($$superglobal) as $var)
			{
				if (isset($GLOBALS[$var]) && ! in_array($var, $_protected, TRUE))
				{
					$GLOBALS[$var] = NULL;
				}
			}
		}
	}
}


/*
 * ------------------------------------------------------
 *  注册自定义的错误处理函数，这样我们就可以将 PHP 错误记入日志
 * ------------------------------------------------------
 */
	set_error_handler('_error_handler');
	set_exception_handler('_exception_handler');
	register_shutdown_function('_shutdown_handler');

/*
 * ------------------------------------------------------
 * 设置子类前缀
 * ------------------------------------------------------
 *
 * 通常"子类前缀"是在配置文件（注：application/config/config.php）中设置的。
 * 子类前缀使 CI 知道，是否在本地项目的 "libraries" 目录中通过类库扩展了核心类。
 * 由于 CI 允许配置选项在 index.php 文件中通过数据重写，在开始前，
 * 我们需要知道子类前缀是否已经被重写。如果是的话，我们将在载入任何类前，设置重写后的值，
 * 提示：因为配置文件将会被缓存，所以在这里加载它并没有什么坏处。
 */
	if ( ! empty($assign_to_config['subclass_prefix']))
	{
		get_config(array('subclass_prefix' => $assign_to_config['subclass_prefix']));
	}

/*
 * ------------------------------------------------------
 * 我们是否应该使用 Composer 自动加载器
 * ------------------------------------------------------
 */
	if ($composer_autoload = config_item('composer_autoload'))
	{
		if ($composer_autoload === TRUE)
		{
			file_exists(APPPATH.'vendor/autoload.php')
				? require_once(APPPATH.'vendor/autoload.php')
				: log_message('error', '$config[\'composer_autoload\'] is set to TRUE but '.APPPATH.'vendor/autoload.php was not found.');
		}
		elseif (file_exists($composer_autoload))
		{
			require_once($composer_autoload);
		}
		else
		{
			log_message('error', 'Could not find the specified $config[\'composer_autoload\'] path: '.$composer_autoload);
		}
	}

/*
 * ------------------------------------------------------
 * 启动定时器...滴答滴答...
 * ------------------------------------------------------
 */
	$BM =& load_class('Benchmark', 'core');
	$BM->mark('total_execution_time_start');
	$BM->mark('loading_time:_base_classes_start');

/*
 * ------------------------------------------------------
 *  实例化钩子类
 * ------------------------------------------------------
 */
	$EXT =& load_class('Hooks', 'core');

/*
 * ------------------------------------------------------
 *  是否存在一个 "pre_system" 钩子？
 * ------------------------------------------------------
 */
	$EXT->call_hook('pre_system');

/*
 * ------------------------------------------------------
 *  实例化配置类
 * ------------------------------------------------------
 *
 * 提示：首先加载 Config 类是极其重要的，
 * 因为大多数其他类都直接或间接地依赖于它。
 *
 */
	$CFG =& load_class('Config', 'core');

	// Do we have any manually set config items in the index.php file?
    // 我们是否在 index.php 文件中手动设置了配置选项？
	if (isset($assign_to_config) && is_array($assign_to_config))
	{
		foreach ($assign_to_config as $key => $value)
		{
			$CFG->set_item($key, $value);
		}
	}

/*
 * ------------------------------------------------------
 * 与字符集相关的重要东西
 * ------------------------------------------------------
 *
 * 配置 mbstring 和/或 iconv 扩展（如果它们被启用），
 * 并设置 MB_ENABLED 和 ICONV_ENABLED 常量，
 * 以便于我们不必重复调用 extension_loaded() 或 function_exists()函数。
 *
 * 提示：UTF-8 类依赖于该操作。
 * 它在它的构造函数中使用该依赖，但是它并不依赖于具体的类。
 *
 */
	$charset = strtoupper(config_item('charset'));
	ini_set('default_charset', $charset);

	if (extension_loaded('mbstring'))
	{
		define('MB_ENABLED', TRUE);
        // mbstring.internal_encoding 自 PHP 5.6 版本开始被弃用，
        // 使用它将会提示一个 E_DEPRECATED 类型的错误信息。
		@ini_set('mbstring.internal_encoding', $charset);
        // 这是 mb_convert_encoding() 去除无效字符所必须的。
        // 这被 CI_Utf8 所利用，但也与 iconv 保持一致性。
		mb_substitute_character('none');
	}
	else
	{
		define('MB_ENABLED', FALSE);
	}

    // 虽然这里可以用 ICONV_IMOL 常量，但是 PHP 手册中提到，
    // 使用 iconv 的预定常量将会影响健壮性（"strongly discouraged"）。
	if (extension_loaded('iconv'))
	{
		define('ICONV_ENABLED', TRUE);
        // iconv.internal_encoding 自 PHP 5.6 版本开始被弃用，
        // 使用它将会提示一个 E_DEPRECATED 类型的错误信息。
		@ini_set('iconv.internal_encoding', $charset);
	}
	else
	{
		define('ICONV_ENABLED', FALSE);
	}

	if (is_php('5.6'))
	{
		ini_set('php.internal_encoding', $charset);
	}

/*
 * ------------------------------------------------------
 *  载入兼容性
 * ------------------------------------------------------
 */

	require_once(BASEPATH.'core/compat/mbstring.php');
	require_once(BASEPATH.'core/compat/hash.php');
	require_once(BASEPATH.'core/compat/password.php');
	require_once(BASEPATH.'core/compat/standard.php');

/*
 * ------------------------------------------------------
 *  实例化 UTF-8 类
 * ------------------------------------------------------
 */
	$UNI =& load_class('Utf8', 'core');

/*
 * ------------------------------------------------------
 *  实例化 URL 类
 * ------------------------------------------------------
 */
	$URI =& load_class('URI', 'core');

/*
 * ------------------------------------------------------
 *  实例化路由类并设置路由
 * ------------------------------------------------------
 */
	$RTR =& load_class('Router', 'core', isset($routing) ? $routing : NULL);

/*
 * ------------------------------------------------------
 *  实例化输出类
 * ------------------------------------------------------
 */
	$OUT =& load_class('Output', 'core');

/*
 * ------------------------------------------------------
 *  是否存在一个有效的缓存文件，如果是的话，那我们就完活了...
 * ------------------------------------------------------
 */
	if ($EXT->call_hook('cache_override') === FALSE && $OUT->_display_cache($CFG, $URI) === TRUE)
	{
		exit;
	}

/*
 * -----------------------------------------------------
 * 加载针对 xss（跨站点脚本攻击） 和 csrf（跨站点请求伪造） 的安全类
 * -----------------------------------------------------
 */
	$SEC =& load_class('Security', 'core');

/*
 * ------------------------------------------------------
 *  载入 Input 类，并清理全局变量
 * ------------------------------------------------------
 */
	$IN	=& load_class('Input', 'core');

/*
 * ------------------------------------------------------
 *  载入语言类
 * ------------------------------------------------------
 */
	$LANG =& load_class('Lang', 'core');

/*
 * ------------------------------------------------------
 *  载入应用控制器和本地控制器
 * ------------------------------------------------------
 *
 */
	// Load the base controller class
    // 载入基础控制器类
	require_once BASEPATH.'core/Controller.php';

	/**
     * 参考 CI_Controller 方法
	 *
     * 返回当前 CI 的实例化对象
	 *
	 * @return CI_Controller
	 */
	function &get_instance()
	{
		return CI_Controller::get_instance();
	}

	if (file_exists(APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php'))
	{
		require_once APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php';
	}

    // 设置一个基准标记点
	$BM->mark('loading_time:_base_classes_end');

/*
 * ------------------------------------------------------
 *  完整性检测
 * ------------------------------------------------------
 *
 *  路由类已经验证了请求，现在我们有三个选项：
 *
 *  1) 一个空的类名，且默认控制器不存在；
 *  2) 一个没有通过 file_exists 检查的查询字符串
 *  3) 一个没有对应的页面的合法的请求
 *
 *  以上所有情况，都会被处理为 404 错误。
 *
 *  此外，应用控制器或加载器类中的所有类，都不能通过 URI 调用,
 *  控制器方法也不能以下划线开头。
 */

	$e404 = FALSE;
	$class = ucfirst($RTR->class);
	$method = $RTR->method;

	if (empty($class) OR ! file_exists(APPPATH.'controllers/'.$RTR->directory.$class.'.php'))
	{
		$e404 = TRUE;
	}
	else
	{
		require_once(APPPATH.'controllers/'.$RTR->directory.$class.'.php');

		if ( ! class_exists($class, FALSE) OR $method[0] === '_' OR method_exists('CI_Controller', $method))
		{
			$e404 = TRUE;
		}
		elseif (method_exists($class, '_remap'))
		{
			$params = array($method, array_slice($URI->rsegments, 2));
			$method = '_remap';
		}
		elseif ( ! method_exists($class, $method))
		{
			$e404 = TRUE;
		}
		/**
         * 不要更改这些代码，没有其他要做的了！
		 *
         * - 对于一个非公共方法，method_exists() 会返回 true，它通过了之前的 elseif
         * - is_callable() 对于 PHP 4 类型的构造方法，会返回 false，就算它拥有一个 __construct()
         * - method_exists($class, '__construct') 将不会得到预期的结果，因为 CI_Controller::__construct() 已被继承
         * - 人们只会抱怨这些代码不能正常运作，即使已经注明了不应该那么做。
		 *
         * ReflectionMethod::isConstructor()
         * 是知道哪个方法将会被作为构造方法执行的唯一可靠检查。
		 */
		elseif ( ! is_callable(array($class, $method)))
		{
			$reflection = new ReflectionMethod($class, $method);
			if ( ! $reflection->isPublic() OR $reflection->isConstructor())
			{
				$e404 = TRUE;
			}
		}
	}

	if ($e404)
	{
		if ( ! empty($RTR->routes['404_override']))
		{
			if (sscanf($RTR->routes['404_override'], '%[^/]/%s', $error_class, $error_method) !== 2)
			{
				$error_method = 'index';
			}

			$error_class = ucfirst($error_class);

			if ( ! class_exists($error_class, FALSE))
			{
				if (file_exists(APPPATH.'controllers/'.$RTR->directory.$error_class.'.php'))
				{
					require_once(APPPATH.'controllers/'.$RTR->directory.$error_class.'.php');
					$e404 = ! class_exists($error_class, FALSE);
				}
                // 我们是否在一个目录中？如果是，检查全局覆盖
				elseif ( ! empty($RTR->directory) && file_exists(APPPATH.'controllers/'.$error_class.'.php'))
				{
					require_once(APPPATH.'controllers/'.$error_class.'.php');
					if (($e404 = ! class_exists($error_class, FALSE)) === FALSE)
					{
						$RTR->directory = '';
					}
				}
			}
			else
			{
				$e404 = FALSE;
			}
		}

        // 我们是否重新设置了 $e404 标记？如果是，设置 rsegments ，从索引 1 开始
		if ( ! $e404)
		{
			$class = $error_class;
			$method = $error_method;

			$URI->rsegments = array(
				1 => $class,
				2 => $method
			);
		}
		else
		{
			show_404($RTR->directory.$class.'/'.$method);
		}
	}

	if ($method !== '_remap')
	{
		$params = array_slice($URI->rsegments, 2);
	}

/*
 * ------------------------------------------------------
 *  是否存在一个 "pre_controller" 钩子？
 * ------------------------------------------------------
 */
	$EXT->call_hook('pre_controller');

/*
 * ------------------------------------------------------
 *  实例化请求类
 * ------------------------------------------------------
 */
    // 标记一个开始点，所以我们可以对控制器进行基准测试
	$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_start');

	$CI = new $class();

/*
 * ------------------------------------------------------
 *  是否存在一个 "post_controller_constructor" 钩子？
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_controller_constructor');

/*
 * ------------------------------------------------------
 *  调用请求的方法
 * ------------------------------------------------------
 */
	call_user_func_array(array(&$CI, $method), $params);

    // 标记基准终点
	$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_end');

/*
 * ------------------------------------------------------
 *  是否存在一个 "post_controller" 钩子？
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_controller');

/*
 * ------------------------------------------------------
 *  将最终渲染的输出发送到浏览器
 * ------------------------------------------------------
 */
	if ($EXT->call_hook('display_override') === FALSE)
	{
		$OUT->_display();
	}

/*
 * ------------------------------------------------------
 *  是否存在一个 "post_system" 钩子？
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_system');
