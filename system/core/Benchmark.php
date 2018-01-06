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
 * 基准类
 *
 * 这个类允许你标记时间点并计算差值，并且可以显示内存消耗。
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/benchmark.html
 */
class CI_Benchmark {

	/**
     * 列出所有基准标记列表
	 *
	 * @var	array
	 */
	public $marker = array();

	/**
     * 设置一个基准标记
	 *
     * 多次调用该方法，即可设置多个标记的时间点。
     *
	 *
	 * @param	string	$name	Marker name     标记名
	 * @return	void
	 */
	public function mark($name)
	{
		$this->marker[$name] = microtime(TRUE);
	}

	// --------------------------------------------------------------------

	/**
     * 经过的时间
	 *
     * 计算两个标记之间的时间差。
	 *
     * 如果第一个参数为空，该方法会返回一个 {elapsed_time} 伪变量，
     * 这允许在模版中显示完整的系统执行时间，输出类会将该变量渲染为实际值。
	 *
	 * @param	string	$point1		一个特定的标记点
	 * @param	string	$point2		一个特定的标记点
	 * @param	int	$decimals	小数点的位数
	 *
	 * @return	string	成功计算出过去的时间，
	 *			如果 $point1 为空，则返回 '{elapsed_string}',
	 *			如果 $point1 没有找到，则返回空字符串。
	 */
	public function elapsed_time($point1 = '', $point2 = '', $decimals = 4)
	{
		if ($point1 === '')
		{
			return '{elapsed_time}';
		}

		if ( ! isset($this->marker[$point1]))
		{
			return '';
		}

		if ( ! isset($this->marker[$point2]))
		{
			$this->marker[$point2] = microtime(TRUE);
		}

		return number_format($this->marker[$point2] - $this->marker[$point1], $decimals);
	}

	// --------------------------------------------------------------------

	/**
     * 内存使用
	 *
     * 简单的返回一个 {memory_usage} 标记
	 *
     * 这允许在模版的任何地方调用，而不需要在最后计算内存。
     * 输出类将会将该变量渲染为实际值。
	 *
	 * @return	string	'{memory_usage}'
	 */
	public function memory_usage()
	{
		return '{memory_usage}';
	}

}
