<?php
// sidebar_etudiant.php
$student_links = [
    ['href' => '../etudiant/student_dashboard.php', 'icon' => 'fas fa-chart-line', 'label' => 'Tableau de bord', 'color' => 'text-purple-600'],
    ['href' => '../etudiant/add_project.php', 'icon' => 'fas fa-plus-square', 'label' => 'Ajouter projet', 'color' => 'text-emerald-600'],
    ['href' => '../etudiant/add_language.php', 'icon' => 'fas fa-code', 'label' => 'Ajouter langage', 'color' => 'text-blue-600'],
    ['href' => '../etudiant/upload_screenshot.php', 'icon' => 'fas fa-image', 'label' => 'Ajouter capture', 'color' => 'text-amber-600'],
    ['href' => '../etudiant/profile.php', 'icon' => 'fas fa-user-circle', 'label' => 'Profil', 'color' => 'text-pink-600'],
    ['href' => '../etudiant/editportfolio.php', 'icon' => 'fas fa-edit', 'label' => 'Editer portfolio', 'color' => 'text-indigo-600'],
];
?>

<!-- Sidebar Navigation -->
<aside class="w-64 min-h-screen bg-gradient-to-b from-gray-50 to-white border-r border-gray-200 fixed left-0 top-0 pt-16 shadow-lg">
    <div class="overflow-y-auto h-full py-6 px-4">
        <div class="mb-8 px-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-graduate mr-3 text-blue-500"></i>
                Menu Étudiant
            </h2>
        </div>
        
        <ul class="space-y-2">
            <?php foreach ($student_links as $link): ?>
                <li>
                    <a href="<?php echo $link['href']; ?>" 
                       class="flex items-center p-3 text-base font-medium rounded-xl transition-all duration-200 group
                              <?php echo (basename($_SERVER['PHP_SELF']) === basename($link['href'])) 
                                  ? 'bg-blue-100 border-l-4 border-blue-500 text-blue-700' 
                                  : 'text-gray-600 hover:bg-gray-100 border-l-4 border-transparent'; ?>">
                        <span class="<?php echo $link['color']; ?> mr-3 group-hover:scale-110 transition-transform">
                            <i class="<?php echo $link['icon']; ?> text-lg"></i>
                        </span>
                        <span class="flex-1"><?php echo $link['label']; ?></span>
                        <?php if (basename($_SERVER['PHP_SELF']) === basename($link['href'])): ?>
                            <i class="fas fa-chevron-right text-blue-500 text-xs"></i>
                        <?php else: ?>
                            <i class="fas fa-chevron-right text-transparent text-xs"></i>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <!-- Profile section -->
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-t border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 relative">
                    <img class="w-10 h-10 rounded-full border-2 border-white shadow" 
                         src="<?php echo isset($_SESSION['avatar']) ? $_SESSION['avatar'] : 'https://ui-avatars.com/api/?name='.urlencode($_SESSION['username'] ?? 'U').'&background=3b82f6&color=fff'; ?>" 
                         alt="Profile">
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">
                        <?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?>
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        <?php echo htmlspecialchars($_SESSION['email'] ?? 'email@example.com'); ?>
                    </p>
                </div>
                <a href="./logout.php" class="text-gray-500 hover:text-red-500 transition-colors" title="Déconnexion">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>
</aside>

<!-- Main content offset -->
<div class="ml-64 transition-all duration-300">
    <!-- Your page content goes here -->
</div>

<style>
    /* Custom scrollbar for sidebar */
    aside::-webkit-scrollbar {
        width: 6px;
    }
    aside::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    aside::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }
    aside::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
    
    /* Animation for active item */
    [aria-current="page"] {
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
    }
</style>

<script>
    // Mobile toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.createElement('button');
        sidebarToggle.innerHTML = '<i class="fas fa-bars text-gray-700"></i>';
        sidebarToggle.className = 'md:hidden fixed top-4 left-4 z-50 p-3 bg-white rounded-xl shadow-lg hover:bg-gray-100 transition-colors';
        sidebarToggle.setAttribute('aria-label', 'Toggle menu');
        document.body.prepend(sidebarToggle);
        
        const sidebar = document.querySelector('aside');
        const content = document.querySelector('div.ml-64');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('shadow-xl');
            content.classList.toggle('ml-0');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768 && !sidebar.contains(e.target) && e.target !== sidebarToggle) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('shadow-xl');
                content.classList.add('ml-0');
            }
        });
    });
</script>