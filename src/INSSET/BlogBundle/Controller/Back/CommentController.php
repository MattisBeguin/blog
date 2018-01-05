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
    public function editAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $id = $request->request->get('id');
            $text = $request->request->get('text');

            if(!(empty($id)) && (!(empty($text)))){
                $em = $this->getDoctrine()->getManager();

                $comment = $em->getRepository('INSSETBlogBundle:Comment')->findOneBy(array('id' => $id));

                if ($comment !== null){
                    $comment->setText($text);
                    $em->flush();

                    return new JsonResponse(json_encode(array('status' => 'Okay', 'data' => $comment->getText()), JSON_UNESCAPED_UNICODE));
                }
            }
        }

        return new JsonResponse(json_encode(array('status' => 'Not Okay'), JSON_UNESCAPED_UNICODE));
    }

    public function deleteAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $id = $request->request->get('id');

            if(!(empty($id))){
                $em = $this->getDoctrine()->getManager();

                $comment = $em->getRepository('INSSETBlogBundle:Comment')->findOneBy(array('id' => $id));

                if ($comment !== null){
                    $em->remove($comment);
                    $em->flush();

                    return new JsonResponse(json_encode(array('status' => 'Okay'), JSON_UNESCAPED_UNICODE));
                }
            }
        }

        return new JsonResponse(json_encode(array('status' => 'Not Okay'), JSON_UNESCAPED_UNICODE));
    }
}
