<?php
/**
 * @file
 * Over see access to college class records
 */
class CurriculumClassModel extends Model {

	/**
	 * def_group class_error
	 * @{
	 * Error message to infor user of the result
	 */
	const NO_CLASS_FOUND = 'We are sorry, but no class could be found with the provided information';

	const ERROR_ALREADY_HAS_SYLLABUS = "hmmm... someone already uploaded a syllabus for this class, but that's okay!";
	const EVENT_ALREADY_HAS_SYLLABUS = 'Attempt to upload syllabus to a class already has one';
	const SYLLABUS_SUCCESS = 'Congratulations! The syllabus is now uploaded!';

	const SYLLABUS_APPROVED = 'approved';
	const SYLLABUS_NEW      = 'has_syllabus';
	const SYLLABUS_REMOVED  = 'removed';

	/**
	 * Implement Model::defineDAO()
	 */
	protected function defineDAO() {
		return array(
			'curriculum_class' => array(
				'class' => 'CurriculumClassDAO',
				'db'    => self::INSTITUTION_DB,
			),
		);
	}

	/**
	 * A helper method to share code
	 *
	 * @return array
	 *  On success:
	 *   - success:
	 *   - content:
	 *  On failure:
	 *   - error:
	 *   - meessage:
	 */
	private function returnResult($has_record) {
		if ($has_record) {
			return array(
				'content' => $this->dao['curriculum_class']->attribute,
			);
		}

		return array(
			'error'   => true,
			'message' => self::NO_CLASS_FOUND,
		);
	}

	/**
	 * Get college class information by section id
	 *
	 * @param string $section_id
	 *
	 * @return array
	 *  On success:
	 *   - success:
	 *   - content:
	 *  On failure:
	 *   - error:
	 *   - meessage:
	 */
	public function getClassById($section_id) {
		$has_record = $this->dao['curriculum_class']->read(array('id' => $section_id));
		return $this->returnResult($has_record);
	}

	/**
	 * Get college class information by section code
	 *
	 * @param string subject_abbr
	 * @param string course_num
	 * @param string section_num
	 *
	 * @return array
	 *  On success:
	 *   - success:
	 *   - content:
	 *  On failure:
	 *   - error:
	 *   - meessage:
	 */
	public function getClassBySectionCode($subject_abbr, $course_num, $section_num) {
		$has_record = $this->dao['curriculum_class']->read(array(
			'subject_abbr'     => strtoupper($subject_abbr),
			'course_num'       => strtoupper($course_num),
			'section_num'      => strtoupper($section_num),
		));
		return $this->returnResult($has_record);
	}

	/**
	 * Check if class already has syllabus uploaded
	 *
	 * @param int $section_id
	 *
	 * @return bool
	 *  return false where there is no syllabus
	 */
	public function hasClassSyllabus($section_id) {
		$section_dao = new SectionDAO($this->institution_db);
		$has_record = $section_dao->read(array('id' => $section_id));

		// debug
		// error_log(__METHOD__ . ' : result ' . print_r($section_dao->attribute, true));

		$result = $section_dao->attribute;
		switch ($result['syllabus_status']) {
			case self::SYLLABUS_APPROVED:
			case self::SYLLABUS_NEW:
				return true;
			default:
			case self::SYLLABUS_REMOVED:
				return false;
		}
	}

	/**
	 * Update section syllabus status
	 */
	public function updateClassSyllabusStatus($section_id, $syllabus_id, $status) {
		$section_dao = new SectionDAO($this->institution_db);
		if ($section_dao->read(array('id' => $section_id))) {
			$section_dao->syllabus_id     = $syllabus_id;
			$section_dao->syllabus_status = $status;
			$section_dao->update();

			// debug
			// error_log(__METHOD__ . ' : result ' . print_r($section_dao->attribute, true));
		}

		return $has_record;
	}

}
