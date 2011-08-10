<?php
/**
 * @file
 * Represent a list of classes belong to user
 *
 * This DAO does not implement DAOInterface
 */
class UserClassListDAO extends DAO {

	/**
	 * Extend DAO::__construct().
	 */
	function __construct($db, $params = NULL) {
		$attr = array(
			'institution_id',
			'institution',
			'subject_id',
			'subject_abbr',
			'course_id',
			'course_num',
			'course_title',
			'section_id',
			'section_num',
		);
		parent::__construct($db, $attr, $params);

	}

	/**
	 * Extend DAO::read().
	 *
	 * @param array $params
	 *  - user_id
	 *  - institution_id
	 *  - year_id
	 *  - term_id
	 */
	public function read($params) {
		if (!isset($params['user_id']) ||
				!isset($params['institution_id']) ||
				!isset($params['year_id']) ||
				!isset($params['term_id'])
		) {
			throw new Exception('unknow user class list identifier - ' . print_r($params, true));
			return false;
		}

		$sql = "
			SELECT 
				s.id AS section_id,
				s.num AS section_num,
				c.num AS course_num,
				sub.abbr AS subject_abbr
			FROM `section` s
			INNER JOIN user_section_linkage us_linkage
				ON s.id = us_linkage.section_id
			INNER JOIN course c
				ON s.course_id = c.id
			INNER JOIN subject sub
				ON c.subject_id = sub.id
			INNER JOIN subject_term_linkage st_linkage
				ON sub.id = st_linkage.subject_id
			INNER JOIN institution_term it
				ON st_linkage.term_id = it.id
			INNER JOIN institution_year iy
				ON it.year_id = iy.id
			INNER JOIN institution_year_linkage iy_linkage
				ON iy.id = iy_linkage.year_id
			INNER JOIN institution i
				ON iy_linkage.institution_id = i.id
			WHERE us_linkage.user_id = :user_id
				AND i.id = :institution_id
				AND iy.id = :year_id
				AND it.id = :term_id
		";

		$sql_param = array(
			'user_id'        => $params['user_id'],
			'institution_id' => $params['institution_id'],
			'year_id'        => $params['year_id'],
			'term_id'        => $params['term_id'],
		);

		$this->list = $this->db->fetch($sql, $sql_param);
		return !empty($this->list);
	}
}
