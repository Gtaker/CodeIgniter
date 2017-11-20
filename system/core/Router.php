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
 * Router Class
 * 路由类
 *
 * Parses URIs and determines routing
 * 解析 URI 并确定路由
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/routing.html
 */
class CI_Router {

	/**
	 * CI_Config class object
     * CI_Config 类对象
	 *
	 * @var	object
	 */
	public $config;

	/**
	 * List of routes
     * 路由表
	 *
	 * @var	array
	 */
	public $routes =	array();

	/**
	 * Current class name
     * 当前类名
	 *
	 * @var	string
	 */
	public $class =		'';

	/**
	 * Current method name
     * 当前方法名
	 *
	 * @var	string
	 */
	public $method =	'index';

	/**
	 * Sub-directory that contains the requested controller class
     * 包含请求的控制器类的子目录
	 *
	 * @var	string
	 */
	public $directory;

	/**
	 * Default controller (and method if specific)
     * 默认控制器（和方法，如果是具体的话）
	 *
	 * @var	string
	 */
	public $default_controller;

	/**
	 * Translate URI dashes
     * 翻译 URI 的破折号
	 *
	 * Determines whether dashes in controller & method segments
	 * should be automatically replaced by underscores.
     * 确定是否将控制器和方法段中的破折号（-）自动替换为下划线（_）
	 *
	 * @var	bool
	 */
	public $translate_uri_dashes = FALSE;

	/**
	 * Enable query strings flag
     * 允许查询字符串的标记
	 *
	 * Determines whether to use GET parameters or segment URIs
     * 确定使用 GET 参数还是 URI 段
	 *
	 * @var	bool
	 */
	public $enable_query_strings = FALSE;

	// --------------------------------------------------------------------

