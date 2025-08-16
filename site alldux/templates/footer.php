<?php
// templates/footer.php
?>
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <p>&copy; 2025 Alldux. Todos os direitos reservados.</p> <p>&copy; Made by Gabriel de Morais Lacerda</p>
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
<?php
// Geração da função JavaScript com base na lógica PHP
echo "function redirecionaDashboard() {";
if ($tipo_usuario == 'consumidor') {
    echo "window.location.href = 'dashboard_consumidor.php';";
} else if ($tipo_usuario == 'promotor') {
    echo "window.location.href = 'dashboard_promotor.php';";
} else {
    echo "alert('Você precisa estar logado para acessar o dashboard.');";
    echo "window.location.href = 'login.php';";
}
echo "}";
?>

    // O restante do JavaScript é estático
    var menuItem = document.querySelectorAll('.item-menu');
    function selectLink() {
        menuItem.forEach((item) => item.classList.remove('ativo'));
        this.classList.add('ativo');
    }
    menuItem.forEach((item) => item.addEventListener('click', selectLink));

    var btnExp = document.querySelector('#btn-exp');
    var menuSide = document.querySelector('.menu-lateral');
    btnExp.addEventListener('click', function () {
        menuSide.classList.toggle('expandir');
    });

    const carousel = document.querySelector('.carousel');
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;
    let currentIndex = 0;
    document.getElementById('next').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
    });
    document.getElementById('prev').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
    });
    function updateCarousel() {
        const offset = -currentIndex * 100;
        carousel.style.transform = `translateX(${offset}%)`;
    }
</script>
</body>
</html>