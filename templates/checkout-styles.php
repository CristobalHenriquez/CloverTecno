<style>
    /* Estilos para el checkout */
    body {
        background-color: #000;
        color: #fff;
    }
    
    .checkout-section {
        padding: 60px 0;
    }

    /* Estilos para el mensaje de éxito */
    .success-message {
        background-color: #222 !important;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        color: #fff !important;
    }

    .success-message h2 {
        color: #fff;
        font-weight: 600;
    }

    .success-message .lead {
        color: #ccc;
    }

    .success-message .btn-primary {
        background-color: #104D43 !important;
        border-color: #104D43 !important;
        color: white !important;
        transition: all 0.3s ease;
    }

    .success-message .btn-primary:hover {
        background-color: #0E443B !important;
        border-color: #0E443B !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 77, 67, 0.25);
    }

    .success-message .btn-outline-secondary {
        color: #104D43 !important;
        border-color: #104D43 !important;
        background-color: transparent !important;
        transition: all 0.3s ease;
    }

    .success-message .btn-outline-secondary:hover {
        background-color: rgba(16, 77, 67, 0.1) !important;
        transform: translateY(-2px);
    }

    .success-message .alert-info {
        background-color: rgba(16, 77, 67, 0.1) !important;
        border-color: rgba(16, 77, 67, 0.2) !important;
        color: #fff !important;
    }

    .success-message .alert-info h4 {
        color: #fff;
        font-weight: 600;
    }

    .success-message .text-success {
        color: #28a745 !important;
    }

    .success-message .btn-success {
        background-color: #25D366 !important;
        border-color: #25D366 !important;
    }

    .success-message .btn-success:hover {
        background-color: #20BD5C !important;
        border-color: #20BD5C !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(37, 211, 102, 0.25);
    }

    /* Resto de los estilos del checkout (mantener los existentes) */
    .checkout-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .checkout-steps .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .checkout-steps .step .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #333;
        border: 2px solid #555;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
        color: #fff;
    }

    .checkout-steps .step .step-title {
        font-size: 0.9rem;
        font-weight: 500;
        color: #fff;
        transition: all 0.3s ease;
    }

    .checkout-steps .step.active .step-number {
        background-color: #104D43;
        border-color: #104D43;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 0 15px rgba(16, 77, 67, 0.3);
    }

    .checkout-steps .step.active .step-title {
        color: #fff;
        font-weight: 600;
    }

    .checkout-steps .step.completed .step-number {
        background-color: rgba(16, 77, 67, 0.2);
        border-color: #104D43;
        color: white;
    }

    .checkout-steps .step.completed .step-number::after {
        content: "\f26b";
        font-family: "bootstrap-icons";
        font-size: 1.2rem;
    }

    .checkout-steps .step-connector {
        flex: 1;
        height: 2px;
        background: #555;
        margin: 0 10px;
        position: relative;
        top: -20px;
        z-index: 1;
        transition: background 0.3s ease;
    }

    .checkout-steps .step-connector.active {
        background: linear-gradient(to right, #104D43 50%, #555 50%);
    }

    .checkout-steps .step-connector.completed {
        background: #104D43;
    }

    .checkout-forms {
        position: relative;
        min-height: 400px;
    }

    .checkout-forms .checkout-form {
        display: none;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.4s ease;
    }

    .checkout-forms .checkout-form.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
        animation: fadeInUp 0.5s ease forwards;
    }

    .checkout-forms .checkout-form .form-header {
        margin-bottom: 1.5rem;
    }

    .checkout-forms .checkout-form .form-header h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #fff;
    }

    .checkout-forms .checkout-form .form-header p {
        color: #ccc;
        font-size: 0.95rem;
    }

    .checkout-forms .checkout-form label {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #fff;
    }

    .checkout-forms .checkout-form .form-control,
    .checkout-forms .checkout-form .form-select {
        color: #333;
        background-color: #fff;
        font-size: 15px;
        border: 1px solid #555;
        border-radius: 10px;
        padding: 14px 18px;
        height: auto;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .checkout-forms .checkout-form .form-control:hover,
    .checkout-forms .checkout-form .form-select:hover {
        border-color: rgba(16, 77, 67, 0.5);
    }

    .checkout-forms .checkout-form .form-control:focus,
    .checkout-forms .checkout-form .form-select:focus {
        border-color: #104D43;
        box-shadow: 0 0 0 3px rgba(16, 77, 67, 0.15);
        outline: none;
    }

    .checkout-forms .checkout-form .form-control::placeholder,
    .checkout-forms .checkout-form .form-select::placeholder {
        color: #777;
        font-size: 14px;
    }

    .checkout-forms .checkout-form .form-check {
        padding-left: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .checkout-forms .checkout-form .form-check .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-left: -1.8rem;
        margin-top: 0.15rem;
        border: 2px solid #555;
        background-color: #333;
        transition: all 0.2s ease;
    }

    .checkout-forms .checkout-form .form-check .form-check-input:checked {
        background-color: #104D43;
        border-color: #104D43;
    }

    .checkout-forms .checkout-form .form-check .form-check-input:focus {
        border-color: #104D43;
        box-shadow: 0 0 0 3px rgba(16, 77, 67, 0.15);
    }

    .checkout-forms .checkout-form .form-check .form-check-input:hover:not(:checked) {
        border-color: #104D43;
    }

    .checkout-forms .checkout-form .form-check .form-check-label {
        font-size: 0.95rem;
        cursor: pointer;
        color: #fff;
    }

    .checkout-forms .checkout-form .success-message {
        padding: 15px;
        background-color: rgba(40, 167, 69, 0.1);
        border: 1px solid rgba(40, 167, 69, 0.2);
        border-radius: 10px;
        color: #28a745;
        font-weight: 500;
        margin-top: 1rem;
        text-align: center;
    }

    .checkout-forms .checkout-form .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .checkout-forms .checkout-form .btn::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .checkout-forms .checkout-form .btn:hover::before {
        transform: translateX(0);
    }

    .checkout-forms .checkout-form .btn.btn-primary {
        background-color: #104D43;
        border-color: #104D43;
        box-shadow: 0 4px 10px rgba(16, 77, 67, 0.2);
        color: #fff;
    }

    .checkout-forms .checkout-form .btn.btn-primary:hover {
        background-color: #0E443B;
        border-color: #0E443B;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 77, 67, 0.25);
    }

    .checkout-forms .checkout-form .btn.btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 5px rgba(16, 77, 67, 0.2);
    }

    .checkout-forms .checkout-form .btn.btn-outline-secondary {
        color: #fff;
        border: 2px solid #555;
        background-color: transparent;
    }

    .checkout-forms .checkout-form .btn.btn-outline-secondary:hover {
        background-color: #333;
        color: #fff;
        border-color: #777;
        transform: translateY(-2px);
    }

    .checkout-forms .checkout-form .btn.btn-outline-secondary:active {
        transform: translateY(0);
    }

    .checkout-forms .checkout-form .btn.btn-success {
        background-color: #28a745;
        border-color: #28a745;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
        color: #fff;
    }

    .checkout-forms .checkout-form .btn.btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(40, 167, 69, 0.25);
    }

    .checkout-forms .checkout-form .btn.btn-success:active {
        transform: translateY(0);
        box-shadow: 0 2px 5px rgba(40, 167, 69, 0.2);
    }

    .payment-methods .payment-method {
        border: 1px solid #555;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .payment-methods .payment-method.active {
        border-color: #104D43;
        box-shadow: 0 5px 15px rgba(16, 77, 67, 0.1);
    }

    .payment-methods .payment-method .payment-method-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background-color: #333;
        cursor: pointer;
    }

    .payment-methods .payment-method .payment-method-header .form-check {
        margin: 0;
    }

    .payment-methods .payment-method .payment-method-header .form-check .form-check-input {
        margin-top: 0.15rem;
    }

    .payment-methods .payment-method .payment-method-header .form-check .form-check-label {
        font-weight: 500;
        margin-left: 0.5rem;
        color: #fff;
    }

    .payment-methods .payment-method .payment-method-header .payment-icons {
        display: flex;
        gap: 10px;
    }

    .payment-methods .payment-method .payment-method-header .payment-icons i {
        font-size: 1.2rem;
        color: #fff;
    }

    .payment-methods .payment-method .payment-method-body {
        padding: 15px;
        border-top: 1px solid #444;
        background-color: #222;
        color: #ccc;
    }

    .payment-methods .payment-method .payment-method-body.d-none {
        display: none;
    }

    .review-sections .review-section {
        border: 1px solid #555;
        border-radius: 10px;
        overflow: hidden;
    }

    .review-sections .review-section .review-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background-color: #333;
    }

    .review-sections .review-section .review-section-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
    }

    .review-sections .review-section .review-section-header .btn-edit {
        background: none;
        border: none;
        color: #104D43;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .review-sections .review-section .review-section-header .btn-edit:hover {
        color: #0E443B;
        text-decoration: underline;
    }

    .review-sections .review-section .review-section-content {
        padding: 15px;
        background-color: #222;
        color: #ccc;
    }

    .review-sections .review-section .review-section-content p {
        margin-bottom: 0.5rem;
    }

    .review-sections .review-section .review-section-content p:last-child {
        margin-bottom: 0;
    }

    .order-summary {
        background-color: #222;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        position: sticky;
        top: 100px;
    }

    .order-summary .order-summary-header {
        padding: 20px;
        border-bottom: 1px solid #444;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-summary .order-summary-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: #fff;
    }

    .order-summary .order-summary-header .btn-toggle-summary {
        background: none;
        border: none;
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .order-summary .order-summary-header .btn-toggle-summary.collapsed {
        transform: rotate(180deg);
    }

    .order-summary .order-summary-content {
        padding: 20px;
    }

    .order-summary .order-summary-content .order-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 20px;
    }

    .order-summary .order-summary-content .order-items::-webkit-scrollbar {
        width: 5px;
    }

    .order-summary .order-summary-content .order-items::-webkit-scrollbar-track {
        background: #333;
        border-radius: 10px;
    }

    .order-summary .order-summary-content .order-items::-webkit-scrollbar-thumb {
        background: #555;
        border-radius: 10px;
    }

    .order-summary .order-summary-content .order-items .order-item {
        display: flex;
        gap: 15px;
        padding-bottom: 15px;
        margin-bottom: 15px;
        border-bottom: 1px solid #444;
    }

    .order-summary .order-summary-content .order-items .order-item:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .order-summary .order-summary-content .order-items .order-item .order-item-image {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .order-summary .order-summary-content .order-items .order-item .order-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .order-summary .order-summary-content .order-items .order-item .order-item-details {
        flex: 1;
    }

    .order-summary .order-summary-content .order-items .order-item .order-item-details h4 {
        font-size: 1rem;
        margin-bottom: 5px;
        font-weight: 600;
        color: #fff;
    }

    .order-summary .order-summary-content .order-items .order-item .order-item-details .order-item-variant {
        font-size: 0.85rem;
        color: #aaa;
        margin-bottom: 5px;
    }

    .order-summary .order-summary-content .order-items .order-item .order-item-details .order-item-price {
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
    }

    .order-summary .order-summary-content .order-items .order-item .order-item-details .order-item-price .quantity {
        color: #aaa;
    }

    .order-summary .order-summary-content .order-items .order-item .order-item-details .order-item-price .price {
        color: #fff;
    }

    .order-summary .order-summary-content .order-totals {
        padding: 15px 0;
        border-top: 1px solid #444;
        border-bottom: 1px solid #444;
        margin-bottom: 15px;
    }

    .order-summary .order-summary-content .order-totals>div {
        margin-bottom: 10px;
        font-size: 0.95rem;
        color: #ccc;
    }

    .order-summary .order-summary-content .order-totals>div:last-child {
        margin-bottom: 0;
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px dashed #555;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
    }

    .order-summary .order-summary-content .promo-code .input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: none;
        z-index: 0;
        background-color: #333;
        color: #fff;
        border-color: #555;
    }

    .order-summary .order-summary-content .promo-code .input-group .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border: 2px solid #555;
        border-left: none;
        color: #104D43;
        font-weight: 600;
        padding: 0 20px;
        background-color: #333;
    }

    .order-summary .order-summary-content .promo-code .input-group .btn:hover {
        background-color: #104D43;
        border-color: #104D43;
        color: white;
    }

    .order-summary .order-summary-content .promo-code .input-group .btn:disabled {
        background-color: #333;
        border-color: #555;
        color: #777;
        opacity: 0.8;
    }

    .order-summary .order-summary-content .secure-checkout {
        text-align: center;
        padding-top: 15px;
        border-top: 1px solid #444;
    }

    .order-summary .order-summary-content .secure-checkout .secure-checkout-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #fff;
        font-weight: 500;
    }

    .order-summary .order-summary-content .secure-checkout .secure-checkout-header i {
        color: #28a745;
        font-size: 1.1rem;
    }

    .order-summary .order-summary-content .secure-checkout .payment-icons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 10px;
    }

    .order-summary .order-summary-content .secure-checkout .payment-icons i {
        font-size: 1.5rem;
        color: #aaa;
    }

    @media (max-width: 991.98px) {
        .order-summary {
            position: relative;
            top: 0;
            margin-top: 2rem;
        }
    }

    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        background-color: #222;
    }

    .modal-content .modal-header {
        border-bottom-color: #444;
        background-color: #333;
    }

    .modal-content .modal-header .modal-title {
        font-weight: 600;
        color: #fff;
    }

    .modal-content .modal-body {
        color: #ccc;
    }

    .modal-content .modal-footer {
        border-top-color: #444;
        background-color: #333;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 77, 67, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(16, 77, 67, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(16, 77, 67, 0);
        }
    }

    @media (max-width: 767.98px) {
        .checkout-form .form-header h3 {
            font-size: 1.3rem;
        }

        .order-summary .order-summary-header h3 {
            font-size: 1.2rem;
        }
    }

    .empty-cart-message {
        text-align: center;
        padding: 40px 20px;
        color: #fff;
    }

    .empty-cart-message i {
        font-size: 4rem;
        color: #555;
        margin-bottom: 20px;
    }

    .empty-cart-message h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #fff;
    }

    .empty-cart-message p {
        color: #aaa;
        margin-bottom: 20px;
    }

    .empty-cart-message .btn-shop-now {
        display: inline-block;
        padding: 10px 20px;
        background: linear-gradient(135deg, #104D43, #187766);
        color: white;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .empty-cart-message .btn-shop-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 77, 67, 0.2);
    }
</style>
