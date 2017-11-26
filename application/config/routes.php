<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| URI 路由
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
| 该文件允许你重映射 URI 请求到指定的控制器函数。
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
| 通常，URL 字符串和 类/方法 之间是一对一的对应关系。
| 一个 URL 中的分段一般是这种形式：
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
| 在某些情况下，不管因为什么，你可能想要重映射关系，
| 以便于调用一个与 URL 匹配到的不同的 类/方法
|
| Please see the user guide for complete details:
| 请查看用户手册以获取详细资料：
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| 保留路由
| -------------------------------------------------------------------------
|
| There are three reserved routes:
| 这里有三个预设的路由：
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
| 这个路由设置在 URI 不包含信息时调用哪个控制器，
| 在上面的例子中，"welcome" 类将被载入。
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
| 这个路由将会告诉 Router 类，如果 URL 中提供的路由不是一个有效的路由，
| 使用哪个 控制器/方法。
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
| 这其实并不是一个路由，但是允许你自动转换控制器和方法名中的破折号。
| '-' 并不是一个有效的类或方法名字符，因此它需要被转换。
| 当你讲该选项设置为 TRUE，它会将会替换掉 URI 段中
| 所有类和方法中的破折号。
|
| Examples  例如:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
