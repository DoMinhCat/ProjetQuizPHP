<?php
session_start();
include('includes/db.php');
require('includes/check_timeout.php');
if (!isset($_SESSION['role']) || empty($_SESSION['role']) || ($_SESSION['role'] != 2 && $_SESSION['role'] != 3)) {
    header('location:erreur.php?message=Vous n\'avez pas le droit pour accéder à cette page !');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<?php
$title = 'GESTION DE COMPTE';
require('includes/head.php');
if (isset($_SESSION['email']) || !empty($_SESSION['email'])) {
    echo '<script src="includes/check_timeout.js"></script>';
} ?>
<style>
    html,
    body {
        overflow-x: hidden;
        width: 100%;
        max-width: 100vw;
        max-height: 100vh !important;
        display: flex;
        flex-direction: column;
        margin: 0;
    }

    .contenue {
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
        overflow-x: auto;
    }

    h1 {
        margin: auto;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .table-container table {
        width: 100%;
        min-width: 600px;
        border-collapse: collapse;
    }

    .navbar {
        background-color: #e1e1e1 !important;
    }

    .nav-link,
    .navbar-brand {
        color: rgb(42, 42, 42);
    }

    .nav-link:hover,
    .navbar-brand:hover {
        color: rgb(106, 106, 106);
    }

    .card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }

    :root[data-bs-theme="dark"] {
        .navbar {
            background-color: dimgray !important;
        }

        .nav-link,
        .navbar-brand {
            color: white;
        }

        .nav-link:hover,
        .navbar-brand:hover {
            color: rgb(220, 220, 220);
        }

        input::placeholder {
            color: #b8b8b8;
        }

        .btn-outline-primary {
            color: white !important;
        }
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 768px) {
        .contenue {
            flex-direction: column;
            align-items: flex-start;
        }

        .table-container {
            overflow-x: scroll;
        }

        .table-container table {
            min-width: 100%;
        }
    }
</style>

<body>
    <?php include('includes/header.php'); ?>
    <main class="container pb-5 mt-3">
        <div class="container py-4 contenue">
            <?php
            if (isset($_GET['error']) && !empty($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                <?php
                echo htmlspecialchars($_GET['error']);
            }
                ?>
                <?php
                if (isset($_GET['succes']) && !empty($_GET['succes'])) { ?>
                    <div class="alert alert-success" role="alert">
                    <?php
                    echo htmlspecialchars($_GET['succes']);
                }
                    ?>
                    </div>
                    <h1 class="pb-5 my-4 text-center">Liste des utilisateurs</h1>

                    <div class="table-container mb-4 py-3">
                        <table style="border:10px;" cellpadding="10" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = $bdd->query("SELECT username, password, id, email, role FROM users");
                                while ($user = $query->fetch()) {
                                    echo '<tr>';
                                    echo '<td>' . $user['id'] . '</td>';
                                    echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                                    echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                                    echo '<td>';
                                    if ($user['role'] == 3) {
                                        echo 'Super Admin';
                                    } elseif ($user['role'] == 2) {
                                        echo 'Admin';
                                    } elseif ($user['role'] == 1) {
                                        echo 'Utilisateur';
                                    } else {
                                        echo 'Inconnu';
                                    }
                                    echo '</td>';
                                    echo '<td>
                    <a href="edit_user.php?id=' . $user['id'] . '" class="btn btn-primary btn-sm">Modifier</a>

                   
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Supprimer le compte
                </button>

                
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression de compte</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="m-0">Voulez-vous vraiment supprimer ce compte ?</p>
                                <br>
                                <p class="m-0">
                                Cette action va également supprimer tous les éléments liés à ce compte (quiz, questions, résultats,...).
                                </p>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                <a href="delete_user.php" type="button" class="btn btn-danger">Supprimer ce compte</a>
                            </div>
                        </div>
                    </div>
                </div>

                
                  </td>';
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