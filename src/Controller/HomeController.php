<?php
namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\EntityBody;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */

    public function getBlague()
    {
        $client = new Client();
        $res = $client->request('GET', 'https://bridge.buddyweb.fr/api/blagues/blagues');
        $blague = json_decode($res->getBody(), true);

        return $this->render('home/home.html.twig',
        [ 'blagues' => $blague, ]
        );
    }
}