<?php
class ControllerModuleCategory extends Controller {
	public function index() {

			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');
			$data['countries'] = array();
			$countries = $this->model_localisation_country->getCountries();
			foreach($countries as $country){
				$zones = $this->model_localisation_zone->getZonesByCountryId($country['country_id']);
				$data['countries'][] = [
					'id' 	=> $country['country_id'],
					'name'  => $country['name'],
					'code'  => $country['iso_code_3'],
					'zones' => $zones
				];
			}

			$state = $this->config->get('paper_filter_status');
			$data['range_filter'] = $this->config->get('range_filter');
			$data['manufacturer_filter'] = $this->config->get('manufacturer_filter');
			$data['location_filter'] = $this->config->get('location_filter');
			$data['reel_filter'] = $this->config->get('reel_filter');
			$data['grain_filter'] = $this->config->get('grain_filter');
	    	
		$this->load->language('module/category');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			$children_data = array();

			
			if ($state ? $category['category_id'] : ($category['category_id'] == $data['category_id'])) {
	    	
				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach($children as $child) {
					$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}
			}

			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);

			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
				'children'    => $children_data,
				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);
		}

		
			return $state ? $this->load->view('module/category_mod', $data) : $this->load->view('module/category', $data);
	    	
	}
}
