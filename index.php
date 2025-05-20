<?php
session_start();
require('includes/check_timeout.php');
require('includes/db.php');
?>
<!DOCTYPE html>
<html lang="fr">

<?php
$title = 'Acceuil';
require('includes/head.php'); ?>
<script src="includes/check_timeout.js"></script>

<body>
    <?php include 'includes/header.php';

    if (isset($_GET['error']) && !empty($_GET['error'])) { ?>
        <div class="alert alert-danger text-center" role="alert">
        <?php
        echo  htmlspecialchars($_GET['error']);
    }

    if (isset($_GET['succes']) && !empty($_GET['succes'])) { ?>
            <div class="alert alert-success text-center" role="alert">
            <?php
            echo htmlspecialchars($_GET['succes']);
            echo '</div>';
        }

        if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
            echo '<div class="container text-center mt-3 mb-0">';
            echo '<h3 class="mt-4">Bienviennue ' . $_SESSION['pseudo'] . '!</h3>';
            echo '</div>';
        } ?>
            </div>


            <div class="container text-center">
                <h1 class="fw-bold my-5">Devenez un pro de la culture générale !</h1>
                <p class="lead my-4">Maîtrisez tous les sujets grâce à nos quiz éducatifs mis à jour régulièrement !</p>

                <div class="my-4">
                    <?php if (isset($_SESSION['email']) && !empty($_SESSION['email'])) { ?>
                        <a href="quiz_list.php" class="btn btn-primary btn-lg my-5">Voir nos quiz !</a>
                    <?php } else { ?>
                        <a href="signin.php" class="btn btn-outline-primary btn-lg me-4 mt-4 mb-5">Se connecter</a>
                        <a href="signup.php" class="btn btn-primary btn-lg mt-4 mb-5">Créer un compte</a>
                    <?php } ?>
                </div>

                <div class="row mt-5 mb-5">
                    <div class="col-md-3 mb-3">
                        <div class="card shadow-lg border-0 rounded-4" style="background-color: #B3E5FC;">
                            <div class="card-body" style="color: #000;">
                                <h5 class="card-title fw-bold">Apprendre l'anglais</h5>
                                <p class="card-text">Améliorez votre vocabulaire et votre grammaire avec nos quiz interactifs.</p>
                                <img src="img/anglais.jpeg" class="img-fluid" alt="Anglais">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card shadow-lg border-0 rounded-4" style="background-color: #F8BBD0;">
                            <div class="card-body" style="color: #000;">
                                <h5 class="card-title fw-bold">Découvre l'histoire</h5>
                                <p class="card-text">Testez vos connaissances sur les grandes périodes historiques.</p>
                                <img src="img/histoire.jpeg" class="img-fluid" alt="Histoire">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card shadow-lg border-0 rounded-4" style="background-color: #D1C4E9;">
                            <div class="card-body" style="color: #000;">
                                <h5 class="card-title fw-bold">Sciences</h5>
                                <p class="card-text">Explorez le monde des sciences avec nos quiz captivants.</p>
                                <img src="img/science.jpeg" class="img-fluid" alt="Sciences">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card shadow-lg border-0 rounded-4" style="background-color: #FFCC80;">
                            <div class="card-body" style="color: #000;">
                                <h5 class="card-title fw-bold">Mathématiques</h5>
                                <p class="card-text">Mettez vos compétences en maths à l'épreuve avec nos défis.</p>
                                <img src="img/math.avif" class="img-fluid" alt="Maths">
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>

                <?php
                $stmt = $bdd->prepare("SELECT username, SUM(score) as total_score FROM results JOIN users ON results.id_user=users.id GROUP BY id_user ORDER BY total_score DESC LIMIT 5");
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <h2 class="mt-4 my-5">Prenez la tête du Leaderboard </h2>

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <table class="table table-striped table-hover mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Rang</th>
                                    <th>Joueur</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rank = 1;
                                foreach ($results as $row) {
                                    echo '<tr>';
                                    echo '<td>' . $rank . '</td>';
                                    echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                                    echo '<td>' . $row['total_score'] . '</td>';
                                    echo '</tr>';
                                    $rank++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php require('includes/footer.php'); ?>
</body>

</html>