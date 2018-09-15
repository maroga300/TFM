<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class InstanceController extends Controller
{
    public function listAction($surveyid){
        
        $em = $this->getDoctrine()->getManager();
        $instances = $em->getRepository('AppBundle:Instance')->findBySurveyid($surveyid);
        
        $survey=$em->getRepository('AppBundle:Survey')->find($surveyid);
        
        return $this->render(
            'instances/list.html.twig',
            array(
               'instances'=>$instances,
                'surveyid' =>$surveyid,
                'surveyname' => $survey->getName()
            )
        );
    }
    
    public function createAction(Request $request, $surveyid){
        
        $active = FALSE;
        $instance = new Instance();
        $instance->setActive($active);
        $instance->setCreationDate(new \DateTime());
        $instance->setModificationDate(new \DateTime('0/0/0'));
        $instance->setSurveyid($surveyid);
        $instance->setCode(md5(time()));

        $form = $this->createFormBuilder($instance)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Nombre de la instancia'),'label'=>false,'required'=>true))
            ->add('description', TextareaType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Descripción'),'label'=>false))
            ->add('active', CheckboxType::class, array('attr' => array('class' => ''),'label' => '¿Activo?: ','required' => false))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Crear'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $instance = $form->getData();

            
            //Guardamos en la Base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($instance);
            $em->flush();

            return $this->redirectToRoute('instance_list', array('surveyid'=>$surveyid));
        }

        return $this->render('instances/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function editAction(Request $request, $instanceid){
        
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('AppBundle:Instance')->find($instanceid);
        $instance->setModificationDate(new \DateTime());
        $surveyid = $instance->getSurveyId();

        $form = $this->createFormBuilder($instance)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Nombre'),'label'=>false,'required'=>true,'data'=>$instance->getName()))
            ->add('description', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Descripción'),'label'=>false,'data'=>$instance->getDescription()))
            ->add('active', CheckboxType::class, array('attr' => array('class' => ''),'label' => '¿Activo?: ','required' => false,'data'=>$instance->getActive()))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Modificar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $formData = $form->getData();
            
            $instance->setName($formData->getName());
            $instance->setDescription($formData->getDescription());
            $instance->setActive($formData->getActive());
            
            
            //Comprobación de errores de activaciones
            
            
            
            //Guardamos en la Base de datos
            $em->persist($instance);
            $em->flush();    

            $this->addFlash('OK', 'Actualización correcta');
            
            return $this->redirectToRoute('instance_list', array('surveyid'=>$surveyid));
        }

        return $this->render('instances/create.html.twig', array(
            'form' => $form->createView(),
        ));
        
        
    }
    
    
    
    
    /*
    En esta función falta controlar que no existan respuestas asociadas con la instancia 
     * que se desea borrar, o bien, se le envía un mensaje que perderá todos los datos asociados.
     *      */
    public function deleteAction(Request $request, $instanceid){
        
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('AppBundle:Instance')->find($instanceid);
        $surveyid = $instance->getSurveyId();

                
        $em->remove($instance);
        $em->flush();
        return $this->redirectToRoute('instance_list', array('surveyid'=>$surveyid));
    }
}
