<?php
/**
 * 角色逻辑层
 * @author gaomin
 *
 */
class Adm_role_model extends MY_Model{
	private $db_group_name = 'ci_db';
	private $table = 'adm_role';
	
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
	 * @param array $fields
	 * @param string $orderby
	 * @param array $groupby
	 * @return Array 一维数组
	 */
	public function get_one($where = array(), $fields = array('id','name','status','remark','create_time','update_time'), $orderby = 'id desc', $groupby = array())
	{
		$this->db_connect($this->db_group_name, 0);
		$this->table_name = $this->table;
		$this->fields = $fields;
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
	public function get_list($where = array(), $fields = array('id','name','status','remark','create_time','update_time'), $limit = array(0,20), $orderby = 'id asc', $groupby = array())
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
	 * 获取角色组
	 * @return multitype:Ambigous <unknown>
	 */
	public function get_adm_role(){
		$res=$this->get_list_map('id',array('status'=>1),array(),'id asc');
			if(!empty($res)){
				$ret=array();
				foreach($res as $key=>$v){
					$ret[$key]=$v['name'];
				}
			}
		return $ret;
	}
}
