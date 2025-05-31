<?php
// etudiantslistes.php
include '../pdo.php';
include '../navbar.php';
// Récupérer tous les étudiants
$stmt = $pdo->prepare("SELECT id, nom, prenom, email, filiere, annee_academique, site FROM users WHERE role = 'etudiant' ORDER BY nom, prenom");
$stmt->execute();
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-blue-700 mb-8">Liste des étudiants</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($students as $student): ?>
                <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-1"><?php echo htmlspecialchars($student['prenom'] . ' ' . $student['nom']); ?></h2>
                        <div class="text-gray-600 text-sm mb-1">Email : <?php echo htmlspecialchars($student['email']); ?></div>
                        <div class="text-gray-500 text-xs mb-2">Filière : <?php echo htmlspecialchars($student['filiere']); ?> | Année : <?php echo htmlspecialchars($student['annee_academique']); ?> | Site : <?php echo htmlspecialchars($student['site']); ?></div>
                    </div>
                    <a href="portfolio.php?id=<?php echo $student['id']; ?>" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center font-semibold">Voir le portfolio</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
