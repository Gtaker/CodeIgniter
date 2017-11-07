<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
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

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 * 应用环境
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * 你可以为你当前的环境加载不同的配置，
 * 改变环境设置（注：ENVIRONMENT常量）将会影响
 * 日志和错误报告等设置（注：如不同的应用版本将有不同的错误提示等级）
 *
 * This can be set to anything, but default usage is:
 * 应用环境可以设置为任何值，但是默认提供三个用例：
 *
 *     development      开发
 *     testing          测试
 *     production       成果
 *
 * NOTE: If you change these, also change the error_reporting() code below
 * 提示：如果你改变了这些设置，会影响到下面的 error_reporting() 函数
 */
	define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 * 错误报告
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 *
 * 不同的环境应该使用不同等级的错误报告。
 * 默认的环境类型会显示错误提示，testing 和 live（注：线上版本） 则不显示错误提示。
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 * 基础目录名
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 *
 * 该变量的值必须与你的基础目录名（注：默认为"system"）保持一致
 * 如果基础目录与该文件不在同意目录下，那么就需要将该变量的值设置为基础文件所在的路径
 *
 * 注：如 $system_path = './test/system'
 */
	$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 * 应用目录名
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * 如果你想要这个前端控制器(index.php)使用一个与默认的"应用"目录
 * 不同的目录，你可以在这里设置目录路径。
 * 该目录同样也可以重命名或移动到你服务器的任何地方，如果你这么做了，
 * 请填写服务器上的绝对路径。
 *
 * 更多的信息请查看用户手册：
 * https://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 * 不要在结尾使用反斜线！
 */
	$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 * 视图目录名
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * 如果你想将视图目录移动到应用目录外，则需要在这里设置路径。
 * 视图目录可以重命名或移动到你服务器上的任何地方。
 * 如果为空，则将会默认设置为你应用目录里的标准位置（注：即"application"/views目录）。
 * 如果你移动了该文件的位置，请在此处填写服务器（完整的）的绝对路径。
 *
 * NO TRAILING SLASH!
 * 不要在结尾使用反斜线！
 */
	$view_folder = '';


/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * 默认控制器
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here. For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * 通常你会在 routes.php 文件下设置默认的控制器,
 * 然而，可以在此处自定义一个硬编码的
 * 特殊 class/function 路由。对于大多数的应用来说，你
 * 不会在这里设置路由，但对于共享同一个CI框架的
 * 特定前端控制器，你可以用这个选项重写他的标准路由
 *
 * 注：如，在同一个CI框架文件中，定义多个入口文件(index1.php、index2.php...)，
 * 用户就可以通过在这些入口文件中分别配置该选项，
 * 来达到通过访问不同入口文件调用不同控制器和方法的目的。
 *
 * IMPORTANT: If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller. Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * 重要：如果你在这里设置了路由，其他控制器将不可被调用。
 * 本质上，这个设置更适用于限制你的应用使用一个特殊的控制器。
 * 如果需要通过URI动态设置函数名，请将函数名留空。
 *
 * Un-comment the $routing array below to use this feature
 * 取消下面 $routing 数组的注释来允许使用该特性
 */
	// The directory name, relative to the "controllers" directory.  Leave blank
	// if your controller is not in a sub-directory within the "controllers" one
    // 目录名的设置应相对于控制器目录。（注：如 当值为'foo'，意为 'controllers/foo' 目录）
    // 如果你的控制器不在控制器目录的子目录中，那么应将该变量的值留空。
    //$routing['directory'] = '';

	// The controller class file name.  Example:  mycontroller
    // 控制器文件名。 例如：mycontroller
	// $routing['controller'] = '';

	// The controller function you wish to be called.
    // 你想要调用的控制器中的方法
    // $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 *  自定义配置文件
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * 在初始化时，下面的 $assign_to_config 数组将会动态的传递给配置类。
 * 这允许你设置自定义的配置项或重写任何 config.php 文件中配置的默认值。
 * 这是一个很灵活的选项，因为它允许你使用多个前端控制器(index.php)共享同一个应用目录，
 *
 *
 * Un-comment the $assign_to_config array below to use this feature
 * 取消 $assign_to_config 数组前的注释来使用该特性
 */
	// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// 用户可配置项已经结束。请不要修改该行下面的代码
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 *  解析系统路径以提高可靠性
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
    // 为CLI（注：命令行）请求设置正确的当前路径
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	//注：判断基础目录是否存在，并将基础目录缩在的路径值替换为绝对路径
	if (($_temp = realpath($system_path)) !== FALSE)
	{
		$system_path = $_temp.DIRECTORY_SEPARATOR;
	}
	else
	{
		// Ensure there's a trailing slash
        // 确保路径后有一个斜线（/）
		$system_path = strtr(
			rtrim($system_path, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		).DIRECTORY_SEPARATOR;
	}

	// Is the system path correct?
    // 这是一个正确的基础路径吗？
	if ( ! is_dir($system_path))
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
		exit(3); // EXIT_CONFIG     退出入口文件
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 *  现在我们知道了路径，设置主要的路径常量
 * -------------------------------------------------------------------
 */
	// The name of THIS file
    // 本文件名
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// Path to the system directory
    //基础目录（注：默认为system目录）的路径
	define('BASEPATH', $system_path);

	// Path to the front controller (this file) directory
    // 前端控制器（当前文件）所在目录的路径
	define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

	// Name of the "system" directory
    // 基础目录名
	define('SYSDIR', basename(BASEPATH));

	// The path to the "application" directory
    // 应用目录（注：默认为 application 目录）的路径
	if (is_dir($application_folder))
	{
		if (($_temp = realpath($application_folder)) !== FALSE)
		{
			$application_folder = $_temp;
		}
		else
		{
			$application_folder = strtr(
				rtrim($application_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	//注：如果在CI框架的根目录下找不到指定的应用目录，
    //那么入口文件还会到寻找基础目录的子目录中是否存在指定的应用目录
	elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
	{
		$application_folder = BASEPATH.strtr(
			trim($application_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG     退出入口文件
	}

	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);

	// The path to the "views" directory
    // 视图目录（注：默认为application/views目录）的路径
	if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.'views';
	}
	elseif (is_dir($view_folder))
	{
		if (($_temp = realpath($view_folder)) !== FALSE)
		{
			$view_folder = $_temp;
		}
		else
		{
			$view_folder = strtr(
				rtrim($view_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.strtr(
			trim($view_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG     退出入口文件
	}

	define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * 载入引导文件
 * --------------------------------------------------------------------
 *
 * And away we go...
 * 然后让我们开始放飞自我
 */
require_once BASEPATH.'core/CodeIgniter.php';
