<?php

namespace App\Controller\web;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request as Req;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\PackageResolver;
use Endroid\QrCode\QrCode;


/**
 * @package App\Controller\web
 * @Route("/")
 */
class indexController extends Controller
{

    public function __construct()
    {

    }

    /**
     * @Route("/", methods={"GET"}, name="web_qr_code")
     */
    public function indexAction()
    {
        $qr_code = uniqid();

        return $this->render('index.html.twig', compact('qr_code'));
    }
}