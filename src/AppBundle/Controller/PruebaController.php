<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PruebaController extends Controller
{
     public function indexAction()
    {
        $number = random_int(0, 100);
     /*   return new Response(
            '<html><body>Lucky number: '.$a.'</body></html>'
        );
     */
        
        return $this->render('vistaPrueba.html.twig', array(
            'number' => $number,
        ));
    }
}