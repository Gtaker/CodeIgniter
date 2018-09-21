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
 * 路由类
 *
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
     * CI_Config 类对象
	 *
	 * @var	object
	 */
	public $config;

	/**
     * 路由表
	 *
	 * @var	array
	 */
	public $routes =	array();

	/**
     * 当前类名
	 *
	 * @var	string
	 */
	public $class =		'';

	/**
     * 当前方法名
	 *
	 * @var	string
	 */
	public $method =	'index';

	/**
     * 包含请求的控制器类的子目录
	 *
	 * @var	string
	 */
	public $directory;

	/**
     * 默认控制器（如果具体点说的话，还有方法）
	 *
	 * @var	string
	 */
	public $default_controller;

	/**
     * 转换 URI 的破折号
	 *
     * 决定是否将控制器和方法段中的破折号（-）自动替换为下划线（_）
	 *
	 * @var	bool
	 */
	public $translate_uri_dashes = FALSE;

	/**
     * 允许查询字符串标记
	 *
     * 决定使用 GET 参数还是 URI 段
	 *
	 * @var	bool
	 */
	public $enable_query_strings = FALSE;

	// --------------------------------------------------------------------

	/**
     * 类构造函数
	 *
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

        // 如果配置了目录重写，那么必须在（使用）任何动态路由逻辑前设置它。
		is_array($routing) && isset($routing['directory']) && $this->set_directory($routing['directory']);
		$this->_set_routing();

        // 在主引导文件中或许设置了路由重写。
		if (is_array($routing))
		{
			empty($routing['controller']) OR $this->set_class($routing['controller']);
			empty($routing['function'])   OR $this->set_method($routing['function']);
		}

		log_message('info', 'Router Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
     * 设置路由映射
	 *
     * 根据 URI 请求和在配置文件中设置的任何"路由（routes）"，
     * 来确定需要提供什么样的服务。
	 * @return	void
	 */
	protected function _set_routing()
	{
        // 加载 routes.php 文件。
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

        // 验证并获取预设路由
		if (isset($route) && is_array($route))
		{
			isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
			isset($route['translate_uri_dashes']) && $this->translate_uri_dashes = $route['translate_uri_dashes'];
			unset($route['default_controller'], $route['translate_uri_dashes']);
			$this->routes = $route;
		}

        // 是否在配置文件中启用了查询字符串(query string)？通常 CI 不会使用查询字符串模式，
        // 因为 URI 段的形式对搜索引擎更友好，但是也可以通过配置启用查询字符串模式。
        // 如果该特性被启用，我们采集 目录/类/方法 的方式将会有点不一样。
		if ($this->enable_query_strings)
		{
            // 如果目录在此时已经被设置，意味着已存在重写，所以跳过这个检查。
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

            // 路由规则没有应用查询字符串（query string），
            // 我们就不需要检测目录，所以我们在这里完工了。
			return;
		}

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
     * 设置请求路由
	 *
     * 取一个 URI 段数组作为输入，然后设置要调用的 类/方法。
	 *
	 * @used-by	CI_Router::_parse_routes()
	 * @param	array	$segments	URI 段
	 * @return	void
	 */
	protected function _set_request($segments = array())
	{
		$segments = $this->_validate_request($segments);
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

        // 这是一个"特别"的方法吗？
		if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
		{
			$method = 'index';
		}

		if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
		{
            // 这将触发 404 错误
			return;
		}

		$this->set_class($class);
		$this->set_method($method);

        // 分配路由分段，索引从 1 开始
		$this->uri->rsegments = array(
			1 => $class,
			2 => $method
		);

		log_message('debug', 'No URI present. Default controller set.');
	}

	// --------------------------------------------------------------------

	/**
     * 验证请求
	 *
     * 尝试验证 URI 请求，并确定控制器路径。
	 *
	 * @used-by	CI_Router::_set_request()
	 * @param	array	$segments	URI 段
	 * @return	mixed	URI 段
	 */
	protected function _validate_request($segments)
	{
		$c = count($segments);
		$directory_override = isset($this->directory);

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

        // 这意味着所有的分段都是真实目录
		return $segments;
	}

	// --------------------------------------------------------------------

	/**
     * 解析路由
	 *
     * 根据 URI 匹配 config/routes.php 文件中可能存在的任何路由，
     * 以确定是否需要重新映射 类/方法。
	 *
	 * @return	void
	 */
	protected function _parse_routes()
	{
        // 将段数组转换为 URI 字符串
		$uri = implode('/', $this->uri->segments);

        // 获取 HTTP 动词
		$http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

        // 遍历路由数组，寻找通配符
		foreach ($this->routes as $key => $val)
		{
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

            // 将通配符转换为正则表达式
			$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);

            // 是否匹配了一个正则表达式？
			if (preg_match('#^'.$key.'$#', $uri, $matches))
			{
                // 我们是否使用回调来处理反向引用？
				if ( ! is_string($val) && is_callable($val))
				{
                    // 从匹配数组中删除原始字符串
					array_shift($matches);

                    // 执行回调，并使用匹配到的值作为回调的参数
					$val = call_user_func_array($val, $matches);
				}
                // 我们是否使用默认的路由方法来进行反向引用？
				elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				$this->_set_request(explode('/', $val));
				return;
			}
		}

        // 如果我们执行到了这里，意味着没有遇到任何匹配的路由，
        // 所以我们设置为默认路由。
		$this->_set_request(array_values($this->uri->segments));
	}

	// --------------------------------------------------------------------

	/**
     * 设置类名
	 *
	 * @param	string	$class	类名
	 * @return	void
	 */
	public function set_class($class)
	{
		$this->class = str_replace(array('/', '.'), '', $class);
	}

	// --------------------------------------------------------------------

	/**
     * 获取当前类
	 *
	 * @deprecated	3.0.0	改为读取'类'的值
	 * @return	string
	 */
	public function fetch_class()
	{
		return $this->class;
	}

	// --------------------------------------------------------------------

	/**
     * 设置方法名
	 *
	 * @param	string	$method	方法名
	 * @return	void
	 */
	public function set_method($method)
	{
		$this->method = $method;
	}

	// --------------------------------------------------------------------

	/**
     * 获取当前方法
	 *
	 * @deprecated	3.0.0	改为读取'方法'属性
	 * @return	string
	 */
	public function fetch_method()
	{
		return $this->method;
	}

	// --------------------------------------------------------------------

	/**
     * 设置目录名
	 *
	 * @param	string	$dir	目录名
	 * @param	bool	$append	（在原值上）追加还是重新设置新的值
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
     * 获取目录
	 *
     * 获取包含请求的控制器类的子目录（如果有的话）。
	 *
	 * @deprecated	3.0.0	改为读取目录属性
	 * @return	string
	 */
	public function fetch_directory()
	{
		return $this->directory;
	}

}
