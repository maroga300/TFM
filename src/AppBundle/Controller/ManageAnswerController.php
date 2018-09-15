<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Answer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ManageAnswerController extends Controller
{
    public function listAction($questionid){
        
        $em = $this->getDoctrine()->getManager();
        $options = $em->getRepository('AppBundle:Answer')->findByQuestionId($questionid);
        
        $ep = $this->getDoctrine()->getManager();
        $question = $em->getRepository('AppBundle:Question')->find($questionid);
        $questionname = $question->getName();
        $questiontype = $question->getTypeId();
        $surveyid = $question->getSurveyId();
   
        return $this->render(
            'answers/list.html.twig',
            array(
               'answers'=>$options,
               'questionid' =>$questionid,
                'questionname' => $questionname,
                'questiontype' => $questiontype,
               'surveyid' =>$surveyid,
            )
        );
    }
    
    public function createAction($questionid,Request $request){
     
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
       
        $answer = new Answer();
        $answer->setQuestionid($questionid);
        
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('AppBundle:Question')->find($questionid);
        $typeid = $question->getTypeId();
        $surveyid = $question->getSurveyId();
         
        //Opción ELECCIÓN ÚNICA O MÚLTIPLE
        if($typeid==2 || $typeid==3){
            $form = $this->createFormBuilder($answer)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Opción'),'label'=>false,'required'=>true))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Crear'))
            ->getForm();
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $answer = $form->getData();

            //Guardamos en la Base de datos
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('answer_list', array('questionid'=>$questionid));
        }

        return $this->render('questions/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    
    
    
    
    public function editAction(Request $request, $answerid){
         
        $em = $this->getDoctrine()->getManager();
        $answer = $em->getRepository('AppBundle:Answer')->find($answerid);
        $questionid = $answer->getQuestionId();

        $form = $this->createFormBuilder()
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Opción'),'label'=>false,'required'=>true,'data'=>$answer->getName()))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Modificar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $formData = $form->getData();
            
            $answer->setName($formData['name']);
            
            //Comprobación de errores de encuestas
            
            
            
            //Guardamos en la Base de datos
            $em->persist($answer);
            $em->flush();    

            $this->addFlash('OK', 'Actualización correcta');
            
            return $this->redirectToRoute('answer_list', array('questionid'=>$questionid));
        }

        return $this->render('answers/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
     public function deleteAction(Request $request, $answerid){
        
         $em = $this->getDoctrine()->getManager();
        $answer = $em->getRepository('AppBundle:Answer')->find($answerid);
        $questionid = $answer->getQuestionId();
        
        $em->remove($answer);
        $em->flush();
        return $this->redirectToRoute('answer_list', array('questionid'=>$questionid));
    }
            
}
