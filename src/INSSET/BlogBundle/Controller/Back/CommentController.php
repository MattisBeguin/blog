<?php
/**
 * Created by PhpStorm.
 * User: mattisbeguin
 * Date: 05/01/2018
 * Time: 01:12
 */

namespace INSSET\BlogBundle\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends Controller
{
    public function ajaxEditAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            if ($request->isXmlHttpRequest()) {
                $id = $request->request->get('id');
                $text = $request->request->get('text');

                if (!(empty($id)) && (!(empty($text)))){
                    $em = $this->getDoctrine()->getManager();

                    $comment = $em->getRepository('INSSETBlogBundle:Comment')->findOneBy(array('id' => (integer) $id));

                    if (!(is_null($comment))){
                        $comment->setText((string) $text);
                        $em->flush();

                        return new JsonResponse(json_encode(array('status' => 'Okay', 'data' => $comment->getText()), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            return new JsonResponse(json_encode(array('status' => 'Not Okay !!!'), JSON_UNESCAPED_UNICODE));
        }

        else{
            return new JsonResponse(json_encode(array('status' => 'Accès refusé !!!'), JSON_UNESCAPED_UNICODE));
        }
    }

    public function ajaxDeleteAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            if ($request->isXmlHttpRequest()){
                $id = $request->request->get('id');

                if (!(empty($id))){
                    $em = $this->getDoctrine()->getManager();

                    $comment = $em->getRepository('INSSETBlogBundle:Comment')->findOneBy(array('id' => (integer) $id));

                    if (!(is_null($comment))){
                        $em->remove($comment);
                        $em->flush();

                        return new JsonResponse(json_encode(array('status' => 'Okay'), JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            return new JsonResponse(json_encode(array('status' => 'Not Okay !!!'), JSON_UNESCAPED_UNICODE));
        }

        else{
            return new JsonResponse(json_encode(array('status' => 'Accès refusé !!!'), JSON_UNESCAPED_UNICODE));
        }
    }
}
