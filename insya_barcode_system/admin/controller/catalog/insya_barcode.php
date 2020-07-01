<?php
class ControllerCatalogInsyaBarcode extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle("İnsya Barkod Yazdırma Sistemi");

		$this->load->model('catalog/product');

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

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model']
			);
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['user_token'] = $this->session->data['user_token'];
  
		$this->response->setOutput($this->load->view('catalog/insya_barcode', $data));
	}
  
}
