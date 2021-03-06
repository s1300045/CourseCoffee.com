<?php
/**
 * @file
 * Represent Types
 */
abstract class TypeDAO extends DAO implements DAOInterface{

	/**
	 * Name of the type table
	 */
	protected $type;


	/**
	 * Define the DAO class which this TypeDAO complements.
	 *
	 * @return array
	 */
	abstract protected function defineType() ;

	/**
	 * Extend DAO::__construct().
	 */
	function __construct($db) {
		parent::__construct($db);
		$this->defineType();
	}

	/**
	 * Implement DAO::defineAttribute().
	 */
	protected function defineAttribute() {
		return array('name', 'id');
	}

	/**
	 * Implement DAO::create()
	 */
	public function create($params) {
		$sql = "INSERT INTO `{$this->type}` (`name`)	VALUE (:name)";
		return $this->db->insert($sql, $params);
	}

	/**
	 * Implement DAO::read()
	 */
	public function read($params) {
		$sql = "SELECT * FROM `{$this->type}` WHERE ";

		if (isset($params['id'])) {
			$params = array('id' => $params['id']);
			$sql .= "id = :id";

		} elseif (isset($params['name'])) {
			$params = array('name' => $params['name']);
			$sql .= "name = :name";

		} else {
			throw new Exception("unknown {$this->type} identifier - " . print_r($params, true));

		}

		$data = $this->db->fetch($sql, $params);
		return $this->updateAttribute($data);

	}

	/**
	 * Implement DAO::update()
	 */
	public function update() {
		$sql = "UPDATE `{$this->type}` SET `name` = :name WHERE `id` = :id";
		$this->db->perform($sql, $this->attr);

	}

	/**
	 * Implement DAO::destroy()
	 */
	public function destroy() {
		$this->db->perform(
			"DELETE FROM `{$this->type}` WHERE `id` = :id", 
			array('id' => $this->id)
		);

	}

}
