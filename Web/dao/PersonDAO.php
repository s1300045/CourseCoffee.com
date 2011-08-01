<?php
/**
 * @file
 * Represent a person record in database
 */
class PersonDAO extends DAO implements DAOInterface{

	/**
	 * Extend DAO::__construct().
	 */
	function __construct($db, $params = NULL) {
		$attr = array('id', 'user_id', 'first_name', 'last_name');
		parent::__construct($db, $attr, $params);

	}

	/**
	 * Extend DAO::create().
	 */
	public function create($params) {
		if (
			!isset($params['user_id']) || 
			!isset($params['first_name']) ||
			!isset($params['last_name'])
		) {
			throw new Exception('incomplete person params - ' . print_r($params, true));
			return ;

		}else{
			return $this->db->insert("
				INSERT INTO `person` (`user_id`, `first_name`, `last_name`)
				VALUES (:user_id, :first_name, :last_name)",
			array(
				'user_id'    => $params['user_id'],
				'first_name' => $params['first_name'], 
				'last_name'  => $params['last_name'],
			));

		}

	}

	/**
	 * Extend DAO::read().
	 */
	public function read($params) {
		$sql = "SELECT * FROM `person` WHERE ";
		
		if (isset($params['id'])) {
			$params = array('id' => $params['id']);
			$sql .= "`id` = :id";

		} elseif (isset($params['user_id'])) {
			$params = array('user_id' => $params['user_id']);
			$sql .= "`user_id` = :user_id";

		} elseif (isset($params['first_name']) && isset($params['last_name'])) {
			$params = array(
				'first_name' => $params['first_name'],
				'last_name' => $params['last_name'],
			);
			$sql .= "`first_name` = :first_name AND `last_name` = :last_name";

		} elseif (isset($params['first_name'])) {
			$params = array('first_name' => $params['first_name']);
			$sql .= "`first_name` = :first_name";

		} else {
			throw new Exception('unknown user identifier');

		}

		$data = $this->db->fetch($sql, $params);
		return $this->updateAttribute($data);

	}

	/**
	 * Extend DAO::update()
	 */
	public function update() {
		$sql = "
			UPDATE `person` SET
				`first_name` = :first_name,
				`last_name` = :last_name
			WHERE `id` = :id
		";
		$this->db->perform($sql, array(
			'first_name' => $this->attr['first_name'], 
			'last_name' => $this->attr['last_name']
		));

	}

	/**
	 * Extend DAO::destroy().
	 */
	public function destroy() {
		$sql = "DELETE FROM `person` WHERE `id` = :id";
		$this->db->perform($sql, array('id' => $this->id));

	}
}