<?php
// editportfolio.php
include '../pdo.php';
if (session_status() === PHP_SESSION_NONE) session_start();
include '../navbar.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
// Récupérer l'utilisateur
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) {
    echo '<div class="text-red-600">Utilisateur introuvable.</div>';
    exit;
}
// Récupérer les projets
$stmt = $pdo->prepare('SELECT * FROM projects WHERE user_id = ? ORDER BY date_realisation DESC');
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll();
// Récupérer les captures d'écran
$stmt = $pdo->prepare('SELECT * FROM screenshots WHERE project_id IN (SELECT id FROM projects WHERE user_id = ?)');
$stmt->execute([$user_id]);
$screenshots = $stmt->fetchAll();
// Récupérer tous les langages
$stmt = $pdo->prepare('SELECT * FROM technologies ORDER BY nom ASC');
$stmt->execute();
$languages = $stmt->fetchAll();

// Traitement de la mise à jour du profil (nom, prenom, email, etc.)
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $annee_academique = $_POST['annee_academique'] ?? '';
    $filiere = $_POST['filiere'] ?? '';
    $site = $_POST['site'] ?? '';
    if ($nom && $prenom && $email && $annee_academique && $filiere && $site) {
        $stmt = $pdo->prepare('UPDATE users SET nom=?, prenom=?, email=?, annee_academique=?, filiere=?, site=? WHERE id=?');
        $stmt->execute([$nom, $prenom, $email, $annee_academique, $filiere, $site, $user_id]);
        $success = 'Profil mis à jour avec succès.';
        // Refresh user data
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    } else {
        $error = 'Veuillez remplir tous les champs du profil.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Editer mon portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar_etudiant.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 ml-64"> <!-- ml-64 to account for sidebar width -->
            <div class="max-w-5xl mx-auto px-4 py-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-blue-700 mb-2">Éditer mon portfolio</h1>
                    <div class="text-gray-700 mb-1">Email : <?php echo htmlspecialchars($user['email']); ?></div>
                    <div class="text-gray-700 mb-1">Filière : <?php echo htmlspecialchars($user['filiere']); ?> | Année : <?php echo htmlspecialchars($user['annee_academique']); ?> | Site : <?php echo htmlspecialchars($user['site']); ?></div>
                </div>

                <?php if ($error): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded"><?php echo $success; ?></div>
                <?php endif; ?>

                <section class="mb-12 bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Informations du profil</h2>
                    <form method="post" class="space-y-4">
                        <input type="hidden" name="update_profile" value="1">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-1/2">
                                <label class="block mb-1 text-gray-700 font-medium">Nom</label>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" 
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="w-full md:w-1/2">
                                <label class="block mb-1 text-gray-700 font-medium">Prénom</label>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" 
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-700 font-medium">Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-1/2">
                                <label class="block mb-1 text-gray-700 font-medium">Année académique</label>
                                <input type="text" name="annee_academique" value="<?php echo htmlspecialchars($user['annee_academique']); ?>" 
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="w-full md:w-1/2">
                                <label class="block mb-1 text-gray-700 font-medium">Filière</label>
                                <input type="text" name="filiere" value="<?php echo htmlspecialchars($user['filiere']); ?>" 
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-700 font-medium">Site</label>
                            <select name="site" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Sélectionner un site</option>
                                <option value="Site 1" <?php if ($user['site'] === 'Site 1') echo 'selected'; ?>>Site 1</option>
                                <option value="Site 2" <?php if ($user['site'] === 'Site 2') echo 'selected'; ?>>Site 2</option>
                                <option value="Site 3" <?php if ($user['site'] === 'Site 3') echo 'selected'; ?>>Site 3</option>
                                <option value="Site 4" <?php if ($user['site'] === 'Site 4') echo 'selected'; ?>>Site 4</option>
                                <option value="Site 5" <?php if ($user['site'] === 'Site 5') echo 'selected'; ?>>Site 5</option>
                            </select>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </section>

                <section class="mb-12 bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-semibold">Mes projets</h2>
                        <a href="add_project.php" class="bg-blue-600 text-white py-1 px-4 rounded text-sm hover:bg-blue-700 transition duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i> Ajouter un projet
                        </a>
                    </div>
                    <?php if (empty($projects)): ?>
                        <div class="text-gray-500 py-4">Aucun projet à afficher. <a href="add_project.php" class="text-blue-600 hover:underline">Ajoutez votre premier projet</a>.</div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($projects as $project): ?>
                                <div class="bg-white rounded-lg border border-gray-200 p-5 flex flex-col justify-between hover:shadow-md transition duration-200">
                                    <div>
                                        <h3 class="text-lg font-bold text-blue-700 mb-2"><?php echo htmlspecialchars($project['titre']); ?></h3>
                                        <p class="text-gray-700 mb-2 text-sm"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                                        <?php if ($project['github_link']): ?>
                                            <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="text-blue-500 hover:underline text-sm">Lien GitHub</a>
                                        <?php endif; ?>
                                        <div class="text-xs text-gray-400 mt-2">Réalisé le : <?php echo htmlspecialchars($project['date_realisation']); ?></div>
                                    </div>
                                    <div class="flex space-x-2 mt-4 pt-2 border-t border-gray-100">
                                        <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="flex-1 text-center px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs transition duration-200 flex items-center justify-center">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                        <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="flex-1 text-center px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs transition duration-200 flex items-center justify-center">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="mb-12 bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-semibold">Mes captures d'écran</h2>
                        <a href="add_screenshot.php" class="bg-blue-600 text-white py-1 px-4 rounded text-sm hover:bg-blue-700 transition duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i> Ajouter capture
                        </a>
                    </div>
                    <?php if (empty($screenshots)): ?>
                        <div class="text-gray-500 py-4">Aucune capture d'écran à afficher. <a href="add_screenshot.php" class="text-blue-600 hover:underline">Ajoutez votre première capture</a>.</div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <?php foreach ($screenshots as $ss): ?>
                                <div class="bg-white rounded-lg border border-gray-200 p-3 flex flex-col items-center hover:shadow-md transition duration-200">
                                    <img src="../<?php echo htmlspecialchars($ss['image_url']); ?>" alt="screenshot" class="rounded mb-2 max-h-40 object-contain w-full" />
                                    <a href="delete_screenshot.php?id=<?php echo $ss['id']; ?>" class="mt-2 text-red-500 hover:text-red-700 text-xs flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Langages utilisés</h2>
                    <?php if (empty($languages)): ?>
                        <div class="text-gray-500 py-4">Aucun langage disponible.</div>
                    <?php else: ?>
                        <div class="flex flex-wrap gap-4">
                            <?php foreach ($languages as $lang): ?>
                                <div class="bg-gray-50 rounded-lg border border-gray-200 px-4 py-2 flex items-center space-x-3">
                                    <?php if ($lang['logo_url']): ?>
                                        <img src="<?php echo htmlspecialchars($lang['logo_url']); ?>" alt="logo" class="h-8 w-8 object-contain rounded-full border" />
                                    <?php endif; ?>
                                    <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($lang['nom']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</body>
</html>