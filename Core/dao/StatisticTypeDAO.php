<?php

require_once __DIR__ . '/TypeDAO.php';

/**
 * Represent statistic types
 */
class StatisticTypeDAO extends TypeDAO{

	/**
	 * Implement TypeDAO::__construct().
	 */
	function __construct($db, $params = NULL) {
		$this->type = 'statistic_type';
		parent::__construct($db, $params);
	}

	/**
	 * Implement TypeDAO::create()
	 */
	public function create($params) {
		parent::create($params);
	}

	/**
	 * Implement TypeDAO::read()
	 */
	public function read($params) {
		$data = parent::read($params);

	}

	/**
	 * Implement TypeDAO::update()
	 */
	public function update() {
		parent::update();

	}

	/**
	 * Implement TypeDAO::destroy()
	 */
	public function destroy() {
		parent::destroy();

	}

}
