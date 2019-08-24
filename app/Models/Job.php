<?php
	namespace App\Models;
	use Illuminate\Database\Eloquent\Model;


	class Job extends Model {
		protected $table = 'jobs';
		protected $primaryKey = 'job_id';

		public function getDurationAsString()	{
			$years = floor($this->months / 12);
			$extraMonths = $this->months % 12;

			    if ($years == 0) {
			        return "$extraMonths months";
			    } else {
			        return "Job duration: $years years, $extraMonths months";
				  }
		 }



	}
