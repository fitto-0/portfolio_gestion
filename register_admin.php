<?php
// register_admin.php
include 'pdo.php';
include 'navbar.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($nom && $prenom && $email && $password) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Cet email est déjà utilisé.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$nom, $prenom, $email, $hash, 'admin']);
            $success = 'Admin créé avec succès.';
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
    <title>Créer un admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Créer un compte admin (temporaire)</h2>
        <?php if ($error): ?>
            <div class="mb-4 text-red-600"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="mb-4 text-green-600"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" class="space-y-4 bg-white p-6 rounded shadow">
            <div>
                <label class="block mb-1 text-gray-700">Nom</label>
                <input type="text" name="nom" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 text-gray-700">Prénom</label>
                <input type="text" name="prenom" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 text-gray-700">Mot de passe</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Créer admin</button>
        </form>
    </div>
</body>
</html>
