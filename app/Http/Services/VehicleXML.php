<?php

namespace App\Http\Services;


class VehicleXML {

	protected $datasourcePath;

	protected $files = array();

	protected $vehicles = array();

	public function __construct() {
		$this->datasourcePath = realpath( app_path(). DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'datasource' ) ;
		$this->readFiles();

	}

	public function getFiles() {
		return $this->files;
	}

	public function getVehicles() {
		return $this->vehicles;
	}

	public function getFirstFile() {
		if (!empty($this->files)) {
			return $this->files[0];
		}
		return "";
	}

	public function readFiles() {
		 $iterator = new \DirectoryIterator($this->datasourcePath);

		 foreach ($iterator as $file) {
		 		if ($file->isFile()) {
		 			$name = $file->getFileName();
		 			$parts = explode('.', $name);
		 			if (count($parts) > 1 && strlen($parts[0]) > 2) {
		 				$extension = array_pop($parts);
		 				$extension = strtolower($extension);
			 			if ($extension == 'xml') {
			 				$this->files[] = $name;
			 			}	
		 			}
		 			
		 		}
		 }
		 return $this->files;
	}

	public function read(string $fileName = "") {
		$filePath = $this->datasourcePath . DIRECTORY_SEPARATOR . $fileName;
		if (strlen($fileName) > 5 && file_exists($filePath)) {
 			$xmlString = file_get_contents($filePath);
 			$xml = simplexml_load_string($xmlString);
 			$vehicles = array();
 			foreach ($xml as $tagName => $vehicle) {
 				if (strtolower($tagName) == 'vehicle') {
 					if ($vehicle instanceof \SimpleXMLElement) {
 						$this->parseVehicle($vehicle);
 					}
 				}
 			}
 		}
 		return $this->vehicles;
	}

	protected function parseVehicle(\SimpleXMLElement $vehicle) {
		$item = array();
		$item['name'] = (string) $vehicle;
		foreach ($vehicle->attributes() as $key => $attr) {
			$item[$key] = (string) $attr;
		}

		foreach ($vehicle as $tag => $child) {
			$item[$tag] = (string) $child;
		}
		$this->vehicles[] = $item;
	}

}