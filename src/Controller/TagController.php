<?php
/**
 * Created by PhpStorm.
 * User: noamcarmi
 * Date: 28/07/2018
 * Time: 00:15
 */

namespace App\Controller;


use App\Entity\Tag;

use App\Form\TagType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class TagController
 * @Route("/data/tag")
 */
class TagController extends AbstractController
{

    /*
     * ========== Tag ==========
     */

    //ok
    /**
     * @Route("/", name="Tag_homePage")
     */
    public function Tag_homepage(Request $request){
        $doc = $this->getDoctrine();
        $data = $doc->getRepository(Tag::class)->findAll();
        return $this->render("Data/tag/Tag_HomePage.html.twig", ["datas" => $data]);
    }


    //pas d'affichage
    /**
     * @Route("/create", name="Tag_Create", methods={"POST"})
     */
    public function Tag_Create(Request $request){
        $em = $this->getDoctrine()->getManager();
        $tag = new  Tag();

        $form = $this->get("form.factory")
            ->create(TagType::class, $tag,
                ['action'=> $this->generateUrl("Tag_Create")]
            );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();

            return new JsonResponse(["state"=>1, 'confirm'=>1]);

        }

        return $this->render("modal/form.html.twig", ['titre'=>'Tag','form' => $form->createView()]);
    }

    //ok
    /**
     * @Route("/{id}/delete", name="Tag_delete", methods={"POST"})
     */
    public function Tag_Delete($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $current = $em->getRepository(Tag::class)->find($id);

        if ($request->request->has('confirm') && $request->request->get('confirm') == 1){

            $em->remove($current);
            $em->flush();

            return new JsonResponse(["state"=>1, "id"=>$id]);
        }
        else{
            return  $this->render('modal/remove.html.twig', [
                'name'=> $current->getName(),
                'url' => $this->generateUrl("Tag_delete", ['id' => $id])
            ]);
        }
    }

    //pas test
    /**
     * @Route("/{id}/update", name="Tag_Update")
     */
    public function Tag_Update($id , Request $request ){
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository(Tag::class)->find($id);

        if( null === $tag){
            throw $this->createNotFoundException('Tag n\'existe pas');
        }

        #$form = $this->createForm(TagType::class, $tag);
        $form = $this->get("form.factory")
            ->create(TagType::class, $tag,
                ['action'=> $this->generateUrl("Tag_Update", ['id'=> $id])]
            );
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return $this->render("Data/tag/Tag_Update.html.twig", ['form'=>$form->createView()]);
    }

}
