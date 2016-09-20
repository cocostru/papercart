<?php
class ControllerModulePaperFilter extends Controller {
	public function index() {

		$this->load->language('module/paper_filter');

		// $this->document->addScript('//code.jquery.com/ui/1.11.1/jquery-ui.js');
		// $this->document->addScript('catalog/view/javascript/paper-filter/paper-filter.js');
		// $this->document->addScript('catalog/view/javascript/paper-filter/touch-punch.js');
		// $this->document->addStyle('catalog/view/javascript/paper-filter/paper-filter.css');
		// $this->document->addStyle('//code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css');

		$data['slider_filter'] = $this->config->get('slider_filter');

		if (version_compare(VERSION, '2.2.0.0', '<') == true) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/paper_filter.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/paper_filter.tpl', $data);
			} else {
				return $this->load->view('default/template/module/paper_filter.tpl', $data);
			}
		} else {
			return $this->load->view('module/paper_filter', $data);
		}
	}

	public function baseUnit() {
		if (isset($this->request->post['bunit'])) {
			$this->session->data['bunit'] = $this->request->post['bunit'];
		}
	}
}
