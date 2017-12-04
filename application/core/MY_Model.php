<?php
/**
 * 
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	static $db_arr_flag = array();
	protected $db;
	protected $table_name;
	protected $fields;
	
	/**
	 * 构造函数，连接数据库
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
	}
	
	/**
	 * 数据库连接及切换
	 * @param string 在文件config/database.php中配置的数据库连接名
	 * @param string $group_name
	 * @param int $master 主从库flag 0为从库，1为主库
	 */
	public function db_connect($group_name='', $master=0)
	{
		$is_master = $master?1:0;
		$group_name = $group_name.'_'.$is_master;
		if (isset(self::$db_arr_flag[$group_name])) {
			$this->db = self::$db_arr_flag[$group_name];
		} else {
			$this->db = $this->load->database($group_name, TRUE);
			self::$db_arr_flag[$group_name] = $this->db;
		}
		return $this->db;
	}

	/**
	 * 保存数据
	 *
	 * @param array $data 需要插入的表数据
	 * @return boolean 插入成功返回ID，插入失败返回false
	 */
	public function insert($data)
	{
		if ($this -> db -> set($data) -> insert($this->table_name)) {
			return $this -> db -> insert_id();
		}
		return FALSE;
	}

	public function insert_batch($data) {
		if (!isset($data[0])) {
			return FALSE;
		}
		if ($this -> db -> insert_batch($this->table_name,$data)) {
			return $this -> db -> affected_rows();
		}
		return FALSE;
	}

	/**
	 * Replace数据
	 * @param array $data
	 */
	public function replace($data)
	{
		return $this->db->replace($this->table_name, $data);
	}

	/**
	 * 更新表记录
	 *
	 * @param array $attributes
	 * @param array $where
	 * @return bollean true更新成功 false更新失败
	 */
	public function update($where=array(), $attributes=array())
	{
		if (empty($where)||empty($attributes)) {
			return FALSE;
		}
		foreach ($where as $k=>$v) {
			if (is_array($v)) {
				$this->db->where_in($k,$v);
				unset($where[$k]);
			}
			if (strpos($k, '#')!==false) {
				$tmp = explode('#', $k);
				if ($tmp[0]=='LK') {
					$this->db->like($tmp[1],$v);
				}
				unset($where[$k]);
			}
		}
		return $this->db->where($where)->update($this->table_name, $attributes);
	}

	/**
	 * 删除记录
	 *
	 * @param array $where 删除条件
	 * @param int $limit 删除行数
	 * @return boolean true删除成功 false删除失败
	 */
	public function delete($where = array(), $limit = NULL)
	{
		if (empty($where)) {
			return FALSE;
		}
		
		foreach ($where as $k=>$v) {
			if (is_array($v)) {
			
				$this->db->where_in($k,$v);
				unset($where[$k]);
			}
			if (strpos($k, '#')!==false) {
				$tmp = explode('#', $k);
				if ($tmp[0]=='LK') {
					$this->db->like($tmp[1],$v);
				}
				unset($where[$k]);
			}
		}
		return $this->db->delete($this->table_name, $where, $limit);
	}

	/**
	 * 根据属性获取一行记录
	 * @param array $where
	 * @param string $orderby
	 * @param array $groupby
	 * @return Array 一维数组
	 */
	public function find_one($where = array(), $orderby = 'id desc', $groupby = array())
	{
		$this->db->from($this->table_name);
		if (!empty($this->fields)) {
			$fields_string = implode(',', $this->fields);
			$this->db->select($fields_string);
		}
		foreach ($where as $k=>$v) {
			if (is_array($v)) {
				$this->db->where_in($k,$v);
				unset($where[$k]);
			}
			if (strpos($k, '#')!==false) {
				$tmp = explode('#', $k);
				if ($tmp[0]=='LK') {
					$this->db->like($tmp[1],$v);
				}
				unset($where[$k]);
			}
		}
		$this->db->where($where);
		$this->db->limit(1);
		if (!empty($groupby)) {
			$this->db->group_by($groupby);
		}
		if (!empty($orderby)) {
			$this->db->order_by($orderby);
		}
		$query = $this->db->get();

		return $query->row_array() ? $query->row_array() : array();
	}
	
	/**
	 * 根据属性获取一行记录中某个字段值
	 * @param string $key_name
	 * @param array $where
	 * @param string $orderby
	 * @param array $groupby
	 * @return string
	 */
	public function find_one_key_map($key_name=NULL, $where = array(), $orderby = 'id desc', $groupby = array())
	{
		$res = $this->find_one($where, $orderby, $groupby);
		if (!empty($key_name)&&isset($res[$key_name])) {
			return $res[$key_name];
		}
		return FALSE;
	}

	/**
	 * 查询记录
	 * @param array $where
	 * @param array $limit
	 * @param string $orderby
	 * @param array $groupby
	 * @return Array 二维数组
	 */
	public function find_list($where = array(), $limit = array(0,20), $orderby = 'id desc', $groupby = array())
	{
		$this->db->from($this->table_name);
		if (!empty($this->fields)) {
			$fields_string = implode(',', $this->fields);
			$this->db->select($fields_string);
		}
		foreach ($where as $k=>$v) {
			if (is_array($v)) {
				$this->db->where_in($k,$v);
				unset($where[$k]);
			}
			if (strpos($k, '#')!==false) {
				$tmp = explode('#', $k);
				if ($tmp[0]=='LK') {
					$this->db->like($tmp[1],$v);
				}
				unset($where[$k]);
			}
		}
		$this->db->where($where);
		if (!empty($limit)) {
			$this->db->limit($limit[1], $limit[0]);
		}
		if (!empty($orderby)) {
			$this->db->order_by($orderby);
		}
		$query = $this->db->get();
		return $query->result_array() ? $query->result_array() : array();
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
	public function find_list_map($key_name=NULL, $where = array(), $limit = array(0,20), $orderby = 'id desc', $groupby = array())
	{
		$result = array();
		$list = $this->find_list($where, $limit, $orderby, $groupby);
		foreach ($list as $row) {
			if (!empty($key_name) && array_key_exists($key_name, $row)) {
				$result[$row[$key_name]] = $row;
			} else {
				$result[] = $row;
			}
		}
		return $result;
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
	public function find_list_key_map($key_name=NULL, $where = array(), $limit = array(0,20), $orderby = 'id desc', $groupby = array())
	{
		$result = array();
		$list = $this->find_list($where, $limit, $orderby, $groupby);
		foreach ($list as $row) {
			if (!empty($key_name) && array_key_exists($key_name, $row)) {
				$result[] = $row[$key_name];
			}
		}
		return $result;
	}

	/**
	 * 统计满足条件的总数
	 *
	 * @param array $where 统计条件
	 * @param array $groupby
	 * @return int 返回记录条数
	 */
	public function count($where = array(), $groupby = array())
	{
		$this->db->from($this->table_name);
		foreach ($where as $k=>$v) {
			if (is_array($v)) {
				$this->db->where_in($k,$v);
				unset($where[$k]);
			}
			if (strpos($k, '#')!==false) {
				$tmp = explode('#', $k);
				if ($tmp[0]=='LK') {
					$this->db->like($tmp[1],$v);
				}
				unset($where[$k]);
			}
		}
		$this -> db -> where($where);
		if (!empty($groupby)) {
			$this -> db -> group_by($groupby);
		}
		return $this -> db -> count_all_results();
	}

	/**
	 * 根据SQL查询， 参数通过$param绑定
	 * @param string $sql 查询语句，如SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?
	 * @param array $param array(3, 'live', 'Rick')
	 * @return array 未找到记录返回空数组，找到记录返回二维数组
	 */
	public function query($sql, $param = array())
	{
		$query = $this->db->query($sql, $param);
		return $query->result_array();
	}

	/**
	 * 根据SQL查询， 参数通过$param绑定(按指定键值做Key,注意 若重复键值后面的会覆盖掉前面的)
	 * @param string $sql 查询语句，如SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?
	 * @param array $param array(3, 'live', 'Rick')
	 * @return array 未找到记录返回空数组，找到记录返回二维数组
	 */
	public function query_map($sql, $param = array(), $key_name=NULL)
	{
		$result = array();
		$query = $this -> db -> query($sql, $param);
		if ($query) {
			foreach ($query->result_array() as $row) {
				if (!empty($key_name) && array_key_exists($key_name, $row)) {
					$result[$row[$key_name]] = $row;
				} else {
					$result[] = $row;
				}
			}
		}
		return $result;
	}
	
	/**
	 * execue
	 */
	public function execue_sql($sql, $param)
	{
		$query = $this -> db -> query($sql, $param);
		return $query;
	}
	
	
	/**
	 * 获取最后执行sql
	 * @return string
	 */
	public function get_last_query()
	{
		return $this -> db -> last_query();
	}
	
	public function table_exists($table_name)
	{
	    return $this -> db -> table_exists($table_name);
	}
}

