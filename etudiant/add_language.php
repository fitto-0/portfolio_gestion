<?php
// add_language.php
include '../pdo.php';
if (session_status() === PHP_SESSION_NONE) session_start();
include '../navbar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Form processing
$success = '';
$error = '';
$formData = [
    'nom' => '',
    'logo_url' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'nom' => trim($_POST['nom'] ?? ''),
        'logo_url' => trim($_POST['logo_url'] ?? '')
    ];

    if ($formData['nom']) {
        try {
            // Check if language already exists
            $stmt = $pdo->prepare('SELECT id FROM technologies WHERE LOWER(nom) = LOWER(?)');
            $stmt->execute([$formData['nom']]);
            
            if ($stmt->fetch()) {
                $error = 'Ce langage existe déjà dans la base de données.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO technologies (nom, logo_url) VALUES (?, ?)');
                $stmt->execute([$formData['nom'], $formData['logo_url']]);
                $success = 'Langage ajouté avec succès!';
                // Clear form on success
                $formData = ['nom' => '', 'logo_url' => ''];
            }
        } catch (PDOException $e) {
            $error = 'Une erreur est survenue lors de l\'ajout du langage.';
        }
    } else {
        $error = 'Veuillez saisir le nom du langage.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un langage | Portfolio Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .logo-preview {
            display: none;
            transition: all 0.3s ease;
        }
        .logo-preview.active {
            display: flex;
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include 'sidebar_etudiant.php'; ?>
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="max-w-3xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <a href="student_dashboard.php" class="text-blue-600 hover:text-blue-800 mr-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-code text-green-500 mr-3"></i>
                        Ajouter un nouveau langage
                    </h1>
                </div>
                <p class="text-gray-600">Ajoutez un langage de programmation ou une technologie à votre portfolio.</p>
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

            <!-- Language Form -->
            <form method="post" class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Language Name -->
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom du langage <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-code text-gray-400"></i>
                            </div>
                            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($formData['nom']); ?>" required
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="JavaScript, Python, PHP...">
                        </div>
                    </div>

                    <!-- Logo URL -->
                    <div>
                        <label for="logo_url" class="block text-sm font-medium text-gray-700 mb-1">
                            URL du logo
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                            <input type="url" id="logo_url" name="logo_url" value="<?php echo htmlspecialchars($formData['logo_url']); ?>"
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="https://example.com/logo.png"
                                   oninput="updateLogoPreview(this.value)">
                        </div>
                        <div class="logo-preview mt-3" id="logoPreview">
                            <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-md">
                                <div class="flex-shrink-0">
                                    <img id="logoImage" src="" alt="Logo preview" class="h-10 w-10 object-contain rounded-md border">
                                </div>
                                <div class="text-sm text-gray-500">
                                    Aperçu du logo
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Vous pouvez trouver des logos sur <a href="https://iconify.design/" target="_blank" class="text-blue-600 hover:underline">Iconify</a> ou <a href="https://simpleicons.org/" target="_blank" class="text-blue-600 hover:underline">Simple Icons</a>.</p>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between border-t border-gray-200">
                    <a href="student_dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                        <i class="fas fa-plus-circle mr-2"></i> Ajouter le langage
                    </button>
                </div>
            </form>

            <!-- Popular Languages Section -->
            <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Langages populaires
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="text-center p-2 hover:bg-gray-50 rounded cursor-pointer" onclick="document.getElementById('nom').value='JavaScript'">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg" class="h-10 w-10 mx-auto mb-1" alt="JavaScript">
                            <span class="text-xs text-gray-700">JavaScript</span>
                        </div>
                        <div class="text-center p-2 hover:bg-gray-50 rounded cursor-pointer" onclick="document.getElementById('nom').value='Python'">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg" class="h-10 w-10 mx-auto mb-1" alt="Python">
                            <span class="text-xs text-gray-700">Python</span>
                        </div>
                        <div class="text-center p-2 hover:bg-gray-50 rounded cursor-pointer" onclick="document.getElementById('nom').value='PHP'">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" class="h-10 w-10 mx-auto mb-1" alt="PHP">
                            <span class="text-xs text-gray-700">PHP</span>
                        </div>
                        <div class="text-center p-2 hover:bg-gray-50 rounded cursor-pointer" onclick="document.getElementById('nom').value='Java'">
                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg" class="h-10 w-10 mx-auto mb-1" alt="Java">
                            <span class="text-xs text-gray-700">Java</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logo URL preview
        function updateLogoPreview(url) {
            const preview = document.getElementById('logoPreview');
            const logoImage = document.getElementById('logoImage');
            
            if (url) {
                logoImage.src = url;
                logoImage.onerror = function() {
                    preview.classList.remove('active');
                };
                logoImage.onload = function() {
                    preview.classList.add('active');
                };
            } else {
                preview.classList.remove('active');
            }
        }

        // Initialize logo preview if there's already a value
        document.addEventListener('DOMContentLoaded', function() {
            const logoUrl = document.getElementById('logo_url');
            if (logoUrl.value) {
                updateLogoPreview(logoUrl.value);
            }
        });
    </script>
</body>
</html>