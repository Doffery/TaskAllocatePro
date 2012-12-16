<?php

/**
 * TestDiary
 *  
 * @author ��
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_TestDiary extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'test';
	protected $_primary = 'Test_Diary_ID';
}
