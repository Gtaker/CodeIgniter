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
 * @since	Version 2.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Utf8 类
 *
 * 提供对 UTF-8 环境的支持
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	UTF-8
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/utf8.html
 */
class CI_Utf8 {

	/**
     * 构造函数
	 *
     * 确定是否启用 UTF-8 支持
	 *
	 * @return	void
	 */
	public function __construct()
	{
		if (
			defined('PREG_BAD_UTF8_ERROR')				// PCRE 必须支持 UTF-8
			&& (ICONV_ENABLED === TRUE OR MB_ENABLED === TRUE)	// 必须安装 iconv 或 mbstring 扩展
			&& strtoupper(config_item('charset')) === 'UTF-8'	// 应用字符集必须是 UTF-8
			)
		{
			define('UTF8_ENABLED', TRUE);
			log_message('debug', 'UTF-8 Support Enabled');
		}
		else
		{
			define('UTF8_ENABLED', FALSE);
			log_message('debug', 'UTF-8 Support Disabled');
		}

		log_message('info', 'Utf8 Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
     * 清理 UTF-8 字符串
	 *
     * 确保字符串只包含有效的 UTF-8 字符。
	 *
	 * @param	string	$str	要清理的字符串
	 * @return	string
	 */
	public function clean_string($str)
	{
		if ($this->is_ascii($str) === FALSE)
		{
			if (MB_ENABLED)
			{
				$str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
			}
			elseif (ICONV_ENABLED)
			{
				$str = @iconv('UTF-8', 'UTF-8//IGNORE', $str);
			}
		}

		return $str;
	}

	// --------------------------------------------------------------------

	/**
     * 删除 ASCII 控制字符
	 *
	 * 删除水平制表符、换行符和回车符之外的所有的 ASCII 控制字符，
     * 因为所有其他字符在 XML 中都可能会引发问题。
     *
	 * @param	string	$str	需要清理的字符串
	 * @return	string
	 */
	public function safe_ascii_for_xml($str)
	{
		return remove_invisible_characters($str, FALSE);
	}

	// --------------------------------------------------------------------

	/**
     * 转换到 UTF-8
	 *
     * 尝试将一个字符串转换为 UTF-8
	 *
	 * @param	string	$str		输入字符串
	 * @param	string	$encoding	输入编码
	 * @return	string	$str        UTF-8 编码的字符串或在失败时返回 FALSE
	 */
	public function convert_to_utf8($str, $encoding)
	{
		if (MB_ENABLED)
		{
			return mb_convert_encoding($str, 'UTF-8', $encoding);
		}
		elseif (ICONV_ENABLED)
		{
			return @iconv($encoding, 'UTF-8', $str);
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
     * 是否是 ASCII 码？
	 *
     * 测试字符串是否是标准的7位 ASCII 码。
	 *
	 * @param	string	$str	要检测的字符串
	 * @return	bool
	 */
	public function is_ascii($str)
	{
		return (preg_match('/[^\x00-\x7F]/S', $str) === 0);
	}

}
