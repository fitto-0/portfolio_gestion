<?php
// admin/sidebar_admin.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? null;
if ($role !== 'admin') {
    // Redirect or prevent access
    header('Location: /unauthorized.php'); // or just return; depending on your setup
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="bg-gray-800 text-white w-64 min-h-screen flex flex-col fixed inset-y-0 z-50">
    <!-- Header -->
    <div class="flex items-center justify-between p-6 border-b border-gray-700">
        <div>
            <h2 class="text-xl font-bold text-blue-400">Admin Dashboard</h2>
            <p class="text-gray-400 text-sm">Menu de navigation</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-1">
        <!-- Dashboard -->
        <a href="./admin_dashboard.php" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors 
           <?= $current_page === 'admin_dashboard.php' ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z 
                       M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z 
                       M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z 
                       M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Dashboard
        </a>

        <!-- Portfolio Section -->
        <div class="mt-6 mb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Gestion des portfolios
        </div>

        <a href="./view_portfolios.php" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors 
           <?= $current_page === 'view_portfolios.php' ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                       M9 5a2 2 0 002 2h2a2 2 0 002-2 
                       M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Tous les portfolios
        </a>

        <a href="./filter_portfolios.php" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors 
           <?= $current_page === 'filter_portfolios.php' ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filtrer les portfolios
        </a>

        <!-- Students Section -->
        <div class="mt-6 mb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Gestion des étudiants
        </div>

        <a href="./etudiantslistes.php" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors 
           <?= $current_page === 'etudiantslistes.php' ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-gray-700'; ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197
                       M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Liste des étudiants
        </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-700">
        <a href="./logout.php" 
           class="flex items-center justify-center px-4 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M17 16l4-4m0 0l-4-4m4 4H7
                       m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Déconnexion
        </a>
    </div>
</aside>
