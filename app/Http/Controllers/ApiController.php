<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Services\VehicleXML;

class ApiController extends Controller
{

	public function index() {

		$data = ['valid' => true, 'message' => "Welcome to our Vehicle API"];
		return \Response::json($data);
	}

	public function ingest() {

		$data = [
			'valid' => false,
			'message' => "Ingesting",
			'numVehicles' => 0,
			'vehicles' => array()
		];

		$vehicleXMLParser = new VehicleXML();

		$data['file'] = $vehicleXMLParser->getFirstFile();
		if (!empty($data['file'])) {
			$data['vehicles'] = $vehicleXMLParser->read($data['file']);	
		}
		
		$data['valid'] = !empty($data['file']) && !empty($data['vehicles']);
		$data['numVehicles'] = count($data['vehicles']);
		return \Response::json($data);
	}

}