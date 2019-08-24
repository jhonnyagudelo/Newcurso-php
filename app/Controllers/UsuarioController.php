<?php

namespace App\Controllers;

use App\Models\Usuario;
use Respect\Validation\Validator as v;
use Illuminate\Support\Facedes\Resquest;

class UsuarioController extends BaseController
{

    public function  getAddUser(){
        return $this->renderHTML('sesion.twig');
    }


    public function postSaveUser($request)
    {
        $responseMessage = null;

        if ($request->getMethod() == 'POST') {
            # code...
            $postData = $request->getParsedBody();
            $UserValidation = v::key('email', v::stringType()->notEmpty())
                ->key('password', v::stringType()->notEmpty());
            try {
     
                    $UserValidation->assert($postData);
                    $postData = $request->getParsedBody();
                    $user = new Usuario();
                    $user->email = $postData['email'];
                    $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
                    $user->save();
                    $responseMessage = 'Saved';
                    
                
            } catch (\Exception $e) {
                $responseMessage = ($e->getMessage());
            }
        }
        return $this->renderHTML('sesion.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}
