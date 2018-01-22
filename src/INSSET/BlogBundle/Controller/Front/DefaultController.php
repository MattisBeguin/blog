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
use Symfony\Component\HttpFoundation\Response;
use INSSET\BlogBundle\Entity\Comment;
use INSSET\BlogBundle\Form\CommentType;

class DefaultController extends Controller
{
    public function indexAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $articleRepository = $em->getRepository('INSSETBlogBundle:Article');

        $articlesNumber = $articleRepository->getTuplesNumber();

        if ($articlesNumber > 0){

            $id = (integer) $id;

            if ($id === 0){
                $article = $articleRepository->findOneBy(array('published' => true), array('date' => 'DESC'));
            }

            else{
                $article = $articleRepository->findOneBy(array('id' => $id, 'published' => true));
            }

            if ($article === null){
                $response = new Response();
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                $response->setContent('ERREUR 404 : L\'article d\'id ' . $id . ' n\'a pas été publié ou il n\' existe pas encore...');

                return $response;
            }

            $comment = new Comment();

            $commentsForm = $this->createForm(CommentType::class, $comment, array('defaultArticle' => $article));

            if ($request->isMethod('POST')){
                $commentsForm->handleRequest($request);

                if (($commentsForm->isSubmitted()) && ($commentsForm->isValid())){
                    $em->persist($comment);
                    $em->flush();

                    return $this->redirectToRoute('insset_blog_front_default_index', array('id' => $article->getId()));
                }
            }

            $commentsForm = $commentsForm->createView();

            $comments = $em->getRepository('INSSETBlogBundle:Comment')->findBy(array('article' => $article));

            $articlesTitles = $articleRepository->findAllOthersTitles($article->getId());
        }

        else{
            if ($id == 0){
                $article = null;
                $comments = null;
                $commentsForm = null;
                $articlesTitles = null;
            }

            else{
                $response = new Response();
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                $response->setContent('ERREUR 404 : L\'article d\'id ' . $id . ' n\'a pas été publié ou il n\' existe pas encore...');

                return $response;
            }
        }

        return $this->render('INSSETBlogBundle:Front/Default:index.html.twig', array('article'  => $article, 'comments' => $comments, 'commentsForm' => $commentsForm, 'articlesTitles' => $articlesTitles));
    }
}
