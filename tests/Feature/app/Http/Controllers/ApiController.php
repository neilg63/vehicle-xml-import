<?php

namespace Tests\Feature\app\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiController extends TestCase
{
  /**
   * Test the API COntroller workers with
   * the VehicleXML service
   *
   * @return void
   */
  public function testSourceCanBeConvertedToJson()
  {
    $data = $this->fetchJsonRequestAsObject('/api/ingest');
    $this->assertTrue(is_object($data));
  }

  /**
   * Test the raw file has a vehicle field with an array of more than one vehicle
   *
   * @return void
   */
  public function testSourceHasDataWithFields()
  {
    $data = $this->fetchJsonRequestAsObject('/api/ingest');

    $this->assertTrue(count($data->vehicles) > 1);
  }

  /**
   * Test the first veheicle has the expected flattened fields
   *
   * @return void
   */
  public function testSourceHasExpectedWithFields()
  {
    $data = $this->fetchJsonRequestAsObject('/api/ingest');
    $fields = ['name',
			'manufacturer',
			'model',
			'type',
			'usage',
			'license_plate',
			'weight_category',
			'no_seats',
			'has_boot',
			'has_trailer',
			'owner_name',
			'owner_company',
			'owner_profession',
			'transmission',
			'colour',
			'is_hgv',
			'no_doors',
			'sunroof',
			'has_gps',
			'no_wheels',
			'engine_cc',
			'fuel_type'
			];
		$objectKeys = array_keys(get_object_vars($data->vehicles[0]));
    $this->assertTrue($objectKeys == $fields);
  }

  /**
   * Test the data import
   *
   * @return void
   */
  public function testDataImport()
  {
    $data = $this->fetchJsonRequestAsObject('/api/ingest/save');
    
    $this->assertTrue(isset($data->items) && is_array($data->items) && count($data->items) > 1);
  }

  /**
   * Test the data import yields the expected number
   * of results
   * @return void
   */
  public function testDataImportTotal()
  {
    $data = $this->fetchJsonRequestAsObject('/api/ingest/save');
    
    $this->assertTrue(count($data->items) === count($data->vehicles));
  }

  /**
   * Test the api listing has many vehicles
   * @return void
   */
  public function testApiHasManyVehicles()
  {
    $data = $this->fetchJsonRequestAsObject('/api');
    
    $this->assertTrue(count($data->vehicles) > 1);
  }

  /**
   * Test validity of first vehicle JSON
   * @return void
   */
  public function testApiFirstVehicleValid()
  {
    $data = $this->fetchJsonRequestAsObject('/api');
    
    $firstVehicle = $data->vehicles[0];
    $this->assertTrue(
      is_object($firstVehicle->model)
      && is_object($firstVehicle->model->maker)
      && isset($firstVehicle->model->name)
      && isset($firstVehicle->model->maker->name)
      && is_array($firstVehicle->owners)
      && !empty($firstVehicle->owners)
      && is_object($firstVehicle->owners[0])
      && isset($firstVehicle->owners[0]->name)
      && is_object($firstVehicle->owners[0]->company)
      && isset($firstVehicle->owners[0]->company->name)
    );
  }

  private function fetchJsonRequestAsObject(string $path = "/api") {
  	$response = $this->call('GET', $path);
      $rawJSON = $response->content();
    return @json_decode($rawJSON);
  }

}
