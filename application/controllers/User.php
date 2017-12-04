<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 后台用户管理控制器
 * @author gaomin
 */
class User extends MY_Controller {
	//获取用户列表的where条件
	private $where = array();
	//获取用户列表的limit条件
	private $limit = array();
	//获取用户列表的order条件
	private $order = 'id desc';
	//获取用户列表的分页条件(pageSize:每页大小   pageCurrent:当前页数)
	private $params = array('pageTotal'=>0,'pageSize'=>'30','pageCurrent'=>1);
	//获取用户列表头部的搜索信息
	private $search = array('roleid'=>'','username'=>'');
	//获取用户列表的字段
	private $fields = array('id','name','realname','lastip','dateline','lasttime','roleid','usertype','state');
	//获取角色组信息
	private $adm_role = array();
	function __construct()
	{
		parent::__construct();
		//加载admin表逻辑层
		$this->load->model('Admin_model');
		//加载admin表逻辑层
		$this->load->model('Adm_role_model');
		//导入重复提交表单验证
		$this->load->library('submitverify', "user_verify");
		$this->adm_role = $this->Adm_role_model->get_adm_role();
	}
	
	/**
	 * 获取筛选条件
	 */
	private function get_where() {
		//排序
		if(in_array($this->input->get_post("orderField"), $this->fields) && in_array($this->input->get_post("orderDirection"), array('asc','desc'))){
			$this->order = $this->input->get_post("orderField") . ' ' . $this->input->get_post("orderDirection");
		}
		//获取当前页数
		$this->params['pageCurrent'] = intval($this->input->get_post("pageCurrent", TRUE)) != 0 ? intval($this->input->get_post("pageCurrent", TRUE)) : 1;
		//获取每页大小
		$this->params['pageSize'] = intval($this->input->get_post("pageSize", TRUE)) != 0 ? intval($this->input->get_post("pageSize", TRUE)) : 30;
		//获取查询条件,返回查询信息
		if(intval($this->input->get_post("roleid", TRUE)) != 0){
			$this->where['roleid'] = intval($this->input->get_post("roleid", TRUE));
			$this->search['roleid'] = $this->where['roleid'];
		}
		if(!empty($this->input->get_post("username",TRUE))){
			$this->where['name'] = $this->input->get_post("username", TRUE);
			$this->search['username'] = $this->where['name'];
		}
	}
	
	/**
	 * 用户列表
	 */
	public function user_list(){
		//var_dump($this->adm_role);exit;
		//获取where、order、search、分页
		$this->get_where();
		//用户信息总条数
		$this->params['pageTotal'] = $this->Admin_model->get_total($this->where);
		//获取limit
		$this->limit = array(($this->params['pageCurrent'] - 1) * $this->params['pageSize'], $this->params['pageSize']);
		$data = array_merge($this->search, $this->params);
		//查询用户数据
		$data['userdata'] = $this->Admin_model->get_list($this->where,$this->fields,$this->limit,$this->order);
		$data['roletype'] = $this->adm_role;
		//加载列表页面
		$this->load->view('user/user_list',$data);
	}
	
	/**
	 * 添加用户
	 */
	public function user_add(){
		$data['verify_string'] = $this->submitverify->create_verify();
		$data['roletype'] = $this->adm_role;
		foreach ($data['roletype'] as $k=>$v){
			unset($data['roletype'][1]);
		}
		$this->load->view('user/user_add',$data);
	}

	/**
	 * 验证用户名是否重复
	 */
	public function check_name_unique(){
		$ret = array();
		if(!empty($this->Admin_model->get_one(array('name'=>$this->input->post('name'))))){
			$ret['error'] = "用户已存在！";
		}else{
			$ret['ok'] = "";
		}
		echo json_encode($ret);
	}
	
