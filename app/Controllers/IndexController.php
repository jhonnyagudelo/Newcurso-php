<?php

	namespace App\Controllers;

	use App\Models\{Job,Project};

class IndexController extends BaseController {
	public function indexAction() {
		$jobs = Job::all();
		$projects = Project::all();

		$limitMounths = 5;
		$name = 'Jhonny Stiven Agudelo Tenorio';

		$filterFunction = function(array $job) use ($limitMounths) {
			return $job['months'] >= $limitMounths;
		};
		
		$j= array_filter($jobs->toArray(), $filterFunction);
		// var_dump($j);

		return $this->renderHTML('index.twig',[
		'name' => $name,
		'jobs' => $jobs,
		'projects' => $projects
		]) ;
	}
}

 ?>

