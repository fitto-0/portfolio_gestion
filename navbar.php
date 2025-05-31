<?php
// navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_logged_in = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? null;
?>
<nav class="bg-gray-900 shadow-lg sticky top-0 z-50">
  <div class="container mx-auto flex items-center justify-between py-3 px-4 md:px-6">
    <div class="flex items-center space-x-1 md:space-x-6">
      <a href="index.php" class="flex items-center space-x-1">
        <span class="text-white text-xl font-bold tracking-wide hover:text-blue-400 transition duration-300">Accueil</span>
      </a>
      
      <?php if ($user_logged_in): ?>
        <div class="hidden md:flex items-center space-x-1 md:space-x-6">
          <a href="./student_dashboard.php" class="px-3 py-2 text-gray-200 hover:text-blue-400 transition duration-300 rounded-md hover:bg-gray-800">Dashboard</a>
          <a href="./portfolio.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>" class="px-3 py-2 text-gray-200 hover:text-blue-400 transition duration-300 rounded-md hover:bg-gray-800">Mon Portfolio</a>
          <a href="./etudiantslistes.php" class="px-3 py-2 text-gray-200 hover:text-blue-400 transition duration-300 rounded-md hover:bg-gray-800">Étudiants</a>
        </div>
      <?php else: ?>
        <a href="./etudiantslistes.php" class="hidden md:block px-3 py-2 text-gray-200 hover:text-blue-400 transition duration-300 rounded-md hover:bg-gray-800">Étudiants</a>
      <?php endif; ?>
    </div>
    
    <div class="flex items-center space-x-3 md:space-x-6">
      <?php if ($user_logged_in): ?>
        <div class="hidden md:flex items-center space-x-2">
          <span class="text-gray-300 text-sm"><?php echo $_SESSION['username'] ?? 'Utilisateur'; ?></span>
          <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
            <?php echo strtoupper(substr(($_SESSION['username'] ?? 'U'), 0, 1)); ?>
          </span>
        </div>
        <a href="../logout.php" class="px-3 py-2 text-sm bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition duration-300 flex items-center space-x-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Déconnexion</span>
        </a>
      <?php else: ?>
        <a href="./login.php" class="px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-300 flex items-center space-x-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
          <span>Connexion</span>
        </a>
        <a href="./register.php" class="hidden md:flex px-3 py-2 text-sm bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-300 items-center space-x-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
          <span>Inscription</span>
        </a>
      <?php endif; ?>
      
      <!-- Mobile menu button -->
      <button class="md:hidden text-gray-300 hover:text-white focus:outline-none">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>
  
  <!-- Mobile menu (hidden by default) -->
  <div class="md:hidden hidden bg-gray-800 px-4 py-2">
    <?php if ($user_logged_in): ?>
      <a href="./etudiant/student_dashboard.php" class="block px-3 py-2 text-gray-200 hover:text-blue-400 transition duration-300 rounded-md hover:bg-gray-700">Dashboard</a>
      <a href="./etudiant/portfolio.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>" class="block px-3 py-2 text-gray-200 hover:text-blue-400 transition duration-300 rounded-md hover:bg-gray-700">Mon Portfolio</a>
    <?php endif; ?>
    <a href="../etudiantslistes.php" class="block px-3 py-2 text-gray-200 hover:text-blue-400 transition duration-300 rounded-md hover:bg-gray-700">Étudiants</a>
    
    <?php if (!$user_logged_in): ?>
      <a href="./register.php" class="block px-3 py-2 text-gray-200 hover:text-blue-400 transition duration-300 rounded-md hover:bg-gray-700">Inscription</a>
    <?php endif; ?>
  </div>
</nav>

<script>
  // Toggle mobile menu
  document.querySelector('button[class*="md:hidden"]').addEventListener('click', function() {
    const mobileMenu = document.querySelector('div[class*="md:hidden hidden"]');
    mobileMenu.classList.toggle('hidden');
  });
</script>