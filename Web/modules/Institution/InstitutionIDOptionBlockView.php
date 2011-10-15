<?php
/**
 * @file
 * Generate a list of institutions and their ID for selections
 *
 * @author Daniel Chen <daniel@coursecoffee.com>
 */
class InstitutionIDOptionBlockView extends BlockView implements BlockViewInterface {

	/**
	 * Generate an option list from an array
	 *
	 * @param array $institution_list
	 * @param string $domain
	 */
	private function generateOption($institution_list) {
		$html = '<option selected="selected">Please select school</option>';
		foreach ($institution_list as $key=> $value) {
			$html .= <<<HTML
<option value="{$value['id']}">{$value['name']}</option>
HTML;
		}
		return $html;
	}

	/**
	 * Implement BlockViewInterface::getContent().
	 */
	public function getContent() {
		extract($this->data);
		$options = $this->generateOption($institution_list, $domain);
		return <<<HTML
<form id="institution-domain-form" name="institution-domain">
	<div class="row">
		<select name="institution-domain-options">
			{$options}
		</select>
	</div>
</form>
HTML;
	}
}
