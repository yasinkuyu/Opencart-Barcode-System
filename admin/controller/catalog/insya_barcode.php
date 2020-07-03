<?php

/*

Opencart-Barcode-System
Opencart Barkod Sistemi

İnsya Bilişim Teknolojileri
 
@yasinkuyu

*/

class ControllerCatalogInsyaBarcode extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle("İnsya Barkod Yazdırma Sistemi");

		$this->load->model('catalog/product');
		$this->load->model('catalog/option');

		$this->getList();
	}
 
	protected function getList() {
		
		$start_number = $this->request->get['start_number'];
		 
		$data['products'] = array();
		$data['start_number'] = $start_number;
		
		if($start_number > 0 && $start_number < 30){
				
			for ($i = 1; $i <= $start_number; $i++) {
				$data['products'][] = array(
					'product_id' => 0,
					'name'       => "",
					'model'      => ""
				);
			}
		}
  
		foreach ($this->request->get['selected'] as $product_id) {
			 
			$result = $this->model_catalog_product->getProduct($product_id);

			$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);
			
			$product = array(
						'barcode_id' => $result['product_id'],
						'product_id' => $result['product_id'],
						'name'       => $result['name'],
						'model'      => $result['model'],
						'size'	 	 => ''
					);

			if($product_options) {
					
				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product['barcode_id'] = $result['product_id'].$option_value_info['name'].$product_option['option_id'];
								$product['size'] = $option_value_info['name'];
								$data['products'][] = $product;
								
							}
						}

					}
				}
			}else{
				$data['products'][] = $product;
			}
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['user_token'] = $this->session->data['user_token'];
  
		$this->response->setOutput($this->load->view('catalog/insya_barcode', $data));
	}
  
}
