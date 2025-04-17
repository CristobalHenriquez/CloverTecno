<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar carrito desde localStorage
        const cart = JSON.parse(localStorage.getItem('carrito')) || [];
        
        // Verificar si el carrito está vacío
        if (cart.length === 0) {
            document.querySelector('.checkout-steps').style.display = 'none';
            document.querySelector('.checkout-forms').style.display = 'none';
            document.querySelector('.order-summary').style.display = 'none';
            
            // Mostrar mensaje de carrito vacío
            const emptyCartMessage = document.createElement('div');
            emptyCartMessage.className = 'empty-cart-message';
            emptyCartMessage.innerHTML = `
                <i class="bi bi-cart-x"></i>
                <h3>Tu carrito está vacío</h3>
                <p>Agrega productos a tu carrito antes de proceder al pago</p>
                <a href="Productos" class="btn-shop-now">Ir a Comprar</a>
            `;
            document.querySelector('.col-lg-8').appendChild(emptyCartMessage);
            return;
        }
        
        // Elementos del DOM
        const steps = document.querySelectorAll('.checkout-steps .step');
        const forms = document.querySelectorAll('.checkout-form');
        const nextButtons = document.querySelectorAll('.next-step');
        const prevButtons = document.querySelectorAll('.prev-step');
        const editButtons = document.querySelectorAll('.btn-edit');
        const paymentMethods = document.querySelectorAll('.payment-method');
        const paymentRadios = document.querySelectorAll('input[name="metodo_pago"]');
        const orderItems = document.getElementById('order-items');
        const orderSubtotal = document.getElementById('order-subtotal');
        const orderTotal = document.getElementById('order-total');
        const checkoutForm = document.getElementById('checkout-form');
        
        // Función para actualizar el resumen del pedido
        function updateOrderSummary() {
            // Limpiar contenedor de items
            orderItems.innerHTML = '';
            
            // Variables para calcular totales
            let subtotal = 0;
            
            // Agregar cada item del carrito
            cart.forEach(item => {
                // Asegurarse de que price y quantity sean números
                const price = parseFloat(item.price);
                const quantity = parseInt(item.quantity);
                const itemTotal = price * quantity;
                subtotal += itemTotal;
                
                const itemElement = document.createElement('div');
                itemElement.className = 'order-item';
                itemElement.innerHTML = `
                    <div class="order-item-image">
                        <img src="${item.image}" alt="${item.name}" class="img-fluid">
                    </div>
                    <div class="order-item-details">
                        <h4>${item.name}</h4>
                        <div class="order-item-price">
                            <span class="quantity">${quantity} ×</span>
                            <span class="price">$${price.toFixed(2)}</span>
                        </div>
                    </div>
                `;
                orderItems.appendChild(itemElement);
            });
            
            // Actualizar totales
            orderSubtotal.textContent = `$${subtotal.toFixed(2)}`;
            orderTotal.textContent = `$${subtotal.toFixed(2)}`;
            
            // Actualizar campo oculto para el total
            document.getElementById('hidden-total').value = subtotal.toFixed(2);
            
            // Preparar carrito para enviar al servidor
            const cartForServer = cart.map(item => ({
                id: parseInt(item.id),
                quantity: parseInt(item.quantity),
                price: parseFloat(item.price),
                name: item.name
            }));
            
            // Actualizar campo oculto para los productos
            document.getElementById('hidden-productos').value = JSON.stringify(cartForServer);
        }
        
        // Función para cambiar de paso
        function goToStep(stepNumber) {
            // Actualizar pasos
            steps.forEach(step => {
                const currentStep = parseInt(step.getAttribute('data-step'));
                step.classList.remove('active', 'completed');
                
                if (currentStep < stepNumber) {
                    step.classList.add('completed');
                } else if (currentStep === stepNumber) {
                    step.classList.add('active');
                }
            });
            
            // Actualizar conectores
            const connectors = document.querySelectorAll('.step-connector');
            connectors.forEach((connector, index) => {
                connector.classList.remove('active', 'completed');
                
                if (index + 1 < stepNumber) {
                    connector.classList.add('completed');
                } else if (index + 1 === stepNumber - 1) {
                    connector.classList.add('active');
                }
            });
            
            // Mostrar formulario correspondiente
            forms.forEach(form => {
                form.classList.remove('active');
                if (parseInt(form.getAttribute('data-form')) === stepNumber) {
                    form.classList.add('active');
                }
            });
            
            // Si es el paso de revisión, actualizar la información
            if (stepNumber === 4) {
                updateReviewInfo();
            }
        }
        
        // Función para actualizar la información de revisión
        function updateReviewInfo() {
            // Obtener valores de los formularios
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const dnicuit = document.getElementById('dnicuit').value;
            const telefono = document.getElementById('telefono').value;
            const direccion = document.getElementById('direccion').value;
            const apartamento = document.getElementById('apartamento').value;
            const ciudad = document.getElementById('ciudad').value;
            const provincia = document.getElementById('provincia').value;
            const codigoPostal = document.getElementById('codigo_postal').value;
            const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
            const notas = document.getElementById('notas').value;
            
            // Actualizar sección de revisión
            document.getElementById('review-name').textContent = nombre;
            document.getElementById('review-email').textContent = email;
            document.getElementById('review-dnicuit').textContent = dnicuit;
            document.getElementById('review-phone').textContent = telefono;
            
            let direccionCompleta = direccion;
            if (apartamento) {
                direccionCompleta += `, ${apartamento}`;
            }
            document.getElementById('review-address-line1').textContent = direccionCompleta;
            document.getElementById('review-address-line2').textContent = `${ciudad}, ${provincia}, CP: ${codigoPostal}`;
            
            document.getElementById('review-payment-method').textContent = metodoPago;
            document.getElementById('review-notes').textContent = notas || 'Sin notas adicionales';
            
            // Actualizar campos ocultos
            document.getElementById('hidden-nombre').value = nombre;
            document.getElementById('hidden-email').value = email;
            document.getElementById('hidden-dnicuit').value = dnicuit;
            document.getElementById('hidden-telefono').value = telefono;
            document.getElementById('hidden-direccion').value = direccion;
            document.getElementById('hidden-apartamento').value = apartamento;
            document.getElementById('hidden-ciudad').value = ciudad;
            document.getElementById('hidden-provincia').value = provincia;
            document.getElementById('hidden-codigo-postal').value = codigoPostal;
            document.getElementById('hidden-metodo-pago').value = metodoPago;
            document.getElementById('hidden-notas').value = notas;
        }
        
        // Event listeners para botones de siguiente paso
        nextButtons.forEach(button => {
            button.addEventListener('click', function() {
                const nextStep = parseInt(this.getAttribute('data-next'));
                
                // Validar formulario actual
                const currentForm = this.closest('.checkout-form');
                const inputs = currentForm.querySelectorAll('input[required], select[required]');
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                if (isValid) {
                    goToStep(nextStep);
                } else {
                    Swal.fire({
                        title: 'Campos incompletos',
                        text: 'Por favor, completa todos los campos obligatorios.',
                        icon: 'warning',
                        confirmButtonColor: '#104D43'
                    });
                }
            });
        });
        
        // Event listeners para botones de paso anterior
        prevButtons.forEach(button => {
            button.addEventListener('click', function() {
                const prevStep = parseInt(this.getAttribute('data-prev'));
                goToStep(prevStep);
            });
        });
        
        // Event listeners para botones de edición
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const editStep = parseInt(this.getAttribute('data-edit'));
                goToStep(editStep);
            });
        });
        
        // Event listeners para métodos de pago
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Ocultar todos los cuerpos de métodos de pago
                document.querySelectorAll('.payment-method-body').forEach(body => {
                    body.classList.add('d-none');
                });
                
                // Quitar clase active de todos los métodos
                paymentMethods.forEach(method => {
                    method.classList.remove('active');
                });
                
                // Mostrar el cuerpo del método seleccionado
                const selectedMethod = this.closest('.payment-method');
                selectedMethod.classList.add('active');
                selectedMethod.querySelector('.payment-method-body').classList.remove('d-none');
                
                // Actualizar el resumen del pedido con el nuevo método de pago
                updateOrderSummary();
            });
        });
        
        // Validar formulario antes de enviar
        checkoutForm.addEventListener('submit', function(e) {
            if (!document.getElementById('terminos').checked) {
                e.preventDefault();
                Swal.fire({
                    title: 'Términos y Condiciones',
                    text: 'Debes aceptar los términos y condiciones para continuar.',
                    icon: 'warning',
                    confirmButtonColor: '#104D43'
                });
            }
        });
        
        // Inicializar resumen del pedido
        updateOrderSummary();
    });
</script>