<?php

	namespace App\Controllers;
	use App\Model\Project;
	use Respect\Validation\Validator as v;

	class ProjectController extends BaseController  {
		public function getAddProjectAction($request) {
				$responseMessenge = null;
				if ($request->getMethod() == 'POST') {
					$postData = $request->getParsedBody();
					$ProjectValidator = v::key('title_project', v::stringType()->notEmpty())
	                  ->key('description', v::stringType()->notEmpty())
	                  ->key('technologies', v::stringType()->notEmpty())
	                  ->key('months', v::numeric()->notEmpty());

				try {
					$ProjectValidator->assert($postData);
				    $postData = $request->getParsedBody();
				    $job = new Project();
				    $job->title_project = $_POST['title_project'];
				    $job->description = $_POST['description'];
				    $job->technologies = $_POST['technologies'];
				    $job->months = $_POST['months'];
				    $job->save();
					$responseMessenge = 'Saved';
				} catch (\Exception $e) {
						$responseMessenge = ($e->getMessage());
					}
			}

				return $this->renderHTML('addProject.twig',[
				'responseMessenge' => $responseMessenge

			]);
		}
	}


 ?>