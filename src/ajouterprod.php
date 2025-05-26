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

$errors = array(); 

$db = new mysqli('localhost', 'root', '', 'projecttst');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productCount = intval($_POST['productCount']);

 
    $idcomposant = $_SESSION['idcomposant'];

    for ($i = 1; $i <= $productCount; $i++) {
       
        $nom = trim($_POST['nomproduit'.$i]);
        $description = trim($_POST['descriptionproduit'.$i]);
        $seuil = intval($_POST['seuilalertproduit'.$i]);
        $prix = floatval($_POST['prixunitaire'.$i]);
        $quantite = intval($_POST['quantitestockprod'.$i]);
        $idfournisseur = intval($_POST['idfournisseur'.$i]);

        if (empty($nom) || empty($description) || $seuil <= 0 || $prix <= 0 || $quantite <= 0 || $idfournisseur <= 0) {
            array_push($errors, "Tous les champs sont obligatoires et doivent contenir des valeurs valides pour le produit $i");
        } else {
            $stmt_produit = $db->prepare("INSERT INTO produit (Nomproduit, Quantitestockpro, Descriptionproduit, Seuilalertproduit, Prixunitaire, idfournisseur) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_produit->bind_param("sisidi", $nom, $quantite, $description, $seuil, $prix, $idfournisseur);

            if ($stmt_produit->execute()) {
                $idproduit = $stmt_produit->insert_id;
                $stmt_compprod = $db->prepare("INSERT INTO compprod (idcomp, idprod) VALUES (?, ?)");
                $stmt_compprod->bind_param("ii", $idcomposant, $idproduit);

                if (!$stmt_compprod->execute()) {
                    array_push($errors, "Erreur lors de l'ajout du produit $i dans la table compprod : " . $stmt_compprod->error);
                }
                $stmt_compprod->close();
            } else {
                array_push($errors, "Erreur lors de l'ajout du produit $i : " . $stmt_produit->error);
            }
            $stmt_produit->close();
        }
    }

    if (count($errors) == 0) {
        $_SESSION['success'] = "Tous les produits ont été ajoutés avec succès";
        header('Location: admin.php');
        exit();
    }
}
$db->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
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
            border-radius: 5px;
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
    <br>
    <div class="content" >
    <div class="container" >
        <h1>Ajouter un Composant</h1><br>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="ajouterprod.php" name="produit" method="post">
            <div class="form-group">
                <?php
                $idcomp = isset($_SESSION['idcomposant']) ? $_SESSION['idcomposant'] : '';
                echo '<input type="text" placeholder="ID du Produit" name="idcomp" class="form-control" value="' . htmlspecialchars($idcomp) . '" readonly>';
                ?>
            </div>
            <div class="form-group">
                <label for="productCount">Nombre de Composants à Ajouter:</label>
                <input type="number" id="productCount" name="productCount" min="1" class="form-control" required>
            </div>
            <div id="productFields"></div>
            <div class="form-btn">
                <input type="submit" value="Enregistrer les Composants" name="ajoute" class="btn btn-primary">
            </div></div>
            <a href="admin.php" class="btn btn-secondary mt-3">Retour à l'Accueil</a>
        </form>
    </div>
    <br>

    <script>
        function generateProductFields() {
            var productCount = document.getElementById('productCount').value;
            var productFieldsContainer = document.getElementById('productFields');
            productFieldsContainer.innerHTML = '';

            for (var i = 1; i <= productCount; i++) {
                var productFieldHTML = `
                    <h4>Composant ${i}</h4>
                    <div class="form-group">
                        <input type="text" placeholder="Nom du composant ${i}" name="nomproduit${i}" maxlength="100" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Description du composant ${i}" name="descriptionproduit${i}" maxlength="500" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="number" placeholder="Quantité en stock ${i}" name="quantitestockprod${i}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="number" placeholder="Seuil d'alerte ${i}" name="seuilalertproduit${i}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="number" step="0.01" placeholder="Prix unitaire ${i}" name="prixunitaire${i}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="number" placeholder="ID du fournisseur ${i}" name="idfournisseur${i}" class="form-control" required>
                    </div>
                    <hr>
                `;
                productFieldsContainer.innerHTML += productFieldHTML;
            }
        }
        
        document.getElementById('productCount').addEventListener('change', generateProductFields);
    </script>
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
