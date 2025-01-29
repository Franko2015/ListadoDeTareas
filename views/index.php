<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg bg-gray-800 p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold"><i class="fas fa-tasks"></i> Lista de Tareas</h1>
            <button id="toggle-dark-mode" class="bg-gray-700 p-2 rounded-lg" onclick="toggleDarkMode()">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        <!-- Formulario para agregar tareas -->
        <form action="<?php echo BASE_URL; ?>/create-task" method="POST" class="flex gap-2 mb-4">
            <input type="text" name="descripcion" class="flex-1 p-2 rounded-lg bg-gray-700 text-white border border-gray-600" 
                   placeholder="Nueva tarea..." required>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg">
                <i class="fas fa-plus"></i>
            </button>
        </form>

        <!-- Filtros para tareas -->
        <div class="flex gap-2 mb-4">
            <button onclick="filterTasks('all')" class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">Todas</button>
            <button onclick="filterTasks('PENDIENTE')" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">Pendientes</button>
            <button onclick="filterTasks('COMPLETADA')" class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">Completadas</button>
        </div>

        <!-- Lista de tareas -->
        <ul class="space-y-2 h-96 overflow-y-scroll">
            <?php
            require_once DB_PATH . '_connect.php';
            $result = $conn->query("SELECT * FROM listado ORDER BY fecha_creacion DESC");

            while ($row = $result->fetch_assoc()) {
                $isChecked = $row['estado'] ? 'checked' : '';
                $taskClass = $row['estado'] ? 'line-through text-gray-400' : '';
            ?>
                <li class="task-item flex items-center justify-between bg-gray-700 p-3 rounded-lg">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="cursor-pointer" data-id="<?= $row['id']; ?>" <?= $isChecked; ?>
                               onchange="updateStatus(this)">
                        <span class="task-text <?= $taskClass; ?>">
                            <?= htmlspecialchars($row['descripcion']); ?>
                        </span>
                    </div>
                    <button onclick="deleteTask(<?= $row['id']; ?>)" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </li>
            <?php
            }
            $conn->close();
            ?>
        </ul>
    </div>

    <!-- Scripts -->
    <script>
    const baseUrl = '<?php echo BASE_URL; ?>';
    
    function updateStatus(checkbox) {
        const taskId = checkbox.dataset.id;
        const isChecked = checkbox.checked;
        
        fetch(`${baseUrl}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${taskId}&estado=${isChecked ? 1 : 0}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                checkbox.nextElementSibling.classList.toggle('line-through');
                checkbox.nextElementSibling.classList.toggle('text-gray-400');
            }
        });
    }

    function deleteTask(taskId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
            fetch(`${baseUrl}/delete-task`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${taskId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function filterTasks(filter) {
        const tasks = document.querySelectorAll('.task-item');
        tasks.forEach(task => {
            const isCompleted = task.querySelector('input[type="checkbox"]').checked;
            if (filter === 'all' || (filter === 'PENDIENTE' && !isCompleted) || (filter === 'COMPLETADA' && isCompleted)) {
                task.classList.remove('hidden');
                task.classList.add('block');
            } else {
                task.classList.remove('block');
                task.classList.add('hidden');
            }
        });
    }

    // Modo oscuro
    function toggleDarkMode() {
        const html = document.documentElement;
        const isDark = html.getAttribute('data-theme') === 'dark';
        html.setAttribute('data-theme', isDark ? 'light' : 'dark');
        
        const icon = document.querySelector('#toggle-dark-mode i');
        icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        
        if (isDark) {
            document.body.classList.remove('bg-gray-900');
            document.body.classList.add('bg-gray-100');
        } else {
            document.body.classList.remove('bg-gray-100');
            document.body.classList.add('bg-gray-900');
        }
    }
    </script>
</body>
</html>
