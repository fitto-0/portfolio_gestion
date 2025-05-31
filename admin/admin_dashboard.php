<?php
session_start();
include '../pdo.php'; // Adjust path as needed

// Fetch statistics
$nb_etudiants = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();
$nb_projets = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$nb_langages = $pdo->query("SELECT COUNT(*) FROM technologies")->fetchColumn();
$nb_screenshots = $pdo->query("SELECT COUNT(*) FROM screenshots")->fetchColumn();

// Include navbar and sidebar
include '../navbar.php';
include 'sidebar_admin.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/> 
  <style>
    .stat-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
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
          <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
          <p class="text-gray-600 mt-1">Vue d’ensemble des statistiques et activités récentes.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Étudiants -->
          <div class="stat-card bg-white p-5 rounded-lg shadow-sm border-l-4 border-blue-500">
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
          <div class="stat-card bg-white p-5 rounded-lg shadow-sm border-l-4 border-green-500">
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

          <!-- Langages -->
          <div class="stat-card bg-white p-5 rounded-lg shadow-sm border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-gray-500 text-sm">Langages</p>
                <p class="text-2xl font-bold mt-1"><?= $nb_langages ?></p>
              </div>
              <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-code text-purple-600"></i>
              </div>
            </div>
            <a href="manage_technologies.php" class="mt-3 inline-block text-purple-600 text-sm hover:underline">Gérer les langages</a>
          </div>

          <!-- Captures -->
          <div class="stat-card bg-white p-5 rounded-lg shadow-sm border-l-4 border-pink-500">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-gray-500 text-sm">Captures</p>
                <p class="text-2xl font-bold mt-1"><?= $nb_screenshots ?></p>
              </div>
              <div class="bg-pink-100 p-3 rounded-full">
                <i class="fas fa-image text-pink-600"></i>
              </div>
            </div>
            <a href="manage_screenshots.php" class="mt-3 inline-block text-pink-600 text-sm hover:underline">Gérer les captures</a>
          </div>
        </div>

        <!-- Recent Activity Placeholder -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Activité récente</h2>
            <a href="#" class="text-sm text-blue-600 hover:underline">Voir tout</a>
          </div>
          <div class="text-center py-10 text-gray-400">
            <i class="fas fa-chart-line text-4xl mb-3"></i>
            <p>Aucune activité récente pour le moment.</p>
          </div>
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