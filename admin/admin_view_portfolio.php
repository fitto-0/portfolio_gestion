<?php
// admin_view_portfolio.php
include '../pdo.php';
include '../navbar.php';
include 'sidebar_admin.php';
$user_id = $_GET['id'] ?? null;
if (!$user_id) {
    echo '<div class="text-red-600">Utilisateur non spécifié.</div>';
    exit;
}
// Récupérer l'utilisateur
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) {
    echo '<div class="text-red-600">Utilisateur introuvable.</div>';
    exit;
}
// Récupérer les projets
$stmt = $pdo->prepare('SELECT * FROM projects WHERE user_id = ? ORDER BY date_realisation DESC');
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll();
// Récupérer les captures d'écran
$stmt = $pdo->prepare('SELECT * FROM screenshots WHERE project_id IN (SELECT id FROM projects WHERE user_id = ?)');
$stmt->execute([$user_id]);
$screenshots = $stmt->fetchAll();
// Récupérer les langages utilisés
$stmt = $pdo->prepare('SELECT t.* FROM technologies t JOIN project_technologies pt ON pt.technology_id = t.id JOIN projects p ON pt.project_id = p.id WHERE p.user_id = ? GROUP BY t.id');
$stmt->execute([$user_id]);
$user_languages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Portfolio de <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> (Admin)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex">
    <?php include 'sidebar_admin.php'; ?>
    <main class="flex-1 px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-blue-700 mb-2">Portfolio de <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h1>
            <div class="text-gray-700 mb-1">Email : <?php echo htmlspecialchars($user['email']); ?></div>
            <div class="text-gray-700 mb-1">Filière : <?php echo htmlspecialchars($user['filiere']); ?> | Année : <?php echo htmlspecialchars($user['annee_academique']); ?> | Site : <?php echo htmlspecialchars($user['site']); ?></div>
        </div>
        <section class="mb-12">
            <h2 class="text-xl font-semibold mb-4">Projets</h2>
            <?php if (empty($projects)): ?>
                <div class="text-gray-500">Aucun projet à afficher.</div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($projects as $project): ?>
                        <div class="bg-white rounded-lg shadow p-5 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-blue-700 mb-2"><?php echo htmlspecialchars($project['titre']); ?></h3>
                                <p class="text-gray-700 mb-2"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                                <?php if ($project['github_link']): ?>
                                    <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="text-blue-500 hover:underline text-sm">Lien GitHub</a>
                                <?php endif; ?>
                                <div class="text-xs text-gray-400 mt-2">Réalisé le : <?php echo htmlspecialchars($project['date_realisation']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <section>
            <h2 class="text-xl font-semibold mb-4">Captures d'écran</h2>
            <?php if (empty($screenshots)): ?>
                <div class="text-gray-500">Aucune capture d'écran à afficher.</div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($screenshots as $ss): ?>
                        <div class="bg-white rounded-lg shadow p-3 flex flex-col items-center">
                            <img src="../<?php echo htmlspecialchars($ss['image_url']); ?>" alt="screenshot" class="rounded mb-2 max-h-40 object-contain w-full" />
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <section class="mt-12">
            <h2 class="text-xl font-semibold mb-4">Langages utilisés</h2>
            <?php if (empty($user_languages)): ?>
                <div class="text-gray-500">Aucun langage sélectionné pour ce portfolio.</div>
            <?php else: ?>
                <div class="flex flex-wrap gap-4">
                    <?php foreach ($user_languages as $lang): ?>
                        <div class="bg-white rounded-lg shadow px-4 py-2 flex items-center space-x-3">
                            <?php if ($lang['logo_url']): ?>
                                <img src="<?php echo htmlspecialchars($lang['logo_url']); ?>" alt="logo" class="h-8 w-8 object-contain rounded-full border" />
                            <?php endif; ?>
                            <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($lang['nom']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
