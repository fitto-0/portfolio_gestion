<?php
// etudiantslistes.php
include 'pdo.php';
include 'navbar.php';

// Récupérer tous les étudiants avec pagination
$studentsPerPage = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $studentsPerPage;

// Get total count for pagination
$totalStudents = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();
$totalPages = ceil($totalStudents / $studentsPerPage);

// Get students for current page
$stmt = $pdo->prepare("SELECT id, nom, prenom, email, filiere, annee_academique, site FROM users WHERE role = 'etudiant' ORDER BY nom, prenom LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $studentsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT id, nom, prenom, email, filiere, annee_academique, site FROM users WHERE role = 'etudiant' AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search OR filiere LIKE :search) ORDER BY nom, prenom");
    $stmt->bindValue(':search', "%$search%");
    $stmt->execute();
    $students = $stmt->fetchAll();
    $totalPages = 1; // Reset pagination when searching
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants | Plateforme Étudiante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .student-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-left-color: #3b82f6;
        }
        .pagination-link {
            transition: all 0.2s ease;
        }
        .pagination-link:hover:not(.active) {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Liste des Étudiants</h1>
                <p class="mt-2 text-lg text-gray-600">Découvrez les portfolios de nos étudiants</p>
            </div>
            
            <!-- Search Box -->
            <form method="get" class="mt-4 md:mt-0">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                           class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                           placeholder="Rechercher un étudiant...">
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Filters -->
        <div class="mb-8 flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="filiere" class="block text-sm font-medium text-gray-700 mb-1">Filière</label>
                <select id="filiere" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option>Toutes les filières</option>
                    <?php
                    $filieres = $pdo->query("SELECT DISTINCT filiere FROM users WHERE role = 'etudiant' AND filiere IS NOT NULL ORDER BY filiere")->fetchAll();
                    foreach ($filieres as $f) {
                        echo '<option>' . htmlspecialchars($f['filiere']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div>
                <label for="annee" class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                <select id="annee" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option>Toutes les années</option>
                    <?php
                    $annees = $pdo->query("SELECT DISTINCT annee_academique FROM users WHERE role = 'etudiant' AND annee_academique IS NOT NULL ORDER BY annee_academique")->fetchAll();
                    foreach ($annees as $a) {
                        echo '<option>' . htmlspecialchars($a['annee_academique']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div>
                <label for="site" class="block text-sm font-medium text-gray-700 mb-1">Site</label>
                <select id="site" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option>Tous les sites</option>
                    <?php
                    $sites = $pdo->query("SELECT DISTINCT site FROM users WHERE role = 'etudiant' AND site IS NOT NULL ORDER BY site")->fetchAll();
                    foreach ($sites as $s) {
                        echo '<option>' . htmlspecialchars($s['site']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Student Grid -->
        <?php if (count($students) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php foreach ($students as $student): ?>
                    <div class="student-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                    <?php echo strtoupper(substr($student['prenom'], 0, 1)) . strtoupper(substr($student['nom'], 0, 1)); ?>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($student['prenom'] . ' ' . $student['nom']); ?></h3>
                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($student['filiere']); ?></p>
                                </div>
                            </div>
                            
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                    <span class="truncate"><?php echo htmlspecialchars($student['email']); ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-graduation-cap mr-2 text-gray-400"></i>
                                    <span><?php echo htmlspecialchars($student['annee_academique']); ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    <span><?php echo htmlspecialchars($student['site']); ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <a href="etudiant/portfolio.php?id=<?php echo $student['id']; ?>" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    <i class="fas fa-eye mr-2"></i> Voir le portfolio
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1 && empty($search)): ?>
                <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <a href="?page=<?php echo max(1, $page - 1); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Précédent
                        </a>
                        <a href="?page=<?php echo min($totalPages, $page + 1); ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Suivant
                        </a>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <a href="?page=<?php echo max(1, $page - 1); ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Précédent</span>
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'; ?> pagination-link relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <a href="?page=<?php echo min($totalPages, $page + 1); ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Suivant</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="text-center py-16 bg-white rounded-lg shadow">
                <i class="fas fa-user-graduate text-5xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">Aucun étudiant trouvé</h3>
                <p class="mt-2 text-sm text-gray-500"><?php echo empty($search) ? 'Aucun étudiant inscrit pour le moment.' : 'Aucun résultat pour votre recherche.'; ?></p>
                <?php if (!empty($search)): ?>
                    <div class="mt-6">
                        <a href="etudiantslistes.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-undo mr-2"></i> Réinitialiser la recherche
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Filter functionality (would need JavaScript implementation)
        document.addEventListener('DOMContentLoaded', function() {
            const filiereSelect = document.getElementById('filiere');
            const anneeSelect = document.getElementById('annee');
            const siteSelect = document.getElementById('site');
            
            // This would need to be implemented with AJAX or form submission
            [filiereSelect, anneeSelect, siteSelect].forEach(select => {
                select.addEventListener('change', function() {
                    // Implement filtering logic here
                    console.log('Filter changed:', this.id, this.value);
                });
            });
        });
    </script>
</body>
</html>