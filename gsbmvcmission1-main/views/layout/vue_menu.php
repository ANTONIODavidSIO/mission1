<nav class="menuLeft">
    <ul class="menu-ul">
    Visiteur :<br>
           <?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
           <br></br>
        
        <li class="menu-item">
              <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
              <a href="index.php?uc=etatFrais&action=fraisMois">Etat des frais</a>
            </li>
         
 	   <li class="menu-item">
        <li class="menu-item"><a href="index.php">Retour</a></li>
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
        
           </li>
           
    </ul>
</nav>
<section class="content">
