<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
class ManageCategoryController extends Controller
{
    public function listAction(){
        
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findByUserid(5);
      
        return $this->render(
            'categories/list.html.twig',
            array(
               'categories'=>$categories,
            )
        );
    }
    
    
    public function createAction(Request $request){
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        
        $category = new Category();
        $category->setUserid(5);

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class,array('label'=>'Nombre de la categoría: ','required'=>true))
            ->add('description', TextareaType::class,array('label'=>'Descripción: '))
            ->add('save', SubmitType::class, array('label' => 'Guardar', 'attr' => array('class' => 'save')))
            ->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

          //$form->getData() holds the submitted values
          // but, the original `$task` variable has also been updated
          $survey = $form->getData();

          //Guardamos en la Base de datos
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($category);
          $entityManager->flush();

          return $this->redirectToRoute('category_list');
        }

        return $this->render('categories/create.html.twig', array(
            'form' => $form->createView()
        ));
        
    }
    
   public function editAction($id, Request $request){
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);
        
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class,array('label'=>'Nombre de la categoría: ','required'=>true,'data'=>$category->getName()))
            ->add('description', TextareaType::class,array('label'=>'Descripción de la categoría: ','data'=>$category->getDescription()))
            ->add('save', SubmitType::class, array('label' => 'Modificar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $formData = $form->getData();
            
            $category->setName($formData->getName());
            $category->setDescription($formData->getDescription());
            
            
            //Comprobación de errores de las categorías
            
            
            
            //Guardamos en la Base de datos
            $em->persist($category);
            $em->flush();    

            $this->addFlash('OK', 'Actualización correcta');
            
            return $this->redirectToRoute('category_list');
        }

        return $this->render('surveys/create.html.twig', array(
            'form' => $form->createView(),
        ));   
    }
    
    
     /**
     * Función para borrar categorias
     *
     * @param 
     *
     * @return 
     */
    public function deleteAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_list');
    }
    
       /**
     * Función para ir a las encuestas de una categoría
     *
     * @param 
     *
     * @return 
        * 
        * 
        * 
        * 
        * 
     */
    
    /*
    public function gotoAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $surveys = $em->getRepository('AppBundle:Survey')->findbyIdCategory($id);
        
       // $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_list');
    }*/
}
