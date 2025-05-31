<?php
// add_project.php
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
    'titre' => '',
    'description' => '',
    'github_link' => '',
    'date_realisation' => date('Y-m-d')
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'titre' => trim($_POST['titre'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'github_link' => trim($_POST['github_link'] ?? ''),
        'date_realisation' => $_POST['date_realisation'] ?? ''
    ];

    if ($formData['titre'] && $formData['description'] && $formData['date_realisation']) {
        try {
            $stmt = $pdo->prepare('INSERT INTO projects (user_id, titre, description, github_link, date_realisation) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                $_SESSION['user_id'],
                $formData['titre'],
                $formData['description'],
                $formData['github_link'],
                $formData['date_realisation']
            ]);
            $success = 'Projet ajouté avec succès!';
            // Clear form on success
            $formData = [
                'titre' => '',
                'description' => '',
                'github_link' => '',
                'date_realisation' => date('Y-m-d')
            ];
        } catch (PDOException $e) {
            $error = 'Une erreur est survenue lors de l\'ajout du projet.';
        }
    } else {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un projet | Portfolio Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .github-preview {
            display: none;
        }
        .github-preview.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include './sidebar_etudiant.php'; ?>
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="max-w-3xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <a href="./student_dashboard.php" class="text-blue-600 hover:text-blue-800 mr-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-plus-circle text-blue-500 mr-3"></i>
                        Ajouter un nouveau projet
                    </h1>
                </div>
                <p class="text-gray-600">Remplissez les détails de votre projet pour l'ajouter à votre portfolio.</p>
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

            <!-- Project Form -->
            <form method="post" class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Project Title -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">
                            Titre du projet <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400"></i>
                            </div>
                            <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($formData['titre']); ?>" required
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Mon super projet">
                        </div>
                    </div>

                    <!-- Project Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                                <i class="fas fa-align-left text-gray-400"></i>
                            </div>
                            <textarea id="description" name="description" rows="5" required
                                      class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Décrivez votre projet en détail..."><?php echo htmlspecialchars($formData['description']); ?></textarea>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Décrivez les technologies utilisées, les fonctionnalités et les défis rencontrés.</p>
                    </div>

                    <!-- GitHub Link -->
                    <div>
                        <label for="github_link" class="block text-sm font-medium text-gray-700 mb-1">
                            Lien GitHub
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-github text-gray-400"></i>
                            </div>
                            <input type="url" id="github_link" name="github_link" value="<?php echo htmlspecialchars($formData['github_link']); ?>"
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://github.com/votre-utilisateur/votre-projet"
                                   oninput="updateGithubPreview(this.value)">
                        </div>
                        <div id="githubPreview" class="github-preview mt-2 bg-gray-50 p-3 rounded-md text-sm">
                            <div class="flex items-center">
                                <i class="fab fa-github mr-2 text-gray-500"></i>
                                <span id="githubRepoName" class="font-medium"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Realization Date -->
                    <div>
                        <label for="date_realisation" class="block text-sm font-medium text-gray-700 mb-1">
                            Date de réalisation <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="date" id="date_realisation" name="date_realisation" value="<?php echo htmlspecialchars($formData['date_realisation']); ?>" required
                                   class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between border-t border-gray-200">
                    <a href="student_dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i> Enregistrer le projet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // GitHub URL preview
        function updateGithubPreview(url) {
            const preview = document.getElementById('githubPreview');
            const repoName = document.getElementById('githubRepoName');
            
            if (url && url.includes('github.com')) {
                try {
                    const urlObj = new URL(url);
                    const pathParts = urlObj.pathname.split('/').filter(Boolean);
                    if (pathParts.length >= 2) {
                        repoName.textContent = `${pathParts[0]}/${pathParts[1]}`;
                        preview.classList.add('active');
                        return;
                    }
                } catch (e) {
                    // Invalid URL
                }
            }
            
            preview.classList.remove('active');
        }

        // Initialize date picker with today's date if empty
        document.addEventListener('DOMContentLoaded', function() {
            const dateField = document.getElementById('date_realisation');
            if (dateField && !dateField.value) {
                dateField.value = new Date().toISOString().split('T')[0];
            }
            
            // Initialize GitHub preview if there's already a value
            const githubLink = document.getElementById('github_link');
            if (githubLink.value) {
                updateGithubPreview(githubLink.value);
            }
        });
    </script>
</body>
</html>