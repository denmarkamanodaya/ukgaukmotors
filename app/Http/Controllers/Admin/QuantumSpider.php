<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class QuantumSpider extends Controller
{

	private $statement;
	private $dealers_obj, $vehicle_obj, $full_data;

	public function showParsedData()
	{
		#$statement = "SELECT d.name, d.slug, count(v.id) AS count FROM dealers AS d INNER JOIN vehicle AS v ON d.id = v.dealer_id GROUP BY d.id ORDER BY d.slug ASC";
		$this->statement = "SELECT id, name, slug FROM dealers ORDER BY slug ASC";
		$this->dealers_obj = \DB::select(\DB::raw($this->statement));	

		foreach($this->dealers_obj AS $key => $value)
		{
			$this->vehicle_obj = \DB::select(\DB::raw("SELECT count(id) AS total FROM vehicle WHERE dealer_id = $value->id"));

			$this->full_data[] = (object) array(
				'id'	=> $value->id,
				'name' 	=> $value->name,
				'slug' 	=> $value->slug,
				'total' => $this->vehicle_obj[0]->total
			);
		}

		$parsed_data = $this->full_data;
	
		return view('admin.QuantumSpider.show', compact('parsed_data'));
	}
}
