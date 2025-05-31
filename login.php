<?php
// login.php
include 'pdo.php';
include 'navbar.php';

// Redirect if already logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/admin_dashboard.php');
    } elseif ($_SESSION['role'] === 'etudiant') {
        header('Location: ./etudiant/student_dashboard.php');
    } else {
        header('Location: ./index.php');
    }
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Security measure
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'] ?? $user['email'];
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: admin/admin_dashboard.php');
            } elseif ($user['role'] === 'etudiant') {
                header('Location: ./etudiant/student_dashboard.php');
            } else {
                header('Location: ./index.php');
            }
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect.';
            // Small delay to prevent brute force
            sleep(1);
        }
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Plateforme Étudiante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
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
                            <h2 class="text-4xl font-bold mb-6">Bienvenue sur la plateforme étudiante</h2>
                            <p class="text-xl mb-8 opacity-90">Accédez à votre espace personnel, consultez votre portfolio et connectez-vous avec la communauté.</p>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="fas fa-check-circle text-2xl text-white opacity-90"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-semibold">Accès sécurisé</h3>
                                        <p class="opacity-80">Protection de vos données personnelles</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="fas fa-check-circle text-2xl text-white opacity-90"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-semibold">Portfolio complet</h3>
                                        <p class="opacity-80">Montrez vos projets et compétences</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="fas fa-check-circle text-2xl text-white opacity-90"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-semibold">Réseau étudiant</h3>
                                        <p class="opacity-80">Échangez avec vos pairs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Login Form -->
                    <div class="md:w-1/2 p-12">
                        <div class="max-w-md mx-auto">
                            <div class="text-center mb-10">
                                <h1 class="text-4xl font-bold text-gray-800 mb-4">Connectez-vous</h1>
                                <p class="text-xl text-gray-600">Accédez à votre compte</p>
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
                            
                            <form method="post" class="space-y-6">
                                <div>
                                    <label for="email" class="block form-label font-medium text-gray-700 mb-2">Adresse email *</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400 text-xl"></i>
                                        </div>
                                        <input type="email" id="email" name="email" required
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
                                               placeholder="••••••••">
                                        <div class="absolute inset-y-0 right-0 pr-5 flex items-center">
                                            <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="togglePasswordVisibility('password')">
                                                <i class="fas fa-eye text-xl" id="togglePasswordIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-right">
                                        <a href="forgot_password.php" class="text-sm text-blue-600 hover:underline">Mot de passe oublié ?</a>
                                    </div>
                                </div>
                                
                                <div class="pt-2">
                                    <button type="submit" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-sm text-xl font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-105">
                                        <i class="fas fa-sign-in-alt mr-3"></i> Se connecter
                                    </button>
                                </div>
                            </form>
                            
                            <div class="mt-8 text-center">
                                <p class="text-lg text-gray-600">
                                    Pas encore de compte ? 
                                    <a href="register.php" class="font-bold text-blue-600 hover:text-blue-500 hover:underline">Créer un compte</a>
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
        
        // Focus on email field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>