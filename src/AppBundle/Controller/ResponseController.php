<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Entity\Answer; //usamos la entidad Answer para modificar el tipo de pregunta: Rango numérico
use App\Form\Type\QuestionType;
use AppBundle\Entity\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ResponseController extends Controller {
    
    public function viewSurveyAction(Request $request, $code){
        
        
        $em = $this->getDoctrine()->getManager();
        
        $instance = $em->getRepository('AppBundle:Instance')->findByCode($code);   
        
        $surveyId = $instance[0]->getSurveyid();
        $startDate = $instance[0]->getStartDate();
        $endDate = $instance[0]->getEndDate();
        $nowDate = new \DateTime("now");
        $instanceId = $instance[0]->getId();
        
        $questions = $em->getRepository('AppBundle:Question')->findBySurveyId($surveyId);  
        $surveyName = $em->getRepository('AppBundle:Survey')->findById($surveyId);
        
        
        if($nowDate<$startDate || $nowDate>$endDate){
            
            
            //En este caso, el usuario no está dentro del período para respnder a la encuesta. 
            //Debería mostar un aviso y regresar a la página de inicio
           return $this->render('encuestanodisponible.html.twig', array(
            'surveyName'=>$surveyName[0]->getName(),
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'nowDate'=>$nowDate   
        ));
        }else{
        
        $form = $this->createFormBuilder();
        
        foreach ($questions as $question) {
            
            if ($question->getTypeId() == 1) { //numérica
                $answers = $em->getRepository('AppBundle:Answer')->findByQuestionId($question->getId());
                $num =(int) $answers[0]->getName();
                for ($i=1; $i<=$num;$i++) {
                    $choices[$i] =  $i;
                }
            } else if ( $question->getTypeId() == 2 || $question->getTypeId() == 3 ) { //preguntas de eleccion única y múltiple
                $answers = $em->getRepository('AppBundle:Answer')->findByQuestionId($question->getId());
                $choices = array();
                foreach ($answers as $answer) {
                    $choices[$answer->getName()] =  $answer->getId();
                }
            } 
            
            if ($question->getTypeId()==0) {
                $form = $form->add('question_'.$question->getId(), TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=>"{$question->getName()}"),'label'=>false,'required'=>true));
            } else if ($question->getTypeId()==1) {
                $form = $form ->add('question_'.$question->getId(), ChoiceType::class, array('attr' => array('class' => 'radio_buttons'),
                                'label'=>$question->getName(),
                                'required'=>true,
                                'expanded'=>true, 
                                'choices'  => $choices));
            } else if ($question->getTypeId()==2) {
                $form = $form ->add('question_'.$question->getId(), ChoiceType::class, array(
                'label'=>$question->getName(),'required'=>true,
                'choices'  => $choices));
            } else if ($question->getTypeId()==3) {
                $form = $form ->add('question_'.$question->getId(), ChoiceType::class, array(
                'label'=>$question->getName(),'required'=>true, 'multiple'=> true,
                'choices'  => $choices));
            }
        }
        
        $form = $form ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Enviar'));
        $form = $form->getForm();
        $form->handleRequest($request);
        
          if ($form->isSubmitted() && $form->isValid()) {
              
            $formData = $form->getData();
            
            foreach ($questions as $question) {
                
                $array_value = 'question_'.$question->getId();
                
                $response = new Response();
                $response->setInstanceId($instanceId);
                $response->setQuestionId($question->getId());
                $response->setCreationDate(new \DateTime());
                
                if ($question->getTypeId()==0) {
                    $response->setValue($formData[$array_value]);
                } else if ($question->getTypeId()==1) {
                    $response->setValue($formData[$array_value]);
                } else if ($question->getTypeId()==2) {
                    $response->setValue($formData[$array_value]);
                } else if ($question->getTypeId()==3) {
                    $multiple_response ='';
                    foreach ($formData[$array_value] as $data) {
                        $multiple_response .= $data .',';
                    }
                    $multiple_response = substr($multiple_response, 0, strlen($multiple_response)-1);
                    $response->setValue($multiple_response);
                }
                
                //Guardamos en la Base de datos
                $em->persist($response);
                $em->flush();    
            }
            
            $this->addFlash('OK', 'Actualización correcta');
            
            return $this->redirectToRoute('category_list');
        }
        
        return $this->render('response/view.html.twig', array(
            'form' => $form->createView(),
            'instanceName'=>$instance[0]->getName()
        ));
        
    }
    }
    
}
