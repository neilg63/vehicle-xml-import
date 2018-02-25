# Simple Vehicle Import API

This is a simple proof of concept application written in Laravel. It ingests custom XML with vehicle data and provides a JSON API to expose the data to frontend frameworks such as Vue, Angular or React.

## XML Import
I created Service class to read the source XML from a custom directory datasources outside the Web root. In theory it looks for all XML files in this directory, but I created a separate routine that matches the first. The VehicleXML parsers then flattens the XML into a simpler representations converting attributes in the parent Vehicle element to properties of the main object.

## Data Import
The static VehicleRecord class handles the import of custom XML into a more relational database schema that better represents the true relationships between vehicles, models, makers and owners.

## Data Schema Notes

In the real world we'd probably have Manufacturers, Models and versions, as the same model may have many variations e.g. 3/5 doors, fuel type, engine cc etc. Such data does not necessarily pertain to the individual vehicle, but we have to allow for modifications (e.g. a new engine with a different capacity). In my simplified schema most attributes are assigned to the vehicle and only core attributes such as is_hgv or weight_category are assigned to the model. Manufacturer or maker data is referenced via the model, though I suspect some model were assigned to the wrong manufacturer in the source XML.

In practice a vehicle may have had many owners. I thus associated owners with vehicles via the *vehicles_owners* table. Note the owners table does not have separate first and last name fields as that wouuld involve string parsing and more testing as well as a title field (e.g. Dr) and not within the scope of this exercise. The source file only had flattened data with the current owner. This may be one abtsraction too far, but in real world systems we have to allow some flexibility.

I tried to stick to *Eloquent* conventions as far as possible. Had to rename the Model class to Vmodel (for obvious reasons due to name conflicts). Most of the custom code is involved either in matching models, makers, owners and vehicle by unique identifier and in preparing the data structure for the API controller listing. Hence there is a fair chunk of code in the core Vehicle model that may be abstracted to a Service component.

## Routes

* / => Rudimentary frontend listing
* /api => JSON listing of all vehicles (yes I know in the world this would be paginated with filter options)
* /api/ingest => Read the Source XML and output data as a flattened JSON dataset
* /api/ingest/save => Import the XML to our relational database and output results as JSON (same as above, but with a new items array)

## VueJS Frontend

In my last couple of Vue projects I've used Webpack with nested components, each having its own subtemplate. However, I wanted to quickly integrate it into this project as a proof of concept only. I this loaded VueJS 2.0 and Axios from a CDN and combined logic which naturally belongs to components in the main Vue controller. I built two separate lists of makers with related models and of vehicles. The sidebar menu lets you filter by manufacturer or model. 

## Installation Notes

I built the project using Composer + artisan commands. I expect there is a good deal of default stuff there. Other than PHP 7.1, the only other dependencies are SimpleXML and MySQL.
As always I created the database schema first. I used Laravels *artisan migrate* scripts and then updated them with all fields. Just in case I added database schema export file to the *datasource* directory.

# Feature Test Notes

To run: 
```
phpunit tests/Feature/app/Http/Controllers/ApiController.php
```
This test empties all DB tables and recreates the database from scratch. In a commercial project I'd naturally set up a test environment with a separate database.
