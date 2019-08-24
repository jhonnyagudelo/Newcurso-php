<?php

	namespace App\Controllers;

	use App\Models\{Job,Project};

class IndexController extends BaseController {
	public function indexAction() {
		$jobs = Job::all();
		$projects = Project::all();

		$limitMounths = 2000;
		$name = 'Jhonny Stiven Agudelo Tenorio';

		return $this->renderHTML('index.twig',[
		'name' => $name,
		'jobs' => $jobs,
		'projects' => $projects
		]) ;
	}
}

 ?>

