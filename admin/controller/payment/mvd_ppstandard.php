<?php
class ControllerPaymentMVDPPStandard extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/mvd_ppstandard');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mvd_ppstandard', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');

		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_canceled_reversal_status'] = $this->language->get('entry_canceled_reversal_status');
		$data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$data['entry_denied_status'] = $this->language->get('entry_denied_status');
		$data['entry_expired_status'] = $this->language->get('entry_expired_status');
		$data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_processed_status'] = $this->language->get('entry_processed_status');
		$data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
		$data['entry_reversed_status'] = $this->language->get('entry_reversed_status');
		$data['entry_voided_status'] = $this->language->get('entry_voided_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_test'] = $this->language->get('help_test');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_status'] = $this->language->get('tab_status');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/mvd_ppstandard', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('payment/mvd_ppstandard', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['mvd_ppstandard_test'])) {
			$data['mvd_ppstandard_test'] = $this->request->post['mvd_ppstandard_test'];
		} else {
			$data['mvd_ppstandard_test'] = $this->config->get('mvd_ppstandard_test');
		}

		if (isset($this->request->post['mvd_ppstandard_transaction'])) {
			$data['mvd_ppstandard_transaction'] = $this->request->post['mvd_ppstandard_transaction'];
		} else {
			$data['mvd_ppstandard_transaction'] = $this->config->get('mvd_ppstandard_transaction');
		}

		if (isset($this->request->post['mvd_ppstandard_debug'])) {
			$data['mvd_ppstandard_debug'] = $this->request->post['mvd_ppstandard_debug'];
		} else {
			$data['mvd_ppstandard_debug'] = $this->config->get('mvd_ppstandard_debug');
		}

		if (isset($this->request->post['mvd_ppstandard_total'])) {
			$data['mvd_ppstandard_total'] = $this->request->post['mvd_ppstandard_total'];
		} else {
			$data['mvd_ppstandard_total'] = $this->config->get('mvd_ppstandard_total');
		}

		if (isset($this->request->post['mvd_ppstandard_canceled_reversal_status_id'])) {
			$data['mvd_ppstandard_canceled_reversal_status_id'] = $this->request->post['mvd_ppstandard_canceled_reversal_status_id'];
		} else {
			$data['mvd_ppstandard_canceled_reversal_status_id'] = $this->config->get('mvd_ppstandard_canceled_reversal_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_completed_status_id'])) {
			$data['mvd_ppstandard_completed_status_id'] = $this->request->post['mvd_ppstandard_completed_status_id'];
		} else {
			$data['mvd_ppstandard_completed_status_id'] = $this->config->get('mvd_ppstandard_completed_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_denied_status_id'])) {
			$data['mvd_ppstandard_denied_status_id'] = $this->request->post['mvd_ppstandard_denied_status_id'];
		} else {
			$data['mvd_ppstandard_denied_status_id'] = $this->config->get('mvd_ppstandard_denied_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_expired_status_id'])) {
			$data['mvd_ppstandard_expired_status_id'] = $this->request->post['mvd_ppstandard_expired_status_id'];
		} else {
			$data['mvd_ppstandard_expired_status_id'] = $this->config->get('mvd_ppstandard_expired_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_failed_status_id'])) {
			$data['mvd_ppstandard_failed_status_id'] = $this->request->post['mvd_ppstandard_failed_status_id'];
		} else {
			$data['mvd_ppstandard_failed_status_id'] = $this->config->get('mvd_ppstandard_failed_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_pending_status_id'])) {
			$data['mvd_ppstandard_pending_status_id'] = $this->request->post['mvd_ppstandard_pending_status_id'];
		} else {
			$data['mvd_ppstandard_pending_status_id'] = $this->config->get('mvd_ppstandard_pending_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_processed_status_id'])) {
			$data['mvd_ppstandard_processed_status_id'] = $this->request->post['mvd_ppstandard_processed_status_id'];
		} else {
			$data['mvd_ppstandard_processed_status_id'] = $this->config->get('mvd_ppstandard_processed_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_refunded_status_id'])) {
			$data['mvd_ppstandard_refunded_status_id'] = $this->request->post['mvd_ppstandard_refunded_status_id'];
		} else {
			$data['mvd_ppstandard_refunded_status_id'] = $this->config->get('mvd_ppstandard_refunded_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_reversed_status_id'])) {
			$data['mvd_ppstandard_reversed_status_id'] = $this->request->post['mvd_ppstandard_reversed_status_id'];
		} else {
			$data['mvd_ppstandard_reversed_status_id'] = $this->config->get('mvd_ppstandard_reversed_status_id');
		}

		if (isset($this->request->post['mvd_ppstandard_voided_status_id'])) {
			$data['mvd_ppstandard_voided_status_id'] = $this->request->post['mvd_ppstandard_voided_status_id'];
		} else {
			$data['mvd_ppstandard_voided_status_id'] = $this->config->get('mvd_ppstandard_voided_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['mvd_ppstandard_geo_zone_id'])) {
			$data['mvd_ppstandard_geo_zone_id'] = $this->request->post['mvd_ppstandard_geo_zone_id'];
		} else {
			$data['mvd_ppstandard_geo_zone_id'] = $this->config->get('mvd_ppstandard_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['mvd_ppstandard_status'])) {
			$data['mvd_ppstandard_status'] = $this->request->post['mvd_ppstandard_status'];
		} else {
			$data['mvd_ppstandard_status'] = $this->config->get('mvd_ppstandard_status');
		}

		if (isset($this->request->post['mvd_ppstandard_sort_order'])) {
			$data['mvd_ppstandard_sort_order'] = $this->request->post['mvd_ppstandard_sort_order'];
		} else {
			$data['mvd_ppstandard_sort_order'] = $this->config->get('mvd_ppstandard_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/mvd_ppstandard', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/mvd_ppstandard')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}