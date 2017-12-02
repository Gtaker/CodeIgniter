<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| 自动加载器
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
| 该文件指定默认加载的系统。
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
| 为了保证框架尽可能的轻量级。默认只会加载最小的绝对资源。
| 例如，数据库不会自动连接，因为我们不假设它会被使用。
| 该文件允许你全局定义一些你会在所有请求中都用得到的系统。
|
| -------------------------------------------------------------------
| Instructions
| 说明
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
| 你可以自动加载这些东西：
|
| 1. Packages                   包
| 2. Libraries                  类库
| 3. Drivers                    驱动
| 4. Helper files               帮助文件
| 5. Custom config files        自定义配置文件
| 6. Language files             语言文件
| 7. Models                     模型
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packages
|  自动加载包
| -------------------------------------------------------------------
| Prototype:
| 示例：
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/
$autoload['packages'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
|  自动加载类库
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
| 这些类在本地的 system/libraries/ 目录或你的 application/libraries/ 目录中，
| 并增加了 'database' 类库，这是一个特例。
|
| Prototype:
| 实例：
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also supply an alternative library name to be assigned
| in the controller:
| 你也可以在指定的控制器中加载不同的类库：
|
|	$autoload['libraries'] = array('user_agent' => 'ua');
*/
$autoload['libraries'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
|  自动加载驱动
| -------------------------------------------------------------------
| These classes are located in system/libraries/ or in your
| application/libraries/ directory, but are also placed inside their
| own subdirectory and they extend the CI_Driver_Library class. They
| offer multiple interchangeable driver options.
| 这些类在本地的 system/libraries/ 目录或你的 application/libraries/ 目录中，
| 但也可以存在于在它们的子目录中并继承 CI_Driver_Library 类，
| 他们提供多种可互换的驱动设置。
|
| Prototype:
| 示例：
|
|	$autoload['drivers'] = array('cache');
|
| You can also supply an alternative property name to be assigned in
| the controller:
| 你也可以在指定的控制器中加载不同的驱动：
|
|	$autoload['drivers'] = array('cache' => 'cch');
|
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
|  自动加载帮助文件
| -------------------------------------------------------------------
| Prototype:
| 示例：
|
|	$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Config files
|  自动加载配置文件
| -------------------------------------------------------------------
| Prototype:
| 示例
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
| 提示：该选项只在你创建了自定义配置文件时有效。
| 否则，请将其留空。
|
*/
$autoload['config'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Language files
|  自动加载语言文件
| -------------------------------------------------------------------
| Prototype:
| 示例：
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
| 提示：不要在你的文件中保留 "_lang" 的部分。
| 例如，"codeigniter_lang.php" 应该被引用为 array("codeigniter');
|
*/
$autoload['language'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
|  自动加载模型
| -------------------------------------------------------------------
| Prototype:
| 示例：
|
|	$autoload['model'] = array('first_model', 'second_model');
|
| You can also supply an alternative model name to be assigned
| in the controller:
| 你也可以在指定的控制器中加载不同的模型：
|
|	$autoload['model'] = array('first_model' => 'first');
*/
$autoload['model'] = array();
