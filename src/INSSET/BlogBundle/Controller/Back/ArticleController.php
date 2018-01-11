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
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends Controller
{
    public function publishAction(Request $request)
    {
        if ($request->isMethod('POST')){
            if ($request->isXmlHttpRequest()){
                $id = $request->request->get('id');
                $published = $request->request->get('published');

                if(!(empty($id)) && (!(empty($published)))){
                    $em = $this->getDoctrine()->getManager();

                    $article = $em->getRepository('INSSETBlogBundle:Article')->findOneBy(array('id' => (integer) $id));

                    $published = filter_var($published, FILTER_VALIDATE_BOOLEAN);

                    if ($article !== null){
                        $article->setPublished($published);
                        $article->setDate(new \Datetime());
                        $em->flush();

                        return new JsonResponse(json_encode(array('status' => 'Okay', 'data' => $article->getPublished()), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            return new JsonResponse(json_encode(array('status' => 'Not Okay'), JSON_UNESCAPED_UNICODE));
        }

        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $blogger = $this->getUser();

            $em = $this->getDoctrine()->getManager();

            $articles = $em->getRepository('INSSETBlogBundle:Article')->findAllByBlogger($blogger, null);

            return $this->render('INSSETBlogBundle:Back/Article:publish.html.twig', array('blogger' => $blogger, 'articles'  => $articles));
        }

        else{
            return $this->redirectToRoute('fos_user_security_login');
        }
    }
}
