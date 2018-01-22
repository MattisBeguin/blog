<?php
/**
 * Created by PhpStorm.
 * User: mattisbeguin
 * Date: 11/01/2018
 * Time: 09:38
 */

namespace INSSET\BlogBundle\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use INSSET\BlogBundle\Entity\Article;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends Controller
{
    public function publishAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $blogger = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $articles = $em->getRepository('INSSETBlogBundle:Article')->findAllByBlogger($blogger->getId(), null);

            return $this->render('INSSETBlogBundle:Back/Article:publish.html.twig', array('articles'  => $articles));
        }

        else{
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    public function ajaxPublishAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            if ($request->isXmlHttpRequest()){
                $id = $request->request->get('id');
                $published = $request->request->get('published');

                if (!(empty($id)) && (!(empty($published)))){
                    $em = $this->getDoctrine()->getManager();

                    $article = $em->getRepository('INSSETBlogBundle:Article')->findOneBy(array('id' => (integer)$id));

                    if (!(is_null($article))){
                        $article->setPublished(filter_var($published, FILTER_VALIDATE_BOOLEAN));
                        $article->setDate(new \Datetime('NOW'));
                        $em->flush();

                        return new JsonResponse(json_encode(array('status' => 'Okay', 'data' => $article->getPublished()), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            return new JsonResponse(json_encode(array('status' => 'Not Okay'), JSON_UNESCAPED_UNICODE));
        }

        else{
            return new JsonResponse(json_encode(array('status' => 'Accès refusé !!!'), JSON_UNESCAPED_UNICODE));
        }
    }

    public function addAction(Request $request)
    {
        if ($request->isMethod('POST')){
            if ($request->isXmlHttpRequest()){
                $id = $request->request->get('id');
                $title = $request->request->get('title');
                $body = $request->request->get('body');

                if ((!(empty($id))) && (!(empty($title))) && (!(empty($body)))){
                    if (strlen((string) $title) <= 255){
                        $em = $this->getDoctrine()->getManager();

                        $article = $em->getRepository('INSSETBlogBundle:Article')->findOneBy(array('id' => (integer) $id));

                        if ($article !== null){
                            $article->setTitle((string) $title);
                            $article->setBody((string) $body);
                            $em->flush();

                            return new JsonResponse(json_encode(array('status' => 'Okay', 'dataTitle' => $article->getTitle(), 'dataBody' => $article->getBody()), JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
            }

            return new JsonResponse(json_encode(array('status' => 'Not Okay'), JSON_UNESCAPED_UNICODE));
        }

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $blogger = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $article = new Article();

            $article->setBlogger($blogger);

            $em->persist($article);

            $em->flush();

            return $this->render('INSSETBlogBundle:Back/Article:add.html.twig', array('article'  => $article));
        }

        else{
            return $this->redirectToRoute('fos_user_security_login');
        }
    }
}
