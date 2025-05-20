<?php
session_start();
require('includes/db.php');

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $id = $_SESSION['id'];

    try {
        $stmt = $bdd->prepare("DELETE FROM results WHERE id_user = :id");
        $stmt->execute(['id' => $id]);

        $stmt = $bdd->prepare("SELECT id FROM quiz WHERE id_user = :id");
        $stmt->execute(['id' => $id]);
        $quizz = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($quizz as $quiz) {
            $quiz_id = $quiz['id'];

            $stmt = $bdd->prepare("DELETE FROM results WHERE id_quiz = :id");
            $stmt->execute(['id' => $quiz_id]);

            $stmt = $bdd->prepare("SELECT id FROM questions WHERE id_quiz = :id");
            $stmt->execute(['id' => $quiz_id]);
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($questions as $question) {
                $question_id = $question['id'];

                $stmt = $bdd->prepare("DELETE FROM answers WHERE id_question = :id");
                $stmt->execute(['id' => $question_id]);
            }
            $stmt = $bdd->prepare("DELETE FROM questions WHERE id_quiz = :id");
            $stmt->execute(['id' => $quiz_id]);

            $stmt = $bdd->prepare("DELETE FROM quiz WHERE id_user = :id");
            $stmt->execute(['id' => $id]);
        }
        $stmt = $bdd->prepare("DELETE FROM users WHERE id = ?");
        $success = $stmt->execute([$id]);



        if ($success) {
            header("Location: manage_account.php?succes=" . urlencode('Compte supprimé'));
            exit();
        } else {
            header("Location: manage_account.php?error=" . urlencode('Erreur lors de la suppresion'));
            exit();
        }
    } catch (Exception $e) {
        header("Location: manage_account.php?error=Erreur lors de la suppresion&msg=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: manage_account.php?error=invalid_id");
    exit();
}
