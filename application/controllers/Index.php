<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 加载页面结构
	 */
	public function index()
	{
		$menus = array();
		//加载菜单逻辑层
		$this->load->model('Adm_menu_model');
		//系统设置的菜单模式
		$menu_pattern = $this->config->item('menu_pattern');
		//获取树形菜单
		$menu['menus'] = $this->Adm_menu_model->menu_array();
		//加载页头、左菜单、页尾
		$data['intohead'] = $this->load->view('include/intohead','',TRUE);
		$data['header'] = $this->load->view('include/header_'.$menu_pattern,$menu,TRUE);
		$data['leftmenu'] = $this->load->view('include/menu_'.$menu_pattern,$menu,TRUE);
		$data['footer'] = $this->load->view('include/footer','',TRUE);
		//加载页面
		$this->load->view('index',$data);
		
	}
	/**
	 * 默认页面
	 */
	public function main()
	{
		$this->load->view('main');
	}
}
