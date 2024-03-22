<!DOCTYPE html>
<?php
$d=0;

	// Create connection to database
	$serveur="localhost";
	$user="root";
	$password="";
	$nomBDD="tpphp11";
	$conn = new mysqli($serveur, $user , $password, $nomBDD);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

    if(isset($_POST["prixArticle"]))
    {
        $sql="INSERT INTO `article` ( `nomArticle` ,`prix`) 
        VALUES ( '".$_POST["nomArticle"]."' , '".$_POST["prixArticle"]."' );" ; 
        if($d==1)
        echo $sql; 
        $conn->query($sql);
    }

    if (isset($_GET["idannule"]))
    {
        $sql="DELETE FROM `achat` WHERE `achat`.`id` =".$_GET["idannule"].";";
        $conn->query($sql);
    }   
    function getLastIdcommande($conn)
    {
    $sql="SELECT `idCommande` FROM `achat` ORDER BY `achat`.`idCommande` DESC LIMIT 1;";
    $id = $conn->query($sql);
    $row= $id->fetch_assoc(); 
    return $row["idCommande"];
    }
    if(isset($_GET["idcommande"]))
    $idcommande=$_GET["idcommande"];
    else
    $idcommande= getLastIdcommande($conn);


    function getArticleName($id,$conn)
	{
	$sql="SELECT `nomArticle` FROM `article` WHERE `id` = ".$id.";";
	$articleName = $conn->query($sql);
	$row= $articleName->fetch_assoc(); 
	return $row["nomArticle"];
	}
	
	function getArticlePrice($id,$conn)
	{
	$sql="SELECT `prix` FROM `article` WHERE `id` = ".$id.";";
	$articlePrice = $conn->query($sql);
	$row= $articlePrice->fetch_assoc(); 
	return $row["prix"];
	}
	
	if(isset($_POST["codeArticle"]))
	{		
	$idArticle=$_POST["codeArticle"];
	$quantite=$_POST["quantite"];
	$sql="INSERT INTO `achat` ( `idCommande` ,`idArticle`,`nomArticle` , `prixArticle` , `quantite`) 
	VALUES ('".$idcommande."', '".$idArticle."',  '".getArticleName($idArticle,$conn)."', '".getArticlePrice($idArticle,$conn)."', '".$quantite."');" ; 
	if($d==1)
    echo $sql; 
	$conn->query($sql);
	}
	

	?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>caisse</title>

        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

<!-- Core CSS -->
<link rel="stylesheet" href="bts/assets/vendor/css/core.css" class="template-customizer-core-css" />
<link rel="stylesheet" href="bts/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
<link rel="stylesheet" href="bts/assets/css/demo.css" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="bts/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

<!-- Page CSS -->

<!-- Helpers -->
<script src="bts/assets/vendor/js/helpers.js"></script>

<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="bts/assets/js/config.js"></script>


</head>
<body>
<h1 class=" text text-center  text-dark"><p>caisse</p></h1> 

<div class= "container">

   <div class="row">

       <div class="col-md-12">

          <div class="card mb-4">

          <form action="index.php" method="GET">

<select class="form-select" name="idcommande" id="idcommande" aria-label="Default select example">
 
<?php
$sql = "SELECT distinct idCommande FROM `achat` ";
$listAchat = $conn->query($sql);
$somme=0;
if ($listAchat->num_rows > 0) {
// output data of each row
    while($row = $listAchat->fetch_assoc()) {
    echo '<option value="'.$row["idCommande"].'"> Commande :'.$row["idCommande"].'</option>';

    }
}
?>
</select>
<input type="submit" class="btn btn-success" value="valider" /> 

</form>

<a class="text text-success"    href="index.php?idcommande=<?php echo $idcommande+1; ?> "> Nouvelle commande</a>
<p class="text text-success">Ajouter une commande:</p> 
<form action="#" method="post">

<input  type="number" class="form-control" name="codeArticle" id="codeArticle" placeholder="code article" aria-describedby="defaultFormControlHelp" required>
<input type="number" class="form-control"  name="quantite" id="quantite"  placeholder="quantité" aria-describedby="defaultFormControlHelp" required>
<input type="submit" class="btn btn-success" value="valider" /> 
</form>

          </div>

       </div> 
       
