<?php

namespace App\Controllers;

use App\Models\Job;
use Respect\Validation\Validator as v;
use Illuminate\Support\Facades\Request;

class JobsController extends BaseController
{
	public function getAddJobAction($request)
	{
		// var_dump($request->getMethod());
		// var_dump((string)$request->getBody());
		// var_dump($request->getParsedBody());

		$responseMessage = null;

		if ($request->getMethod() == 'POST') {
			$postData = $request->getParsedBody();
			$JobValidator = v::key('title', v::stringType()->notEmpty())
				->key('description', v::stringType()->notEmpty())
				->key('months', v::numeric()->notEmpty());
			//   ->key('images', v::image()->validate('$fileName'));

			try {
				var_dump($JobValidator->assert($postData));
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
