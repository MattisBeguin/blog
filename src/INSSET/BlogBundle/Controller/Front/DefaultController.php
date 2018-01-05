<?php
/**
 * Created by PhpStorm.
 * User: mattisbeguin
 * Date: 29/12/2017
 * Time: 14:33
 */

namespace INSSET\BlogBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use INSSET\BlogBundle\Entity\Comment;
use INSSET\BlogBundle\Form\CommentType;
use INSSET\BlogBundle\Entity\Article;

class DefaultController extends Controller
{
    public function indexAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $articleNumber = $em->getRepository('INSSETBlogBundle:Article')->getNumberEntities();

        if ($articleNumber > 0){
            if ($id == 0){
                $article = $em->getRepository('INSSETBlogBundle:Article')->findOneBy(array('published' => false), array('id' => 'DESC'));
            }

            else{
                $article = $em->getRepository('INSSETBlogBundle:Article')->findOneBy(array('id' => $id, 'published' => false));
            }

            if ($article === null){
                throw new NotFoundHttpException('L\'article d\'id ' . $id . ' n\'a pas été publié ou il n\' existe pas.');
            }

            $comment = new Comment();

            $form = $this->createForm(CommentType::class, $comment, array('defaultArticle' => $article));

            if ($request->isMethod('POST')){
                $form->handleRequest($request);

                if (($form->isSubmitted()) && ($form->isValid())){
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comment);
                    $em->flush();

                    return $this->redirectToRoute('insset_blog_front_default_index', array('id' => $article->getId()));
                }
            }

            $form = $form->createView();

            $comments = $em->getRepository('INSSETBlogBundle:Comment')->findBy(array('article' => $article->getId()));

            $articlesTitles = $em->getRepository('INSSETBlogBundle:Article')->findAllOthersTitles($article->getId());
        }

        else{
            if ($id == 0){
                $article = null;
                $comments = null;
                $form = null;
                $articlesTitles = null;
            }

            else{
                throw new NotFoundHttpException('L\'article d\'id ' . $id . ' n\'a pas été publié ou il n\' existe pas.');
            }
        }

        return $this->render('INSSETBlogBundle:Front/Default:index.html.twig', array('article'  => $article, 'comments' => $comments, 'form' => $form, 'articlesTitles' => $articlesTitles));
    }
}
