<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
     * 该控制器的引导页。
	 *
	 * Maps to the following URL
     * 影射到以下 URL 地址
	 * 		http://example.com/index.php/welcome
	 *	- 或 -
	 * 		http://example.com/index.php/welcome/index
	 *	- 或 -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
     * 如果在 config/routes.php 中将此控制器设置为默认控制器，
     * 则该方法映射到 http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
     * 所以任何其他不带下划线前缀的公共方法都将映射到 /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
}
