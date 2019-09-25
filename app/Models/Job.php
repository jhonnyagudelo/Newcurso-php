<?php
	namespace App\Models;

use App\Traits\HasDEfaultImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model {

		use HasDEfaultImage;
		// use SoftDeletes; borrado suave, no muestra en el view pero si en la bd
		
		protected $table = 'jobs';
		protected $primaryKey = 'job_id';

		public function getDurationAsString()	{
			$years = floor($this->months / 12);
			$extraMonths = $this->months % 12;

			    if ($years == 0) {
			        return "Job duration: $extraMonths months";
			    } else {
			        return "Job duration: $years years, $extraMonths months";
				  }
		 }



	}
