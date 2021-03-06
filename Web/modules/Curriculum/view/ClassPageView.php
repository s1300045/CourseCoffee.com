<?php
/**
 * @file
 * Generate the Class page for visiters
 */
class ClassPageView extends PageView implements PageViewInterface {

	/**
	 * Extend PageView::__construct().
	 */
	function __construct($content = null) {
		parent::__construct($content);
		$this->setPageTitle('class');

		$this->addJQueryUI();
		$this->addJQueryUIPlugin('datetime');

		if (is_array($content['class_list'])) {
			$this->buildClassList($content['class_list']);
		}

		$this->addJS('Quest/task-model');
		$this->addJS('CourseCoffee/timer');
		$this->addJS('CourseCoffee/panel-model');
		$this->addJS('Curriculum/class-remove-model');
		$this->addJS('Curriculum/class-info-model');
		$this->addJS('Curriculum/class-controller');

		$this->addCSS('CourseCoffee/dialog');
		$this->addCSS('CourseCoffee/panel');
		$this->addCSS('Quest/task');
		$this->addCSS('Item/book-list');
		$this->addCSS('Curriculum/class-remove');
		$this->addCSS('Curriculum/class');
	}

	/**
	 * Implement View::getHeader()
	 */
	protected function getHeader() {
    header(self::STATUS_OK);
	}

	/**
	 * Implement PageViewInterface::getBlocks()
	 */
	public function getBlocks() {
		return array(
			'header' => array(
				'callback' => 'NavigationBlockView',
				'params'   => array('role'),
			),
			'upload_form' => array(
				'callback' => 'UploadFormBlockView',
			),
			'footer' => array(
				'callback' => 'FooterBlockView',
			),
		);
	}

	/**
	 * Build Class list
	 *
	 * @param array $class_list
	 */
	private function buildClassList($class_list) {
		$html = "<ul>";
		foreach ($class_list as $id => $class_code) {
			$html .= "<li><a href='#' id='{$id}' class='option'>{$class_code}</a></li>";
		}
		$html .= "</ul>";
		$this->data['panel_menu'] = $html;
	}

	private function getClassOption($class_info) {

		// debug
		//  error_log('class info - ' . print_r($class_info, true));
		if ($class_info['content']) {
			extract($class_info['content']);

			// debug
			// error_log('institution_uri - ' . $institution_uri);

			return <<<HTML
	<input type="hidden" name="institution-uri" value="{$institution_uri}" />
	<input type="hidden" name="institution-id" value="{$institution_id}" />
	<input type="hidden" name="year" value="{$year}" />
	<input type="hidden" name="term" value="{$term}" />
	<input type="hidden" name="subject-abbr" value="{$subject_abbr}" />
	<input type="hidden" name="course-title" value="{$course_title}" />
	<input type="hidden" name="course-num" value="{$course_num}" />
	<input type="hidden" name="section-num" value="{$section_num}" />
	<input type="hidden" name="section-id" value="{$section_id}" />
	<input type="hidden" name="syllabus-id" value="{$syllabus_id}" />
	<input type="hidden" name="syllabus-status" value="{$syllabus_status}" />
HTML;
		} else {
			return <<<HTML
	<input type="hidden" name="institution-uri" value="" />
	<input type="hidden" name="institution-id" value="" />
	<input type="hidden" name="year" value="" />
	<input type="hidden" name="term" value="" />
	<input type="hidden" name="subject-abbr" value="" />
	<input type="hidden" name="course-title" value="" />
	<input type="hidden" name="course-num" value="" />
	<input type="hidden" name="section-num" value="" />
	<input type="hidden" name="section-id" value="{$section_id}" />
	<input type="hidden" name="syllabus-id" value="" />
	<input type="hidden" name="syllabus-status" value="" />
HTML;
		}
	}

	/**
	 * Implement PageViewInterface::getContent()
	 */
	public function getContent() {
		extract($this->data);
		$option = '';
		if (is_array($class_list)) {
			foreach ($class_list as $section_id => $section_code) {
				$option .= "<option value='{$section_id}'>{$section_code}</option>";
			}
		}
		$class_select = <<<HTML
<select name="section_id" class="class-list">
	<option selected="selected">pick a class</option>
	{$option}
</select>
HTML;
	
		$class_option = $this->getClassOption($default_class);

		return <<<HTML
<div class="class container">
	<div class="container-inner">
		<div class="header">
			<div class="header-inner">
				{$header}
			</div>
		</div>
		<div class="system-message hidden">
			<div class="system-message-inner">
			</div>
		</div>
		<div class="class body">
			<div class="body-inner">
				<div class="content">
					<div class="class panel-menu">
						<div class="panel-menu-inner">
							<form name="class-option" id="class-option-form">
								{$class_option}
								<input type="hidden" name="filter" value="pending" />
								<input type="hidden" name="paginate" value="0" />
							</form>
							{$panel_menu}
						</div>
					</div>
					<div class="panel-01">
						<div class="panel-inner">
							<div class="class-section-info"></div>
							<div id="class-info-menu">
								<ul>
									<li id="option-book" class="active">books</li>
									<li id="option-comment" >comments</li>
								</u>
							</div>
							<div id="class-book-list" class="book-list class-info-content" ></div>
						</div>
					</div>
					<div class="panel-02">
						<div class="panel-inner">
							{$upload_form}
							<div class="task-create-form-wrapper">
								<form id="class-task-creation-form" class="task-create-form" action="task/create" method="post">
									<fieldset class="required">
										<legend>NEW to-do</legend>
										<input type="hidden" name="token" />
										<div class="required">
											<div class="row">
												<input type="text" name="objective" class="objective" value="reading due in the morning, etc" maxlength="72"/>
											</div>
										</div>
										<div class="additional hidden">
											<div class="row">
												<label for="due_date" class="title">Due: </label>
												<input type="text" name="due_date" id="time-picker" class="due_date" />
											</div>
											<div class="row">
												<label for="section_id" class="title">Class: </label>
												{$class_select}
												<a href="#" class="button show-detail">more detail</a>
											</div>
											<div class="optional hidden">
												<div class="row">
													<label for="location" class="title">Place: </label>
													<input type="text" name="location" class="location"/>
												</div>
												<div class="row">
													<textarea name="description" rows="6" class="description">read chapter 12 to 13, etc</textarea>
												</div>
											</div>
											<a href="#" class="button submit">add</a>
										</div>
									</fieldset>
								</form>
							</div>
							<div id="task-info-menu">
								<ul>
									<li id="option-pending" class="active">to-do</li>
									<li id="option-finished" >finished</li>
									<li id="option-all">all</li>
								</ul>
							</div>
							<div id="class-task-list" class="task-list">
							</div>
							<a href="#" class="button more">more</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="footer">
	<div class="footer-inner">
		{$footer}
	</div>
</div>
HTML;
	}
}
