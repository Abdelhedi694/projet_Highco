<?php

namespace App\Controller;

use App\Database;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {


        $db = new Database();
        $pdo = $db->Pdo();
        $dooneesBartChart = $pdo->query("SELECT montant, COUNT(montant) as nbrDeFoisDonne from contact group by montant")->fetchAll();
        $donneesPieChart10results = $pdo->query("SELECT code_postal, COUNT(code_postal) as nbrDeFois FROM contact GROUP BY code_postal ORDER BY nbrDeFois DESC LIMIT 10")->fetchAll();
        $resteCodePostal = [];
        foreach ($donneesPieChart10results as $item){
            $resteCodePostal[] = $item["code_postal"];
        }

        $resteCodePostal = implode(",",$resteCodePostal);
        $donneesPieChartRestResults = $pdo->query("SELECT count(DISTINCT code_postal) as nbrCode_postal_restant FROM contact WHERE code_postal NOT IN ($resteCodePostal)")->fetchAll();


        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'donneesBartChart'=>$dooneesBartChart,
            'donneesPieChart10results'=>$donneesPieChart10results,
            'donneesPieChartRestResults'=>$donneesPieChartRestResults
        ]);
    }
}
