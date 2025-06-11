<?php
session_start();
include '../pdo.php';
include '../navbar.php';
include 'sidebar_admin.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Gérer les captures</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="flex">
    <div class="flex-1 md:ml-64">
      <main class="p-6">
        <h1 class="text-2xl font-bold mb-4">Gestion des captures</h1>
        <p class="mb-4">Page de gestion des captures. (À compléter)</p>
        <a href="admin_dashboard.php" class="text-blue-600 hover:underline">Retour au tableau de bord</a>
      </main>
    </div>
  </div>
</body>
</html>
