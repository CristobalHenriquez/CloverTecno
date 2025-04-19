<?php
// Estas variables deben estar definidas en login-clientes.php
$login_error = isset($login_error) ? $login_error : '';
$register_error = isset($register_error) ? $register_error : '';
$register_success = isset($register_success) ? $register_success : '';
?>

<style>
    .login-register .login-register-wrapper {
        background: linear-gradient(145deg, var(--surface-color), color-mix(in srgb, var(--surface-color), white 15%));
        border-radius: 20px;
        padding: 3rem 2.5rem;
        box-shadow: 0 20px 40px -15px color-mix(in srgb, var(--default-color), transparent 90%), 0 0 15px -3px color-mix(in srgb, var(--default-color), transparent 95%) inset;
    }

    .login-register .auth-tabs {
        position: relative;
        border-radius: 12px;
        background-color: color-mix(in srgb, var(--default-color), transparent 95%);
        padding: 4px;
        border: 0;
    }

    .login-register .auth-tabs .auth-tab-btn.nav-link {
        background: transparent;
        border: none;
        padding: 1rem;
        color: color-mix(in srgb, var(--default-color), transparent 40%);
        font-weight: 500;
        position: relative;
        transition: all 0.3s ease;
        z-index: 1;
    }

    .login-register .auth-tabs .auth-tab-btn.nav-link:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .login-register .auth-tabs .auth-tab-btn.nav-link:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .login-register .auth-tabs .auth-tab-btn.nav-link i {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .login-register .auth-tabs .auth-tab-btn.nav-link span {
        display: block;
        font-size: 0.95rem;
    }

    .login-register .auth-tabs .auth-tab-btn.nav-link.active,
    .login-register .auth-tabs .auth-tab-btn.nav-link.nav-link.active {
        color: var(--contrast-color);
        border: none;
    }

    .login-register .auth-tabs .auth-tab-btn.nav-link.active::before,
    .login-register .auth-tabs .auth-tab-btn.nav-link.nav-link.active::before {
        content: "";
        position: absolute;
        inset: 4px;
        background: linear-gradient(135deg, var(--accent-color), color-mix(in srgb, var(--accent-color), transparent 25%));
        border-radius: 8px;
        z-index: -1;
    }

    .login-register .auth-title {
        color: var(--heading-color);
        font-size: 1.75rem;
        font-weight: 600;
    }

    .login-register .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: color-mix(in srgb, var(--heading-color), transparent 15%);
    }

    .login-register .input-group {
        border-radius: 10px;
        border: 2px solid color-mix(in srgb, var(--default-color), transparent 85%);
        background-color: var(--surface-color);
        transition: all 0.3s ease;
    }

    .login-register .input-group:focus-within {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent-color), transparent 90%);
    }

    .login-register .input-group .input-group-text {
        background-color: transparent;
        border: none;
        color: color-mix(in srgb, var(--default-color), transparent 50%);
        padding-right: 10px;
    }

    .login-register .input-group .input-group-text i {
        font-size: 1.1rem;
    }

    .login-register .input-group .form-control {
        border: none;
        padding: 0.75rem 0.5rem;
        background-color: transparent;
        font-size: 1rem;
        color: var(--default-color);
    }

    .login-register .input-group .form-control:focus {
        box-shadow: none;
    }

    .login-register .input-group .form-control::placeholder {
        color: color-mix(in srgb, var(--default-color), transparent 70%);
    }

    .login-register .form-check .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        border: 2px solid color-mix(in srgb, var(--default-color), transparent 70%);
        margin-right: 0.5rem;
        cursor: pointer;
    }

    .login-register .form-check .form-check-input:checked {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .login-register .form-check .form-check-input:focus {
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent-color), transparent 90%);
        border-color: var(--accent-color);
    }

    .login-register .form-check .form-check-label {
        color: color-mix(in srgb, var(--default-color), transparent 35%);
        cursor: pointer;
        font-size: 0.9rem;
    }

    .login-register .form-check .form-check-label a {
        color: var(--accent-color);
        font-weight: 500;
        position: relative;
    }

    .login-register .form-check .form-check-label a::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 100%;
        height: 2px;
        background-color: currentColor;
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .login-register .form-check .form-check-label a:hover::after {
        transform: scaleX(1);
    }

    .login-register .btn-primary {
        background: linear-gradient(135deg, var(--accent-color), color-mix(in srgb, var(--accent-color), transparent 25%));
        border: none;
        padding: 0.875rem;
        font-weight: 500;
        font-size: 1rem;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .login-register .btn-primary::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(255, 255, 255, 0.2), transparent);
        transform: translateY(-100%);
        transition: transform 0.3s ease;
    }

    .login-register .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px color-mix(in srgb, var(--accent-color), transparent 75%);
    }

    .login-register .btn-primary:hover::before {
        transform: translateY(0);
    }

    .login-register .btn-primary:active {
        transform: translateY(0);
    }

    .login-register .forgot-password {
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        position: relative;
    }

    .login-register .forgot-password::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 100%;
        height: 2px;
        background-color: currentColor;
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .login-register .forgot-password:hover::after {
        transform: scaleX(1);
    }

    @media (max-width: 576px) {
        .login-register {
            padding: 2rem 1.5rem;
        }

        .login-register .auth-title {
            font-size: 1.5rem;
        }

        .login-register .auth-tab-btn.nav-link {
            padding: 0.75rem;
        }

        .login-register .auth-tab-btn.nav-link i {
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }

        .login-register .auth-tab-btn.nav-link span {
            font-size: 0.85rem;
        }

        .login-register .form-control {
            font-size: 16px;
        }
    }
