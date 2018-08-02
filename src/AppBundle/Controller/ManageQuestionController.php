<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $options = $this->getTypeQuestions();
   
        return $this->render(
            'questions/list.html.twig',
            array(
               'questions'=>$questions,
                'surveyid' =>$surveyid,
                'surveyname' => $survey->getName(),
                'categoryid' => $survey->getCategoryid(),
                'options'=>$options,
            )
        );
    }
    
     /**
     * Función para la creación de preguntas nuevas
     *
     * @param 
     *
     * @return 
     */
    public function createAction(Request $request, $surveyid){
        /*
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }*/
        
        $questions = $this->getTypeQuestions();

        $form = $this->createFormBuilder()
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Escribe la pregunta'),'label'=>false,'required'=>true))
            ->add('typeid', ChoiceType::class, array(
                'label'=>'Tipo de pregunta','required'=>true,
                'choices'  => array(
                    $questions[0]=>0,
                    $questions[1]=>1,
                    $questions[2]=>2,
                    $questions[3]=>3)))
           ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Guardar'))
            ->getForm();

        $form->handleRequest($request);
        
         if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $dataForm = $form->getData();
            $question = new Question();
            $question->setName($dataForm['name']);
            $question->setTypeId($dataForm['typeid']);
            $question->setSurveyId($surveyid);
        
            //Guardamos en la Base de datos
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            if($dataForm['typeid'] < 2) {
                return $this->redirectToRoute('question_list', array('surveyid'=>$surveyid));
            } else{
                return $this->redirectToRoute('question_list', array('surveyid'=>$surveyid));            
            }  
        }

        return $this->render('questions/create.html.twig', array(
            'form' => $form->createView(),
            'surveyid' => $surveyid
        ));
    }
    
    private function getTypeQuestions() {
        $questions = array();
        $questions[0] = 'Numérica';
        $questions[1] = 'Texto';
        $questions[2] = 'Elección única';
        $questions[3] = 'Elección múltiple';
        return $questions;
    }
    
    
    
    
    
    
    /*
  if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $questions = $this->getTypeQuestions();
        $form = $this->createFormBuilder()
                ->add('name', 'text', array('label' => 'Enunciado de la Pregunta'))
                ->add('type', 'choice', array('label' => 'Tipo de Pregunta', 'choices' => $questions))
                ->add('create', 'submit', array('label' => 'Añadir Pregunta'))
                ->getForm();
        $form->handleRequest($request);
        //se envia el formulario
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $question = new \IW\EasySurveyBundle\Entity\Question;
            $question->setName($dataForm['name']);
            $question->setTypeId($dataForm['type']);
            $question->setQuizId($id);
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();    
            if($dataForm['type'] < 2) {
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $id)));
            } else{
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $question->getId())));
            }  
        }

        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id' => $id, 'form' => $form->createView()));
    }
     
     *      */
    
   
}
