<style>
    .password-recovery {
        background: linear-gradient(145deg, var(--surface-color), color-mix(in srgb, var(--surface-color), white 15%));
        border-radius: 20px;
        padding: 3rem 2.5rem;
        box-shadow: 0 20px 40px -15px color-mix(in srgb, var(--default-color), transparent 90%), 0 0 15px -3px color-mix(in srgb, var(--default-color), transparent 95%) inset;
        max-width: 550px;
        margin: 0 auto;
    }

    .password-recovery .recovery-title {
        color: var(--heading-color);
        font-size: 1.75rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .password-recovery .recovery-subtitle {
        color: color-mix(in srgb, var(--default-color), transparent 20%);
        text-align: center;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .password-recovery .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: color-mix(in srgb, var(--heading-color), transparent 15%);
    }

    .password-recovery .input-group {
        border-radius: 10px;
        border: 2px solid color-mix(in srgb, var(--default-color), transparent 85%);
        background-color: var(--surface-color);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .password-recovery .input-group:focus-within {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent-color), transparent 90%);
    }

    .password-recovery .input-group .input-group-text {
        background-color: transparent;
        border: none;
        color: color-mix(in srgb, var(--default-color), transparent 50%);
        padding-right: 0;
    }

    .password-recovery .input-group .input-group-text i {
        font-size: 1.1rem;
    }

    .password-recovery .input-group .form-control {
        border: none;
        padding: 0.75rem 0.5rem;
        background-color: transparent;
        font-size: 1rem;
        color: var(--default-color);
    }

    .password-recovery .input-group .form-control:focus {
        box-shadow: none;
    }

    .password-recovery .input-group .form-control::placeholder {
        color: color-mix(in srgb, var(--default-color), transparent 70%);
    }

    .password-recovery .btn-primary {
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

    .password-recovery .btn-primary::before {
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

    .password-recovery .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px color-mix(in srgb, var(--accent-color), transparent 75%);
    }

    .password-recovery .btn-primary:hover::before {
        transform: translateY(0);
    }

    .password-recovery .btn-primary:active {
        transform: translateY(0);
    }

    .password-recovery .back-to-login {
        text-align: center;
        margin-top: 1rem;
    }

    .password-recovery .back-to-login a {
        color: var(--accent-color);
        font-weight: 500;
        text-decoration: none;
        position: relative;
    }

    .password-recovery .back-to-login a::after {
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

    .password-recovery .back-to-login a:hover::after {
        transform: scaleX(1);
    }

    @media (max-width: 576px) {
        .password-recovery {
            padding: 2rem 1.5rem;
        }

        .password-recovery .recovery-title {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Password Recovery Section -->
<section id="password-recovery" class="section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="password-recovery">
                    <h2 class="recovery-title">Recuperar Contraseña</h2>
                    <p class="recovery-subtitle">Ingrese su correo electrónico y le enviaremos instrucciones para restablecer su contraseña.</p>

                    <form id="recovery-form">
                        <div class="mb-4">
                            <label for="recovery-email" class="form-label">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                    class="form-control"
                                    id="recovery-email"
                                    name="correo"
                                    placeholder="Ingrese su correo electrónico"
                                    required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar Instrucciones</button>
                    </form>

                    <div class="back-to-login">
                        <a href="Registro"><i class="bi bi-arrow-left me-1"></i> Volver a Iniciar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- /Password Recovery Section -->

<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const recoveryForm = document.getElementById('recovery-form');
        
        if (recoveryForm) {
            recoveryForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validar el correo electrónico
                const email = document.getElementById('recovery-email').value;
                if (!email || !email.includes('@')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, ingrese un correo electrónico válido'
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
                const formData = new FormData(recoveryForm);
                
                // Enviar solicitud AJAX
                fetch('controllers/procesar_recuperacion.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Éxito - Mostrar mensaje
                        Swal.fire({
                            icon: 'success',
                            title: '¡Correo enviado!',
                            text: data.message,
                            confirmButtonText: 'Entendido',
                        });
                        // Limpiar el formulario
                        recoveryForm.reset();
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