<?php
/**
 * Created by PhpStorm.
 * User: noamcarmi
 * Date: 09/09/2018
 * Time: 01:29
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TagController
 * @Route("/data/test")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/, name="retard_graph")
     */
    public function GraphAction(Request $request){
      $json_request = $this->get("https://api.sncf.com/v1/coverage/sncf/commercial_modes");

      $this->render("test.html.twig");
    }

}