<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AnalyticsController extends Controller
{
	private $_newArray;
	private $_dbSelect;
	
    function Analytics (Request $array)
	{
		$this->_dbSelect = DB::select('SELECT comment, rating
												FROM feedback 
												WHERE rating <= 3');
												
		/*************
		
		RIGHT NOW IT ONLY SENDS ALL THE COMMENTS, FOR EVERYTHING. RECOMMENDATION FOR FUTURE, ALLOW TO SEPARETE BY STORE NAME:
		
		SELECT comments, rating FROM feedback f, store s, WHERE rating >= 3 AND s.name = ? AND s.ID = f.IDStore');
		
		-> I know there needs more something to work as it should, but it helps understand the idea.
		
		**************/
		$SelectedComments = [];								
		if($this->_dbSelect >= 1)
		{
			foreach($this->_dbSelect as $Objs)
			{
				$save=[];
				
				//Here we separete each result from DB in their diferent keys and values
				foreach($Objs as $key => $x)
				{
					$save[$key] = $x;
				}
				array_push($SelectedComments, $save);
			}
			
			
			
			$OverallAdvices = array("here will be advices coming from AI", "right now that isn't connected, but here some string examples", "Focus on customer service to enhance satisfaction", "Consider expanding the product line");
			
			$this->_newArray = array("SelectedComments" => $SelectedComments, "OverallAdvices" => $OverallAdvices);
		}
		else
		{
			$this->_newArray = "Something didn't work as intended, please try again later";
		}
		
		return $newArray = $this->_newArray;
	}
}
