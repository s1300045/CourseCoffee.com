<?php
/**
 * @file
 * Create table schema for Section and populate default types
 */
class SectionDBA implements DBAInterface{

	/**
	 * Populate database
	 */
	public function script() {
		return array(
			"INSERT INTO section_type (name) VALUES 
				('" . SectionType::HONOR . "'), 
				('" . SectionType::HYBRID . "'),
				('" . SectionType::OFF_CAMPUS . "'),
				('" . SectionType::ON_CAMPUS . "'),
				('" . SectionType::ONLINE . "')"
		);
	}

	/**
	 * Create table schema
	 *
	 * @return array
	 */
	public function schema() {
		return array(
			'section' => array(
				'column' => array(
					'id' => array(
						'type' => 'serial',
						'unsigned' => TRUE,
						'not null' => TRUE,
						'description' => 'The primary key',
					),
					'course_id' => array(
						'type' => 'int',
						'size' => 'normal',
						'not null' => TRUE,
						'default' => 0,
						'description' => 'The course which this section belongs.',
					),
					'num' => array(
						'type' => 'char',
						'length' => 12,
						'not null' => TRUE,
						'description' => 'The section num, e.g. 101, 929, 132H, etc.',
					),
					'credit' => array(
						'type' => 'char',
						'length' => 12,
						'not null' => TRUE,
						'description' => 'The section credit, e.g. a specific numeric value or a range of possiblity.',
					),
					'syllabus_status' => array(
						'type' => 'char',
						'length' => 64,
						'not null' => TRUE,
						'description' => 'a status flag, e.g. HAS_SYLLABUS.',
					),
					'syllabus_raw' => array(
						'type' => 'text',
						'not null' => TRUE,
						'description' => 'the raw output generated from the document',
					),
				),
				'primary' => array('id'),
				'index' => array(
					'course_id' => array('course_id'),
					'num' => array('num'),
				),
			),
		);
	}
}
