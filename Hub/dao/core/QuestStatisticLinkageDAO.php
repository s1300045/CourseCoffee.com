<?php

require_once DAO_PATH . '/abstract/LinkageDAO.php';

/**
 * Represent a linkage between a quest and a statistic
 */
class QuestStatisticLinkageDAO extends LinkageDAO{

	/**
	 * Extend LinkageDAO::__construct().
	 */
	function __construct($db, $params = NULL) {
		$attr = array('quest_id', 'statistic_id', 'id');
		$this->linkage = 'quest_statistic_linkage';
		parent::__construct($db, $attr, $params);
	}

}
