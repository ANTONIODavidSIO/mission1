<?php
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch ($action) {
    case 'demandeConnexion':
    {
        include("views/v_connexion.php");
        break;
    }
    case 'valideConnexion':
    {
        $login = $_REQUEST['login'];
        $mdp = $_REQUEST['mdp'];
        $visiteur = $pdo->getInfosVisiteur($login, $mdp);
        if (!is_array($visiteur)) {
            ajouterErreur("Login ou mot de passe incorrect");
            include("views/v_erreurs.php");
            include("views/v_connexion.php");
        } else {
            $id = $visiteur['id'];
            $nom = $visiteur['nom'];
            $prenom = $visiteur['prenom'];
            connecter($id, $nom, $prenom);
            
        }
        break;
    }
    default :
    {
        include("views/v_connexion.php");
        break;
    }
}
