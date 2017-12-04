<?php

/**
 * Encoding		:  UTF-8
 * Created on	:  2014-6-4 by Tom , xiuluo_0816@163.com
 * WebSite		:  www.statnet.com.cn 
 */
class MY_Controller extends CI_Controller {

    public $userid;
    public $username;
    public $roleid;
    public $usertype;
    public $trunpage;
    /*
     * 状态码(ok = 200, error = 300, timeout = 301)，可以在BJUI.init时配置三个参数的默认值
     * 信息内容
     * 待刷新navtab id，多个id以英文逗号分隔开，当前的navtab id不需要填写，填写后可能会导致当前navtab重复刷新。
     * 待刷新dialog id，多个id以英文逗号分隔开，请不要填写当前的dialog id，要控制刷新当前dialog，请设置dialog中表单的reload参数。
     * 待刷新div id，多个id以英文逗号分隔开，请不要填写当前的div id，要控制刷新当前div，请设置该div中表单的reload参数。
     * 是否关闭当前窗口(navtab或dialog)。
     * 跳转到某个url。
     * 跳转url前的确认提示信息。
     */
    public $return = array(
        'statusCode' => '301',
        'message' => '登陆失效，请重新登录!',
        'tabid' => '',
        'dialogid' => '',
        'divid' => '',
        'closeCurrent' => '',
        'forward' => '',
        'forwardConfirm' => ''
    );

    public function __construct() {
        parent::__construct();
        //获取session中的userid
        $this->userid = $this->session->userdata('userid');
        if (empty($this->userid)) {
            //跳转
            Header("HTTP/1.1 303 See Other");
            Header("Location: " . site_url('login'));
            exit;
        } else {
        	//非超级管理员验证权限
        	if(!isset($this->config->item('root')[$this->session->userdata('name')])){
        		$m = $this->uri->rsegment(1);
        		$a = $this->uri->rsegment(2);
        		if(!isset($this->session->userdata('auth_access')[$m][$a])){
        			$this->return['statusCode'] = '300';
					$this->return['message'] = '您没有访问权限！';
					echo json_encode($this->return);
        			exit();
        		}
        	}
            $this->username = $this->session->userdata('name');
            $this->roleid = $this->session->userdata('roleid');
            $this->usertype = $this->session->userdata('usertype');
            $this->get_trunpage();
        }
    }

    /**
     * 获取跳转标示  page+trunpage 唯一确定一个rel
     */
    public function get_trunpage() {
        $segs = $this->uri->uri_to_assoc(4);
        if (isset($segs['turnpage'])) {
            setcookie("turnpage", "", time() - config_item("cookie_expire"), config_item("cookie_path"), "/");
            setcookie("turnpage", $segs['turnpage'], time() + config_item("cookie_expire"), "/");
            $_COOKIE["turnpage"] = $segs['turnpage'];
        }
        $this->trunpage = isset($_COOKIE["turnpage"]) ? $_COOKIE["turnpage"] : '';
    }

}

?>
