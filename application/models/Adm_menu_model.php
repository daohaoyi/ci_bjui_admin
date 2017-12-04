<?php
/**
 * 菜单逻辑层
 * @author gaomin
 *
 */
class Adm_menu_model extends MY_Model{
	private $db_group_name = 'ci_db';
	private $table = 'adm_menu';
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 保存数据
	 *
	 * @param array $data 需要插入的表数据
	 * @return boolean 插入成功返回ID，插入失败返回false
	 */
	public function add($data)
	{
		$this->db_connect($this->db_group_name, 1);
		$this->table_name = $this->table;
		return $this->insert($data);
	}
	
	/**
	 * 批量存储
	 * @param unknown $data
	 * @return boolean
	 */
	public function add_batch($data)
	{
		$this->db_connect($this->db_group_name, 1);
		$this->table_name = $this->table;
		return $this->insert_batch($data);
	}
	
	/**
	 * Replace数据
	 * @param array $data
	 */
	public function add_raplace($data)
	{
		$this->db_connect($this->db_group_name, 1);
		$this->table_name = $this->table;
		return $this->replace($data);
	}
	
	/**
	 * 更新表记录
	 *
	 * @param array $attributes
	 * @param array $where
	 * @return bollean true更新成功 false更新失败
	 */
	public function save($where, $data)
	{
		$this->db_connect($this->db_group_name, 1);
		$this->table_name = $this->table;
		return $this->update($where, $data);
	}
	
	/**
	 * 删除记录
	 *
	 * @param array $where 删除条件
	 * @param int $limit 删除行数
	 * @return boolean true删除成功 false删除失败
	 */
	public function del($where, $limit=NULL)
	{
		$this->db_connect($this->db_group_name, 1);
		$this->table_name = $this->table;
		return $this->delete($where, $limit);
	}
	
