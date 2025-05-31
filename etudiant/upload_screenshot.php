<?php
// upload_screenshot.php
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
$formData = ['project_id' => ''];

// Get user's projects for dropdown
$stmt = $pdo->prepare('SELECT id, titre FROM projects WHERE user_id = ? ORDER BY date_realisation DESC');
$stmt->execute([$_SESSION['user_id']]);
$projects = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['project_id'] = $_POST['project_id'] ?? '';
    $uploadedFile = $_FILES['screenshot'] ?? null;

    if ($formData['project_id'] && $uploadedFile && $uploadedFile['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $uploadedFile['tmp_name'];
        $fileName = basename($uploadedFile['name']);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowed)) {
            $uploadDir = '../uploads/screenshots/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $newName = 'ss_' . uniqid() . '.' . $ext;
            $dest = $uploadDir . $newName;
            
            if (move_uploaded_file($fileTmp, $dest)) {
                $url = 'uploads/screenshots/' . $newName;
                $stmt = $pdo->prepare('INSERT INTO screenshots (project_id, image_url) VALUES (?, ?)');
                if ($stmt->execute([$formData['project_id'], $url])) {
                    $success = 'Capture d\'écran ajoutée avec succès!';
                    $formData['project_id'] = ''; // Reset form on success
                } else {
                    // Delete the uploaded file if DB insert failed
                    unlink($dest);
                    $error = 'Erreur lors de l\'enregistrement dans la base de données.';
                }
            } else {
                $error = 'Erreur lors du téléchargement du fichier.';
            }
        } else {
            $error = 'Format de fichier non autorisé. Formats acceptés: JPG, PNG, GIF, WEBP.';
        }
    } else {
        $error = 'Veuillez sélectionner un projet et une image valide.';
        if ($uploadedFile && $uploadedFile['error'] !== UPLOAD_ERR_OK) {
            $error = 'Erreur lors du téléchargement du fichier: ' . getUploadError($uploadedFile['error']);
        }
    }
}

function getUploadError($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'Fichier trop volumineux (limite du serveur)',
        UPLOAD_ERR_FORM_SIZE => 'Fichier trop volumineux (limite du formulaire)',
        UPLOAD_ERR_PARTIAL => 'Téléchargement partiel',
        UPLOAD_ERR_NO_FILE => 'Aucun fichier téléchargé',
        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant',
        UPLOAD_ERR_CANT_WRITE => 'Erreur d\'écriture sur le disque',
        UPLOAD_ERR_EXTENSION => 'Extension PHP a arrêté le téléchargement'
    ];
    return $errors[$errorCode] ?? 'Erreur inconnue';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une capture | Portfolio Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .file-upload {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }
        .file-upload:hover {
            border-color: #3b82f6;
            background-color: #f8fafc;
        }
        .file-upload.dragover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .preview-container {
            display: none;
            transition: all 0.3s ease;
        }
        .preview-container.active {
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
                        <i class="fas fa-camera text-purple-500 mr-3"></i>
                        Ajouter une capture d'écran
                    </h1>
                </div>
                <p class="text-gray-600">Ajoutez des captures d'écran à vos projets pour illustrer votre travail.</p>
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

            <!-- Upload Form -->
            <form method="post" enctype="multipart/form-data" class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Project Selection -->
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Projet <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-project-diagram text-gray-400"></i>
                            </div>
                            <select id="project_id" name="project_id" required
                                    class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Sélectionnez un projet</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?php echo $project['id']; ?>" <?php echo ($formData['project_id'] == $project['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($project['titre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (empty($projects)): ?>
                            <p class="mt-1 text-xs text-red-500">Vous n'avez aucun projet. <a href="add_project.php" class="text-blue-600 hover:underline">Créez d'abord un projet</a>.</p>
                        <?php endif; ?>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Capture d'écran <span class="text-red-500">*</span>
                        </label>
                        
                        <div id="fileUpload" class="file-upload relative rounded-lg p-6 text-center cursor-pointer">
                            <input type="file" id="screenshot" name="screenshot" accept="image/*" required
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   onchange="previewImage(this)">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium text-blue-600">Cliquez pour télécharger</span> ou glissez-déposez
                                </p>
                                <p class="text-xs text-gray-500">Formats supportés: JPG, PNG, GIF, WEBP (max 5MB)</p>
                            </div>
                        </div>
                        
                        <!-- Image Preview -->
                        <div id="previewContainer" class="preview-container mt-4">
                            <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <img id="imagePreview" src="#" alt="Aperçu" class="h-16 w-16 object-cover rounded border">
                                    <div>
                                        <p id="fileName" class="text-sm font-medium text-gray-700 truncate max-w-xs"></p>
                                        <p id="fileSize" class="text-xs text-gray-500"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="clearPreview()" class="text-gray-400 hover:text-red-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between border-t border-gray-200">
                    <a href="student_dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200" <?php echo empty($projects) ? 'disabled' : ''; ?>>
                        <i class="fas fa-save mr-2"></i> Enregistrer la capture
                    </button>
                </div>
            </form>

            <!-- Recent Screenshots -->
            <?php if (!empty($projects)): ?>
                <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-history text-gray-500 mr-2"></i>
                            Vos captures récentes
                        </h2>
                        <?php
                        $recent_stmt = $pdo->prepare('
                            SELECT s.image_url, p.titre 
                            FROM screenshots s
                            JOIN projects p ON s.project_id = p.id
                            WHERE p.user_id = ?
                            ORDER BY s.id DESC
                            LIMIT 4
                        ');
                        $recent_stmt->execute([$_SESSION['user_id']]);
                        $recent_screenshots = $recent_stmt->fetchAll();
                        ?>
                        
                        <?php if (!empty($recent_screenshots)): ?>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <?php foreach ($recent_screenshots as $ss): ?>
                                    <div class="relative group">
                                        <img src="../<?php echo htmlspecialchars($ss['image_url']); ?>" alt="Capture récente" class="rounded-lg h-24 w-full object-cover border">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center transition-opacity">
                                            <span class="text-white text-xs text-center px-2"><?php echo htmlspecialchars($ss['titre']); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-image text-3xl text-gray-300 mb-2"></i>
                                <p>Aucune capture récente</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Drag and drop functionality
        const fileUpload = document.getElementById('fileUpload');
        const fileInput = document.getElementById('screenshot');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUpload.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            fileUpload.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileUpload.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            fileUpload.classList.add('dragover');
        }
        
        function unhighlight() {
            fileUpload.classList.remove('dragover');
        }
        
        fileUpload.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                fileInput.files = files;
                previewImage(fileInput);
            }
        }
        
        // Image preview
        function previewImage(input) {
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux (max 5MB)');
                    clearPreview();
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Format de fichier non supporté');
                    clearPreview();
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);
                    previewContainer.classList.add('active');
                }
                
                reader.readAsDataURL(file);
            }
        }
        
        function clearPreview() {
            const previewContainer = document.getElementById('previewContainer');
            const fileInput = document.getElementById('screenshot');
            
            fileInput.value = '';
            previewContainer.classList.remove('active');
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</body>
</html>