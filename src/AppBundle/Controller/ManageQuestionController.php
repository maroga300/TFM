<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Entity\Answer; //usamos la entidad Answer para modificar el tipo de pregunta: Rango numérico
use App\Form\Type\QuestionType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
          
           
            return $this->redirectToRoute('question_list', array('surveyid'=>$surveyid));            
           
        }

        return $this->render('questions/create.html.twig', array(
            'form' => $form->createView(),
            'surveyid' => $surveyid
        ));
    }
    
    private function getTypeQuestions() {
        $questions = array();
        $questions[0] = 'Texto';
        $questions[1] = 'Escala Numérica';
        $questions[2] = 'Elección única';
        $questions[3] = 'Elección múltiple';
        return $questions;
    }
    
    public function editAction(Request $request, $questionid){
        /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('AppBundle:Question')->find($questionid);
        $surveyid = $question->getSurveyId();
        $typeid = $question->getTypeId();

        $answer = $em->getRepository('AppBundle:Answer')->findByQuestionId($questionid);
        
        if(empty($answer)){
            $answer = array();
            $answer[0] = new Answer();
            $answer[0]->setQuestionId($questionid);
            $var="";
        }else{
            $var = $answer[0]->getName();
        }
            
        if($typeid==1){
         $form = $this->createFormBuilder()
         ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Enunciado de la pregunta'),'label'=>false,'required'=>true,'data'=>$question->getName()))
         ->add('rango', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Rango máximo'),'label'=>false,'required'=>true,'data'=>$var))
         ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Guardar'))
         ->getForm();
        }else{
         $form = $this->createFormBuilder()
         ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Enunciado de la pregunta'),'label'=>false,'required'=>true,'data'=>$question->getName()))
         ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Guardar'))
         ->getForm();
        }
   
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $formData = $form->getData();
            
            $question->setName($formData['name']);
            
            if($typeid==1){
                $answer[0]->setName($formData['rango']);
            }
                   
            //Guardamos en la Base de datos
            $em->persist($question);
            
            if($typeid==1){
               $em->persist($answer[0]);
             }
            $em->flush();    
           
            $this->addFlash('OK', 'Actualización correcta');
            
            return $this->redirectToRoute('question_list', array('surveyid'=>$surveyid));
        }

        return $this->render('questions/create.html.twig', array(
            'form' => $form->createView(),
            'surveyid' => $surveyid
        ));   
    }
    
    public function deleteAction($questionid, Request $request){
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('AppBundle:Question')->find($questionid);
        $surveyid = $question->getSurveyId();
       
        $answers = $em->getRepository('AppBundle:Answer')->findByQuestionId($questionid);
        foreach ($answers as $data) {
            $em->remove($data);
        }
        
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('question_list', array('surveyid'=>$surveyid));
    }
   
}
