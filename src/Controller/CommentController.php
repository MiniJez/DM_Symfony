<?php
namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\EntityBody;

use App\Entity\Commentary;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\DateTime;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/post", name="leaveComment")
     */

    public function leaveComment(Request $request) {
        $value =  $request->request->get('value');
        $idjoke = $request->request->get('IDjoke');
        $Owner = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();

        $commentary = new Commentary();
        $commentary->setValue($value);
        $commentary->setOwner($Owner->getUsername());
        $commentary->setDate(new \DateTime());
        $commentary->setIDjoke($idjoke);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($commentary);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return self::showComment($idjoke);
    }

    /**
     * @Route("/comment/{id}", name="comment")
     */

    public function showComment($id)
    {
        $client = new Client();
        $res = $client->request('GET', 'https://bridge.buddyweb.fr/api/blagues/blagues/' . $id);
        $blague = json_decode($res->getBody(), true);

        $commentarys = $this->getDoctrine()
        ->getRepository(Commentary::class)
        ->findBy(
            ['IDjoke' => $id]
        );

        return $this->render('comments/comment.html.twig',
        [ 'blagues' => $blague, 'id' => $id, 'commentarys' => $commentarys ]
        );
    }
}