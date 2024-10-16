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


$sql = "SELECT 
            cc.Idcommandecli,
            cc.Datecommandecli,
            cc.Montantcommandecli,
            c.Nomclient,
            comp.Nomcomposant,
            lc.Quantitecomposant
        FROM 
            commandeclient cc
        INNER JOIN 
            client c ON cc.Idclient = c.Idclient
        INNER JOIN 
            Lignecommandeclient lc ON cc.Idcommandecli = lc.Idcommandecli
        INNER JOIN 
            Composant comp ON lc.Idcomposant = comp.Idcomposant
        ORDER BY 
            cc.Datecommandecli DESC, cc.Idcommandecli, lc.Idlignecommandecli";

$result = $conn->query($sql);
$orders = [];

while ($row = $result->fetch_assoc()) {
    $order_id = $row['Idcommandecli'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'Datecommandecli' => $row['Datecommandecli'],
            'Montantcommandecli' => $row['Montantcommandecli'],
            'Nomclient' => $row['Nomclient'],
            'composants' => []
        ];
    }
    $orders[$order_id]['composants'][] = [
        'Nomcomposant' => $row['Nomcomposant'],
        'Quantitecomposant' => $row['Quantitecomposant']
    ];
}
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
        <h2>Détails des Commandes Clients:</h2>
        <br><br>
        <div class="table-container">
            <table class='table'>
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Date Commande</th>
                    <th scope="col">Client</th>
                    <th scope="col">Nom Produit</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Montant</th>
                </tr>
               </thead>

                <?php
                $row_count = 0;
                foreach ($orders as $order_id => $order) {
                    $composants = $order['composants'];
                    $row_span = count($composants);
                    for ($i = 0; $i < $row_span; $i++) {
                        echo "<tr class='" . ($row_count >= 4 ? 'hidden-row' : '') . "'>";
                        if ($i === 0) {
                            echo "<td rowspan='$row_span'>" . htmlspecialchars($order['Datecommandecli']) . "</td>";
                            echo "<td rowspan='$row_span'>" . htmlspecialchars($order['Nomclient']) . "</td>";
                        }
                        echo "<td>" . htmlspecialchars($composants[$i]['Nomcomposant']) . "</td>";
                        echo "<td>" . htmlspecialchars($composants[$i]['Quantitecomposant']) . "</td>";
                        if ($i === 0) {
                            echo "<td rowspan='$row_span'>" . htmlspecialchars($order['Montantcommandecli']) . " MAD</td>";
                        }
                        echo "</tr>";
                    }
                    $row_count++;
                }
                ?>
            </table>
        </div>
        <div class="bottom-button">
            <button id="show-more-btn" class="btn btn-primary" onclick="showMore()">Afficher plus</button>
            <button id="show-less-btn" onclick="showLess()" class="btn btn-primary" style="display:none;">Afficher moins</button>
        </div>
        <br>
        <a href="admin.php"><button class="btn btn-secondary">Retour à l'Acceuil</button></a> 
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
