<?php
/**
 * 后台登录模块 
 */
class Login extends CI_Controller{
	
	private $userdata;
	
	function __construct(){
		parent::__construct();
		//验证码
		$this->load->helper('captcha');
		//表单验证
		$this->load->library('form_validation');
		//用户逻辑类
		$this->load->model('Admin_model');
		//用户权限逻辑类
		$this->load->model('User_access_model');		
	}
	
	/**
	 * 后台登陆
	 */
	public function index(){
		//检查是否登录，登录了则显示登录后，没有登录则显示登录界面
		$userid = $this->session->userdata('userid');
		
		if (!empty($userid)) {
			Header("Location: " . site_url('index'));
		}
		//未登录显示登陆页面
		$this->load->view('login');
	}
	
	/**
	 * 处理登陆
	 */
	public function do_login(){
		//获取提交的用户信息json格式
		$user = array();
		$is_login = FALSE;
		$user['username'] = $this->input->post('username');
		$user['passwordhash'] = $this->input->post('passwordhash');
		$user['j_captcha'] = $this->input->post('j_captcha');
		$user_json = json_encode($user);
		//验证验证码输入是否正确
		$this->form_validation->set_rules('j_captcha', '验证码', 'callback_check_code');
		if($this->form_validation->run() !== FALSE){
			//验证用户信息输入是否正确
			$this->form_validation->set_rules('username', '用户名', 'callback_check_user['.$user_json.']');
			if($this->form_validation->run() !== FALSE){
				$is_login = TRUE;
			}
		}
		if($is_login == FALSE){
			//表单验证失败,返回登录
			$this->index();
		}else{
			//登录成功,删除验证码
			$this->session->unset_userdata('code');
			//存放用户session
			$this->session->set_userdata($this->userdata);
			//添加登录信息
			$this->Admin_model->save(array('name'=>$user['username']),array('lastip' => get_client_ip(), 'lasttime' => date('Y-m-d H:i:s')));
			//不是超级管理员用户，存放用户权限session
			if(!isset($this->config->item('root')[$user['username']])){
				$auth_access = $this->get_auth_access();
				$this->session->set_userdata('auth_access',$auth_access);
			}
			//跳转
			Header("Location: " . site_url('index'));
		}
	}
	
	
	/**
	 * 验证验证码
	 * @param string $code 验证码
	 * @return bool
	 */
	public function check_code($code){
		if (strcmp(strtoupper($code), strtoupper($this->session->userdata('code'))) != 0) {
			$this->form_validation->set_message('check_code', '验证码输入错误！');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	/**
	 * 验证用户信息
	 * @param string $username 用户名
	 * @param string $user_json 用户信息json串
	 */
	public function check_user($username,$user_json){
		$user_array = json_decode($user_json,true);
		$userinfo = $this->Admin_model->get_one(array('name'=>$user_array['username']),array('id as userid','name','password','roleid','realname','usertype','state'));
		$error_msg = '';
		$ret = FALSE;
		//用户是否存在
		if(!empty($userinfo)){
			//状态是否正常
			if($userinfo['state'] != 0){
				//密码是否正确
				$new_password = ydr2_password($user_array['passwordhash']);
				if(strcmp($userinfo['password'],$new_password) != 0){
					$error_msg = '密码错误！';
				}else{
					$error_msg = '';
					//删除密码
					unset($userinfo['password']);
					$this->userdata = $userinfo;
					$ret = TRUE;
				}
			}else{
				$error_msg = '用户被禁用！';
			}
		}else{
			$error_msg = '用户名不存在！';
		}
		$this->form_validation->set_message('check_user', $error_msg);
		return $ret;
	}
	
	
	/**
	 * 更换验证码
	 */
	public function change_code() {
		$code = create_captcha();
		//保存验证码session
		$this->session->set_userdata('code',$code);
	}
	
	/**
	 * 获取登录成功后用户的权限
	 */
	public function get_auth_access(){
		$userid = $this->userdata['userid'];
		//默认model=>Index,action=>index
		$auth_access = array();
		$auth_access['index']['index'] = 'index/index';
		$auth_access['index']['main'] = 'index/main';
		$res = $this->User_access_model->get_list(array('user_id'=>$userid),array(),'user_id desc');
		foreach ($res as $v){
			$m = strtolower($v['m']);
			$a = strtolower($v['a']);
			$auth_access[$m][$a] = $m.'/'.$a;
		}
		return $auth_access;
	}
	
	/**
	 * 登录注销
	 */
	public function logout() {
		$this->session->sess_destroy();
		redirect('login');
	}
	
	/**
	 * 我的账户修改密码
	 */
	public function changepwd(){
		$this->load->view('changepwd');
	}
	
	/**
	 * 修改密码时验证旧密码输入是否正确
	 */
	public function check_oldpwd(){
		$ret = array();
		//需要验证的密码
		$oldpwd = ydr2_password($this->input->post('oldpwd'));
		//查询原密码
		$nowpwd = $this->Admin_model->get_one_key_map('password',array('id'=>$this->session->userdata('userid')));
		//验证
		if(strcmp($oldpwd,$nowpwd) != 0){
			$ret['error'] = "密码不正确！";
		}else{
			$ret['ok'] = "";
		}
		echo json_encode($ret);
	}
	
	/**
	 * 执行我的账户修改密码
	 */
	public function changepwd_post(){
		//新密码
		$newpwd = ydr2_password($this->input->post('newpwd'));
		//修改密码
		if($this->Admin_model->save(array('id'=>$this->session->userdata('userid')),array('password'=>$newpwd)) !== false){
			$this->return['statusCode'] = '200';
			$this->return['message'] = '修改成功！';
			$this->return['closeCurrent'] = true;
		}else{
			$this->return['statusCode'] = '300';
			$this->return['message'] = '修改失败！';
		}
		echo json_encode($this->return);
	}
}
