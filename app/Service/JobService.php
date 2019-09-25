<?php

namespace App\Service;
use App\Models\Job;

class JobService {
    public function deleteJobs($id)
    {
		$job = Job::find($id);
		$job->delete();
    }
}