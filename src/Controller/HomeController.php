<?php
/**
 * Created by PhpStorm.
 * User: noamcarmi
 * Date: 28/07/2018
 * Time: 00:06
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="HomePage")
     */
    public function HomePage(){

        return new Response("Futur Awesome page");
    }

}