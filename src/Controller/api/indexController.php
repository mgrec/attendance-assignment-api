<?php

namespace App\Controller\api;

use App\Entity\Event;
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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
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
                $token      = uniqid();
                $rtn->setToken($token);
                $em->persist($rtn);
                $em->flush();
            }else{
                $token      = $rtn->getToken();
            }
            $data = array(
                'token' => $token
            );
        }else{
            $data = array(
                'token' => null
            );
        }
        return $this->json($data);
    }

    /**
     * @param Req $request
     * @Route("/refreshToken", methods={"POST"}, name="api_refresh_token")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function refreshTokenAction(Req $request)
    {
        $token          = $request->get('token');
        $isAuth         = $this->checkAuth($token);
        $em             = $this->getDoctrine()->getManager();
        $repoUser       = $em->getRepository(User::class);
        $rtn            = $repoUser->findOneBy(array('Token' => $token));

        if ($isAuth == true) {
            $token      = uniqid();
            $rtn->setToken($token);
            $em->persist($rtn);
            $em->flush();
            $data       = array('token' => $token);
        }

        return $this->json($data);
    }

    /**
     * @param Req $request
     * @Route("/getLocation", methods={"GET"}, name="api_get_location")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getLocationAction(Req $request)
    {
        $token          = $request->get('token');
        $isAuth         = $this->checkAuth($token);
        $em             = $this->getDoctrine()->getManager();
        $repoEvent      = $em->getRepository(Event::class);

        if ($isAuth == true) {
            $date = date('Y-m-d H:i:s');
            $event = $repoEvent->findNextEvent($date);
            $data = array(
                'name' => $event[0]->getName(),
                'date' => $event[0]->getDate(),
                'location' => $event[0]->getLocation()->getDescription()
            );
        }

        return $this->json($data);
    }

    /**
     * @param $token
     * @return bool|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkAuth($token)
    {
        $em             = $this->getDoctrine()->getManager();
        $repoUser       = $em->getRepository(User::class);
        $rtn            = $repoUser->findOneBy(array('Token' => $token));

        if ($token == null){
            $data       = array('error' => 'Token is missing');
            echo $this->json($data);
            die();
        }elseif($rtn == null){
            $data       = array('error' => 'Token not match');
            echo $this->json($data);
            die();
        }
        return true;
    }
}