<style>
    .password-change {
        background: linear-gradient(145deg, var(--surface-color), color-mix(in srgb, var(--surface-color), white 15%));
        border-radius: 20px;
        padding: 3rem 2.5rem;
        box-shadow: 0 20px 40px -15px color-mix(in srgb, var(--default-color), transparent 90%), 0 0 15px -3px color-mix(in srgb, var(--default-color), transparent 95%) inset;
        max-width: 550px;
        margin: 0 auto;
    }

    .password-change .change-title {
        color: var(--heading-color);
        font-size: 1.75rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .password-change .change-subtitle {
        color: color-mix(in srgb, var(--default-color), transparent 20%);
        text-align: center;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .password-change .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: color-mix(in srgb, var(--heading-color), transparent 15%);
    }

    .password-change .input-group {
        border-radius: 10px;
        border: 2px solid color-mix(in srgb, var(--default-color), transparent 85%);
        background-color: var(--surface-color);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .password-change .input-group:focus-within {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent-color), transparent 90%);
    }

    .password-change .input-group .input-group-text {
        background-color: transparent;
        border: none;
        color: color-mix(in srgb, var(--default-color), transparent 50%);
        padding-right: 0;
    }

    .password-change .input-group .input-group-text i {
        font-size: 1.1rem;
    }

    .password-change .input-group .form-control {
        border: none;
        padding: 0.75rem 0.5rem;
        background-color: transparent;
        font-size: 1rem;
        color: var(--default-color);
    }

    .password-change .input-group .form-control:focus {
        box-shadow: none;
    }

    .password-change .input-group .form-control::placeholder {
        color: color-mix(in srgb, var(--default-color), transparent 70%);
    }

    .password-change .btn-primary {
        background: linear-gradient(135deg, var(--accent-color), color-mix(in srgb, var(--accent-color), transparent 25%));
        border: none;
        padding: 0.875rem;
        font-weight: 500;
        font-size: 1rem;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        width: 100%;
        margin-bottom: 1.5rem;
    }

    .password-change .btn-primary::before {
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

    .password-change .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px color-mix(in srgb, var(--accent-color), transparent 75%);
    }

    .password-change .btn-primary:hover::before {
        transform: translateY(0);
    }

    .password-change .btn-primary:active {
        transform: translateY(0);
    }

    .password-requirements {
        background-color: color-mix(in srgb, var(--default-color), transparent 95%);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .password-requirements h5 {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--heading-color);
    }

    .password-requirements ul {
        padding-left: 1.5rem;
        margin-bottom: 0;
    }

    .password-requirements li {
        font-size: 0.85rem;
        color: color-mix(in srgb, var(--default-color), transparent 20%);
        margin-bottom: 0.25rem;
    }

    @media (max-width: 576px) {
        .password-change {
            padding: 2rem 1.5rem;
        }

        .password-change .change-title {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Password Change Section -->
<section id="password-change" class="section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="password-change">
                    <h2 class="change-title">Cambiar Contraseña</h2>
                    <p class="change-subtitle">Hola <?php echo htmlspecialchars($user_data['nombre_apellido']); ?>, crea una nueva contraseña para tu cuenta.</p>

                    <form id="change-password-form">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_data['id']); ?>">
                        
                        <div class="mb-4">
                            <label for="new-password" class="form-label">Nueva contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password"
                                    class="form-control"
                                    id="new-password"
                                    name="password"
                                    placeholder="Ingrese su nueva contraseña"
                                    required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm-password" class="form-label">Confirmar contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password"
                                    class="form-control"
                                    id="confirm-password"
                                    name="confirm_password"
                                    placeholder="Confirme su nueva contraseña"
                                    required>
                            </div>
                        </div>

                        <div class="password-requirements">
                            <h5>La contraseña debe:</h5>
                            <ul>
                                <li>Tener al menos 6 caracteres</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section><!-- /Password Change Section -->

<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const changePasswordForm = document.getElementById('change-password-form');
        
        if (changePasswordForm) {
            changePasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Obtener valores
                const password = document.getElementById('new-password').value;
                const confirmPassword = document.getElementById('confirm-password').value;
                
                // Validar contraseñas
                if (password !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas no coinciden'
                    });
                    return;
                }
                
                // Validar requisitos de contraseña
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
                const formData = new FormData(changePasswordForm);
                
                // Enviar solicitud AJAX
                fetch('controllers/procesar_cambio_contraseña.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Éxito - Mostrar mensaje y redirigir
                        Swal.fire({
                            icon: 'success',
                            title: '¡Contraseña actualizada!',
                            text: data.message,
                            confirmButtonText: 'Iniciar Sesión'
                        }).then(() => {
                            window.location.href = 'Registro';
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