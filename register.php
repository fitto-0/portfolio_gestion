<?php
// register.php
include 'pdo.php';
include 'navbar.php';

// Redirect if already logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ./index.php');
    exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $annee_academique = $_POST['annee_academique'] ?? '';
    $filiere = $_POST['filiere'] ?? '';
    $site = $_POST['site'] ?? '';

    // Validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($annee_academique) || empty($filiere) || empty($site)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez entrer une adresse email valide.';
    } elseif (strlen($password) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Cet email est déjà utilisé.';
        } else {
            // Create account
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (nom, prenom, email, password, annee_academique, filiere, site, role) VALUES (?, ?, ?, ?, ?, ?, ?, "etudiant")');
            if ($stmt->execute([$nom, $prenom, $email, $hash, $annee_academique, $filiere, $site])) {
                $success = 'Inscription réussie. <a href="login.php" class="text-blue-600 hover:underline font-medium">Connectez-vous maintenant</a>.';
                // Clear form on success
                $nom = $prenom = $email = $annee_academique = $filiere = $site = '';
            } else {
                $error = 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | Plateforme Étudiante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }
        .password-strength {
            height: 6px;
            transition: all 0.3s ease;
        }
        .form-input {
            font-size: 1.1rem;
            padding: 1rem 1.5rem;
        }
        .form-label {
            font-size: 1.1rem;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <main class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-6xl">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="md:flex">
                    <!-- Hero Section -->
                    <div class="md:w-1/2 gradient-bg p-12 text-white flex flex-col justify-center">
                        <div class="max-w-md mx-auto">
                            <h2 class="text-4xl font-bold mb-6">Rejoignez notre communauté étudiante</h2>
                            <p class="text-xl mb-8 opacity-90">Créez votre portfolio professionnel et connectez-vous avec d'autres étudiants et recruteurs.</p>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="fas fa-check-circle text-2xl text-white opacity-90"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-semibold">Portfolio personnalisé</h3>
                                        <p class="opacity-80">Montrez vos projets et compétences</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="fas fa-check-circle text-2xl text-white opacity-90"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-semibold">Réseau étudiant</h3>
                                        <p class="opacity-80">Connectez-vous avec vos pairs</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="fas fa-check-circle text-2xl text-white opacity-90"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-semibold">Opportunités professionnelles</h3>
                                        <p class="opacity-80">Découvrez des stages et emplois</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Registration Form -->
                    <div class="md:w-1/2 p-12">
                        <div class="max-w-md mx-auto">
                            <div class="text-center mb-10">
                                <h1 class="text-4xl font-bold text-gray-800 mb-4">Créer un compte</h1>
                                <p class="text-xl text-gray-600">Commencez votre parcours académique avec nous</p>
                            </div>
                            
                            <?php if ($error): ?>
                                <div class="bg-red-50 border-l-4 border-red-500 p-6 mb-8 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-lg text-red-700"><?php echo $error; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-8 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-lg text-green-700"><?php echo $success; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <form method="post" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="nom" class="block form-label font-medium text-gray-700 mb-2">Nom *</label>
                                        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom ?? ''); ?>" required
                                               class="block w-full form-input border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Votre nom">
                                    </div>
                                    <div>
                                        <label for="prenom" class="block form-label font-medium text-gray-700 mb-2">Prénom *</label>
                                        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom ?? ''); ?>" required
                                               class="block w-full form-input border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Votre prénom">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="email" class="block form-label font-medium text-gray-700 mb-2">Email *</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400 text-xl"></i>
                                        </div>
                                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required
                                               class="block w-full form-input pl-16 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="votre@email.com">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="password" class="block form-label font-medium text-gray-700 mb-2">Mot de passe *</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400 text-xl"></i>
                                        </div>
                                        <input type="password" id="password" name="password" required
                                               class="block w-full form-input pl-16 pr-16 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="••••••••" onkeyup="checkPasswordStrength()">
                                        <div class="absolute inset-y-0 right-0 pr-5 flex items-center">
                                            <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="togglePasswordVisibility('password')">
                                                <i class="fas fa-eye text-xl" id="togglePasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-4 gap-2" id="passwordStrength">
                                        <div class="password-strength bg-gray-200 rounded-full" id="strength1"></div>
                                        <div class="password-strength bg-gray-200 rounded-full" id="strength2"></div>
                                        <div class="password-strength bg-gray-200 rounded-full" id="strength3"></div>
                                        <div class="password-strength bg-gray-200 rounded-full" id="strength4"></div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Minimum 8 caractères avec majuscules, chiffres et symboles</p>
                                </div>
                                
                                <div>
                                    <label for="confirm_password" class="block form-label font-medium text-gray-700 mb-2">Confirmer le mot de passe *</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400 text-xl"></i>
                                        </div>
                                        <input type="password" id="confirm_password" name="confirm_password" required
                                               class="block w-full form-input pl-16 pr-16 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="••••••••">
                                        <div class="absolute inset-y-0 right-0 pr-5 flex items-center">
                                            <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="togglePasswordVisibility('confirm_password')">
                                                <i class="fas fa-eye text-xl" id="toggleConfirmPasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="annee_academique" class="block form-label font-medium text-gray-700 mb-2">Année académique *</label>
                                        <select id="annee_academique" name="annee_academique" required
                                                class="block w-full form-input border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Sélectionner</option>
                                            <option value="1ère année" <?php echo ($annee_academique ?? '') === '1ère année' ? 'selected' : ''; ?>>1ère année</option>
                                            <option value="2ème année" <?php echo ($annee_academique ?? '') === '2ème année' ? 'selected' : ''; ?>>2ème année</option>
                                            <option value="3ème année" <?php echo ($annee_academique ?? '') === '3ème année' ? 'selected' : ''; ?>>3ème année</option>
                                            <option value="4ème année" <?php echo ($annee_academique ?? '') === '4ème année' ? 'selected' : ''; ?>>4ème année</option>
                                            <option value="5ème année" <?php echo ($annee_academique ?? '') === '5ème année' ? 'selected' : ''; ?>>5ème année</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="filiere" class="block form-label font-medium text-gray-700 mb-2">Filière *</label>
                                        <input type="text" id="filiere" name="filiere" value="<?php echo htmlspecialchars($filiere ?? ''); ?>" required
                                               class="block w-full form-input border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Votre filière">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="site" class="block form-label font-medium text-gray-700 mb-2">Site *</label>
                                    <select id="site" name="site" required
                                            class="block w-full form-input border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Sélectionner un site</option>
                                        <option value="Site 1" <?php echo ($site ?? '') === 'Site 1' ? 'selected' : ''; ?>>Site 1</option>
                                        <option value="Site 2" <?php echo ($site ?? '') === 'Site 2' ? 'selected' : ''; ?>>Site 2</option>
                                        <option value="Site 3" <?php echo ($site ?? '') === 'Site 3' ? 'selected' : ''; ?>>Site 3</option>
                                        <option value="Site 4" <?php echo ($site ?? '') === 'Site 4' ? 'selected' : ''; ?>>Site 4</option>
                                        <option value="Site 5" <?php echo ($site ?? '') === 'Site 5' ? 'selected' : ''; ?>>Site 5</option>
                                    </select>
                                </div>
                                
                                <div class="pt-4">
                                    <button type="submit" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-sm text-xl font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-105">
                                        <i class="fas fa-user-plus mr-3"></i> S'inscrire maintenant
                                    </button>
                                </div>
                            </form>
                            
                            <div class="mt-8 text-center">
                                <p class="text-lg text-gray-600">
                                    Déjà un compte ? 
                                    <a href="login.php" class="font-bold text-blue-600 hover:text-blue-500 hover:underline">Connectez-vous ici</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(`toggle${fieldId.charAt(0).toUpperCase() + fieldId.slice(1)}Icon`);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
        
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBars = [
                document.getElementById('strength1'),
                document.getElementById('strength2'),
                document.getElementById('strength3'),
                document.getElementById('strength4')
            ];
            
            // Reset all bars
            strengthBars.forEach(bar => {
                bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
                bar.classList.add('bg-gray-200');
            });
            
            if (password.length === 0) return;
            
            // Calculate strength
            let strength = 0;
            if (password.length > 0) strength += 1;
            if (password.length >= 8) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Update bars
            for (let i = 0; i < strengthBars.length; i++) {
                if (i < strength) {
                    strengthBars[i].classList.remove('bg-gray-200');
                    if (strength <= 2) {
                        strengthBars[i].classList.add('bg-red-500');
                    } else if (strength <= 3) {
                        strengthBars[i].classList.add('bg-yellow-500');
                    } else {
                        strengthBars[i].classList.add('bg-green-500');
                    }
                }
            }
        }
        
        // Focus on first field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nom').focus();
        });
    </script>
</body>
</html>