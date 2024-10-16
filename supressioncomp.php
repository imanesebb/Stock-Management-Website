<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: siteweb.html'); 
    exit();
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: siteweb.html');
    exit();
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$errors = array(); 

$db = mysqli_connect('localhost', 'root', '', 'projecttst');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['supprimer'])) {
    $typemod = mysqli_real_escape_string($db, $_POST['typemod']);
    $idsupp = mysqli_real_escape_string($db, $_POST['idsupp']);
    
   
    if (empty($typemod) || empty($idsupp)) {
        array_push($errors, "Tous les champs sont obligatoires");
    }

    if (count($errors) == 0) {
   
        if ($typemod == 'produit') {
            $query_check = "SELECT * FROM produit WHERE idproduit = '$idsupp'";
        } elseif ($typemod == 'composant') {
            $query_check = "SELECT * FROM composant WHERE idcomposant = '$idsupp'";
        } else {
            array_push($errors, "Type de modification invalide");
        }
        
        $result_check = mysqli_query($db, $query_check);
        if (mysqli_num_rows($result_check) == 0) {
            array_push($errors, "L'ID fourni n'existe pas dans la table sélectionnée");
        } else {
            
            if ($typemod == 'produit') {
   
                $query_delete_compprod = "DELETE FROM compprod WHERE idprod = '$idsupp'";
                if (mysqli_query($db, $query_delete_compprod)) {
                 
                    $query_delete = "DELETE FROM produit WHERE idproduit = '$idsupp'";
                    if (mysqli_query($db, $query_delete)) {
                        $_SESSION['success'] = "Produit supprimé avec succès";
                        header('Location: admin.html');
                        exit();
                    } else {
                        array_push($errors, "Erreur lors de la suppression du produit : " . mysqli_error($db));
                    }
                } else {
                    array_push($errors, "Erreur lors de la suppression des enregistrements compprod : " . mysqli_error($db));
                }
            } elseif ($typemod == 'composant') {
              
                $query_delete_lignecommandeclient = "DELETE FROM lignecommandeclient WHERE idcomposant = '$idsupp'";
                if (mysqli_query($db, $query_delete_lignecommandeclient))  {
                   
                        $query_delete_compprod = "DELETE FROM compprod WHERE idcomp = '$idsupp'";
                        if (mysqli_query($db, $query_delete_compprod)) {
                       
                            $query_delete = "DELETE FROM composant WHERE idcomposant = '$idsupp'";
                            if (mysqli_query($db, $query_delete)) {
                                $_SESSION['success'] = "Composant supprimé avec succès";
                                header('Location: admin.php');
                                exit();
                            } else {
                                array_push($errors, "Erreur lors de la suppression du composant : " . mysqli_error($db));
                            }
                        } else {
                            array_push($errors, "Erreur lors de la suppression des enregistrements compprod : " . mysqli_error($db));
                        }
                    } 
                } else {
                    array_push($errors, "Erreur lors de la suppression des enregistrements lignecommandeclient : " . mysqli_error($db));
                }
            }
        
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
} ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression d'information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <script type="text/javascript" src="header.js"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

        .wrapper {
            width: 250px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .wrapper.hidden {
            transform: translateX(-200px);
        }

        .wrapper.hidden .icon-only {
            display: block;
        }

        .wrapper.hidden .full-text {
            display: none;
        }

        .icon-only {
            display: none;
        }

        .toggle-button {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
            background-color: #1668e4;
            border: none;
            border-radius: 2px;
            padding: 10px 15px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            font-size: 16px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
        }

        .content.full-width {
            margin-left: 50px;
            width: calc(100% - 50px);
        }

        .wrapper h1 {
            font-size: 24px;
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        .wrapper .btn {
            width: 100%;
            height: 50px;
            background: #fff;
            border: none;
            outline: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            font-size: 16px;
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrapper .btn i {
            margin-right: 10px;
        }

        .wrapper .btnl {
            width: 80%;
            height: 50px;
            background: #1668e4;
            border: none;
            outline: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <button class="toggle-button">≡</button>
    <div class="wrapper"><br><br>
        <h1>Gestion de stock</h1>
        <a href="ajoutercomp.php">
            <button class="btn"><i class='bx bx-add-to-queue icon-only'></i><span class="full-text"> Ajouter un Produit</span></button>
        </a>
        <a href="supressioncomp.php">
            <button class="btn"><i class='bx bx-trash icon-only'></i><span class="full-text"> Supprimer un élément</span></button>
        </a>
        <a href="modificationcomp.php">
            <button class="btn"><i class='bx bx-edit-alt icon-only'></i><span class="full-text"> Modifier un Produit</span></button>
        </a>
        <a href="modificationprod.php">
            <button class="btn"><i class='bx bx-edit-alt icon-only'></i><span class="full-text"> Modifier un Composant</span></button>
        </a>
        <a href="commandeclient.php">
            <button class="btn"><i class='bx bx-add-to-queue icon-only'></i><span class="full-text"> Afficher tout les commandes</span></button>
        </a>
        <a href="composantdetail.php">
            <button class="btn"><i class='bx bx-add-to-queue icon-only'></i><span class="full-text"> Gérer les stocks</span></button>
        </a>
        <form method="post" action="">
            <button type="submit" name="logout" class="btnl"><i class='bx bx-log-out icon-only'></i><span class="full-text">Déconnexion</span></button>
        </form>
    </div>
    <div class="content"><br>
        <div class="container">
            <h1>Suppression</h1> <br>
            <form action="supressioncomp.php" method="post">
                <div class="form-group">
                    <label for="type de modification">Supprimer un :</label>
                    <select class="form-select" name="typemod" id="typemod">
                        <option value="produit">Composant</option>
                        <option value="composant">Produit</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="ID" name="idsupp" class="form-control">
                </div>
                <div class="form-btn" style="text-align:center">
                    <input type="submit" value="Supprimer" name="supprimer" class="btn btn-primary">
                </div>
                <a href="admin.php" style="text-align:center" class="btn btn-secondary mt-3">Retour à l'Accueil</a>
            </form>
        </div>
    </div>
    <script>
        const toggleButton = document.querySelector('.toggle-button');
        const wrapper = document.querySelector('.wrapper');
        const content = document.querySelector('.content');

        toggleButton.addEventListener('click', () => {
            wrapper.classList.toggle('hidden');
            content.classList.toggle('full-width');
        });
    </script>
</body>
</html>
