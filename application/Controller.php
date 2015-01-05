<?php
/**
 * Created by IntelliJ IDEA.
 * User: remi
 * Date: 25/10/14
 * Time: 09:22
 */
require_once 'Viewer.php';
require_once 'Database.php';
require_once 'models/User.php';
require_once 'models/Message.php';

class Controller {


    /**
     * @var Viewer
     */
    private $viewer;

    /**
     * @var Database
     */
    private $db;


    /**
     * Costructeur
     */
    public function __construct(){
        $this->viewer = new Viewer();
        $this->db = new Database();
    }

    /**
     * Affiche la page d'accueil
     */
    public function index(){


        $this->viewer->render('index.twig', array(
            'application' => array(
                'nom' => 'MyCompany'
            ),
            'name' => 'World',
            'current'=> "index"
        ));
    }

    public function login(){

        $datas = array(
            'application' => array(
                'nom' => 'MyCompany'
            ),
            'name' => 'World',
            'current'=> "login"
        );
        if (!empty($_POST)) {
            // Si la variable $_POST est non vide c'est que le formulaire est posté.
            // Il faut donc vérifier les champs :
            // Pour le moment il n'y a pas d'erreur,
            $error = false;
            // Et il n'y a pas de message d'erreur (il peut y avoir plusieurs messages)
            $messages = array();
            // LOGIN

            if (!isset($_POST['login']) || $_POST['login'] == '') {
                // soit le champ login n'est pas envoyé soit il est vide
                // alors il y a une erreur :
                $error = true;
                array_push($messages, "Le login est obligatoire.");
            }

            if (!isset($_POST['password']) || $_POST['password'] == '') {
                // soit le champ password n'est pas envoyé soit il est vide
                // alors il y a une erreur :
                $error = true;
                array_push($messages, "Le mot de passe est obligatoire.");
            }

            // Si il y a une erreur
            if ($error) {
                // on re affiche le formulaire avec les erreurs
                $datas['errors'] = $messages;
            } else {
                // Pas d'erreur
                // On doit enregistrer l'utilisateyr en base de données
                // ==> ATTENTION il peut y avoir une erreur lors de l'insertion en base de données
                try {

                    $_SESSION['user'] = $this->db->getUserFrom($_POST['login'], $_POST['password']);
                    header('Location: /profile');
                } catch (Exception $e) {
                    $datas['errors'] = array("" . $e->getMessage());
                }
            }
        }


        $this->viewer->render('login.twig', $datas);
    }

    public function logout()
    {

        unset($_SESSION['user']);
        header('Location: /');
    }

    public function register()
    {

        $datas = array(
            'application' => array(
                'nom' => 'MyCompany'
            ),
            'name' => 'World',
            'current'=> "register"
        );

        if (!empty($_POST)) {
            // Si la variable $_POST est non vide c'est que le formulaire est posté.
            // Il faut donc vérifier les champs :
            // Pour le moment il n'y a pas d'erreur,
            $error = false;
            // Et il n'y a pas de message d'erreur (il peut y avoir plusieurs messages)
            $messages = array();
            // LOGIN

            if (!isset($_POST['login']) || $_POST['login'] == '') {
                // soit le champ login n'est pas envoyé soit il est vide
                // alors il y a une erreur :
                $error = true;
                array_push($messages, "Le login est obligatoire.");
            }

            if (!isset($_POST['email']) || $_POST['email'] == '') {
                // soit le champ email n'est pas envoyé soit il est vide
                // alors il y a une erreur :
                $error = true;
                array_push($messages, "L'email est obligatoire.");
            }

            if (!isset($_POST['password']) || $_POST['password'] == '') {
                // soit le champ password n'est pas envoyé soit il est vide
                // alors il y a une erreur :
                $error = true;
                array_push($messages, "Le mot de passe est obligatoire.");
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = true;
                array_push($messages, "L'email n'est pas valide.");
            }

            // Validation des mots de passe
            if ($_POST['password'] != $_POST['confirm']) {
                // les deux mots de passe ne sont pas identiques
                // alors il y a une erreur :
                $error = true;
                array_push($messages, "Les mots de passe sont différents.");
            }

            // Si il y a une erreur
            if ($error) {
                // on re affiche le formulaire avec les erreurs
                $datas['errors'] = $messages;
            } else {
                // Pas d'erreur
                // On doit enregistrer l'utilisateyr en base de données
                // ==> ATTENTION il peut y avoir une erreur lors de l'insertion en base de données
                try {
                    if ($this->db->saveUser($_POST['login'], $_POST['password'], $_POST['email'])) {
                        // Et redirigé vers la home
                        header('Location: /');
                    } else {
                        throw new Exception("Erreur lors de l'enregistrement de l'utilisateur");
                    }
                } catch (Exception $e) {

                    $datas['errors'] = array("Erreur lors de l'insertion en base : " . $e->getMessage());
                }
            }
        }
        $this->viewer->render('register.twig', $datas);
    }

    public function profile(){
        $datas = array(
            'application' => array(
                'nom' => 'MyCompany'
            ),
            'name' => 'World',
            'current'=> "register"
        );
        if (!empty($_POST)) {
            if (!isset($_POST['message']) || $_POST['message'] == '') {
                $datas['errors'] = array("Le message ne peut être vide ...");
            } else {
                try{
                    $this->db->addMessage($_POST['message']);
                } catch( Exception $e){
                    $datas['errors'] = array($e->getMessage());
                }
            }
        }
        $datas['messages'] = $this->db->getMessagesForCurrentUser();
        $this->viewer->render('profile.twig', $datas);
    }

} 