<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ManageSurveyController extends Controller
{
    public function showAction(){
       
        $em = $this->getDoctrine()->getManager();
        $surveys = $em->getRepository(Survey::class)->findAll();
      
        return $this->render(
            'surveys/showall.html.twig',
            array(
               'surveys'=>$surveys,
            )
        );
    }
    
    /**
     * Función para la creación de encuestas nuevas
     *
     * @param 
     *
     * @return 
     */
    public function createAction(Request $request){
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        $active = '';
        
        
        
        
        $survey = new Survey();
        $survey->setIsActive($active);
        $survey->setCreationDate(new \DateTime());

        $form = $this->createFormBuilder($survey)
            ->add('name', TextType::class,array('label'=>'Nombre del nuevo Proyecto','required'=>true))
            ->add('description', TextType::class)
            ->add('isActive', CheckboxType::class, array('label'    => 'activo?','required' => false))
            ->add('creationDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Nueva encuesta'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $survey = $form->getData();

            //Guardamos en la Base de datos
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($survey);
            $entityManager->flush();

            return $this->redirectToRoute('show_all');
        }

        return $this->render('surveys/create.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
}
