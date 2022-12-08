<h2> Voici la liste des frais par mois </h2>
  <table class="listeLegere">
          <tr>
             <th class="idVisiteur">IdVisiteur</th>
             <th class='montant'>Montant</th>                
          </tr>
     <?php      
       foreach ( $listLignesFraisForfait as $unFraisHorsForfait ) 
       {
         $idVisiteur = $unFraisHorsForfait['idVisiteur'];
         $montant = $unFraisHorsForfait['Total'];
     ?>
          <tr>
             <td><?php echo $idVisiteur ?></td>
             <td><?php echo $montant ?></td>
          </tr>
     <?php 
       }
     ?>
 </table>
</div>

