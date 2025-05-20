<?php
require('includes/check_session.php');
require('includes/check_timeout.php');
require('includes/db.php');
$id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="fr">
<?php
$title = 'Résultats';
require('includes/head.php');
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
}
?>

<body>
    <?php
    require 'includes/header.php';
    ?>
    <main class="container pb-5 mt-3">
        <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
            <div class="card shadow-lg p-5" style="width: 100%; max-width: 70vw; border-radius: 15px;">
                <h1 class="text-center mb-5">Mes résultats</h1>
                <?php


                $stmt = $bdd->prepare("SELECT * FROM results WHERE id_user=:id");
                $stmt->execute(['id' => $id]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($results) {
                ?>

                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Quiz</th>
                                <th scope="col">Score</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($results as $key) {
                                echo '<tr>';
                                echo '<th scope="row">' . $key['id'] . '</th>';
                                $stmt = $bdd->prepare("SELECT title FROM quiz WHERE id=:id");
                                $stmt->execute(['id' => $key['id_quiz']]);
                                $title = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo '<td>' . $title['title'] . '</td>';
                                echo '<td>' . $key['score'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else
                    echo '<p class="text-center">Aucun résultat disponible. Jouez à un quiz pour voir vos résultats</p>'; ?>
            </div>
        </div>
        <?php require('includes/footer.php') ?>
    </main>
</body>

</html>