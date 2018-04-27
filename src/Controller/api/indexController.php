<?php

namespace App\Controller\api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request as Req;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\PackageResolver;

/**
 * @package App\Controller\api
 * @Route("/api")
 */
class indexController extends Controller
{

    public function __construct()
    {

    }

    public function indexAction()
    {

    }

    /**
     * @Route("/login", methods={"POST"}, name="api_login")
     * @param Req $email
     * @param Req $password
     */
    public function loginAction(Req $request)
    {

        $email          = $request->get('email');
        $password       = $request->get('password');
        $em             = $this->getDoctrine()->getManager();
        $repoUser       = $em->getRepository(User::class);
        $rtn            = $repoUser->findOneBy(array('Email' => $email, 'Password' => $password));

        if ($rtn != null){
            if ($rtn->getToken() == null){
                $token = uniqid();
                $rtn->setToken($token);
                $em->persist($rtn);
                $em->flush();
            }else{
                $token = $rtn->getToken();
            }
            $data = array(
                'connection' => true,
                'token' => $token
            );
        }else{
            $data = array(
                'connection' => false,
                'token' => null
            );
        }

        return $this->json($data);
    }

}