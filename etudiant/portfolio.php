<?php
// portfolio.php
// Affichage public du portfolio étudiant personnalisé
include '../pdo.php';
if (session_status() === PHP_SESSION_NONE) session_start();
include '../navbar.php';

$user_id = $_GET['id'] ?? null;
if (!$user_id) {
    echo '<div class="text-red-600">Utilisateur non spécifié.</div>';
    exit;
}

// Récupérer l'utilisateur
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) {
    echo '<div class="text-red-600">Utilisateur introuvable.</div>';
    exit;
}

// Récupérer les projets de l'utilisateur
$stmt = $pdo->prepare('SELECT * FROM projects WHERE user_id = ? ORDER BY date_realisation DESC');
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll();

// Récupérer tous les langages
$stmt = $pdo->prepare('SELECT * FROM technologies ORDER BY nom ASC');
$stmt->execute();
$languages = $stmt->fetchAll();

// Récupérer les captures d'écran des autres projets
$stmt = $pdo->prepare('SELECT s.*, p.titre as project_title, u.prenom, u.nom FROM screenshots s JOIN projects p ON s.project_id = p.id JOIN users u ON p.user_id = u.id WHERE p.user_id != ? ORDER BY s.id DESC LIMIT 12');
$stmt->execute([$user_id]);
$other_screenshots = $stmt->fetchAll();

// Récupérer les captures d'écran de l'utilisateur
$stmt = $pdo->prepare('SELECT * FROM screenshots WHERE project_id IN (SELECT id FROM projects WHERE user_id = ?)');
$stmt->execute([$user_id]);
$user_screenshots = $stmt->fetchAll();

// Récupérer les langages sélectionnés par l'utilisateur
$stmt = $pdo->prepare('SELECT t.* FROM technologies t JOIN project_technologies pt ON pt.technology_id = t.id JOIN projects p ON pt.project_id = p.id WHERE p.user_id = ? GROUP BY t.id');
$stmt->execute([$user_id]);
$user_languages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio de <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-picture {
            transition: all 0.3s ease;
        }
        .profile-picture:hover {
            transform: scale(1.05);
        }
        .project-card {
            transition: all 0.3s ease;
        }
        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .screenshot-card {
            transition: all 0.3s ease;
        }
        .screenshot-card:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php include 'sidebar_etudiant.php'; ?>
    <?php else : ?>
        <?php include 'sidebar_public.php'; ?>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div class="<?php echo isset($_SESSION['user_id']) ? 'ml-64' : ''; ?> p-8">
        <div class="max-w-6xl mx-auto">
            <!-- Profile Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="../<?php echo htmlspecialchars($user['profile_picture']); ?>" 
                                alt="Photo de profil" 
                                class="profile-picture h-32 w-32 rounded-full object-cover border-4 border-blue-100 shadow-sm">
                        <?php else: ?>
                            <div class="profile-picture h-32 w-32 rounded-full bg-blue-100 flex items-center justify-center border-4 border-blue-200 shadow-sm">
                                <i class="fas fa-user text-blue-400 text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                        
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-800 mb-1">
                                <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>
                            </h1>
                            <div class="text-blue-600 mb-2">
                                <i class="fas fa-envelope mr-1"></i>
                                <?php echo htmlspecialchars($user['email']); ?>
                            </div>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <span class="inline-flex items-center">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    <?php echo htmlspecialchars($user['filiere']); ?>
                                </span>
                                <span class="inline-flex items-center">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    <?php echo htmlspecialchars($user['annee_academique']); ?>
                                </span>
                                <span class="inline-flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <?php echo htmlspecialchars($user['site']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-project-diagram text-indigo-500 mr-2"></i>
                            Projets
                        </h2>
                    </div>
                    
                    <?php if (empty($projects)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-folder-open text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">Aucun projet à afficher pour le moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($projects as $project): ?>
                                <div class="project-card bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md">
                                    <div class="p-5">
                                        <h3 class="text-lg font-bold text-blue-700 mb-2"><?php echo htmlspecialchars($project['titre']); ?></h3>
                                        <p class="text-gray-700 mb-2 text-sm"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                                        <?php if ($project['github_link']): ?>
                                            <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" 
                                               class="text-blue-500 hover:underline text-sm inline-flex items-center">
                                                <i class="fab fa-github mr-1"></i> Lien GitHub
                                            </a>
                                        <?php endif; ?>
                                        <div class="text-xs text-gray-400 mt-2">
                                            <i class="far fa-calendar-alt mr-1"></i> Réalisé le : <?php echo htmlspecialchars($project['date_realisation']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Screenshots Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-image text-green-500 mr-2"></i>
                            Captures d'écran
                        </h2>
                    </div>
                    
                    <?php if (empty($user_screenshots)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-images text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">Aucune capture d'écran à afficher.</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <?php foreach ($user_screenshots as $ss): ?>
                                <div class="screenshot-card bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md">
                                    <img src="../<?php echo htmlspecialchars($ss['image_url']); ?>" 
                                         alt="screenshot" 
                                         class="w-full h-40 object-contain cursor-pointer"
                                         onclick="openModal('<?php echo htmlspecialchars($ss['image_url']); ?>')">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Languages Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-code text-purple-500 mr-2"></i>
                            Langages et Technologies
                        </h2>
                    </div>
                    
                    <?php if (empty($user_languages)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-laptop-code text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">Aucun langage sélectionné pour ce portfolio.</p>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-wrap gap-4">
                            <?php foreach ($user_languages as $lang): ?>
                                <div class="bg-gray-50 rounded-lg border border-gray-200 px-4 py-2 flex items-center space-x-3">
                                    <?php if ($lang['logo_url']): ?>
                                        <img src="<?php echo htmlspecialchars($lang['logo_url']); ?>" 
                                             alt="logo" 
                                             class="h-8 w-8 object-contain rounded-full border" />
                                    <?php endif; ?>
                                    <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($lang['nom']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Other Screenshots Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-images text-orange-500 mr-2"></i>
                            Autres projets étudiants
                        </h2>
                    </div>
                    
                    <?php if (empty($other_screenshots)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-images text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">Aucune capture d'écran d'autres projets pour le moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <?php foreach ($other_screenshots as $ss): ?>
                                <div class="screenshot-card bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md">
                                    <img src="../<?php echo htmlspecialchars($ss['image_url']); ?>" 
                                         alt="screenshot" 
                                         class="w-full h-40 object-contain cursor-pointer"
                                         onclick="openModal('<?php echo htmlspecialchars($ss['image_url']); ?>')">
                                    <div class="p-3 text-center">
                                        <div class="text-sm text-gray-700 font-semibold mb-1 truncate">
                                            <?php echo htmlspecialchars($ss['project_title']); ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            par <?php echo htmlspecialchars($ss['prenom'] . ' ' . $ss['nom']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-4xl w-full">
            <button onclick="closeModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="modalImage" src="" alt="Enlarged screenshot" class="w-full max-h-screen object-contain">
        </div>
    </div>

    <script>
        function openModal(imageUrl) {
            document.getElementById('modalImage').src = '../' + imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>