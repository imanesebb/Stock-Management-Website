<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: siteweb.html'); 
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projecttst";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $idcomposant = intval($_GET['id']);
    
   
    $sqlProducts = "SELECT p.Idproduit, p.Nomproduit, p.Descriptionproduit, p.Quantitestockpro, p.Seuilalertproduit, p.Prixunitaire, f.nomfournisseur, f.telefournisseur, f.emailfournisseur
                    FROM Produit p
                    JOIN compprod cp ON p.Idproduit = cp.idprod
                    JOIN fournisseur f ON p.idfournisseur = f.idfournisseur
                    WHERE cp.idcomp = ?";
    $stmtProducts = $conn->prepare($sqlProducts);
    $stmtProducts->bind_param("i", $idcomposant);
    $stmtProducts->execute();
    $productsResult = $stmtProducts->get_result();
} else {
    echo "<p>ID du composant non spécifié.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        flex-direction: column;
        transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
    }

    .content.full-width {
        margin-left: 50px;
        width: calc(100% - 50px);
    }

    .content table {
        margin-bottom: 20px; 
       
    }

    .content .return-button {
        margin-top: 20px; 
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
        #myModal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }

        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Produits de Composant</title>
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
    <div class="content">
        <h1>Composants:</h1>
        <?php if ($productsResult->num_rows > 0): ?>
            <table class='table' style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 5%;">ID Composant</th>
                        <th>Nom Composant</th>
                        <th style="width: 20%;">Description du Composant</th>
                        <th>Quantité en Stock</th>
                        <th>Seuil d'Alerte</th>
                        <th>Prix Unitaire</th>
                        <th style="width: 10%;">Nom Fournisseur</th>
                        <th style="width: 15%;">Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($product = $productsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['Idproduit']); ?></td>
                            <td><?php echo htmlspecialchars($product['Nomproduit']); ?></td>
                            <td><?php echo htmlspecialchars($product['Descriptionproduit']); ?></td>
                            <td><?php echo htmlspecialchars($product['Quantitestockpro']); ?></td>
                            <td><?php echo htmlspecialchars($product['Seuilalertproduit']); ?></td>
                            <td><?php echo htmlspecialchars($product['Prixunitaire']); ?> MAD</td>
                            <td><?php echo htmlspecialchars($product['nomfournisseur']); ?></td>
                            <td>
                                <a href="tel:<?php echo htmlspecialchars($product['telefournisseur']); ?>" class="btn btn-outline-primary">Appeler </a>
                                <button class="btn btn-outline-primary" onclick="openModal('<?php echo htmlspecialchars($product['emailfournisseur']); ?>')">Email</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun Composant lié trouvé.</p>
        <?php endif; ?>
       <CENTER> <a href="alert_page.php?id=<?php echo $idcomposant; ?>" class="btn btn-secondary mt-3">Retour</a></CENTER>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">
           
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quantité à Commander</h5>
                    <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="number" id="orderQuantity" class="form-control mb-3" placeholder="Entrer la Quantité..." min="1">
                    <button onclick="sendOrder()" class="btn btn-primary">Passer la Commande</button>
                </div>
            </div>
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
     <script>
     
        function openModal(quantity) {
            document.getElementById("myModal").style.display = "block";
            document.getElementById("orderQuantity").value = quantity;
        }

        
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
            document.getElementById("orderQuantity").value = "";
        }

        function sendOrder() {
            var orderQuantity = document.getElementById("orderQuantity").value;
           
            console.log("Order placed for quantity: " + orderQuantity);
            closeModal();
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
