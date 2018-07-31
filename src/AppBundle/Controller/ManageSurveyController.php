<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
        $active = TRUE;
        $survey = new Survey();
        $survey->setIsActive($active);
        $survey->setCreationDate(new \DateTime());
        $survey->setModificationDate(new \DateTime('0/0/0'));

        $form = $this->createFormBuilder($survey)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Nombre de la encuesta'),'label'=>false,'required'=>true))
            ->add('description', TextareaType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Descripción'),'label'=>false))
            ->add('isActive', CheckboxType::class, array('attr' => array('class' => ''),'label' => '¿Activo?: ','required' => false))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Crear'))
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

            return $this->redirectToRoute('survey_list');
        }

        return $this->render('surveys/create.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    
    /**
     * Función para la edición de encuestas
     *
     * @param 
     *
     * @return 
     */
    public function editAction($id, Request $request){
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        $em = $this->getDoctrine()->getManager();
        $survey = $em->getRepository('AppBundle:Survey')->find($id);
        $survey->setModificationDate(new \DateTime());

        $form = $this->createFormBuilder($survey)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Nombre'),'label'=>false,'required'=>true,'data'=>$survey->getName()))
            ->add('description', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Descripción'),'label'=>false,'data'=>$survey->getDescription()))
            ->add('isActive', CheckboxType::class, array('attr' => array('class' => ''),'label' => '¿Activo?: ','required' => false,'data'=>$survey->getIsActive()))
           // ->add('creationDate', DateType::class, array('label' => 'Fecha: ','required' => false,'data'=>$survey->getCreationDate()))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Modificar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $formData = $form->getData();
            
            $survey->setName($formData->getName());
            $survey->setDescription($formData->getDescription());
            $survey->setIsActive($formData->getIsActive());
            
            
            //Comprobación de errores de encuestas
            
            
            
            //Guardamos en la Base de datos
            $em->persist($survey);
            $em->flush();    

            $this->addFlash('OK', 'Actualización correcta');
            
            return $this->redirectToRoute('survey_list');
        }

        return $this->render('surveys/create.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    
     /**
     * Función para borrar encuestas
     *
     * @param 
     *
     * @return 
     */
    public function deleteAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $survey = $em->getRepository('AppBundle:Survey')->find($id);
        $em->remove($survey);
        $em->flush();
        return $this->redirectToRoute('survey_list');
    }
}
