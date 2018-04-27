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
     * @Route("/refreshToken", methods={"POST"}, name="api_login")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function refreshTokenAction(Req $request)
    {
        $token = $request->get('token');
        $em             = $this->getDoctrine()->getManager();
        $repoUser       = $em->getRepository(User::class);
        $rtn            = $repoUser->findOneBy(array('Token' => $token));

        if ($token == null){
            $data       = array('error' => 'Token is missing');
        }elseif ($rtn == null){
            $data       = array('error' => 'Token not match');
        }else{
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
     * @Route("/getLocation", methods={"GET"}, name="api_login")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getLocationAction(Req $request)
    {
        $token = $request->get('token');
        $em             = $this->getDoctrine()->getManager();
        $repoUser       = $em->getRepository(User::class);
        $repoEvent      = $em->getRepository(Event::class);
        $rtn            = $repoUser->findOneBy(array('Token' => $token));

        if ($token == null){
            $data       = array('error' => 'Token is missing');
        }elseif ($rtn == null){
            $data       = array('error' => 'Token not match');
        }else{
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
}