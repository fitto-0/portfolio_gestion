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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer mon Portfolio | Portfolio Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-picture {
            transition: all 0.3s ease;
        }
        .profile-picture:hover {
            transform: scale(1.05);
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .project-card {
            transition: all 0.3s ease;
        }
        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'sidebar_etudiant.php'; ?>
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="max-w-6xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <a href="student_dashboard.php" class="text-blue-600 hover:text-blue-800 mr-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-edit text-blue-500 mr-3"></i>
                        Éditer mon Portfolio
                    </h1>
                </div>
                <p class="text-gray-600">Gérez les projets et éléments de votre portfolio.</p>
            </div>

            <!-- Status Messages -->
            <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?php echo $error; ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700"><?php echo $success; ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Profile Information Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                            Informations du profil
                        </h2>
                    </div>
                    
                    <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="hidden" name="update_profile" value="1">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required
                                   class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required
                                   class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                                   class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Année académique <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="annee_academique" value="<?php echo htmlspecialchars($user['annee_academique']); ?>" required
                                   class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Filière <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="filiere" value="<?php echo htmlspecialchars($user['filiere']); ?>" required
                                   class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Site <span class="text-red-500">*</span>
                            </label>
                            <select name="site" class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Sélectionner un site</option>
                                <option value="Site 1" <?php if ($user['site'] === 'Site 1') echo 'selected'; ?>>Site 1</option>
                                <option value="Site 2" <?php if ($user['site'] === 'Site 2') echo 'selected'; ?>>Site 2</option>
                                <option value="Site 3" <?php if ($user['site'] === 'Site 3') echo 'selected'; ?>>Site 3</option>
                                <option value="Site 4" <?php if ($user['site'] === 'Site 4') echo 'selected'; ?>>Site 4</option>
                                <option value="Site 5" <?php if ($user['site'] === 'Site 5') echo 'selected'; ?>>Site 5</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2 flex justify-end pt-4">
                            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Projects Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-project-diagram text-indigo-500 mr-2"></i>
                            Mes Projets
                        </h2>
                        <a href="add_project.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i> Ajouter un projet
                        </a>
                    </div>
                    
                    <?php if (empty($projects)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-folder-open text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 mb-4">Vous n'avez aucun projet pour le moment.</p>
                            <a href="add_project.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700">
                                <i class="fas fa-plus mr-2"></i> Créer votre premier projet
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($projects as $project): ?>
                                <div class="project-card bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md">
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-blue-700 mb-2"><?php echo htmlspecialchars($project['titre']); ?></h3>
                                        <p class="text-gray-700 mb-2 text-sm"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                                        <?php if ($project['github_link']): ?>
                                            <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="text-blue-500 hover:underline text-sm inline-flex items-center">
                                                <i class="fab fa-github mr-1"></i> Lien GitHub
                                            </a>
                                        <?php endif; ?>
                                        <div class="text-xs text-gray-400 mt-2">
                                            <i class="far fa-calendar-alt mr-1"></i> Réalisé le : <?php echo htmlspecialchars($project['date_realisation']); ?>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-5 py-3 flex justify-between border-t border-gray-200">
                                        <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="text-sm text-blue-600 hover:text-blue-800 inline-flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                        <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="text-sm text-red-600 hover:text-red-800 inline-flex items-center">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Screenshots Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-image text-green-500 mr-2"></i>
                            Mes Captures d'écran
                        </h2>
                        <a href="add_screenshot.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-plus mr-2"></i> Ajouter capture
                        </a>
                    </div>
                    
                    <?php if (empty($screenshots)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-images text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 mb-4">Vous n'avez aucune capture d'écran pour le moment.</p>
                            <a href="add_screenshot.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                                <i class="fas fa-plus mr-2"></i> Ajouter votre première capture
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <?php foreach ($screenshots as $ss): ?>
                                <div class="project-card bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md">
                                    <img src="../<?php echo htmlspecialchars($ss['image_url']); ?>" alt="screenshot" class="w-full h-40 object-cover">
                                    <div class="p-3 flex justify-center">
                                        <a href="delete_screenshot.php?id=<?php echo $ss['id']; ?>" class="text-sm text-red-600 hover:text-red-800 inline-flex items-center">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Languages Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-code text-purple-500 mr-2"></i>
                            Langages et Technologies
                        </h2>
                        <a href="add_technology.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <i class="fas fa-plus mr-2"></i> Ajouter technologie
                        </a>
                    </div>
                    
                    <?php if (empty($languages)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-laptop-code text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 mb-4">Vous n'avez sélectionné aucune technologie pour le moment.</p>
                            <a href="add_technology.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700">
                                <i class="fas fa-plus mr-2"></i> Ajouter une technologie
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-wrap gap-4">
                            <?php foreach ($languages as $lang): ?>
                                <div class="bg-gray-50 rounded-lg border border-gray-200 px-4 py-2 flex items-center space-x-3">
                                    <?php if ($lang['logo_url']): ?>
                                        <img src="<?php echo htmlspecialchars($lang['logo_url']); ?>" alt="logo" class="h-8 w-8 object-contain rounded-full border" />
                                    <?php endif; ?>
                                    <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($lang['nom']); ?></span>
                                    <a href="remove_technology.php?id=<?php echo $lang['id']; ?>" class="text-red-500 hover:text-red-700 ml-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>