<?php
require_once '../vendor/autoload.php';

/**
 * Class Viewer
 */
class Viewer {

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * Creation de la classe
     */
    public function __construct(){
        $loader = new Twig_Loader_Filesystem('../application/views');
        $this->twig = new Twig_Environment($loader);
    }

    /**
     * Affichage du template avec les données
     *
     * @param $template
     * @param $datas
     */
    public function render($template, $datas){

        $user = null;
        if( isset($_SESSION['user']) && !is_null($_SESSION['user']) ){
            $user = $_SESSION['user'];

        }
        $datas['user'] = $user;

        echo $this->twig->render($template, $datas);
    }
}