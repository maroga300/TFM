<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Survey;
//use Symfony\Component\HttpFoundation\Request;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
}
