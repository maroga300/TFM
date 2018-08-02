<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class ManageQuestionController extends Controller
{
    public function listAction($surveyid) {
    
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('AppBundle:Question')->findBySurveyId($surveyid);
        
        $survey = $em->getRepository('AppBundle:Survey')->find($surveyid);
      
        
        return $this->render(
            'questions/list.html.twig',
            array(
               'questions'=>$questions,
                'surveyid' =>$surveyid,
                'surveyname' => $survey->getName(),
                'categoryid' => $survey->getCategoryid()
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
    public function createAction(Request $request, $surveyid){
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        $active = TRUE;
        $question = new Question();
        $question->setSurveyid($surveyid);

        $form = $this->createFormBuilder($question)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Escribe la pregunta'),'label'=>false,'required'=>true))
           ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Guardar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $question = $form->getData();

            //Guardamos en la Base de datos
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_list', array('surveyid'=>$surveyid));
        }

        return $this->render('questions/create.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    
   
}
