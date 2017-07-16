<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Adresse;
use AppBundle\Entity\Paiement;
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

            //Appel d'une fonction pour insérer les données dans la base de données
            $this->putDataInDatabase($data);
        }


        return $this->render('default/index.html.twig',
            [
                "fileForm" => $form->createView(),

            ]);

    }


    private function putDataInDatabase($data){

        //Découpage du fichier dans un tableau indicé
        $rows = str_getcsv($data,"\n");
        //Supression de la première ligne du tableau
        unset($rows[0]);

        foreach ($rows as $line){
            //foreach ($line as $elmt){
                var_dump('$ligne =',$line);
                $columns = explode(';', $line);

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

                //Vérification si les données du fichier csv sont bien ordonnées
                if (empty($columns[8])){
                    //Décalage des données d'une colonne à partir de la date
                    $columns[8] = $columns[7];
                    $columns[7] = $columns[6];
                    $columns[6] = $columns[5];
                }

                //Création d'une éventuelle nouvelle adresse
                if (!empty($columns[3])){
                    $adress = new Adresse();
                    $adress->setAdresse($columns[3])
                        ->setCodePostal($columns[4])
                        ->setVille($columns[5]);
                    //Ajout de la personne à cette adresse
                    $adress->addPersonne($person);
                    //Ajout de l'adresse à la personne
                    $person->setAdresse($adress);
                }

                //Récupération des éléments de la date au format français
                $date = explode("/",$columns[6]);

                //Création d'un éventuel nouveau paiement
                $paiement = new Paiement();
                $paiement->setDate(date_date_set(date_create(),$date[2],$date[1],$date[0]))
                    ->setMontant($columns[7])
                    ->setNature($columns[8]);
                //Ajout de la personne à ce paiement
                $paiement->setPersonne($person);
                //Ajout du paiement à la personne
                $person->addPaiement($paiement);

                $em = $this->getDoctrine()->getManager();
                $em->persist($person);
                $em->flush();

            //}
        }


    }
}
