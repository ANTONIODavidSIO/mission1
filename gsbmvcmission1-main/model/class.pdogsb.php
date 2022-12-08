<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsbfrais';   		
      	private static $user='root' ;    		
      	private static $mdp='' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
    /**
     * Retourne les informations d'un visiteur

     * @param $login
     * @param $mdp
     * @return l'id, le nom et le pr�nom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login, $mdp){
        $sql = "SELECT id, nom, prenom from visiteur where login= :login and mdp= :mdp";
        $req = PdoGsb::$monPdo->prepare($sql);
        $req -> bindParam(':login', $login);
        $req -> bindParam(':mdp', $mdp);
        $req -> execute();
        $ligne = $req->fetch();
        return $ligne;
    }
    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
     * concern�es par les deux arguments

     * La boucle foreach ne peut �tre utilis�e ici car on proc�de
     * � une modification de la structure it�r�e - transformation du champ date-

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur,$mois){
        $sql = "SELECT * from lignefraishorsforfait where idvisiteur =:idVisiteur and mois = :mois ";
        $req = PdoGsb::$monPdo->prepare($sql);
        $req -> bindParam(':idVisiteur', $idVisiteur);
        $req -> bindParam(':mois', $mois);
        $req -> execute();
        $lesLignes = $req->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i=0; $i<$nbLignes; $i++){
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    public function getTypeFrais()
    {
        $sql = "SELECT id FROM fraisforfait";
        // $lesFrais =array();
        $req = PdoGsb::$monPdo->query($sql);
        $lesLignes = $req->fetchAll();
        
        return $lesLignes;
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais

     * @param $idVisiteur
     * @return un tableau associatif de cl� un mois -aaaamm- et de valeurs l'ann�e et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur){
        $sql = "SELECT mois from fichefrais where idvisiteur =:idVisiteur order by mois desc ";
        $req = PdoGsb::$monPdo->prepare($sql);
        $req -> bindParam(':idVisiteur', $idVisiteur);
        $req->execute();
        $lesMois = array();
        $laLigne = $req->fetch();
        while($laLigne != null)	{
            $mois = $laLigne['mois'];
            $numAnnee =substr( $mois,0,4);
            $numMois =substr( $mois,4,2);
            $lesMois["$mois"]=array(
                "mois"=>"$mois",
                "numAnnee"  => "$numAnnee",
                "numMois"  => "$numMois"
            );
            $laLigne = $req->fetch();
        }
        return $lesMois;
    }
    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donn�

     * @param $idVisiteur
     * @param $mois sous la forme aaaamm
     * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'�tat
     */
    public function getLesInfosFicheFrais($idVisiteur,$mois){
        $sql = "SELECT fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
			where fichefrais.idVisiteur = :idVisiteur and fichefrais.mois = :mois";
        $req = PdoGsb::$monPdo->prepare($sql);
        $req -> bindParam(':idVisiteur', $idVisiteur);
        $req -> bindParam(':mois', $mois);
        $req -> execute();
        $laLigne = $req->fetch();
        return $laLigne;
    }

    public function getInfosTypeFrais() {
        $sql = "SELECT id, montant from fraisforfait";
        $req = PdoGsb::$monPdo->query($sql);
        $lesLignes = $req->fetchAll();
        return $lesLignes;
    }


    public function getIdVisiteurEtMontant($mois, $type)
    {
        $sql = "SELECT idVisiteur, (montant*quantite) as Total FROM lignefraisforfait INNER JOIN fraisforfait ON id = idFraisForfait WHERE mois = :mois AND idFraisForfait = :type ";
        $req = PdoGsb::$monPdo->prepare($sql);
        $req -> bindParam(':mois', $mois);
        $req -> bindParam(':type', $type);
        $req -> execute();
        $lesLignes = $req->fetchAll();
        return $lesLignes;
    }
}

