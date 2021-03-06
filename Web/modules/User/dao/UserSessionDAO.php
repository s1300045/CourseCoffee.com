<?php
/**
 * @file
 * Represents a user_session cookie record in database
 */
class UserSessionDAO extends DAO implements DAOInterface{


	/**
	 * Implement DAO::defineAttribute().
	 */
	protected function defineAttribute() {
		return array('user_id', 'session');
	}

	/**
	 * Implement DAOInterface::create().
	 */
	public function create($params) {
		if (!isset($params['user_id']) || !isset($params['session'])) {
			throw new Exception('incomplete user_session params - ' . print_r($params, true));
			return ;

		}else{
			return $this->db->insert("
				REPLACE INTO `user_session` (`user_id`, `session`)
				VALUES (:user_id, :session)",
			array(
				'user_id' => $params['user_id'], 
				'session' => $params['session']
			));

		}

	}

	/**
	 * Implement DAOInterface::read().
	 */
	public function read($params) {
		$sql = "SELECT * FROM `user_session` WHERE ";
		
		if (isset($params['user_id'])) {
			$params = array('user_id' => $params['user_id']);
			$sql .= "`user_id` = :user_id";

		} elseif (isset($params['session'])) {
			$params = array('session' => $params['session']);
			$sql .= "session = :session";

		} else {
			throw new Exception('unknown user_session identifier');

		}

		$data = $this->db->fetch($sql, $params);
		return $this->updateAttribute($data);

	}

	/**
	 * Implement DAOInterface::update()
	 */
	public function update() {
		$sql = "
			UPDATE `user_session` SET
				`user_id` = :user_id,
				`session` = :session
			WHERE `id` = :id
		";
		$this->db->perform($sql, array(
			'user_id' => $this->attr['user_id'], 
			'session' => $this->attr['session']
		));

	}

	/**
	 * Implement DAOInterface::destroy().
	 */
	public function destroy() {
		$sql = "DELETE FROM `user_session` WHERE `user_id` = :user_id";
		$this->db->perform($sql, array('user_id' => $this->user_id));

	}
}
