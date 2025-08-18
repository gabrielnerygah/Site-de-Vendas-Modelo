<?php
// templates/footer.php
?>
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> Alldux. Todos os direitos reservados.</p>
                <p>&copy; Made by Gabriel de Morais Lacerda</p>
                <p>TEL: +55 85 981258196</p>
                <ul class="social-links">
                    <li><a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>
</div> <script>
    // Função para redirecionar para o dashboard correto
    function redirecionaDashboard() {
        // Variável PHP passada de forma segura para o JavaScript
        const tipoUsuario = <?php echo json_encode($tipo_usuario ?? null); ?>;

        if (tipoUsuario === 'consumidor') {
            // URL corrigida, sem .php e apontando para o roteador
            window.location.href = 'index.php?i=dashboard_consumidor';
        } else if (tipoUsuario === 'promotor') {
            // URL corrigida, sem .php e apontando para o roteador
            window.location.href = 'index.php?i=dashboard_promotor';
        } else {
            // Redireciona para o login se não estiver logado ou o tipo for inválido
            window.location.href = 'login.php';
        }
    }

    // O restante do JavaScript estático
    var menuItem = document.querySelectorAll('.item-menu');
    function selectLink() {
        menuItem.forEach((item) => item.classList.remove('ativo'));
        this.classList.add('ativo');
    }
    menuItem.forEach((item) => item.addEventListener('click', selectLink));

    var btnExp = document.querySelector('#btn-exp');
    var menuSide = document.querySelector('.menu-lateral');
    if (btnExp && menuSide) {
        btnExp.addEventListener('click', function () {
            menuSide.classList.toggle('expandir');
        });
    }

    const carousel = document.querySelector('.carousel');
    const items = document.querySelectorAll('.carousel-item');
    if (carousel && items.length > 0) {
        const totalItems = items.length;
        let currentIndex = 0;

        const nextButton = document.getElementById('next');
        const prevButton = document.getElementById('prev');

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
            });
        }
        
        if (prevButton) {
            prevButton.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                updateCarousel();
            });
        }

        function updateCarousel() {
            const offset = -currentIndex * 100;
            carousel.style.transform = `translateX(${offset}%)`;
        }
    }
</script>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="app/js/bootstrap.min.js"></script>

</body>
</html>