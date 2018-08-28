<?php
/**
 * Created by PhpStorm.
 * User: noamcarmi
 * Date: 28/07/2018
 * Time: 00:15
 */

namespace App\Controller;


use App\Entity\Photo;
use App\Entity\Project;
use App\Entity\Tag;

use App\Form\PhotoType;
use App\Form\ProjectType;

use App\Form\TagType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DdController extends AbstractController
{

    /*
     * ========== recap ==========
     */

    /**
     * @Route("/data", name="Data_HomePage")
     */
    public function Data_HomePage()
    {
        return $this->render("AdminLayout.html.twig");
    }

    /*
     * ========== Tag ==========
     */

    /**
     * @Route("/data/tag", name="Tag_homePage")
     */
    public function Tag_homepage(Request $request){
        $doc = $this->getDoctrine();

        $data = $doc->getRepository(Tag::class)->findAll();
        $tag = new  Tag();

        $form = $this->createForm( TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doc->getManager();
            $em->persist($tag);
            $em->flush();

            return $this->redirect($this->generateUrl('Tag_homePage'));
        }


        return $this->render("Data/tag/Tag_HomePage.html.twig", ["datas" => $data, 'modal' => 0, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/data/tag/{id}/delete", name="Tag_delete", methods={"POST"})
     */
    public function Tag_Delete($id){
        $doc = $this->getDoctrine();
        $product = $doc->getRepository(Tag::class)->find($id);

        $em = $doc->getManager();
        $em->remove($product);
        $em->flush();

        $data= $doc->getRepository(Tag::class)->findAll();

        return $this->render("Data/tag/Tag_liste.html.twig",["datas"=>$data]);
    }

    /**
     * @Route("/data/tag/{id}/update", name="Tag_Update")
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



    /*
     * ========== Photo ==========
     */
    /**
     * @Route("/data/photo", name="Photo_HomePage")
     */
    public function Photo_HomePage(Request $request){

        $photo = new Photo();
        var_dump(PhotoType::class);
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $doc = $this->getDoctrine();



            /** @var UploadedFile */
            $file = $photo->getFile();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('Photo_directory'),
                $fileName
            );
            $photo->setFile($fileName);

            $em->persist($photo);
            #$em->flush();


            return new Response("coucou");

        }

        return $this->render('Data/Project/Photo_form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @Route("/data/project", name="Project_HomePage")
     */
    public function Project_HomePage(Request $request){

        $doc = $this->getDoctrine();

        $data = $doc->getRepository(Project::class)->findAll();

        $project = new Project();

        $form = $this->createForm( ProjectType::class, $project);
        $form->handleRequest($request);

        $retour = ['code'=> 0];

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doc->getManager();

            foreach ($project->getFiles()[0] as $file){

                $photo = new Photo();

                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('Photo_directory'),
                    $fileName
                );

                $photo->setFile($fileName);
                $photo->setProject($project);
                $em->persist($photo);
                $project->addPhoto($photo);

            }
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('Project_HomePage'));
        }else{
            $retour = ['code'=> 1];
        }

        return $this->render("Data/Project/Projet_Homepage.html.twig",['projects'=>$data, 'form'=>$form->createView(),'retour'=>$retour]);
    }

    /**
     * @Route("/data/project/{id}/zoom", name="Project_zoom")
     */
    public function Project_Zoom($id){
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);
        return $this->render("Data/Project/Project_Zoom.html.twig",["data"=>$project]);
    }

}
