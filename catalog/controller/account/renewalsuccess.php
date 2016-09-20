<?php 
class ControllerAccountRenewalSuccess extends Controller {  
	public function index() {
    	$this->language->load('account/renewal');
  
    	$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	);     	

    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_message'] = sprintf($this->language->get('text_message'), $this->config->get('config_name'), $this->url->link('common/home'));
				
    	$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = $this->url->link('common/home');

		$this->response->setOutput($this->load->view('template/common/success', $data));
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());				
  	}
}
?>