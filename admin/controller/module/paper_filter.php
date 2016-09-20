<?php
// namespace Paper;
class ControllerModulePaperFilter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/paper_filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paper_filter', $this->request->post);
			$this->model_setting_setting->editSetting('range_filter', $this->request->post);
			$this->model_setting_setting->editSetting('manufacturer_filter', $this->request->post);
			$this->model_setting_setting->editSetting('location_filter', $this->request->post);
			$this->model_setting_setting->editSetting('reel_filter', $this->request->post);
			$this->model_setting_setting->editSetting('grain_filter', $this->request->post);
			$this->model_setting_setting->editSetting('slider_filter', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/paper_filter', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/paper_filter', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['paper_filter_status'])) {
			$data['paper_filter_status'] = $this->request->post['paper_filter_status'];
		} else {
			$data['paper_filter_status'] = $this->config->get('paper_filter_status');
		}

		if (isset($this->request->post['range_filter'])) {
			$data['range_filter'] = $this->request->post['range_filter'];
		} else {
			$data['range_filter'] = $this->config->get('range_filter');
		}

		if (isset($this->request->post['manufacturer_filter'])) {
			$data['manufacturer_filter'] = $this->request->post['manufacturer_filter'];
		} else {
			$data['manufacturer_filter'] = $this->config->get('manufacturer_filter');
		}

		if (isset($this->request->post['location_filter'])) {
			$data['location_filter'] = $this->request->post['location_filter'];
		} else {
			$data['location_filter'] = $this->config->get('location_filter');
		}

		if (isset($this->request->post['reel_filter'])) {
			$data['reel_filter'] = $this->request->post['reel_filter'];
		} else {
			$data['reel_filter'] = $this->config->get('reel_filter');
		}

		if (isset($this->request->post['grain_filter'])) {
			$data['grain_filter'] = $this->request->post['grain_filter'];
		} else {
			$data['grain_filter'] = $this->config->get('grain_filter');
		}

		if (isset($this->request->post['slider_filter'])) {
			$data['slider_filter'] = $this->request->post['slider_filter'];
		} else {
			$data['slider_filter'] = $this->config->get('slider_filter');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/paper_filter.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/paper_filter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
