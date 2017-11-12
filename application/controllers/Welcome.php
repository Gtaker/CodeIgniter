<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
     * 该控制器的引导页。
	 *
     * 影射到以下 URL 地址
	 * 		http://example.com/index.php/welcome
	 *	- 或 -
	 * 		http://example.com/index.php/welcome/index
	 *	- 或 -
     * 由于已在 config/routes.php 中将此控制器设置为默认控制器，
     * 所以该方法也可以通过 http://example.com/ 调用
	 *
     * 所以任何其他不带下划线前缀的公共方法都将映射到 /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
}
