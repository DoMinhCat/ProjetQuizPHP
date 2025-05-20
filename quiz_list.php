<?php
require('includes/check_session.php');
require('includes/check_timeout.php');
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$title = 'Quiz list';
require('includes/head.php');
require('includes/db.php');
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
                <h1 class="text-center mb-5 mt-3">Nos quiz</h1>
                <?php


                $stmt = $bdd->prepare("SELECT * FROM quiz");
                $stmt->execute();
                $quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <table class="table table-hover table-striped my-3">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Lien pour voir</th>
                            <?php if (isset($_SESSION['role']) && !empty($_SESSION['role']) && ($_SESSION['role'] == 2 || $_SESSION['role'] == 3)) {
                                echo '<th scope="col">Option</th>';
                            } ?>

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($quiz as $key) {
                            echo '<tr>';
                            echo '<th scope="row">' . $key['id'] . '</th>';
                            echo '<td>' . $key['title'] . '</td>';
                            echo '<td><a href="quiz.php?id=' . htmlspecialchars($key['id']) . '">Voir</a></td>';
                            if (isset($_SESSION['role']) && !empty($_SESSION['role']) && ($_SESSION['role'] == 2 || $_SESSION['role'] == 3)) {
                                echo '<td>
                                <a href="update_quiz.php?id=' . htmlspecialchars($key['id']) . '" class="btn btn-primary">Modifier</a>
                                </td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php require('includes/footer.php') ?>
    </main>
</body>

</html>