</div>  


<div class="row">
       <div class="col-md-12">
            <div class="card mb-4">
  
            <form action="index.php" method="POST" >
                <div>
                Article:<input type="text" name="nomArticle" id="nomArticle" class="form-control"/> 
                </div>

                <div>
                Prix:<input type="text" name="prixArticle" id="prixArticle"  class="form-control"/>
                </div>
            <input type="submit" value="valider" class="btn btn-success"/> </p>
    </form>

            </div>
      </div>

    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">

            <?php
	
	echo "<h2 class='text text-center  text'> liste des achats de la commande:".$idcommande."</h2>";
	
	echo '<table border=1 class="table table-hover"  table-striped-columns">';
	
	echo '<tr  > <td><h2>Action</h2></td> <td><h2>image article</h2></td> <td><h2>nom Article</h2></td> <td><h2>quantité</h2></td> <td><h2>Prix</h2></td> </tr> ';

	$sql = "SELECT * FROM `achat` WHERE `idCommande` =".$idcommande."  ";
	$listAchat = $conn->query($sql);
	$somme=0;
	if ($listAchat->num_rows > 0) {
  // output data of each row
		while($row = $listAchat->fetch_assoc()) {
		
	$somme= $somme + $row["quantite"] * $row["prixArticle"]; 
	echo "<tr> <td><a href=index.php?idcommande=".$idcommande."&idannule=".$row["id"].">annuler</a></td> <td><img width=30%  src=images/".$row["idArticle"].".jpg /></td> <td><h2>".$row["nomArticle"]."</h2></td> <td><h2>".$row["quantite"]."</h2></td> <td><h2>".$row["prixArticle"]."</h2></td></tr> ";

		}
	}
	
	echo "<tr> <td></td> <td></td> <td><h2> Prix Total</h2></td> <td><h2>".$somme."</h2></td> </tr> ";

	echo "</table>";
	
	?>

            </div>
        </div>

<div class="col-md-6">
    <div class="card mb-4">
    <table border=1 class="table table-hover" >
           <tr ><td><h2>ID</h2></td> <td><h2>image article</h2></td> <td><h2>nom Article</h2></td> <td><h2>Prix</h2></td> </tr>

           <?php
            	$sql = "SELECT * FROM `article`";
                $listArticle = $conn->query($sql);
                if($d==1)
                echo $sql;
                if ($listArticle->num_rows > 0) {
              // output data of each row
                    while($row = $listArticle->fetch_assoc()) {
                    
                    
                echo "<tr> <td><h2>".$row["id"]."</h2></td> <td><img width=30% src=images/".$row["id"].".jpg /></td> <td><h2>".$row["nomArticle"]."</h2></td> <td><h2>".$row["prix"]."</h2></td> </tr> ";
            
                    }
                }
           ?>
           </table>
        </div>
     </div>
  </div>
</div>


<div class="container">

      <div class="row">

         <div class="col-md-12">

              <div class="card mb-4">

              <form action="#" method="post">
	 <p> paiement : <input type="number"  class="form-control" name="paiement" id="paiement" /> 
	 <input type="submit" value="valider"  class="btn btn-success"/> </p>
	</form>

              <?php
	$pieces = array(200, 100, 50, 20,10,5,2,1);
	$paiement=0;
	if(isset($_POST["paiement"]))
	$paiement=$_POST["paiement"];
	{
		if($paiement<$somme)
		echo " ajouter d'autre pieces";
		else
		{
		$rest=$paiement-$somme;
		echo $rest."Dh =";
		
		
			for ($j=0 ; $j<count($pieces) ; $j++)
			{
				if($pieces[$j]<=$rest)
				{
					$rest=$rest-$pieces[$j];
					echo $pieces[$j]."Dh -";
					$j=$j-1;
				}
				
			}
		
		}
	}
	
    ?>
             </div>
		 
        </div>
	      
    </div>
	
  </div>

</body>
</html>

	





