<?php

namespace App\Controller\web;

use App\Entity\Event;
use App\Entity\Location;
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
     * @Route("/", methods={"GET"}, name="display_qr_code")
     */
    public function indexAction()
    {
        $em             = $this->getDoctrine()->getManager();
        $repoLocation   = $em->getRepository(Location::class);
        $locations      = $repoLocation->findAll();

        return $this->render('index.html.twig', compact("locations"));
    }

    /**
     * @Route("/getQRcode", methods={"POST"}, name="generate_qr_code")
     */
    public function getQRcodeAction(Req $request)
    {
        $id             = $request->get('id');
        $em             = $this->getDoctrine()->getManager();
        $repoLocation   = $em->getRepository(Location::class);
        $location       = $repoLocation->findById($id);
        $qrcode         = $location[0]->getQrCode();
        $rtn            = $this->render('block/qrcode-block.html.twig', compact('qrcode'));

        return $rtn;
    }
}