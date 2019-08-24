<?php
	namespace App\Models;
	use Illuminate\Database\Eloquent\Model;


	class Project extends Model {

		protected $table = 'project';
		protected $primaryKey = 'project_id';
		const CREATED_AT = 'create_at';
    	const UPDATED_AT = 'update_at';

	public function getDurationAsString()	{
		$years = floor($this->months / 12);
		$extraMonths = $this->months % 12;

	    if ($years == 0) {
	        return   "Project duration: $extraMonths months";
	    } else {
	        return "Project duration:  $years years, $extraMonths months";
	    }
	}


	}
