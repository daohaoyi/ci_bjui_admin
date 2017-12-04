<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 后台菜单管理控制器
 * @author gaomin
 */
class Menu extends MY_Controller{
	
	function __construct(){
		parent::__construct();
		//导入菜单表逻辑层
		$this->load->model('Adm_menu_model');
		//导入用户权限逻辑层
		$this->load->model('User_access_model');
		//导入角色权限逻辑层
		$this->load->model('Role_access_model');		
		//导入重复提交表单验证
		$this->load->library('submitverify', "menu_verify");
	}
	
	/**
	 * 树形菜单列表
	 */
	public function menu_list(){
		$this->load->view('menu/menu_list');
	}
	
	/**
	 * 获取菜单
	 */
	public function get_menu(){
		//获取需要加载数据的父类id
		$parentid = "0";
		$id = $this->input->post('id');
		if(!is_null($id)){
			$parentid = $id;
		}
		//查询数据
		$menus = $this->Adm_menu_model->get_list(array('parentid'=>$parentid, 'status!='=>-1),array('id','name','type'),array(),'listorder desc');
		$data = array();
		//整合数据
		foreach ($menus as $k=>$v){
			$data[$k]['id'] = $v['id'];
			$data[$k]['name'] = $v['name'];
			//只要是菜单类型则isParent为true
			if($v['type'] == 0){
				$data[$k]['isParent'] = true;
			}else{
				//否则查询是否有子项
				$submenus = $this->Adm_menu_model->get_total(array('parentid'=>$v['id']));
				if($submenus > 0){
					$data[$k]['isParent'] = true;
				}else{
					$data[$k]['isParent'] = false;
					//拖拽时不能成为父节点及根节点
					$data[$k]['dropInner'] = false;
					//$data[$k]['dropRoot'] = false;
				}
			}
		}
		echo json_encode($data);
	}
	
	/**
	 * 添加菜单
	 */
	public function menu_add(){
		$data['verify_string'] = $this->submitverify->create_verify();
		$data['parentid'] = $this->input->get('id');
		$this->load->view('menu/menu_add',$data);
	}
	
	/**
	 * 执行添加菜单
	 */
	public function menu_add_post(){
		$verify_string = strip_tags($this->input->get_post("verify_string"));
		//验证表单是否重复提交
		if ($this->submitverify->do_verify_submit($verify_string)) {
			$data = array();
			//添加菜单信息
			$data['parentid'] = $this->input->post('parentid');
			$data['model'] = $this->input->post('model');
			$data['action'] = $this->input->post('action');
			$data['type'] = $this->input->post('type');
			$data['status'] = $this->input->post('status');
			$data['name'] = $this->input->post('name');
			$data['icon'] = $this->input->post('icon');
			$data['remark'] = $this->input->post('remark');
			$data['listorder'] = $this->input->post('listorder');
			//添加
			if($this->Adm_menu_model->add($data) !== false){
				$this->return['statusCode'] = '200';
				$this->return['message'] = '添加成功！';
				$this->return['closeCurrent'] = true;
				if($this->input->post('parentid') == "0"){
					$this->return['tabid'] = 'menu/menu_list';        //刷新列表页
				}
			}
		}else{
			$this->return['statusCode'] = '300';
			$this->return['message'] = '请勿重复提交！';
		}
		echo json_encode($this->return);
	}
	
	/**
	 * 验证菜单model+action是否重复
	 */
	public function check_menu_unique(){
		$ret = array();
		$model = $this->input->post('model');
		$action = $this->input->post('action');
		if(!empty($this->Adm_menu_model->get_one(array('model'=>$model,'action'=>$action)))){
			$ret['error'] = "菜单已存在/被删除，联系管理员！";
		}else{
			$ret['ok'] = "";
		}
		echo json_encode($ret);
	}
	
	/**
	 * 编辑菜单
	 */
	public function menu_edit(){
		$data['info'] = $this->Adm_menu_model->get_one(array('id'=>$this->input->get('id')));
		$this->load->view('menu/menu_edit',$data);
	}
	
	/**
	 * 执行编辑菜单
	 */
	public function menu_edit_post(){
		$id = $this->input->post('id');
		//修改数据
		$data = array();
		$data['name'] = $this->input->post('name');
		$data['icon'] = $this->input->post('icon');
		$data['model'] = $this->input->post('model');
		$data['action'] = $this->input->post('action');
		$data['remark'] = $this->input->post('remark');
		$data['listorder'] = $this->input->post('listorder');
		$data['type'] = $this->input->post('type');
		$data['status'] = $this->input->post('status');
		if($this->Adm_menu_model->save(array('id'=>$id),$data) !== false){
			$this->return['statusCode'] = '200';
			$this->return['message'] = '编辑成功！';
			$this->return['closeCurrent'] = true;
			//如果编辑的是一级菜单则刷新列表
			if($this->input->post('parentid') == "0"){
				$this->return['tabid'] = 'menu/menu_list';    //刷新列表页
			}
		}else{
			$this->return['statusCode'] = '300';
			$this->return['message'] = '编辑失败！';
		}
		echo json_encode($this->return);
	}
	
	/**
	 * 删除菜单
	 */
	public function menu_delete(){
		$id = $this->input->post('id');
		$ret = array();
		//查询是否有下级
		$submenus = $this->Adm_menu_model->get_total(array('parentid'=>$id));
		//查询该菜单信息
		$menuinfo = $this->Adm_menu_model->get_one(array('id'=>$id));
		if($submenus > 0){
			$ret['code'] = '9999';
			$ret['msg'] = '该菜单下还有子菜单，不能删除！';
		}else{
			if($this->Adm_menu_model->save(array('id'=>$id), array('status'=>-1)) !== false){
				$ret['code'] = '0000';
				$ret['msg'] = '删除成功！';
				//删除该菜单对应的权限
				$where = array();
				$where['m'] = $menuinfo['model'];
				$where['a'] = $menuinfo['action'];
				$this->User_access_model->del($where);
				$this->Role_access_model->del($where);
			}else{
				$ret['code'] = '9999';
				$ret['msg'] = '删除失败！';
 			}
		}
		echo json_encode($ret);
	}
	
	/**
	 * 拖拽修改菜单父级
	 */
	public function menu_drag(){
		$id = $this->input->post('id');
		$pid = $this->input->post('pid');
		//修改parentid
		$this->Adm_menu_model->save(array('id'=>$id),array('parentid'=>$pid));
	}
	
	/**
	 * 查看菜单
	 */
	public function menu_show(){
		$data['info'] = $this->Adm_menu_model->get_one(array('id'=>$this->input->get('id')));
		$this->load->view('menu/menu_show',$data);
	}
}