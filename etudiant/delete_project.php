<?php
// delete_project.php
// Supprimer un projet
include '../pdo.php';
if (session_status() === PHP_SESSION_NONE) session_start();
include '../navbar.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$project_id = $_GET['id'] ?? null;
if (!$project_id) {
    echo '<div class="text-red-600">Projet non spécifié.</div>';
    exit;
}

// Suppression du projet
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM projects WHERE id=? AND user_id=?');
    $stmt->execute([$project_id, $_SESSION['user_id']]);
    if ($stmt->rowCount()) {
        $success = 'Projet supprimé avec succès.';
    } else {
        $error = 'Erreur lors de la suppression ou projet introuvable.';
    }
}

// Charger le projet pour affichage
$stmt = $pdo->prepare('SELECT * FROM projects WHERE id=? AND user_id=?');
$stmt->execute([$project_id, $_SESSION['user_id']]);
$project = $stmt->fetch();
if (!$project && !$success) {
    echo '<div class="text-red-600">Projet introuvable ou accès refusé.</div>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'sidebar_etudiant.php'; ?>
    <main class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Supprimer le projet</h1>
        <?php if ($error): ?><div class="mb-4 text-red-600"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="mb-4 text-green-600"><?php echo $success; ?></div><?php else: ?>
        <div class="mb-4">Êtes-vous sûr de vouloir supprimer le projet <span class="font-semibold"><?php echo htmlspecialchars($project['titre']); ?></span> ?</div>
        <form method="post">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Supprimer</button>
            <a href="student_dashboard.php" class="ml-4 text-blue-600 hover:underline">Annuler</a>
        </form>
        <?php endif; ?>
    </main>
</body>
</html>
