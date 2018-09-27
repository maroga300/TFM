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
    public function showAction($categoryid){
       
        $em = $this->getDoctrine()->getManager();
        $surveys = $em->getRepository('AppBundle:Survey')->findByCategoryid($categoryid);
        
        $category = $em->getRepository('AppBundle:Category')->find($categoryid);
        
        $user = $this->getUser();
        
       
        
        return $this->render(
            'surveys/showall.html.twig',
            array(
               'surveys'=>$surveys,
                'categoryid' =>$categoryid,
                'categoryname' => $category->getName()
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
    public function createAction(Request $request, $categoryid){
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        $survey = new Survey();
        $survey->setCreationDate(new \DateTime());
        $survey->setModificationDate(new \DateTime('0/0/0'));
        $survey->setCategoryid($categoryid);

        $form = $this->createFormBuilder($survey)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Nombre de la encuesta'),'label'=>false,'required'=>true))
            ->add('description', TextareaType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Descripción'),'label'=>false))
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

            return $this->redirectToRoute('survey_list', array('categoryid'=>$categoryid));
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
    public function editAction($surveyid, Request $request){
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        $em = $this->getDoctrine()->getManager();
        $survey = $em->getRepository('AppBundle:Survey')->find($surveyid);
        $survey->setModificationDate(new \DateTime());
        $categoryid = $survey->getCategoryId();

        $form = $this->createFormBuilder($survey)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Nombre'),'label'=>false,'required'=>true,'data'=>$survey->getName()))
            ->add('description', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Descripción'),'label'=>false,'data'=>$survey->getDescription()))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Modificar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $formData = $form->getData();
            
            $survey->setName($formData->getName());
            $survey->setDescription($formData->getDescription());
            
            
            //Comprobación de errores de encuestas
            
            
            
            //Guardamos en la Base de datos
            $em->persist($survey);
            $em->flush();    

            $this->addFlash('OK', 'Actualización correcta');
            
            return $this->redirectToRoute('survey_list', array('categoryid'=>$categoryid));
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
    public function deleteAction($surveyid, Request $request){
        $em = $this->getDoctrine()->getManager();
        $survey = $em->getRepository('AppBundle:Survey')->find($surveyid);
        $categoryid = $survey->getCategoryId();

                
        $em->remove($survey);
        $em->flush();
        return $this->redirectToRoute('survey_list', array('categoryid'=>$categoryid));
    }
}
