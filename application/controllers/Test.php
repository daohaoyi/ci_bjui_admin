<?php
/**
 * Test
 */
class Test extends CI_Controller{
	function __construct()
	{
		parent::__construct();
	}
	
	public function test(){
		$this->load->model('Testdb_model');
		$data = array();
		$data['name'] = 'fff';
		$data['age'] = '12';
		//echo $this->Testdb_model->add($data);
		echo '<br>';
		//echo $this->Testdb_model->get_last_query();
		
		//$sql = "INSERT INTO `xk_test` (`name`, `age`) VALUES (?, ?)";
		//echo $this->Testdb_model->execue($sql, array('fff', '14'));
		//echo '<br>';
		//echo $this->Testdb_model->get_last_query();
		$list = $this->Testdb_model->get_list(array('name'=>array('fff'),'id'=>array(4,5,6)));
		echo $this->Testdb_model->get_last_query();
		echo '<br>';
		var_dump($list);
	}
}