</style>

<!-- Login Register Section -->
<section id="login-register" class="login-register section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="login-register-wrapper">
                    <!-- Tab Navigation -->
                    <div class="auth-tabs nav nav-tabs mb-5" role="tablist">
                        <div class="row g-0 w-100">
                            <div class="col-6">
                                <button class="auth-tab-btn nav-link <?php echo (!isset($_GET['register'])) ? 'active' : ''; ?> w-100"
                                    data-bs-toggle="tab"
                                    data-bs-target="#login-register-login-form"
                                    type="button"
                                    role="tab"
                                    aria-controls="login-register-login-form"
                                    aria-selected="<?php echo (!isset($_GET['register'])) ? 'true' : 'false'; ?>">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    <span>Iniciar Sesión</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="auth-tab-btn nav-link <?php echo (isset($_GET['register'])) ? 'active' : ''; ?> w-100"
                                    data-bs-toggle="tab"
                                    data-bs-target="#login-register-registration-form"
                                    type="button"
                                    role="tab"
                                    aria-controls="login-register-registration-form"
                                    aria-selected="<?php echo (isset($_GET['register'])) ? 'true' : 'false'; ?>">
                                    <i class="bi bi-person-plus"></i>
                                    <span>Crear Cuenta</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Login Form -->
                        <div class="tab-pane fade <?php echo (!isset($_GET['register'])) ? 'show active' : ''; ?>"
                            id="login-register-login-form"
                            role="tabpanel">
                            <div class="auth-form">
                                <h2 class="auth-title text-center mb-4">Bienvenido de Nuevo</h2>

                                <form id="login-form">
                                    <div class="mb-4">
                                        <label for="login-register-login-email" class="form-label">Correo electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email"
                                                class="form-control"
                                                id="login-register-login-email"
                                                name="correo"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="login-register-login-password" class="form-label">Contraseña</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control"
                                                id="login-register-login-password"
                                                name="password"
                                                required>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="login-register-remember-me"
                                                name="remember_me">
                                            <label class="form-check-label" for="login-register-remember-me">Recordarme</label>
                                        </div>
                                        <a href="RecuperarContraseña" class="forgot-password">¿Olvidó su contraseña?</a>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                                </form>
                            </div>
                        </div>

                        <!-- Registration Form -->
                        <div class="tab-pane fade <?php echo (isset($_GET['register'])) ? 'show active' : ''; ?>"
                            id="login-register-registration-form"
                            role="tabpanel">
                            <div class="auth-form">
                                <h2 class="auth-title text-center mb-4">Crear Cuenta</h2>

                                <form id="register-form">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="login-register-reg-nombre" class="form-label">Nombre y Apellido</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                                <input type="text"
                                                    class="form-control"
                                                    id="login-register-reg-nombre"
                                                    name="nombre_apellido"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="login-register-reg-email" class="form-label">Correo electrónico</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input type="email"
                                                    class="form-control"
                                                    id="login-register-reg-email"
                                                    name="correo"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="login-register-reg-password" class="form-label">Contraseña</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password"
                                                    class="form-control"
                                                    id="login-register-reg-password"
                                                    name="password"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="login-register-reg-confirm-password" class="form-label">Confirmar contraseña</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password"
                                                    class="form-control"
                                                    id="login-register-reg-confirm-password"
                                                    name="confirm_password"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary w-100">Crear Cuenta</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- /Login Register Section -->

<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Formulario de inicio de sesión
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Mostrar indicador de carga
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Crear FormData
                const formData = new FormData(loginForm);
                formData.append('login', '1'); // Agregar campo para identificar la acción

                // Enviar solicitud AJAX
                fetch('controllers/procesar_login_clientes.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Éxito - Mostrar mensaje y redirigir
                            Swal.fire({
                                icon: 'success',
                                title: '¡Bienvenido!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        } else {
                            // Error - Mostrar mensaje de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ha ocurrido un error. Por favor, inténtelo de nuevo.'
                        });
                    });
            });
        }

        // Formulario de registro
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validar contraseñas
                const password = document.getElementById('login-register-reg-password').value;
                const confirmPassword = document.getElementById('login-register-reg-confirm-password').value;

                if (password !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas no coinciden'
                    });
                    return;
                }

                if (password.length < 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La contraseña debe tener al menos 6 caracteres'
                    });
                    return;
                }

                // Mostrar indicador de carga
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Crear FormData
                const formData = new FormData(registerForm);
                formData.append('register', '1'); // Agregar campo para identificar la acción

                // Enviar solicitud AJAX
                fetch('controllers/procesar_registro.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Éxito - Mostrar mensaje y cambiar a la pestaña de inicio de sesión
                            Swal.fire({
                                icon: 'success',
                                title: '¡Registro exitoso!',
                                text: data.message
                            }).then(() => {
                                // Cambiar a la pestaña de inicio de sesión
                                document.querySelector('.auth-tab-btn[data-bs-target="#login-register-login-form"]').click();
                                // Limpiar el formulario
                                registerForm.reset();
                            });
                        } else {
                            // Error - Mostrar mensaje de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ha ocurrido un error. Por favor, inténtelo de nuevo.'
                        });
                    });
            });
        }
    });
</script>