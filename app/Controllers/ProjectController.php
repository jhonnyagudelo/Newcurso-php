<?php

	namespace App\Controllers;
	use App\Models\Project;
	use Respect\Validation\Validator as v;

	class ProjectController extends BaseController  {

		public function indexActionProject()
		{
			$projects = Project::all();
			return $this->renderHTML('Projects/index.twig', compact('projects'));
		}



		public function getAddProjectAction(ServerRequest $request) {
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
				    $job->title_project = $postData['title_project'];
				    $job->description = $postData['description'];
				    $job->technologies = $postData['technologies'];
				    $job->months = $postData['months'];
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