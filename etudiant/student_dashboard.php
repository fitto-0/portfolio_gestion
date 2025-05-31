<?php
// student_dashboard.php
include '../pdo.php';
if (session_status() === PHP_SESSION_NONE) session_start();
include '../navbar.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get user projects
$stmt = $pdo->prepare('SELECT * FROM projects WHERE user_id = ? ORDER BY date_realisation DESC');
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll();

// Get all technologies
$stmt = $pdo->prepare('SELECT * FROM technologies ORDER BY nom ASC');
$stmt->execute();
$languages = $stmt->fetchAll();

// Get screenshots from other projects
$stmt = $pdo->prepare('SELECT s.*, p.titre as project_title, u.prenom, u.nom FROM screenshots s JOIN projects p ON s.project_id = p.id JOIN users u ON p.user_id = u.id WHERE p.user_id != ? ORDER BY s.id DESC LIMIT 12');
$stmt->execute([$user_id]);
$other_screenshots = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .project-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .project-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-left-color: #3b82f6;
        }
        .screenshot-card {
            transition: all 0.3s ease;
        }
        .screenshot-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'sidebar_etudiant.php'; ?>
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <!-- Welcome Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Bonjour, <span class="text-blue-600"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></span>
                </h1>
                <p class="text-gray-600 mt-2">Voici votre espace personnel et vos projets</p>
            </div>
            <a href="add_project.php" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <i class="fas fa-plus-circle mr-2"></i> Nouveau projet
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-xl shadow p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-project-diagram text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Projets</p>
                    <p class="text-2xl font-semibold text-gray-800"><?php echo count($projects); ?></p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-code text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Langages</p>
                    <p class="text-2xl font-semibold text-gray-800"><?php echo count($languages); ?></p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-image text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Captures</p>
                    <p class="text-2xl font-semibold text-gray-800"><?php echo count($other_screenshots); ?></p>
                </div>
            </div>
        </div>

        <!-- My Projects Section -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-folder-open text-blue-500 mr-2"></i>
                    Mes projets
                </h2>
                <a href="add_project.php" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-plus mr-1"></i> Ajouter
                </a>
            </div>
            
            <?php if (empty($projects)): ?>
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <i class="fas fa-project-diagram text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700">Aucun projet pour le moment</h3>
                    <p class="text-gray-500 mt-2 mb-4">Commencez par ajouter votre premier projet</p>
                    <a href="add_project.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i> Créer un projet
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-bold text-gray-800 truncate"><?php echo htmlspecialchars($project['titre']); ?></h3>
                                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded"><?php echo htmlspecialchars($project['date_realisation']); ?></span>
                                </div>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                                
                                <?php if ($project['github_link']): ?>
                                    <div class="mb-4">
                                        <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                            <i class="fab fa-github mr-2"></i> Voir sur GitHub
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <div class="flex space-x-2">
                                        <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                        <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </a>
                                    </div>
                                    <a href="portfolio.php?id=<?php echo $user_id; ?>&project=<?php echo $project['id']; ?>" class="text-xs text-gray-500 hover:text-blue-600">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Languages Section -->
        <section class="mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-code text-green-500 mr-2"></i>
                Technologies maîtrisées
            </h2>
            
            <?php if (empty($languages)): ?>
                <div class="bg-white rounded-xl shadow p-6 text-center">
                    <i class="fas fa-code text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Aucune technologie disponible</p>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex flex-wrap gap-4">
                        <?php foreach ($languages as $lang): ?>
                            <div class="flex items-center px-4 py-2 bg-gray-50 rounded-lg border border-gray-200">
                                <?php if ($lang['logo_url']): ?>
                                    <img src="<?php echo htmlspecialchars($lang['logo_url']); ?>" alt="logo" class="h-6 w-6 object-contain mr-2" />
                                <?php else: ?>
                                    <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                        <i class="fas fa-code text-xs"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="font-medium text-gray-700"><?php echo htmlspecialchars($lang['nom']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="add_language.php" class="text-sm text-blue-600 hover:text-blue-800 flex items-center justify-end">
                            <i class="fas fa-plus mr-1"></i> Ajouter des langages
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- Other Projects Screenshots -->
        <section>
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-images text-purple-500 mr-2"></i>
                Inspirations des autres étudiants
            </h2>
            
            <?php if (empty($other_screenshots)): ?>
                <div class="bg-white rounded-xl shadow p-6 text-center">
                    <i class="fas fa-image text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Aucune capture d'écran disponible</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php foreach ($other_screenshots as $ss): ?>
                        <div class="screenshot-card bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="relative pt-[75%] bg-gray-100">
                                <img src="../<?php echo htmlspecialchars($ss['image_url']); ?>" alt="screenshot" class="absolute top-0 left-0 w-full h-full object-contain" loading="lazy" />
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-800 truncate"><?php echo htmlspecialchars($ss['project_title']); ?></h3>
                                <p class="text-xs text-gray-500 mt-1">Par <?php echo htmlspecialchars($ss['prenom'] . ' ' . $ss['nom']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-4 text-right">
                    <a href="etudiantslistes.php" class="text-sm text-blue-600 hover:text-blue-800 flex items-center justify-end">
                        <i class="fas fa-arrow-right mr-1"></i> Voir plus de projets
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <script>
        // Mobile sidebar toggle integration
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('[aria-label="Toggle menu"]');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    const sidebar = document.querySelector('aside');
                    const mainContent = document.querySelector('.ml-64');
                    
                    sidebar.classList.toggle('-translate-x-full');
                    sidebar.classList.toggle('shadow-xl');
                    mainContent.classList.toggle('ml-0');
                    mainContent.classList.toggle('ml-64');
                });
            }
        });
    </script>
</body>
</html>