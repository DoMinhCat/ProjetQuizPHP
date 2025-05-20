<?php
require('includes/check_session.php');
require('includes/check_timeout.php');
?>

<!DOCTYPE html>
<html lang="fr">

<?php
$title = 'Quizzez';
include('includes/head.php');
include('includes/db.php');
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
}

$id_quiz = $_GET['id'] ?? null;
if (!$id_quiz) {
    header('location:erreur.php?message=Id du quiz manquant!');
    exit();
}

$stmt = $bdd->prepare("SELECT title FROM quiz WHERE id=:id");
$stmt->execute(['id' => $id_quiz]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$quiz) {
    header('location:erreur.php?message=Quiz introuvable!');
    exit();
}


$stmt = $bdd->prepare("SELECT * FROM questions WHERE id_quiz=:id");
$stmt->execute(['id' => $id_quiz]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<body>

    <?php require 'includes/header.php'; ?>
    <main class="container pb-5 mt-3">
        <div class="container-fluid min-vh-90 d-flex justify-content-center align-items-center bg-body">
            <div class="w-100" style="max-width: 700px;">
                <?php if (!empty($_GET['message']))
                    echo '<div class="alert alert-danger my-5" role="alert">'
                        . htmlspecialchars($_GET['message']) . '</div>';

                if (isset($_GET['score']) && isset($_GET['total']) && !empty($_GET['score']) && !empty($_GET['total']))
                    echo '<div class="alert alert-success my-5" role="alert">';
                elseif (isset($_GET['score']) && empty($_GET['score']) && isset($_GET['total']) && !empty($_GET['total']))
                    echo '<div class="alert alert-danger my-5" role="alert">';
                if (isset($_GET['score']) && isset($_GET['total'])) {
                    echo 'Votre score est de ' . $_GET['score'] . ' sur ' . $_GET['total'] . ' question(s) !';
                    echo '</div>';
                }
                ?>

                <h1 class="text-center my-5"><?= htmlspecialchars($quiz['title']) ?></h1>
                <form action="traitements/processing.php" method="post">
                    <?php
                    if (!isset($questions) || empty($questions)) {
                        echo '<p class="text-center">Ce quiz n\'a aucune question :(</p>';
                        echo '<br>';
                        echo '<p class="text-center">Veuillez jouez à un autre quiz pendant que nous perfectionnons celui-ci.</p>';
                    } else {
                        echo '<input type="hidden" name="quiz_id" value="' . $id_quiz . '">';
                        if (!isset($_GET['show_result']) || !isset($_SESSION['results'])) {
                            foreach ($questions as $question) {
                                echo '<h5 class="question mt-4">Question : ' . htmlspecialchars($question['content']) . '</h5>';

                                $stmt = $bdd->prepare("SELECT * FROM answers WHERE id_question=:id");
                                $stmt->execute(['id' => $question['id']]);
                                $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!isset($answers) || empty($answers)) {
                                    echo '<p>Aucune réponse disponible :(</p>';
                                    echo '<input type="hidden" name="no_answers[]" value="' . $question['id'] . '">';
                                } else {
                                    foreach ($answers as $answer) {
                                        echo '<div class="form-check mt-2">';
                                        echo '<input required class="form-check-input" type="radio" name="question_' . htmlspecialchars($question['id']) . '" value="' . $answer['id'] . '">';
                                        echo '<label class="form-check-label">' . htmlspecialchars($answer['content']) . '</label>';
                                        echo '</div>';
                                    }
                                }
                            }
                            echo '<button type="submit" class="btn btn-primary mt-4">Check it</button>';
                        } else {
                            foreach ($_SESSION['results']['questions'] as $q) {
                                echo '<h5 class="question mt-4">Question : ' . htmlspecialchars($q['question']) . '</h5>';

                                foreach ($q['answers'] as $answer) {
                                    $select_result = '';
                                    $comment = '';

                                    if (!$answer['selected']) {
                                        $class = '';
                                        $comment = '';
                                    }
                                    if ($answer['correct']) {
                                        $class = ' text-success';
                                        $comment = ' (bonne réponse)';
                                    }
                                    if ($answer['selected'] && !$answer['correct']) {
                                        $class = ' text-danger';
                                        $comment = ' (votre réponse)';
                                    }


                                    echo '<div class="form-check mt-2' . $class . '">';
                                    echo '<input disabled class="form-check-input" type="radio"' .  ($answer['selected'] ? 'checked' : '') . '>';
                                    echo '<label class="form-check-label">' . htmlspecialchars($answer['content']) . $comment . '</label>';
                                    echo '</div>';
                                }
                            }
                            echo '<a href="quiz.php?id=' . $id_quiz . '" class="btn btn-primary mt-4">Rejouer</a>';
                        }
                    }
                    ?>
                </form>
                <?php
                if (isset($_GET['showResult'])) {
                    unset($_SESSION['results']);
                }
                ?>
            </div>
        </div>

    </main>
    <?php require('includes/footer.php') ?>
</body>

</html>