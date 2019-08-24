<?php

namespace App\Controllers;

use App\Models\Usuario;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController
{

    public function  getLogin(){
        return $this->renderHTML('login.twig');
    }


    public function postLogin($request)
    {
        $responseMessage=null;
        $postData = $request->getParsedBody();
       $user = Usuario::where('email', $postData['email'])->first();
       if($user){
        if(\password_verify($postData['password'], $user->password)) {
            $_SESSION['userId'] = $user->usuario_id;
            return new RedirectResponse('/curso-php/admin');
        }else {
            $responseMessage='Bad credetianl';
        }
    }else{
        $responseMessage='Bad credetianl';
        
       }
       return $this->renderHTML('login.twig',[
         'responseMessage' => $responseMessage
       ]);
    }

    public function  getLogout(){
        unset($_SESSION['userId']);
        return new RedirectResponse('/curso-php/Login');
    }

}
