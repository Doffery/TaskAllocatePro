<?php

/**
 * Mission
 *  
 * @author ��
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbTable_Mission extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_name = 'mission';
	protected $_primary = 'Mission_ID';
}
