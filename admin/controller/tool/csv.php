<?php
/**
 * @total-module	Hide Admin Products and Category fields
 * @author-name 	◘ Dotbox Creative
 * @copyright		Copyright (C) 2014 ◘ Dotbox Creative www.dotboxcreative.com
 * @license			GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
class ControllerToolCsv extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tool/csv');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/csv');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate() && $this->user->hasPermission('modify', 'tool/csv')) {
			if (is_uploaded_file($this->request->files['csv_import']['tmp_name'])) {
				$content = file_get_contents($this->request->files['csv_import']['tmp_name']);
				$filename = $this->request->files['csv_import']['tmp_name'];
			} else {
				$content = false;
			}
			if ($content) {
				$this->model_tool_csv->csvImport($filename);
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('tool/csv', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->error['warning'] = $this->language->get('error_empty');	
			}	
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_export'] = $this->language->get('entry_export');
		$data['entry_import'] = $this->language->get('entry_import');
		
		$data['entry_export_info'] = $this->language->get('entry_export_info');
		$data['entry_import_info'] = $this->language->get('entry_import_info');
		$data['entry_backup_reminder'] = $this->language->get('entry_backup_reminder');
		$data['entry_erase'] = $this->language->get('entry_erase');
		$data['entry_erase_info'] = $this->language->get('entry_erase_info');

		$data['text_semi_colon'] = $this->language->get('text_semi_colon');
		$data['text_comma'] = $this->language->get('text_comma');
		
		$data['tab_csv'] = $this->language->get('tab_csv');
		$data['tab_truncate'] = $this->language->get('tab_truncate');

		$data['button_export'] = $this->language->get('button_export');
		$data['button_import'] = $this->language->get('button_import');
		$data['button_export_info'] = $this->language->get('button_export_info');
		$data['button_import_info'] = $this->language->get('button_import_info');

		$data['button_erase'] = $this->language->get('button_erase');
		$data['button_erase_info'] = $this->language->get('button_erase_info');


		$data['info_erase'] = $this->language->get('info_erase');

		$data['entry_separator'] = $this->language->get('entry_separator');
		$data['entry_separator_help'] = $this->language->get('entry_separator_help');
		$data['entry_from_record'] = $this->language->get('entry_from_record');
		$data['entry_from_record_help'] = $this->language->get('entry_from_record_help');
		$data['entry_number_of_record'] = $this->language->get('entry_number_of_record');
		$data['entry_number_of_record_help'] = $this->language->get('entry_number_of_record_help');

 		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if ($this->user->hasPermission('access', 'tool/csv_erase')) {
			$data['erasepermission'] = true;
		} else {
			$data['erasepermission'] = '';
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/csv', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['restore'] = $this->url->link('tool/csv', 'token=' . $this->session->data['token'], 'SSL');
		$data['csv'] = $this->url->link('tool/csv/csv', 'token=' . $this->session->data['token'], 'SSL');
		$data['csv_import'] = $this->url->link('tool/csv', 'token=' . $this->session->data['token'], 'SSL');
		$data['csv_export'] = $this->url->link('tool/csv/csvExport', 'token=' . $this->session->data['token'], 'SSL');
		$data['csv_erase'] = $this->url->link('tool/csv/csvErase', 'token=' . $this->session->data['token'], 'SSL');


		$this->load->model('tool/csv');

		$data['tables'] = $this->model_tool_csv->getTables();

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/csv.tpl', $data));
	}

	public function csvExport() {
		$this->language->load('tool/csv');
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate() && isset($this->request->post['csv_export'])) {
			
			$ReflectionResponse = new ReflectionClass($this->response);
			if ($ReflectionResponse->getMethod('addheader')->getNumberOfParameters() == 2) {
				
				$this->response->addheader('Pragma', 'public');
				$this->response->addheader('Expires', '0');
				$this->response->addheader('Content-Description', 'File Transfer');
				$this->response->addheader("Content-type', 'text/octect-stream");
				$this->response->addheader("Content-Disposition', 'attachment;filename=" . $this->request->post['csv_export'] . ".csv");
				$this->response->addheader('Content-Transfer-Encoding', 'binary');
				$this->response->addheader('Cache-Control', 'must-revalidate, post-check=0,pre-check=0');
			} else {
				$this->response->addheader('Pragma: public');
				$this->response->addheader('Expires: 0');
				$this->response->addheader('Content-Description: File Transfer');
				$this->response->addheader("Content-type:text/octect-stream");
				$this->response->addheader("Content-Disposition:attachment;filename=" . $this->request->post['csv_export'] . ".csv");
				$this->response->addheader('Content-Transfer-Encoding: binary');
				$this->response->addheader('Cache-Control: must-revalidate, post-check=0,pre-check=0');
			}

			$this->load->model('tool/csv');

			$this->response->setOutput($this->model_tool_csv->csvExport($this->request->post['csv_export'],$this->request->post['csv_from_record'],$this->request->post['csv_number_of_record'],$this->request->post['csv_separator']));
		} else {
			$this->session->data['error'] = $this->language->get('error_permission');

			$this->response->redirect($this->url->link('tool/csv', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function csvErase() {
		$this->language->load('tool/csv');
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate() && isset($this->request->post['csv_erase']) && $this->user->hasPermission('modify', 'tool/csv_erase')) {

			$this->load->model('tool/csv');

			$table_processed = $this->model_tool_csv->csvErase($this->request->post['csv_erase']);

			$this->session->data['success'] = sprintf($this->language->get('text_success_erase'),$table_processed);

			$this->response->redirect($this->url->link('tool/csv', 'token=' . $this->session->data['token'] . '#tab-truncate', 'SSL'));
		} else {
			$this->session->data['error'] = $this->language->get('error_permission_erase');

			$this->response->redirect($this->url->link('tool/csv', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/csv')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>