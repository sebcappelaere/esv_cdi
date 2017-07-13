<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Adresse;
use AppBundle\Entity\Personne;
use AppBundle\Repository\PersonneRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $data="";
        //Création d'un formulaire de téléchargement
        $form = $this->createFormBuilder()
            ->add('file', FileType::class, ["label" => "Fichier à charger"])
            ->add('submit', SubmitType::class, ["label" => "Télécharger"])
            ->getForm();

        //Injection des données postées dans le formulaire
        $form->handleRequest($request);

        //Traitement si le formulaire est soumis et validé
        if ($form->isSubmitted() && $form->isValid()) {

            //Récupération du fichier chargé
            $file = $form['file']->getData();

            //Récupération des données du fichier
            $data = file_get_contents($file);
        }

        //Appel d'une fonction pour insérer les données dans la base de données
        $this->putDataInDatabase($data);



        return $this->render('default/index.html.twig',
            [
                "fileForm" => $form->createView(),

            ]);

    }


    private function putDataInDatabase($data){

        //Découpage du fichier dans un tableau indicé
        $rows = str_getcsv($data,"\n");

        foreach ($rows as $line){
            foreach ($line as $elmt){
                $columns = explode(';', $elmt);

                //Recherche de l'email dans la base de données
                $person = $this->getDoctrine()
                    ->getRepository('AppBundle:Personne')
                    ->findOneByEmail($columns[2]);

                //Création d'une éventuelle nouvelle personne
                if (empty($person)){
                    $person = new Personne();
                    $person->setNom($columns[0])
                        ->setPrenom($columns[1])
                        ->setEmail($columns[2]);
                }

                //Création d'une éventuelle nouvelle adresse
                if (!empty($columns[3])){
                    $adress = new Adresse();
                    $adress->setAdresse($columns[3])
                        ->setCodePostal($columns[4])
                        ->setVille($columns[5]);
                }

                //Création d'un éventuel nouveau paiement
                if ()
            }
        }

        //Boucle sur chaque ligne
        for ($i=0; $i<count($rows);$i++){
            //Découpage d'une ligne en un tableau de colonnes
            $columns = explode(';', $rows[$i]);
        }
    }
}