	/**
	 * 执行添加用户
	 */
	public function user_add_post(){
		//加载admin表逻辑层
		$this->load->model('Role_access_model');
		$this->load->model('User_access_model');
		$verify_string = strip_tags($this->input->get_post("verify_string"));
		//验证表单是否重复提交
		if ($this->submitverify->do_verify_submit($verify_string)) {
			$data = array();
			//添加用户信息
			$data['name'] = $this->input->post('name');
			$data['realname'] = $this->input->post('realname');
			$data['password'] = ydr2_password($this->input->post('pwd'));
			$data['roleid'] = $this->input->post('roleid');
			$data['usertype'] = $this->input->post('usertype');
			$data['dateline'] = date('Y-m-d H:i:s');
			$data['secretkey'] = substr(md5(uniqid('xkdx')), 0, 16);
			//添加
			$user_id=$this->Admin_model->add($data);
			if($user_id !== false){
				if($data['roleid']!==1){
					$role_info=$this->Role_access_model->get_list(array('role_id'=>$data['roleid']), array());
					$user_data=array();
					foreach($role_info as $k=>$v){
						$user_data[$k]['user_id']=$user_id;
						$user_data[$k]['a']=$v['a'];
						$user_data[$k]['m']=$v['m'];
					}
					//用户权限表写数据
					$this->User_access_model->add_batch($user_data);
				}
				
				$this->return['statusCode'] = '200';
				$this->return['message'] = '添加成功！';
				$this->return['closeCurrent'] = true;
				$this->return['tabid'] = 'user/user_list';    //刷新列表页
			
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
	 * 修改用户
	 */
	public function user_edit($id){
		//用户信息
		$data['info'] = $this->Admin_model->get_one(array('id'=>$id),$this->fields);
		$data['roletype'] = $this->adm_role;
		foreach ($data['roletype'] as $k=>$v){
			unset($data['roletype'][1]);
		}
		$this->load->view('user/user_edit',$data);
	}
	
	/**
	 * 执行修改用户
	 */
	public function user_edit_post(){
		//导入角色权限逻辑层
		$this->load->model('User_access_model');
		//导入角色权限逻辑层
		$this->load->model('Role_access_model');
		$id = $this->input->post('id');
		$data = array();
		$data['name'] = $this->input->post('name');
		$data['realname'] = $this->input->post('realname');
		$data['roleid'] = $this->input->post('roleid');
		$data['usertype'] = $this->input->post('usertype');
		//获取用户信息
		$user_info = $this->Admin_model->get_one(array('id'=>$id));
		//修改
		if($this->Admin_model->save(array('id'=>$id),$data) !== false){
			if ($user_info['roleid']!=$data['roleid']) {
				$this->User_access_model->del(array('user_id'=>$id));
				$role_info=$this->Role_access_model->get_list(array('role_id'=>$data['roleid']), array());
				$user_data=array();
				foreach($role_info as $k=>$v){
					$user_data[$k]['user_id']=$id;
					$user_data[$k]['a']=$v['a'];
					$user_data[$k]['m']=$v['m'];
				}
				//用户权限表写数据
				$this->User_access_model->add_batch($user_data);
			}
			
			$this->return['statusCode'] = '200';
			$this->return['message'] = '编辑成功！';
			$this->return['closeCurrent'] = true;
			$this->return['tabid'] = 'user/user_list';    //刷新列表页
		}else{
			$this->return['statusCode'] = '300';
			$this->return['message'] = '编辑失败！';
		}
		echo json_encode($this->return);
	}
	
	/**
	 * 启用/禁用
	 */
	public function enable_disable($id, $state) {
		if ($this->Admin_model->save(array('id'=>$id), array('state'=>$state)) !== false) {
			$this->return['statusCode'] = '200';
			$this->return['message'] = '操作成功！';
			$this->return['tabid'] = 'user/user_list';    //刷新列表页
		} else {
			$this->return['statusCode'] = '300';
			$this->return['message'] = '操作失败！';
		}
		echo json_encode($this->return);
	}
	/**
	 * 用户分配权限
	 * @param unknown $id
	 */
	public function set_authority($id){
		//导入菜单逻辑层
		$this->load->model('Adm_menu_model');
		//导入用户逻辑层
		$this->load->model('User_access_model');
		//获取所有菜单
		$menus = $this->Adm_menu_model->get_list(array('status !='=>-1),array(),array(),'id asc');
		//获取用户信息
		$user_info=$this->Admin_model->get_one(array('id'=>$id));
	
		//超级用户给所有权限
		if(isset($this->config->item('root')[$user_info['name']])){
			$access=true;
		}else{
			//获取该角色所有的权限
			$access = $this->User_access_model->get_list(array('user_id'=>$id),array(),'');
			
		}
		//ztree数据
		$data['menus'] = array();
		foreach ($menus as $k=>$v){
			$data['menus'][$k]['id'] = $v['id'];
			$data['menus'][$k]['pId'] = $v['parentid'];
			$data['menus'][$k]['name'] = $v['name'];
			$data['menus'][$k]['m'] = $v['model'];
			$data['menus'][$k]['a'] = $v['action'];
			$data['menus'][$k]['checked'] = ($access=='true'?$access:$this->Adm_menu_model->is_checked_user($v, $id, $access));
		}
		$data['menus'] = json_encode($data['menus']);
		
		$data['roleid'] = $id;
		$this->load->view('user/user_authority',$data);
	}
	/**
	 * 执行分配权限
	 */
	public function set_authority_post(){
		//导入用户逻辑层
		$this->load->model('User_access_model');
		$nodes = $this->input->post('nodes');
		$id = $this->input->post('userid');
		//删除该用户旧权限
		$this->User_access_model->del(array('user_id'=>$id));
		if(count($nodes) > 0){
			//批量添加
			if($this->User_access_model->add_batch($nodes) !== false){
				$this->return['statusCode'] = '200';
				$this->return['message'] = '设置成功！';
			}else{
				$this->return['statusCode'] = '300';
				$this->return['message'] = '设置失败！';
			}
		}else{
			//清除旧权限
			$this->return['statusCode'] = '200';
			$this->return['message'] = '设置成功！';
		}
		echo json_encode($this->return);			
	}
}