	/**
	 * 根据属性获取一行记录
	 * @param array $where
	 * @param string $orderby
	 * @param array $groupby
	 * @return Array 一维数组
	 */
	public function get_one($where = array(), $orderby = 'id desc', $groupby = array())
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		return $this->find_one($where, $orderby, $groupby);
	}
	
	/**
	 * 根据属性获取一行记录中某个字段值
	 * @param string $key_name
	 * @param array $where
	 * @param string $orderby
	 * @param array $groupby
	 * @return string
	 */
	public function get_one_key_map($key_name=NULL, $where = array(), $orderby = 'id desc', $groupby = array())
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		return $this->find_one_key_map($key_name, $where, $orderby, $groupby);
	}
	
	/**
	 * 查询记录
	 * @param array $where
	 * @param array $fields
	 * @param array $limit
	 * @param string $orderby
	 * @param array $groupby
	 * @return Array 二维数组
	 */
	public function get_list($where = array(),  $fields = array('id','parentid','model','action','data','type','status','name','icon','remark','listorder'), $limit = array(0,20), $orderby = 'id desc', $groupby = array())
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		$this->fields = $fields;
		return $this->find_list($where, $limit, $orderby, $groupby);
	}
	
	/**
	 * 查询记录(按指定键值做Key,注意 若重复键值后面的会覆盖掉前面的)
	 * @param string $key_name
	 * @param array $where
	 * @param array $limit
	 * @param string $orderby
	 * @param array $groupby
	 * @return Array 二维数组
	 */
	public function get_list_map($key_name=NULL, $where = array(), $limit = array(0,20), $orderby = 'id desc', $groupby = array())
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		return $this->find_list_map($key_name, $where, $limit, $orderby, $groupby);
	}
	
	/**
	 * 查询记录(返回指定key的值的结果集合)
	 * @param string $key_name
	 * @param array $where
	 * @param array $limit
	 * @param string $orderby
	 * @param array $groupby
	 * @return Array 一维数组
	 */
	public function get_list_key_map($key_name=NULL, $where = array(), $limit = array(0,20), $orderby = 'id desc', $groupby = array())
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		return $this->find_list_key_map($key_name, $where, $limit, $orderby, $groupby);
	}
	
	/**
	 * 统计满足条件的总数
	 *
	 * @param array $where 统计条件
	 * @param array $groupby
	 * @return int 返回记录条数
	 */
	public function get_total($where = array(), $groupby = array())
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		return $this->count($where, $groupby);
	}
	
	/**
	 * 根据SQL查询， 参数通过$param绑定
	 * @param string $sql 查询语句，如SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?
	 * @param array $param array(3, 'live', 'Rick')
	 * @return array 未找到记录返回空数组，找到记录返回二维数组
	 */
	public function get_by_sql($sql, $param = array())
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		return $this->query($sql, $param);
	}
	
	/**
	 * 根据SQL查询， 参数通过$param绑定(按指定键值做Key,注意 若重复键值后面的会覆盖掉前面的)
	 * @param string $sql 查询语句，如SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?
	 * @param array $param array(3, 'live', 'Rick')
	 * @return array 未找到记录返回空数组，找到记录返回二维数组
	 */
	public function get_map_by_sql($sql, $param = array(), $key_name=NULL)
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		return $this->query_map($sql, $param, $key_name);
	}
	
	/**
	 * execue
	 */
	public function execue($sql, $param = array())
	{
		$this->db_connect($this->db_group_name, 1);
		$this->table_name = $this->table;
		return $this->execue_sql($sql, $param);
	}
	
	/**
	 * 菜单树状结构集合
	 */
	public function menu_array() {
		$data = $this->get_tree(0);
		return $data;
	}
	
	//取得树形结构的菜单
	public function get_tree($myid, $parent = "", $Level = 1) {
		$data = $this->admin_menu($myid);
		$menu_pattern = $this->config->item('menu_pattern');
		$Level++;
		$ret = array();
		if (is_array($data)) {
			foreach ($data as $a) {
				$id = $a['id'];
				$model = strtolower($a['model']);
				$action = strtolower($a['action']);
				//附带参数
				$fu = "";
				if ($a['data']) {
					$fu = "?" . $a['data'];
				}
				$array = array(
						"icon" => $a['icon'],
						"id" => $id,
						"type" => $a['type'],
						"name" => $a['name'],
						"parent" => $parent,
						"url" => "{$model}/{$action}{$fu}",
				);
	
				$ret[$id] = $array;
				$child = $this->get_tree($a['id'], $id, $Level);
				//由于后台管理界面只支持四层，超出的不层级的不显示
				if ($child && $Level <= 4) {
				//左侧菜单使用
					if($menu_pattern == 'left'){
						foreach ($child as $k=>$v){
							$ret[$id]['items'][$k] = $v;
						}
					}elseif($menu_pattern == 'top'){
						//横向菜单使用
						foreach ($child as $k=>$v){
							//如果是带url的菜单，则加入defaul组
							if($v['type'] == 1){
							$ret[$id]['default'][] = $v;
							}else{
							//如果是不带url的菜单，加入items组
							$ret[$id]['items'][$k] = $v;
							}
						}
					}
				}
			}
			return $ret;
		}
		return false;
	}
	
	/**
	 * 按父ID查找菜单子项
	 * @param integer $parentid   父菜单ID
	 * @param integer $with_self  是否包括他自己
	 */
	public function admin_menu($parentid, $with_self = false) {
		//父节点ID
		$parentid = (int) $parentid;
		$result = $this->get_list(array('parentid' => $parentid, 'status' => 1),array(),array(),'listorder desc');
		if ($with_self) {
			$result2[] = $this->get_one(array('id' => $parentid, 'status !='=>-1));
			$result = array_merge($result2, $result);
		}
		
		//权限检查
		if (isset($this->config->item('root')[$this->session->userdata('name')])) {
			//如果是超级管理员返回全部
			return $result;
		}
	
		$array = array();
		//实例化权限表
		$this->load->model('User_access_model');
		foreach ($result as $v) {
			//方法
			$action = $v['action'];
	
// 			if (preg_match('/^ajax_([a-z]+)_/', $action, $_match)) {

// 				$action = $_match[1];
// 			}
			$r = $this->User_access_model->get_one(array('m' => $v['model'], 'a' => $action, 'user_id' => $this->session->userdata('userid')),'user_id desc');

			if ($r) {
				$array[] = $v;
			}
		}
		return $array;
		
	}
	
	/**
	 *  检查指定角色对该菜单是否有权限
	 * @param array $data 菜单
	 * @param int $roleid 需要检查的角色ID
	 * @param array $access 角色拥有的菜单权限
	 */
	public function is_checked($data, $roleid, $access) {
		
		$mdata['role_id'] = $roleid;
		$mdata["m"] = $data['model'];
		$mdata["a"] = $data['action'];
		$info = in_array($mdata, $access);
		if ($info) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 *  检查指定角色对该菜单是否有权限
	 * @param array $data 菜单
	 * @param int $roleid 需要检查的角色ID
	 * @param array $access 角色拥有的菜单权限
	 */
	public function is_checked_user($data, $user_id, $access) {
		$mdata['user_id'] = $user_id;
		$mdata["m"] = $data['model'];
		$mdata["a"] = $data['action'];
		$info = in_array($mdata, $access);
		if ($info) {
			return true;
		} else {
			return false;
		}
	}	
}