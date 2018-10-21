<?php



if ($_GET) {
    $name = "upload/" . $_GET['var1'];
    if (file_exists($name)) {
        unlink($name);
    }
}
$files = [];

if(isset($_POST['submit'])) {

    if(count($_FILES['fichier']['name']) > 0){

        for($i=0; $i<count($_FILES['fichier']['name']); $i++) {
            //Get the temp file path
            $tmpFilePath = $_FILES['fichier']['tmp_name'][$i];


            if (filesize($tmpFilePath) > 1000000) {
                // Vérifie si un fichier a bien sélectionné un fichier avant de l'envoyer, sinon retourne un message d'erreur
                echo 'fichier trop volumineux';
            }else{
                if ((mime_content_type($tmpFilePath)=='image/jpeg') or (mime_content_type($tmpFilePath)=='image/gif') or (mime_content_type($tmpFilePath)=='image/png')){

                    $extension = pathinfo($_FILES['fichier']['name'][$i], PATHINFO_EXTENSION);
                    $filePath = "upload/" . "image-" . uniqid() . "." . $extension;


                    (move_uploaded_file($tmpFilePath, $filePath));
                }else{
                    echo 'le fichier n\'est pas au bon format (jpeg, png ou gif)';
                }
            }
        }
    }


}



?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body style="background-color: black; color: aliceblue; text-shadow: 1px 1px 10px azure">
<div class="container">

    <h1>Formulaire d'Upload :</h1> <br />

    <form action="" method="post" enctype="multipart/form-data">

        <input id="fichier" type="file" name="fichier[]" multiple="multiple" />


        <input type="submit" name= "submit" value="submit" />
    </form>

</div>
<?php
//show success message
echo "<h1 class='container'>Uploaded:</h1>";
if(is_array($files)){
    echo "<ul>";
    foreach($files as $file){
        echo "<li class='row'>$file</li>";
    }
    echo "</ul>";
}
//PARTIE AFFICHAGE DES IMAGES IMPORTEES
echo '<div class="container" style="color: black">';
echo '<div class="row">';
//création de chaque "card" qui contiendra l'image, le nom et le bouton pour l'effacer
//crée un objet $it qui contiendra sous forme de tableau toutes les informations sur les photos qui se trouvent dans le fichier upload
$it = new FilesystemIterator(dirname('upload/upload'));
//crée une card pour chaque image trouvée
foreach ($it as $fileinfo) {  // chaque nom d'image trouvé dans le tableau $i est stockée dans $fileinfo
    echo '<div class="col-6">';
    echo '<div class="card" style="box-shadow: 0px 0px 100px aliceblue" style="width: 20rem; margin-top: 10px; margin-left: 10px">';
    echo '<img class="card-img-top" src="' . $fileinfo . '" alt="Card image cap">';  //crée l'url où trouver l'image dont le nom est stocké dans $fileinfo pour pouvoir afficher la vignette
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $fileinfo->getFilename() . '</h5>'; // utilise la méthode getFilename pour récupérer le nom du fichier pour pouvoir l'afficher
    // construit l'adresse url qui place dans la variable 'var1' le nom du fichier qu'il faudra effacer grâce à la méthode GET qu'on a créé au début
    // exemple d'url avec une image photo.jpg : index.php?var1=photo.jpg
    // la méthode get récupérera la variable var1, et saura donc que la photo a effacer est photo.jpg
    echo '<a href="index.php?var1=' . $fileinfo->getFilename() . '" class="btn btn-primary">DELETE</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
echo '</div>';
echo '</div>';
?>


</div>

</body>
</html>