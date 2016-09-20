<?php
class ControllerApiCart extends Controller {
	public function add() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->post['product'])) {
				$this->cart->clear();

				foreach ($this->request->post['product'] as $product) {
					if (isset($product['option'])) {
						$option = $product['option'];
					} else {
						$option = array();
					}

					$this->cart->add($product['product_id'], $product['quantity'], $option);
				}

				$json['success'] = $this->language->get('text_success');

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			} elseif (isset($this->request->post['product_id'])) {
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

				if ($product_info) {
					if (isset($this->request->post['quantity'])) {
						$quantity = $this->request->post['quantity'];
					} else {
						$quantity = 1;
					}

					if (isset($this->request->post['option'])) {
						$option = array_filter($this->request->post['option']);
					} else {
						$option = array();
					}

					$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
						}
					}

					if (!isset($json['error']['option'])) {
						$this->cart->add($this->request->post['product_id'], $quantity, $option);

						$json['success'] = $this->language->get('text_success');

						unset($this->session->data['shipping_method']);
						unset($this->session->data['shipping_methods']);
						unset($this->session->data['payment_method']);
						unset($this->session->data['payment_methods']);
					}
				} else {
					$json['error']['store'] = $this->language->get('error_store');
				}
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->cart->update($this->request->post['key'], $this->request->post['quantity']);

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Remove
			if (isset($this->request->post['key'])) {
				$this->cart->remove($this->request->post['key']);

				unset($this->session->data['vouchers'][$this->request->post['key']]);

				$json['success'] = $this->language->get('text_success');

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['reward']);
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function products() {
		$this->load->language('api/cart');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Stock
			if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
				$json['error']['stock'] = $this->language->get('error_stock');
			}

			// Products
			$json['products'] = array();

			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$json['error']['minimum'][] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}


            $get_vendor_id = $this->db->query("SELECT v.vendor AS vendor, cm.commission_type AS commission_type, cm.commission AS commission, cm.commission_name as commission_name FROM `" . DB_PREFIX . "vendor` v LEFT JOIN `" . DB_PREFIX . "vendors` vds ON (v.vendor = vds.vendor_id) LEFT JOIN `" . DB_PREFIX . "commission` cm ON (vds.commission_id = cm.commission_id) WHERE v.vproduct_id = '" . (int)$product['product_id'] . "'");
			if (isset($get_vendor_id->row['vendor'])) {
				switch ($get_vendor_id->row['commission_type']) {
					case '0':
					$commission = (float)$product['total']*((float)$get_vendor_id->row['commission']/100);
					$vendor_id = $get_vendor_id->row['vendor'];
					$vendor_total = (float)$product['total']*(1-((float)$get_vendor_id->row['commission'])/100);
					$vendor_tax = $this->tax->getTax($vendor_total, $product['tax_class_id']);
					$store_tax = $this->tax->getTax($product['total']-$vendor_total, $product['tax_class_id']);
					$title = '(' . $get_vendor_id->row['commission'] . '%' . ') ' . $this->language->get('text_commission');
					break;
							
					case '1':
					$commission = (float)$get_vendor_id->row['commission'];
					$vendor_id = $get_vendor_id->row['vendor'];
					$vendor_total = (float)($product['total']-($get_vendor_id->row['commission']));
					$vendor_tax = $this->tax->getTax($vendor_total, $product['tax_class_id']);
					$store_tax = $this->tax->getTax($product['total']-$vendor_total, $product['tax_class_id']);
					$title = '(' . $this->currency->format($get_vendor_id->row['commission'],$this->config->get('config_currency')) . ') ' . $this->language->get('text_commission');
					break;
							
					case '2': 					
					$comrate = explode(':',$get_vendor_id->row['commission']);
					$commission = ((float)$product['total']*((float)$comrate[0]/100))+(float)$comrate[1];
					$vendor_id = $get_vendor_id->row['vendor'];
					$vendor_total = (float)$product['total']-$commission;
					$vendor_tax = $this->tax->getTax($vendor_total, $product['tax_class_id']);
					$store_tax = $this->tax->getTax($product['total']-$vendor_total, $product['tax_class_id']);
					$title = '(' . $comrate[0] . '% + ' . $this->currency->format($comrate[1],$this->config->get('config_currency')) . ') ' . $this->language->get('text_commission');
					break;
							
					case '3':
					$comrate = explode(':',$get_vendor_id->row['commission']);
					$commission = ((float)$product['total']+(float)$comrate[0])*((float)$comrate[1]/100);
					$vendor_id = $get_vendor_id->row['vendor'];
					$vendor_total = (float)$product['total']-$commission;
					$vendor_tax = $this->tax->getTax($vendor_total, $product['tax_class_id']);
					$store_tax = $this->tax->getTax($product['total']-$vendor_total, $product['tax_class_id']);
					$title = '(' . $this->currency->format($comrate[0],$this->config->get('config_currency')) . ' + ' . $comrate[1] . '%' . ') ' . $this->language->get('text_commission');
					break;
						
					default:
					$commission = '0';
					$vendor_id = $get_vendor_id->row['vendor'];
					$vendor_total = (float)$product['total'];
					$vendor_tax = $this->tax->getTax($vendor_total, $product['tax_class_id']);
					$store_tax = '0';
					$title = $get_vendor_id->row['commission_name'] . '(' . $this->currency->format($get_vendor_id->row['commission'],$this->config->get('config_currency')) . ') ' . $this->language->get('text_commission');
				}
			} else {
				$commission = '0';
				$vendor_id = '0';
				$vendor_total = '0';
				$vendor_tax = '0';
				$store_tax = '0';
				$title = '';
			}
					
			if (!$this->config->get('tax_status')) {
				$vendor_tax = '0';
				$store_tax = '0';
			}
            
				$json['products'][] = array(
					'cart_id'    => $product['cart_id'],
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'quantity'   => $product['quantity'],

            'commission' 	=> $commission,
			'vendor_id'  	=> $vendor_id,
			'vendor_total' 	=> $vendor_total,
			'store_tax'	 	=> $store_tax,
			'vendor_tax' 	=> $vendor_tax,
			'title'			=> $title,
            
					'stock'      => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'shipping'   => $product['shipping'],
					'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
					'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']),
					'reward'     => $product['reward']
				);
			}

			// Voucher
			$json['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$json['vouchers'][] = array(
						'code'             => $voucher['code'],
						'description'      => $voucher['description'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'price'            => $this->currency->format($voucher['amount'], $this->session->data['currency']),			
						'amount'           => $voucher['amount']
					);
				}
			}

			// Totals
			$this->load->model('extension/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array. 
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
					
					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$json['totals'] = array();

			foreach ($totals as $total) {
				$json['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				);
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
