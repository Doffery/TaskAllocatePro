<?php

/**
 * Tester
 *  
 * @author ��
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_Tester extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'tester';
	protected $_primary = 'Tester_ID';
}
