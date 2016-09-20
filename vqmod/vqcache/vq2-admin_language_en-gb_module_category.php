<?php
// Heading

		  $load = new Loader;
			$load->registry('setting/setting');
			$state = $load->model('setting/setting');
			$_['heading_title']    = $state ? 'Category / Paper Side Panel' : var_dump($state);
	    

// Text
$_['text_module']      = 'Modules';
$_['text_success']     = 'Success: You have modified category module!';
$_['text_edit']        = 'Edit Category Module';

// Entry
$_['entry_status']     = 'Status';

// Error
$_['error_permission'] = 'Warning: You do not have permission to modify category module!';