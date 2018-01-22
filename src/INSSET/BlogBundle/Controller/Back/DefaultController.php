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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $blogger = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $articles = $em->getRepository('INSSETBlogBundle:Article')->findAllByBlogger($blogger->getId(), true);

            $comments = $em->getRepository('INSSETBlogBundle:Comment')->findAllTextsDatesArticlesByBlogger($blogger->getId());

            return $this->render('INSSETBlogBundle:Back/Default:index.html.twig', array('articles'  => $articles, 'comments' => $comments));
        }

        else{
            return $this->redirectToRoute('fos_user_security_login');
        }
    }
}