	/**
	 * Class constructor
     * 类构造函数
	 *
	 * Runs the route mapping function.
     * 启动路由映射函数
	 *
	 * @param	array	$routing
	 * @return	void
	 */
	public function __construct($routing = NULL)
	{
		$this->config =& load_class('Config', 'core');
		$this->uri =& load_class('URI', 'core');

		$this->enable_query_strings = ( ! is_cli() && $this->config->item('enable_query_strings') === TRUE);

		// If a directory override is configured, it has to be set before any dynamic routing logic
        // 如果配置了目录重写，那么必须在任何动态路由逻辑前设置它。
		is_array($routing) && isset($routing['directory']) && $this->set_directory($routing['directory']);
		$this->_set_routing();

		// Set any routing overrides that may exist in the main index file
        // 在主引导文件中可能设置了任何路由重写。
		if (is_array($routing))
		{
			empty($routing['controller']) OR $this->set_class($routing['controller']);
			empty($routing['function'])   OR $this->set_method($routing['function']);
		}

		log_message('info', 'Router Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Set route mapping
     * 设置路由映射
	 *
	 * Determines what should be served based on the URI request,
	 * as well as any "routes" that have been set in the routing config file.
     * 根据 URI 请求和在配置文件中设置的任何"路由（routes）"，
     * 来确定需要提供什么样的服务
	 * @return	void
	 */
	protected function _set_routing()
	{
		// Load the routes.php file. It would be great if we could
		// skip this for enable_query_strings = TRUE, but then
		// default_controller would be empty ...
        // 加载 routes.php 文件
        // 如果我们在 enable_query_string = TRUE 时跳过这个步骤那真是极好的，
        // 不过那样的话默认的控制器就将为空。
		if (file_exists(APPPATH.'config/routes.php'))
		{
			include(APPPATH.'config/routes.php');
		}

		if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
		{
			include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}

		// Validate & get reserved routes
        // 验证并获取预设路由
		if (isset($route) && is_array($route))
		{
			isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
			isset($route['translate_uri_dashes']) && $this->translate_uri_dashes = $route['translate_uri_dashes'];
			unset($route['default_controller'], $route['translate_uri_dashes']);
			$this->routes = $route;
		}

		// Are query strings enabled in the config file? Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
        // 是否在配置文件中启用了查询字符串(query string)？通常 CI 不会使用查询字符串模式，
        // 因为 URI 段的形式对搜索引擎更友好，但是也可以通过配置启用查询字符串模式。
        // 如果该特性被启用，我们采集 目录/类/方法 的方式会有点不一样。
		if ($this->enable_query_strings)
		{
			// If the directory is set at this time, it means an override exists, so skip the checks
            // 如果目录在此时已经被设置，意味着已经存在重写，所以跳过这个检查。
			if ( ! isset($this->directory))
			{
				$_d = $this->config->item('directory_trigger');
				$_d = isset($_GET[$_d]) ? trim($_GET[$_d], " \t\n\r\0\x0B/") : '';

				if ($_d !== '')
				{
					$this->uri->filter_uri($_d);
					$this->set_directory($_d);
				}
			}

			$_c = trim($this->config->item('controller_trigger'));
			if ( ! empty($_GET[$_c]))
			{
				$this->uri->filter_uri($_GET[$_c]);
				$this->set_class($_GET[$_c]);

				$_f = trim($this->config->item('function_trigger'));
				if ( ! empty($_GET[$_f]))
				{
					$this->uri->filter_uri($_GET[$_f]);
					$this->set_method($_GET[$_f]);
				}

				$this->uri->rsegments = array(
					1 => $this->class,
					2 => $this->method
				);
			}
			else
			{
				$this->_set_default_controller();
			}

			// Routing rules don't apply to query strings and we don't need to detect
			// directories, so we're done here
            // 路由规则没有应用查询字符串（query string），
            // 我们就不需要检测目录，所以我们在这里完工了。
			return;
		}

		// Is there anything to parse?
        // 是否有东西需要我们解析？
		if ($this->uri->uri_string !== '')
		{
			$this->_parse_routes();
		}
		else
		{
			$this->_set_default_controller();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set request route
     * 设置请求
	 *
	 * Takes an array of URI segments as input and sets the class/method
	 * to be called.
     * 取一个 URI 段数组作为输入，然后设置要调用的 类/方法。
	 *
	 * @used-by	CI_Router::_parse_routes()
	 * @param	array	$segments	URI segments    URI 段
	 * @return	void
	 */
	protected function _set_request($segments = array())
	{
		$segments = $this->_validate_request($segments);
		// If we don't have any segments left - try the default controller;
		// WARNING: Directories get shifted out of the segments array!
        // 如果我们没有任何剩下的段 - 尝试默认控制器；
        // 警告：目录被移除分段数组
		if (empty($segments))
		{
			$this->_set_default_controller();
			return;
		}

		if ($this->translate_uri_dashes === TRUE)
		{
			$segments[0] = str_replace('-', '_', $segments[0]);
			if (isset($segments[1]))
			{
				$segments[1] = str_replace('-', '_', $segments[1]);
			}
		}

		$this->set_class($segments[0]);
		if (isset($segments[1]))
		{
			$this->set_method($segments[1]);
		}
		else
		{
			$segments[1] = 'index';
		}

		array_unshift($segments, NULL);
		unset($segments[0]);
		$this->uri->rsegments = $segments;
	}

	// --------------------------------------------------------------------

	/**
	 * Set default controller
     * 设置默认控制器
	 *
	 * @return	void
	 */
	protected function _set_default_controller()
	{
		if (empty($this->default_controller))
		{
			show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
		}

		// Is the method being specified?
        // 这是一个指定的类吗？
		if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
		{
			$method = 'index';
		}

		if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
		{
			// This will trigger 404 later
            // 这将在之后触发 404
			return;
		}

		$this->set_class($class);
		$this->set_method($method);

		// Assign routed segments, index starting from 1
        // 分配路由分段，索引从 1 开始
		$this->uri->rsegments = array(
			1 => $class,
			2 => $method
		);

		log_message('debug', 'No URI present. Default controller set.');
	}

	// --------------------------------------------------------------------

	/**
	 * Validate request
     * 验证请求
	 *
	 * Attempts validate the URI request and determine the controller path.
     * 尝试验证 URI 请求，并确定控制器路径
	 *
	 * @used-by	CI_Router::_set_request()
	 * @param	array	$segments	URI segments     URI 段
	 * @return	mixed	URI segments    URI 段
	 */
	protected function _validate_request($segments)
	{
		$c = count($segments);
		$directory_override = isset($this->directory);

		// Loop through our segments and return as soon as a controller
		// is found or when such a directory doesn't exist
        // 遍历我们的段，当遇到一个控制器或所有的目录都不存在时返回。
		while ($c-- > 0)
		{
			$test = $this->directory
				.ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);

			if ( ! file_exists(APPPATH.'controllers/'.$test.'.php')
				&& $directory_override === FALSE
				&& is_dir(APPPATH.'controllers/'.$this->directory.$segments[0])
			)
			{
				$this->set_directory(array_shift($segments), TRUE);
				continue;
			}

			return $segments;
		}

		// This means that all segments were actually directories
        // 这意味着所有的分段都是真实目录
		return $segments;
	}

	// --------------------------------------------------------------------

	/**
	 * Parse Routes
     * 解析路由
	 *
	 * Matches any routes that may exist in the config/routes.php file
	 * against the URI to determine if the class/method need to be remapped.
     * 根据 URI 匹配 config/routes.php 文件中可能存在的任何路由，
     * 以确定是否需要重新映射 类/方法。
	 *
	 * @return	void
	 */
	protected function _parse_routes()
	{
		// Turn the segment array into a URI string
        // 将段数组转换为 URI 字符串
		$uri = implode('/', $this->uri->segments);

		// Get HTTP verb
        // 获取 HTTP 动词
		$http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

		// Loop through the route array looking for wildcards
        // 遍历路由数组，寻找通配符
		foreach ($this->routes as $key => $val)
		{
			// Check if route format is using HTTP verbs
            // 如果路由格式中使用了 HTTP 动词，那么进行检查
			if (is_array($val))
			{
				$val = array_change_key_case($val, CASE_LOWER);
				if (isset($val[$http_verb]))
				{
					$val = $val[$http_verb];
				}
				else
				{
					continue;
				}
			}

			// Convert wildcards to RegEx
            // 将通配符转换为正则表达式
			$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);

			// Does the RegEx match?
            // 是否匹配了一个正则表达式？
			if (preg_match('#^'.$key.'$#', $uri, $matches))
			{
				// Are we using callbacks to process back-references?
                // 我们是否使用回调来处理反向引用？
				if ( ! is_string($val) && is_callable($val))
				{
					// Remove the original string from the matches array.
                    // 从匹配数组中删除原始字符串
					array_shift($matches);

					// Execute the callback using the values in matches as its parameters.
                    // 执行回调，并使用匹配到的值作为回调的参数
					$val = call_user_func_array($val, $matches);
				}
				// Are we using the default routing method for back-references?
                // 我们是否使用默认的路由方法来进行反向引用？
				elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				$this->_set_request(explode('/', $val));
				return;
			}
		}

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
        // 如果我们执行到了这里，意味着我们没有遇到任何匹配的路由，
        // 所以我们设置为默认路由。
		$this->_set_request(array_values($this->uri->segments));
	}

	// --------------------------------------------------------------------

	/**
	 * Set class name
     * 设置类名
	 *
	 * @param	string	$class	Class name      类名
	 * @return	void
	 */
	public function set_class($class)
	{
		$this->class = str_replace(array('/', '.'), '', $class);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current class
     * 获取当前类
	 *
	 * @deprecated	3.0.0	Read the 'class' property instead       改为读取'类'的值
	 * @return	string
	 */
	public function fetch_class()
	{
		return $this->class;
	}

	// --------------------------------------------------------------------

	/**
	 * Set method name
     * 设置方法名
	 *
	 * @param	string	$method	Method name     方法名
	 * @return	void
	 */
	public function set_method($method)
	{
		$this->method = $method;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current method
     * 获取当前方法
	 *
	 * @deprecated	3.0.0	Read the 'method' property instead      改为读取'方法'属性
	 * @return	string
	 */
	public function fetch_method()
	{
		return $this->method;
	}

	// --------------------------------------------------------------------

	/**
	 * Set directory name
     * 设置目录名
	 *
	 * @param	string	$dir	Directory name      目录名
	 * @param	bool	$append	Whether we're appending rather than setting the full value      是（在原值上）追加还是重新设置新的值
	 * @return	void
	 */
	public function set_directory($dir, $append = FALSE)
	{
		if ($append !== TRUE OR empty($this->directory))
		{
			$this->directory = str_replace('.', '', trim($dir, '/')).'/';
		}
		else
		{
			$this->directory .= str_replace('.', '', trim($dir, '/')).'/';
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch directory
     * 获取目录
	 *
	 * Feches the sub-directory (if any) that contains the requested
	 * controller class.
     * 获取包含请求的控制器类的子目录（如果有的话）。
	 *
	 * @deprecated	3.0.0	Read the 'directory' property instead       改为读取目录属性
	 * @return	string
	 */
	public function fetch_directory()
	{
		return $this->directory;
	}

}
