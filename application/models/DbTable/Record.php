<?php

/**
 * ApplyMission
 *  
 * @author ��
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_Record extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'record';
	
	public function getmissioncompleted($num) {
		$result = $this->fetchAll('Price > 0','Mission_ID DESC',$num)->toArray();
		rsort($result);
		return $result;
	}
}
