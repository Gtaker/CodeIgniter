<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI 路由
| -------------------------------------------------------------------------
| 该文件允许你重映射 URI 请求到指定的控制器函数。
|
| 通常，URL 字符串和 类/方法 之间是一对一的对应关系。
| 一个 URL 中的分段一般是这种形式：
|
|	example.com/class/method/id/
|
| 在某些情况下，不管因为什么，你可能想要重映射关系，
| 以便调用一个与 URL 匹配到的不同的 类/方法
|
| 请查看用户手册获取详细资料：
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| 保留路由
| -------------------------------------------------------------------------
|
| 这里有三个预设的路由：
|
|	$route['default_controller'] = 'welcome';
|
| 这个路由指明在 URI 不包含信息时调用哪个控制器，
| 在上面的例子中，"welcome" 类将被载入。
|
|	$route['404_override'] = 'errors/page_missing';
|
| 这个路由将会告诉 Router 类，如果 URL 中提供的路由不是一个有效的路由，
| 使用哪个 控制器/方法。
|
|	$route['translate_uri_dashes'] = FALSE;
|
| 这并不是一个路由，但是它允许你设置自动转换控制器和方法名中的破折号。
| '-' 并不是一个有效的类或方法名字符，因此它需要被转换。
| 当你将该选项设置为 TRUE，它会将会替换掉 URI 段中
| 所有类和方法中的破折号。
|
| 例如:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
