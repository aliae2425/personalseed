<?php
/**
 * Created by PhpStorm.
 * User: noamcarmi
 * Date: 28/08/2018
 * Time: 17:23
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{


    /**
     * @Route("/", name="Data_HomePage")
     */
    public function Data_HomePage()
    {
        return $this->render("AdminLayout.html.twig");
    }

}