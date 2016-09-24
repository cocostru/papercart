<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


			$this->load->language('product/category');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');

			$data['column_image'] = $this->language->get('column_image');
			$data['column_name'] = $this->language->get('column_name');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_qty_buy'] = $this->language->get('column_qty_buy');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_location'] = $this->language->get('column_location');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['cart'] = $this->load->controller('common/cart');

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

            $data['weights'] = $this->weight->getWeights();
			$data['bunit_sess'] = key_exists('bunit', $this->session->data) ? $this->session->data['bunit'] : '';

			$tresholds = array();

			$filter_data = array(
				'filter_category_id' => '',
				'filter_filter'      => '',
				'sort'               => '',
				'order'              => '',
				'limit'              => '',
				'start'              => 0
			);

			foreach($this->model_catalog_product->getProducts($filter_data) as $result){

				$product_info = $this->model_catalog_product->getProduct($result['product_id']);
				$product_cats = $this->model_catalog_product->getCategories($result['product_id']);

				foreach ($product_cats as $product_cat) {
					$category_info = $this->model_catalog_category->getCategory($product_cat['category_id']);
					$thresholds[$product_info['model']][] = $category_info['threshold'];
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

				if ($product_info['quantity'] <= 0) {
					$data['stock'] = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$data['stock'] = max($thresholds[$product_info['model']]) != 0 && max($thresholds[$product_info['model']]) < $product_info['quantity'] ? max($thresholds[$product_info['model']]) . ' +' : $product_info['quantity'];
				} else {
					$data['stock'] = $this->language->get('text_instock');
				}
				$base_unit = array();
				$alt_unit = array();
				$alt_qty = array();
				$alt_loc = array();
				foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
					if ($option['option_alt'] == 1) $base_unit = $option;
					if ($option['option_alt'] == 2) $alt_unit = $option;
					if ($option['option_alt'] == 3) $alt_qty = $option;
					if ($option['option_alt'] == 4) $alt_loc = $option;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')),
					'stock'       => $data['stock'],
					'location' 	  => $product_info['location'],
					'manufacturer'=> $manufacturer,
					'length' 	  => $this->length->convert($product_info['length'], $product_info['length_class_id'], 1),
					'width' 	  => $this->length->convert($product_info['width'], $product_info['length_class_id'], 1),
					'gsm' 	  	  => $product_info['height'],
					'weight' 	  => $product_info['weight'],
					'weight_unit' => $this->weight->getUnit($product_info['weight_class_id']),
					'base_unit'	  => $base_unit,
					'alt_unit'	  => $alt_unit,
					'alt_qty'	  => $alt_qty,
					'alt_loc'	  => $alt_loc,
					'name'        => $result['name'],
					'model'       => $result['model'],
					'price'       => $price,
					'special'     => $special,
					'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'])
				);

			}
	    	
		
			$state = $this->config->get('paper_filter_status');
			$state ? $this->response->setOutput($this->load->view('common/home_mod', $data)) : $this->response->setOutput($this->load->view('common/home', $data));
	    	
	}
}