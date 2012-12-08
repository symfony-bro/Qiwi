<?php
namespace IShop\Soap;
use IShop\ServerMethods as S;

class Server extends \SoapServer {

	protected $classmapMethods = array(
		'tns:updateBill'         => 'UpdateBill',
		'tns:updateBillResponse' => 'UpdateBillResponse',
	);
	protected $classmap;

	protected function createDefaultClassmap() {
		$namespace = 'IShop\ServerMethods';
		$this->classmap = array();
		foreach ($this->classmapMethods as $method => $binding) {
			$this->classmap[$method] = $namespace . '\\' . $binding;
		}
	}

	protected $wsdl;

	public function __construct($wsdl) {
		$this->createDefaultClassmap();
		$this->wsdl = $wsdl;
	}

	protected $callback;

	public function processRequest(callable $callback) {
		$server = new \SoapServer(
			$this->wsdl, array('classmap' => $this->classmap)
		);
		$server->setObject($this);
		$this->callback = $callback;
		$server->handle();
		$this->callback = null;
	}

	public function updateBill($param) {
		$callback = $this->callback;
		$result = $callback($param);

		$ret = new S\UpdateBill();
		$ret->updateBillResult = $result;
		return $ret;
	}

}
