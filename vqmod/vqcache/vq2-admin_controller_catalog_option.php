<?php
class ControllerCatalogOption extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/option');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/option');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_option->addOption($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/option', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/option');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_option->editOption($this->request->get['option_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/option', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/option');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $option_id) {
				$this->model_catalog_option->deleteOption($option_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/option', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'od.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/option', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/option/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/option/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['options'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$option_total = $this->model_catalog_option->getTotalOptions();

		$results = $this->model_catalog_option->getOptions($filter_data);


			$exists_alt = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "option` LIKE 'option_alt'");
			// $exists_add = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "option_value_description` LIKE 'option_add'");

			if (!$exists_alt->rows) $this->db->query("ALTER TABLE `" . DB_PREFIX . "option` ADD `option_alt` TINYINT(1) NOT NULL default '0'");
			// if (!$exists_add->rows) $this->db->query("ALTER TABLE `" . DB_PREFIX . "option_value_description` ADD `option_add` VARCHAR(128) NOT NULL");

			$data['count_alt'] = 0;
            
		foreach ($results as $result) {

			if (key_exists('option_alt', $result)) {
				if ($result['option_alt'] > 0) $data['count_alt']++;
			}
            
			$data['options'][] = array(

			'option_alt' => key_exists('option_alt', $result) ? $result['option_alt'] : '',
            
				'option_id'  => $result['option_id'],
				'name'       => $result['name'],
				'sort_order' => $result['sort_order'],
				'edit'       => $this->url->link('catalog/option/edit', 'token=' . $this->session->data['token'] . '&option_id=' . $result['option_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'] . '&sort=od.name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'] . '&sort=o.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $option_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/option', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($option_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($option_total - $this->config->get('config_limit_admin'))) ? $option_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $option_total, ceil($option_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/option_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['option_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_choose'] = $this->language->get('text_choose');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_radio'] = $this->language->get('text_radio');
		$data['text_checkbox'] = $this->language->get('text_checkbox');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_input'] = $this->language->get('text_input');
		$data['text_text'] = $this->language->get('text_text');
		$data['text_textarea'] = $this->language->get('text_textarea');
		$data['text_file'] = $this->language->get('text_file');
		$data['text_date'] = $this->language->get('text_date');
		$data['text_datetime'] = $this->language->get('text_datetime');
		$data['text_time'] = $this->language->get('text_time');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_option_value'] = $this->language->get('entry_option_value');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_option_value_add'] = $this->language->get('button_option_value_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['option_value'])) {
			$data['error_option_value'] = $this->error['option_value'];
		} else {
			$data['error_option_value'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/option', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['option_id'])) {
			$data['action'] = $this->url->link('catalog/option/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/option/edit', 'token=' . $this->session->data['token'] . '&option_id=' . $this->request->get['option_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['option_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$option_info = $this->model_catalog_option->getOption($this->request->get['option_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['option_description'])) {
			$data['option_description'] = $this->request->post['option_description'];
		} elseif (isset($this->request->get['option_id'])) {
			$data['option_description'] = $this->model_catalog_option->getOptionDescriptions($this->request->get['option_id']);
		} else {
			$data['option_description'] = array();
		}


			if (isset($this->request->post['option_alt'])) {
				$data['option_alt'] = $this->request->post['option_alt'];
			} elseif (!empty($option_info)) {
				$data['option_alt'] = key_exists('option_alt', $option_info) ? $option_info['option_alt'] : '';
			} else {
				$data['option_alt'] = '';
			}

			$this->load->model('catalog/product');
            
		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} elseif (!empty($option_info)) {
			$data['type'] = $option_info['type'];
		} else {
			$data['type'] = '';
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($option_info)) {
			$data['sort_order'] = $option_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->post['option_value'])) {
			$option_values = $this->request->post['option_value'];
		} elseif (isset($this->request->get['option_id'])) {
			$option_values = $this->model_catalog_option->getOptionValueDescriptions($this->request->get['option_id']);
		} else {
			$option_values = array();
		}

		$this->load->model('tool/image');

		$data['option_values'] = array();

		foreach ($option_values as $option_value) {
			if (is_file(DIR_IMAGE . $option_value['image'])) {
				$image = $option_value['image'];
				$thumb = $option_value['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['option_values'][] = array(

			'count_assigned' => $this->model_catalog_product->getTotalProductsByOptionValueId($option_value['option_value_id']),
            
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value['option_value_description'],
				
			'image' => $option_value['image'],
            
				'thumb'                    => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order'               => $option_value['sort_order']
			);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


			$data['units'] = array();
			$data['zones'] = array();
			$data['new_base_units'] = array();
			$data['new_units'] = array();
			$data['new_zones'] = array();

			$old_base_units = array();
			$old_units = array();
			$old_zones = array();

			$this->load->model('localisation/weight_class');
			$this->load->model('localisation/zone');

			$units = $this->model_localisation_weight_class->getWeightClasses();
			$zones = $this->model_localisation_zone->getZones();

			foreach ($units as $unit) {
				$data['units'][] = array(
					'unit'            => $unit['unit'],
					'value'           => $unit['value'],
					'unit_type' 	  => key_exists('unit_type', $unit) ? $unit['unit_type'] : '',
				);
			}

			foreach ($zones as $zone) {
				if ($zone['country_id'] > 257)
				$data['zones'][] = array(
					'country' => $zone['country'],
					'name'    => $zone['name'],
					'code'    => $zone['code'],
				);
			}

			foreach ($option_values as $option_value) {
				if ($data['option_alt'] && $data['option_alt'] == 1)
					$old_base_units[] = $option_value['option_value_description'][1]['name'];
				if ($data['option_alt'] && $data['option_alt'] == 2)
					$old_units[] = $option_value['option_value_description'][1]['name'];
				if ($data['option_alt'] && $data['option_alt'] == 4)
					$old_zones[] = explode(' (', $option_value['option_value_description'][1]['name'], 2)[0];

			}

			foreach ($data['units'] as $cunit) {
				if (!in_array($cunit['unit'], $old_base_units) && $cunit['unit_type'] < 1)
					$data['new_base_units'][] = array(
						'unit'    	=> $cunit['unit'],
						'value'    	=> $cunit['value'],
						'unit_type' => $cunit['unit_type'],
					);
				if (!in_array($cunit['unit'], $old_units) && $cunit['unit_type'] > 0)
					$data['new_units'][] = array(
						'unit'    	=> $cunit['unit'],
						'value'    	=> $cunit['value'],
						'unit_type' => $cunit['unit_type'],
					);
			}

			foreach ($data['zones'] as $czone) {
				if (!in_array($czone['code'], $old_zones))
					$data['new_zones'][] = array(
						'country' => $czone['country'],
						'name'    => $czone['name'],
						'code'    => $czone['code'],
					);
			}
            
		$this->response->setOutput($this->load->view('catalog/option_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/option')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['option_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 128)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if (($this->request->post['type'] == 'select' || $this->request->post['type'] == 'radio' || $this->request->post['type'] == 'checkbox') && !isset($this->request->post['option_value'])) {
			$this->error['warning'] = $this->language->get('error_type');
		}

		if (isset($this->request->post['option_value'])) {
			foreach ($this->request->post['option_value'] as $option_value_id => $option_value) {
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					if ((utf8_strlen($option_value_description['name']) < 1) || (utf8_strlen($option_value_description['name']) > 128)) {
						$this->error['option_value'][$option_value_id][$language_id] = $this->language->get('error_option_value');
					}
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/option')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $option_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByOptionId($option_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->language('catalog/option');

			$this->load->model('catalog/option');

			$this->load->model('tool/image');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$options = $this->model_catalog_option->getOptions($filter_data);

			foreach ($options as $option) {
				$option_value_data = array();

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_values = $this->model_catalog_option->getOptionValues($option['option_id']);

					foreach ($option_values as $option_value) {
						if (is_file(DIR_IMAGE . $option_value['image'])) {
							$image = $this->model_tool_image->resize($option_value['image'], 50, 50);
						} else {
							$image = $this->model_tool_image->resize('no_image.png', 50, 50);
						}

						$option_value_data[] = array(
							'option_value_id' => $option_value['option_value_id'],
							'name'            => strip_tags(html_entity_decode($option_value['name'], ENT_QUOTES, 'UTF-8')),
							'image'           => $image
						);
					}

					$sort_order = array();

					foreach ($option_value_data as $key => $value) {
						$sort_order[$key] = $value['name'];
					}

					array_multisort($sort_order, SORT_ASC, $option_value_data);
				}

				$type = '';

				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$type = $this->language->get('text_choose');
				}

				if ($option['type'] == 'text' || $option['type'] == 'textarea') {
					$type = $this->language->get('text_input');
				}

				if ($option['type'] == 'file') {
					$type = $this->language->get('text_file');
				}

				if ($option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$type = $this->language->get('text_date');
				}

				$json[] = array(
					'option_id'    => $option['option_id'],
					'name'         => strip_tags(html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8')),
					'category'     => $type,
					'type'         => $option['type'],
					'option_value' => $option_value_data
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}