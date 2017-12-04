<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 后台角色管理控制器
 * @author gaomin
 */
class Roles extends MY_Controller{
	function __construct(){
		parent::__construct();
		//导入角色逻辑层
		$this->load->model('Adm_role_model');
		//导入重复提交表单验证
		$this->load->library('submitverify', "roles_verify");
		//导入用户逻辑层
		$this->load->model('Admin_model');
		//导入菜单逻辑层
		$this->load->model('Adm_menu_model');
		//导入角色权限逻辑层
		$this->load->model('Role_access_model');
	}
	
	/**
	 * 角色列表
	 */
	public function roles_list(){
		//查询角色
		$data['rolesdata'] = $this->Adm_role_model->get_list(array(), array('id','name','status','remark','create_time','update_time'), array());
		$this->load->view('roles/roles_list',$data);
	}
	
	/**
	 * 添加角色
	 */
	public function roles_add(){
		$data['verify_string'] = $this->submitverify->create_verify();
		$this->load->view('roles/roles_add',$data);
	}
	
	/**
	 * 执行添加角色
	 */
	public function roles_add_post(){
		$verify_string = strip_tags($this->input->get_post("verify_string"));
		//验证表单是否重复提交
		if ($this->submitverify->do_verify_submit($verify_string)) {
			//添加角色信息
			$data = array();
			$data['name'] = $this->input->post('name');
			$data['remark'] = $this->input->post('remark');
			$data['create_time'] = date('Y-m-d H:i:s');
			//添加
			if($this->Adm_role_model->add($data) !== false){
				$this->return['statusCode'] = '200';
				$this->return['message'] = '添加成功！';
				$this->return['closeCurrent'] = true;
				$this->return['tabid'] = 'roles/roles_list';    //刷新列表页
			}else{
				$this->return['statusCode'] = '300';
				$this->return['message'] = '添加失败！';
			}
		}else{
			$this->return['statusCode'] = '300';
			$this->return['message'] = '请勿重复提交！';
		}
		echo json_encode($this->return);
	}
	
	/**
	 * 编辑橘色
	 */
	public function roles_edit($id){
		//角色信息
		$data['info'] = $this->Adm_role_model->get_one(array('id'=>$id));
		$this->load->view('roles/roles_edit',$data);
	}
	
	/**
	 * 执行编辑角色
	 */
	public function roles_edit_post(){
		$id = $this->input->post('id');
		//编辑信息
		$data = array();
		$data['name'] = $this->input->post('name');
		$data['remark'] = $this->input->post('remark');
		//编辑
		if($this->Adm_role_model->save(array('id'=>$id),$data) !== false){
			$this->return['statusCode'] = '200';
			$this->return['message'] = '编辑成功！';
			$this->return['closeCurrent'] = true;
			$this->return['tabid'] = 'roles/roles_list';    //刷新列表页
		}else{
			$this->return['statusCode'] = '300';
			$this->return['message'] = '编辑失败！';
		}
		echo json_encode($this->return);
	}
	
	/**
	 * 删除角色
	 */
	public function roles_delete($id){
		//查询该角色下是否有用户
		$user_num = $this->Admin_model->get_total(array('roleid'=>$id));
		if(!$user_num){
			if($this->Adm_role_model->del(array('id'=>$id)) !== false){
				$this->return['statusCode'] = '200';
				$this->return['message'] = '删除成功！';
				$this->return['tabid'] = 'roles/roles_list';    //刷新列表页
			}else{
				$this->return['statusCode'] = '300';
				$this->return['message'] = '删除失败！';
			}
		}else{
			$this->return['statusCode'] = '300';
			$this->return['message'] = '该角色已经有用户！';
		}
		echo json_encode($this->return);
	}
	
	/**
	 * 分配权限
	 */
	public function set_authority($id){
		//获取所有菜单
		$menus = $this->Adm_menu_model->get_list(array('status !='=>-1),array(),array(),'id asc');
		//获取该角色所有的权限
		$access = $this->Role_access_model->get_list(array('role_id'=>$id),array(),'');
		//ztree数据
		$data['menus'] = array();
		foreach ($menus as $k=>$v){
			$data['menus'][$k]['id'] = $v['id'];
			$data['menus'][$k]['pId'] = $v['parentid'];
			$data['menus'][$k]['name'] = $v['name'];
			$data['menus'][$k]['m'] = $v['model'];
			$data['menus'][$k]['a'] = $v['action'];
			$data['menus'][$k]['checked'] = $this->Adm_menu_model->is_checked($v, $id, $access);
		}
		$data['menus'] = json_encode($data['menus']);
		$data['roleid'] = $id;
		$this->load->view('roles/roles_authority',$data);
	}
	
	/**
	 * 执行分配权限
	 */
	public function set_authority_post(){
		//导入角色权限逻辑层
		$this->load->model('User_access_model');	
		$nodes = $this->input->post('nodes');
		$roleid = $this->input->post('roleid');
		
		//删除该角色旧权限
		$this->Role_access_model->del(array('role_id'=>$roleid));
		//查询角色权限下的用户
		$userids=$this->Admin_model->get_list(array('roleid'=>$roleid),array('id'),array());
		$user_ids=array();
		foreach ($userids as $k=>$value){
			$user_ids[$k]=$value['id'];
		}
		if(count($nodes) > 0){
			$data=array();
			//批量添加
			if($this->Role_access_model->add_batch($nodes) !== false){
				if (!empty($user_ids)) {
					$temp=array();
					//删除该用户旧权限
					$this->User_access_model->del(array('user_id'=>$user_ids));
					foreach($userids as $key=>$v){
						foreach($nodes as $k=>$val){
							$temp['user_id']=$v['id'];
							$temp['m']=$val['m'];
							$temp['a']=$val['a'];
							$data[]=$temp;
						}
					}
					//添加新的用户权限
					$this->User_access_model->add_batch($data);
				}
				$this->return['statusCode'] = '200';
				$this->return['message'] = '设置成功！';
			}else{
				$this->return['statusCode'] = '300';
				$this->return['message'] = '设置失败！';
			}
		}else{
			if (!empty($user_ids)) {
				//清除旧权限
				$this->User_access_model->del(array('user_id'=>$user_ids));
			}
			$this->return['statusCode'] = '200';
			$this->return['message'] = '设置成功！';
		}
		echo json_encode($this->return);			
	}
	
}