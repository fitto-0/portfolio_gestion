<?php
// profile.php
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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'nom' => trim($_POST['nom'] ?? ''),
        'prenom' => trim($_POST['prenom'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'annee_academique' => $_POST['annee_academique'] ?? '',
        'filiere' => $_POST['filiere'] ?? '',
        'site' => $_POST['site'] ?? '',
        'bio' => trim($_POST['bio'] ?? ''),
        'linkedin' => trim($_POST['linkedin'] ?? ''),
        'github' => trim($_POST['github'] ?? '')
    ];

    // Basic validation
    if (empty($formData['nom']) || empty($formData['prenom']) || empty($formData['email']) || 
        empty($formData['annee_academique']) || empty($formData['filiere']) || empty($formData['site'])) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez entrer une adresse email valide.';
    } else {
        try {
            $stmt = $pdo->prepare('UPDATE users SET 
                nom=?, prenom=?, email=?, annee_academique=?, filiere=?, site=?, bio=?, linkedin=?, github=?, updated_at=NOW() 
                WHERE id=?');
            $stmt->execute([
                $formData['nom'],
                $formData['prenom'],
                $formData['email'],
                $formData['annee_academique'],
                $formData['filiere'],
                $formData['site'],
                $formData['bio'],
                $formData['linkedin'],
                $formData['github'],
                $user_id
            ]);
            
            $success = 'Profil mis à jour avec succès!';
            
            // Refresh user data
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            // Update session data
            $_SESSION['username'] = $formData['prenom'] . ' ' . $formData['nom'];
            $_SESSION['email'] = $formData['email'];
            
        } catch (PDOException $e) {
            $error = 'Une erreur est survenue lors de la mise à jour du profil.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil | Portfolio Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-picture {
            transition: all 0.3s ease;
        }
        .profile-picture:hover {
            transform: scale(1.05);
        }
        .social-input {
            padding-left: 2.5rem;
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include './sidebar_etudiant.php'; ?>
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <a href="./student_dashboard.php" class="text-blue-600 hover:text-blue-800 mr-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-circle text-pink-500 mr-3"></i>
                        Mon Profil
                    </h1>
                </div>
                <p class="text-gray-600">Gérez les informations de votre profil étudiant.</p>
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

            <!-- Profile Form -->
            <form method="post" class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Profile Picture -->
                    <div class="md:col-span-2 flex flex-col items-center">
                        <div class="relative mb-4">
                            <img src="<?php echo isset($user['avatar']) ? htmlspecialchars($user['avatar']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['prenom'] . '+' . urlencode($user['nom'])) . '&background=3b82f6&color=fff&size=128'; ?>" 
                                 alt="Photo de profil" 
                                 class="profile-picture h-32 w-32 rounded-full border-4 border-white shadow-lg">
                            <button type="button" class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2 shadow hover:bg-blue-600">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-id-card text-blue-500 mr-2"></i>
                            Informations personnelles
                        </h2>
                    </div>

                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required
                               class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required
                               class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                               class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Academic Information -->
                    <div class="md:col-span-2 mt-4">
                        <h2 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-graduation-cap text-indigo-500 mr-2"></i>
                            Informations académiques
                        </h2>
                    </div>

                    <div>
                        <label for="annee_academique" class="block text-sm font-medium text-gray-700 mb-1">
                            Année académique <span class="text-red-500">*</span>
                        </label>
                        <select id="annee_academique" name="annee_academique" required
                                class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionnez une année</option>
                            <option value="1ère année" <?php echo ($user['annee_academique'] === '1ère année') ? 'selected' : ''; ?>>1ère année</option>
                            <option value="2ème année" <?php echo ($user['annee_academique'] === '2ème année') ? 'selected' : ''; ?>>2ème année</option>
                            <option value="3ème année" <?php echo ($user['annee_academique'] === '3ème année') ? 'selected' : ''; ?>>3ème année</option>
                            <option value="4ème année" <?php echo ($user['annee_academique'] === '4ème année') ? 'selected' : ''; ?>>4ème année</option>
                            <option value="5ème année" <?php echo ($user['annee_academique'] === '5ème année') ? 'selected' : ''; ?>>5ème année</option>
                        </select>
                    </div>

                    <div>
                        <label for="filiere" class="block text-sm font-medium text-gray-700 mb-1">
                            Filière <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="filiere" name="filiere" value="<?php echo htmlspecialchars($user['filiere']); ?>" required
                               class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="site" class="block text-sm font-medium text-gray-700 mb-1">
                            Site <span class="text-red-500">*</span>
                        </label>
                        <select id="site" name="site" required
                                class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionnez un site</option>
                            <option value="Site 1" <?php echo ($user['site'] === 'Site 1') ? 'selected' : ''; ?>>Site 1</option>
                            <option value="Site 2" <?php echo ($user['site'] === 'Site 2') ? 'selected' : ''; ?>>Site 2</option>
                            <option value="Site 3" <?php echo ($user['site'] === 'Site 3') ? 'selected' : ''; ?>>Site 3</option>
                            <option value="Site 4" <?php echo ($user['site'] === 'Site 4') ? 'selected' : ''; ?>>Site 4</option>
                            <option value="Site 5" <?php echo ($user['site'] === 'Site 5') ? 'selected' : ''; ?>>Site 5</option>
                        </select>
                    </div>

                    <!-- Additional Information -->
                    <div class="md:col-span-2 mt-4">
                        <h2 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">
                            <i class="fas fa-info-circle text-green-500 mr-2"></i>
                            Informations supplémentaires
                        </h2>
                    </div>

                    <div class="md:col-span-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                            Bio / Description
                        </label>
                        <textarea id="bio" name="bio" rows="3"
                                  class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Parlez un peu de vous, vos compétences, vos centres d'intérêt..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>

                    <!-- Social Links -->
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Réseaux professionnels</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-linkedin text-blue-700"></i>
                                    </div>
                                    <input type="url" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($user['linkedin'] ?? ''); ?>"
                                           class="social-input form-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="https://linkedin.com/in/votre-profil">
                                </div>
                            </div>
                            <div>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-github text-gray-800"></i>
                                    </div>
                                    <input type="url" id="github" name="github" value="<?php echo htmlspecialchars($user['github'] ?? ''); ?>"
                                           class="social-input form-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="https://github.com/votre-utilisateur">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between border-t border-gray-200">
                    <a href="student_dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Profile picture upload functionality would go here
        document.addEventListener('DOMContentLoaded', function() {
            // This would be implemented with a file upload modal
            const profilePicture = document.querySelector('.profile-picture');
            profilePicture.addEventListener('click', function() {
                console.log('Profile picture click - would open upload modal');
            });
        });
    </script>
</body>
</html>