<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Oficina Laravel') - Gestão de Oficina Mecânica</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- FontAwesome 5 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #212529;
            min-height: 100vh;
        }

        /* Estrutura da Barra Lateral */
        #wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        #sidebar {
            width: 250px;
            min-width: 250px;
            background-color: #343a40;
            color: #c2c7d0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            border-right: 1px solid #4b545c;
        }

        .sidebar-brand {
            padding: 18px 16px;
            background-color: #2b3035;
            border-bottom: 1px solid #4b545c;
            display: flex;
            align-items: center;
        }

        .sidebar-menu {
            padding: 12px 8px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-header-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #869099;
            padding: 8px 12px 4px;
            font-weight: 700;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            color: #c2c7d0;
            text-decoration: none !important;
            font-weight: 500;
            font-size: 0.9rem;
            border-radius: 4px;
            margin-bottom: 2px;
            transition: all 0.15s ease-in-out;
        }

        .sidebar-link i {
            width: 24px;
            margin-right: 8px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-link.active {
            color: #ffffff;
            background-color: #ffc107;
            color: #212529 !important;
            font-weight: 700;
        }

        .sidebar-link.active i {
            color: #212529 !important;
        }

        .sidebar-footer {
            padding: 14px;
            background-color: #2b3035;
            border-top: 1px solid #4b545c;
        }

        /* Área de Conteúdo */
        #page-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            @auth
            margin-left: 250px;
            @endauth
        }

        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .main-container {
            padding: 24px;
            flex-grow: 1;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            background: #ffffff;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #ebedf2;
            font-weight: 600;
            padding: 12px 18px;
        }

        @media (max-width: 991px) {
            #sidebar {
                margin-left: -250px;
            }
            #sidebar.show {
                margin-left: 0;
            }
            #page-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

<div id="wrapper">
    @auth
    <!-- Barra Lateral (Sidebar) -->
    <aside id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-tools text-warning fa-lg mr-2"></i>
            <span class="font-weight-bold text-white text-uppercase" style="letter-spacing: 0.5px;">Oficina Laravel</span>
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-header-label">Operacional</div>
            
            <a href="{{ route('painel') }}" class="sidebar-link {{ request()->routeIs('painel') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Painel Geral
            </a>

            <a href="{{ route('ordens_servico.index') }}" class="sidebar-link {{ request()->routeIs('ordens_servico.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Ordens de Serviço
            </a>

            <a href="{{ route('orcamentos.index') }}" class="sidebar-link {{ request()->routeIs('orcamentos.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i> Orçamentos
            </a>

            <div class="sidebar-header-label mt-3">Cadastros & Catálogo</div>

            <a href="{{ route('clientes.index') }}" class="sidebar-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Clientes
            </a>

            <a href="{{ route('veiculos.index') }}" class="sidebar-link {{ request()->routeIs('veiculos.*') ? 'active' : '' }}">
                <i class="fas fa-car"></i> Veículos
            </a>

            <a href="{{ route('servicos.index') }}" class="sidebar-link {{ request()->routeIs('servicos.*') ? 'active' : '' }}">
                <i class="fas fa-wrench"></i> Serviços
            </a>

            <a href="{{ route('pecas.index') }}" class="sidebar-link {{ request()->routeIs('pecas.*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i> Peças & Estoque
            </a>

            @if(Auth::user()->ehAdministrador())
            <div class="sidebar-header-label mt-3">Administração</div>

            <a href="{{ route('usuarios.index') }}" class="sidebar-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i> Funcionários / Usuários
            </a>
            @endif
        </div>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-user-circle fa-2x text-warning mr-2"></i>
                <div class="overflow-hidden">
                    <div class="text-white font-weight-bold text-truncate" style="font-size: 0.85rem;">{{ Auth::user()->nome ?? 'Usuário' }}</div>
                    <small class="text-muted text-capitalize">{{ Auth::user()->perfil ?? 'Atendente' }}</small>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger btn-block text-white" style="border-color: #dc3545;">
                    <i class="fas fa-sign-out-alt mr-1"></i> Sair
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <div id="page-content">
        @auth
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary d-lg-none mr-2" id="btnToggleNav">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0 font-weight-bold text-dark">
                    @yield('titulo', 'Oficina Laravel')
                </h5>
            </div>

            <div class="d-flex align-items-center">
                <a href="{{ route('ordens_servico.criar') }}" class="btn btn-sm btn-primary mr-2">
                    <i class="fas fa-plus mr-1"></i> Nova O.S.
                </a>
                <a href="{{ route('orcamentos.criar') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-plus mr-1"></i> Novo Orçamento
                </a>
            </div>
        </header>
        @endauth

        <main class="main-container">
            @if(session('sucesso'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('sucesso') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(session('erro'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('erro') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @yield('conteudo')
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const btn = document.getElementById('btnToggleNav');
    if (btn) {
        btn.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    }
</script>
@yield('scripts')
</body>
</html>
