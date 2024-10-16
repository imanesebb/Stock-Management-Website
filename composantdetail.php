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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projecttst";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$composantSql = "SELECT Nomcomposant, QuantitestockComp, Seuilalertcomp, Idcomposant FROM Composant";
$composantResult = $conn->query($composantSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Details des Commandes Clients</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .content h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-container {
            flex: 1;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .table {
            width: 100%;
            max-width: 1000px;
        }

        .bottom-button {
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <script>
    function showMore() {
        var hiddenRows = document.querySelectorAll('.hidden-row');
        hiddenRows.forEach(function(row) {
            row.style.display = 'table-row';
        });
        document.getElementById('show-more-btn').style.display = 'none';
        document.getElementById('show-less-btn').style.display = 'block';
    }

    function showLess() {
        var hiddenRows = document.querySelectorAll('.hidden-row');
        hiddenRows.forEach(function(row) {
            row.style.display = 'none';
        });
        document.getElementById('show-more-btn').style.display = 'block';
        document.getElementById('show-less-btn').style.display = 'none';
    }
    </script>
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
        <h2 style="text-align:center;">Details Des Produits</h2>
        <div class="table-container">
            <table class='table'>
                <tr>
                    <th>Nom Du Produit:</th>
                    <th>Quantité en Stock</th>
                </tr>
                <?php
                if ($composantResult->num_rows > 0) {
                    while($row = $composantResult->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($row["Nomcomposant"]) . "</td><td>";
                        if ($row["QuantitestockComp"] <= $row["Seuilalertcomp"]) {
                            echo "<button class='btn btn-danger' style='width: 100%' onclick=\"location.href='alert_page.php?id=" . htmlspecialchars($row["Idcomposant"]) . "'\">" . htmlspecialchars($row["QuantitestockComp"]) . "</button>";
                        } else {
                            echo "<button class='btn btn-outline-primary' style='width: 100%' onclick=\"location.href='alert_page.php?id=" . htmlspecialchars($row["Idcomposant"]) . "'\">" . htmlspecialchars($row["QuantitestockComp"]) . "</button>";
                        }
                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No results found</td></tr>";
                }
                ?>
            </table>
        </div>
        <div class="bottom-button">
           <a href="admin.php"><button class="btn btn-secondary">Retour à l'Acceuil</button></a> 
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

<?php
$conn->close();
?>
