<?php

namespace App\Controllers;

use App\Models\Job;
use App\Service\JobService;
use Respect\Validation\Validator as v;
use Illuminate\Support\Facades\Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class JobsController extends BaseController
{

	private	$jobService;

	public function __construct(JobService $jobService)
	{
			parent::__construct();
			$this->jobService = $jobService;
	}

	public function indexAction(){
		$jobs= Job::all();
		// $jobs= Job::withTrashed()->get();
		return $this->renderHTML('Jobs/index.twig', compact('jobs')); 
	}


	public function deleteAction(ServerRequest $request){
		// var_dump($request);
		// die;
		$params = $request->getQueryParams();
		$this->jobService->deleteJobs($params['id']);
		// $job = Job::find($params['id']);
		// $job->delete();
		return new RedirectResponse('/curso-php/jobs');
	}

	public function getAddJobAction(ServerRequest $request)
	{
		// var_dump($request->getMethod());
		// var_dump((string)$request->getBody());
		// var_dump($request->getParsedBody());

		$responseMessage = null;
		$ruta = null;

		if ($request->getMethod() == 'POST') {
			$postData = $request->getParsedBody();
			$JobValidator = v::key('title', v::stringType()->notEmpty())
				->key('description', v::stringType()->notEmpty())
				->key('months', v::numeric()->notEmpty());
			//   ->key('images', v::image()->validate('$fileName'));

			try {
				$JobValidator->assert($postData);
				$postData = $request->getParsedBody();

				$files = $request->getUploadedFiles();
				$images = $files['images'];

				if ($images->getError() == UPLOAD_ERR_OK) {
					$fileName = $images->getClientFilename();
					$images->moveTo("uploads/$fileName");
					$ruta = "public/uploads/$fileName";
				}
				$job = new Job();
				$job->titulo = $postData['title'];
				$job->description = $postData['description'];
				$job->months = $postData['months'];
				$job->images = $ruta;
				$job->save();
				$responseMessage = 'Saved';
			} catch (\Exception $e) {
				$responseMessage = ($e->getMessage());
			}
		}
		return $this->renderHTML('addJob.twig', [
			'responseMessage' => $responseMessage

		]);
	}
}
