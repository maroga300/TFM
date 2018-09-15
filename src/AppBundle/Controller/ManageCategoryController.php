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
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control hide', 'placeholder'=> 'Nombre de la categoría'),'label'=>false,'required'=>true))
            ->add('description', TextareaType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Descripción'),'label'=>false))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Guardar'))
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
    
   public function editAction($categoryid, Request $request){
    /*    $error = '';
     * 
    //TO-DO: Comprobación Usuario logueado.
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
    */   
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($categoryid);
        //
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Nombre de la categoría'),'label'=>false,'required'=>true,'data'=>$category->getName()))
            ->add('description', TextareaType::class,array('attr' => array('class' => 'form-control', 'placeholder'=> 'Descripción'),'label'=>false,'data'=>$category->getDescription()))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary'),'label' => 'Modificar'))
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

        return $this->render('categories/create.html.twig', array(
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
    public function deleteAction($categoryid, Request $request){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($categoryid);
        
        //NO OLVIDAR: Borrar las respuestas de una pregunta
        
      
        //borramos las encuestas asociadas a dicha categoría
        $surveys = $em->getRepository('AppBundle:Survey')->findByCategoryid($categoryid);
        foreach ($surveys as $data) {
            $em->remove($data);
        }
        
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_list');
    }
   
}
