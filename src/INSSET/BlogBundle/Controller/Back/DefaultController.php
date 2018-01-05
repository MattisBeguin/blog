<?php
/**
 * Created by PhpStorm.
 * User: mattisbeguin
 * Date: 04/01/2018
 * Time: 17:58
 */

namespace INSSET\BlogBundle\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $em = $this->getDoctrine()->getManager();

            $blogger = $this->getUser();

            $articles = $em->getRepository('INSSETBlogBundle:Article')->findAllTitlesDates($blogger);

            $comments = $em->getRepository('INSSETBlogBundle:Comment')->findAllTextsDatesArticles($blogger);

            return $this->render('INSSETBlogBundle:Back/Default:index.html.twig', array('blogger' => $blogger, 'articles'  => $articles, 'comments' => $comments));
        }

        else{
            return $this->redirectToRoute('fos_user_security_login');
        }
    }
}
