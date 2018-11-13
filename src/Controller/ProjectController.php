<?php
/**
 * Created by PhpStorm.
 * User: noamcarmi
 * Date: 28/08/2018
 * Time: 17:19
 */

namespace App\Controller;



use App\Entity\Photo;
use App\Entity\Project;

use App\Form\PhotoType;
use App\Form\ProjectType;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class TagController
 * @Route("/data/project")
 */
class ProjectController extends AbstractController
{

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    //ok
    /**
     * @Route("/", name="Project_HomePage")
     */
    public function Project_HomePage(){

        $doc = $this->getDoctrine();

        $data = $doc->getRepository(Project::class)->findAll();

        return $this->render("Data/Project/Projet_Homepage.html.twig",['projects'=>$data]);
    }

    //ok
    /**
     * @Route("/create", name="Project_Create", methods={"POST"})
     */
    public function Project_Create(Request $request){

        $project = new Project();

        $form = $this->get("form.factory")
            ->create(ProjectType::class, $project,
                ['action'=> $this->generateUrl("Project_Create")]
            );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return new JsonResponse(["state"=>1, 'confirm'=>1]);
        }
        return $this->render('modal/form.html.twig', ['titre'=> "projet", 'form'=>$form->createView()]);
    }

    //pas test
    /**
     * @Route("/{id}/zoom", name="Project_zoom")
     */
    public function Project_Zoom($id){
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);
        return $this->render("Data/Project/Project_Zoom.html.twig",["data"=>$project]);
    }

    //ok
    /**
     * @Route("/{id}/remove", name="Project_remove", methods={"POST"})
     */
    public function ProjetRemove($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository(Project::class)->find($id);
        if ($request->request->has('confirm') && $request->request->get('confirm') == 1){

            foreach ($project->getPhotos() as $photo){
                $this->RemovePhoto($photo);
            }
            $em->remove($project);
            $em->flush();

            return new JsonResponse(["state"=>1, "id"=>$id]);
        }
        else{
            return  $this->render('modal/remove.html.twig', [
                'name'=> $project->getName(),
                'url' => $this->generateUrl("Project_remove", ['id' => $id])
            ]);
        }


    }

    //ok
    private function RemovePhoto($photo){
        $em = $this->getDoctrine()->getManager();

        unlink($this->getParameter('Photo_directory') . '/' . $photo->getFile());
        $em->remove($photo);
        $em->flush();
    }

    /*
     * @param File $photo
     */
    private function AddPhoto($photo, $project){
        $current = new Photo();


        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

        $file->move(
            $this->getParameter('brochures_directory'),
            $fileName
        );



    }

    //to do
    /**
     * @Route("/{id}/updatePhotos", name="Project_update_photos", methods={"POST"})
     */
    public function ProjectUpdatePhoto($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository(Project::class)->find($id);
        $photos = $em->getRepository(Photo::class)->findBy(["Project" => $project]);


        $form = $this->get("form.factory")
            ->create(PhotoType::class, new Photo(),
                ['action'=> $this->generateUrl("Project_update_photos", ['id'=>$id])]
            );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($request->request->get('photos'));
            return new JsonResponse(["state"=>1, 'confirm'=>1]);
        }

        return $this->render('modal/photo-form.html.twig', ["photos"=>$photos, 'id'=>$id, "form"=>$form->createView()]);
    }


}