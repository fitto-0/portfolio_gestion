<?php
session_start();
include '../pdo.php';
include '../navbar.php';
include 'sidebar_admin.php';

$stmt = $pdo->prepare("SELECT id, nom, prenom, filiere, annee_academique, site FROM users WHERE role = 'etudiant' ORDER BY nom, prenom");
$stmt->execute();
$students = $stmt->fetchAll();

// Count statistics for the header
$nb_etudiants = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();
$nb_projets = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - Tous les portfolios</title>
  <script src="https://cdn.tailwindcss.com"></script> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/> 
  <style>
    .portfolio-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .portfolio-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- Main wrapper -->
  <div class="flex">
    
    <!-- Sidebar (already included via include) -->

    <!-- Main Content -->
    <div class="flex-1 md:ml-64"> <!-- ml-64 offsets for sidebar width -->
      
      <!-- Navbar (already included via include) -->

      <!-- Page Content -->
      <main class="p-6">
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-gray-800">Tous les portfolios étudiants</h1>
          <p class="text-gray-600 mt-1">Consultez et gérez les portfolios de tous les étudiants.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
          <!-- Étudiants -->
          <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-gray-500 text-sm">Étudiants</p>
                <p class="text-2xl font-bold mt-1"><?= $nb_etudiants ?></p>
              </div>
              <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-users text-blue-600"></i>
              </div>
            </div>
            <a href="manage_students.php" class="mt-3 inline-block text-blue-600 text-sm hover:underline">Gérer les étudiants</a>
          </div>

          <!-- Projets -->
          <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-green-500">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-gray-500 text-sm">Projets</p>
                <p class="text-2xl font-bold mt-1"><?= $nb_projets ?></p>
              </div>
              <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-project-diagram text-green-600"></i>
              </div>
            </div>
            <a href="manage_projects.php" class="mt-3 inline-block text-green-600 text-sm hover:underline">Gérer les projets</a>
          </div>
        </div>

        <!-- Portfolio Grid -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Liste des portfolios</h2>
            <span class="text-sm text-gray-500"><?= count($students) ?> portfolio(s)</span>
          </div>
          
          <?php if (count($students) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <?php foreach ($students as $student): ?>
                <div class="portfolio-card bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                  <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-1">
                      <?= htmlspecialchars($student['prenom'] . ' ' . $student['nom']) ?>
                    </h3>
                    <div class="flex items-center text-gray-500 text-sm mb-3">
                      <i class="fas fa-graduation-cap mr-2"></i>
                      <?= htmlspecialchars($student['filiere']) ?>
                    </div>
                    <div class="flex items-center text-gray-500 text-sm mb-3">
                      <i class="fas fa-calendar-alt mr-2"></i>
                      <?= htmlspecialchars($student['annee_academique']) ?>
                    </div>
                    <div class="flex items-center text-gray-500 text-sm mb-4">
                      <i class="fas fa-map-marker-alt mr-2"></i>
                      <?= htmlspecialchars($student['site']) ?>
                    </div>
                    <a href="admin_view_portfolio.php?id=<?= $student['id'] ?>" 
                      class="w-full block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold transition duration-200">
                      <i class="fas fa-eye mr-2"></i> Voir le portfolio
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-10 text-gray-400">
              <i class="fas fa-folder-open text-4xl mb-3"></i>
              <p>Aucun portfolio étudiant disponible pour le moment.</p>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

  <!-- Mobile menu script -->
  <script>
    document.querySelector('button[class*="md:hidden"]').addEventListener('click', function () {
      const mobileMenu = document.querySelector('div[class*="md:hidden hidden"]');
      mobileMenu.classList.toggle('hidden');
    });
  </script>

</body>
</html>