<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rey de Jardineros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #198754;
            color: white;
        }
        .nav-link {
            color: rgba(255,255,255,.8);
            padding: 0.8rem 1rem;
        }
        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
        }
        .nav-link.active {
            background: rgba(255,255,255,.2);
            color: white;
        }
        .content {
            padding: 20px;
        }
        .stats-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="d-flex flex-column p-3">
                    <h4 class="text-white mb-4">Rey de Jardineros</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                        </li>
                        @if(auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-users me-2"></i> Usuarios
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-tasks me-2"></i> Trabajos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-chart-bar me-2"></i> Reportes
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-9 col-lg-10 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard</h2>
                    <span class="text-muted">Bienvenido, {{ auth()->user()->name }}</span>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <h5 class="card-title">Trabajos Activos</h5>
                                <h3 class="text-primary">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <h5 class="card-title">Trabajadores</h5>
                                <h3 class="text-success">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card stats-card">
                            <div class="card-body">
                                <h5 class="card-title">Trabajos Completados</h5>
                                <h3 class="text-info">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>