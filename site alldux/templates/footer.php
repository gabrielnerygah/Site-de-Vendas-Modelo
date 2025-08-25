<?php
// templates/footer.php
?>

<footer>
    <p>&copy; <?php echo date('Y'); ?> Alldux. Todos os direitos reservados.</p>
    <p>&copy; Made by Gabriel de Morais Lacerda</p>
    <p>TEL: +55 85 981258196</p>
    <div>
        <p><a href="sobre">Sobre Nos</a></p>
    </div>
    <ul class="social-links">
        <li><a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a></li>
        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
        <li><a href="#"><i class="fa fa-whatsapp"></i></a></li>
    </ul>
</footer>

<div id="modal-compra" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close" onclick="fecharModalCompra()">&times;</button>
        <div id="modal-etapa-1">
            <h2 id="modal-produto-nome">Nome do Produto</h2>
            <img id="modal-produto-img" src="" alt="Produto" style="max-width: 150px; border-radius: 8px;">
            <p id="modal-produto-descricao">Descrição do produto aqui.</p>
            <p><strong>Preço: R$ <span id="modal-produto-preco">0.00</span></strong></p>
            <hr>
            <form id="form-compra">
                <input type="hidden" id="produto_id" name="produto_id">

                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" value="1" min="1" required>

                <label for="endereco">Endereço de Entrega:</label>
                <textarea id="endereco" name="endereco" rows="3" required></textarea>

                <label>Método de Pagamento:</label>
                <div class="payment-methods">
                    <label><input type="radio" name="metodo_pagamento" value="pix" checked> PIX</label>
                    <label><input type="radio" name="metodo_pagamento" value="cartao"> Cartão de Crédito</label>
                </div>

                <button type="button" onclick="avancarParaPagamento()">Continuar para Pagamento</button>
            </form>
        </div>

        <div id="modal-etapa-2" style="display: none;">
            <h3>Finalizar Pagamento</h3>
            <div id="pagamento-pix" style="display: none;">
                <h4>Pagamento com PIX</h4>
                <p>Escaneie o QR Code abaixo para pagar:</p>
                <div id="pix-qrcode"></div>
                <p><strong>Pedido registrado! Aguardando pagamento.</strong></p>
            </div>
            <div id="pagamento-cartao" style="display: none;">
                <h4>Pagamento com Cartão de Crédito</h4>
                <p>Funcionalidade de cartão de crédito a ser implementada.</p>
            </div>
        </div>
    </div>
</div>
<div id="modal-pedidos" class="modal-overlay" style="display: none;">


    <div class="modal-content">
        <button class="modal-close" onclick="fecharModalPedidos()">&times;</button>
        <div class="loop-wrapper">
            <div class="mountain"></div>
            <div class="hill"></div>
            <div class="tree"></div>
            <div class="tree"></div>
            <div class="tree"></div>
            <div class="rock"></div>
            <div class="truck"></div>
            <div class="wheels"></div>
        </div>
        <h2>Meus Pedidos</h2>
        <div id="pedidos-lista">
        </div>
    </div>
</div>

<div id="modal-carrinho" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <button class="modal-close" onclick="fecharModalCarrinho()">&times;</button>
        <h2>Seu Carrinho de Compras</h2>
        <div id="cart-items-container">
        </div>
        <div class="cart-summary">
            <strong>Total: R$ <span id="cart-total">0,00</span></strong>
        </div>
        <button class="btn-checkout">Finalizar Compra</button>
    </div>
</div>

<script>

    // Adicione estas funções na sua tag <script> em footer.php

    function fecharModalCarrinho() {
        document.getElementById('modal-carrinho').style.display = 'none';
    }

    async function abrirModalCarrinho() {
        // Busca os itens do carrinho no backend
        const response = await fetch('api/obter_carrinho.php');
        const result = await response.json();

        const itemsContainer = document.getElementById('cart-items-container');
        const cartTotalEl = document.getElementById('cart-total');
        itemsContainer.innerHTML = ''; // Limpa o conteúdo anterior

        if (result.success && result.itens.length > 0) {
            let total = 0;

            result.itens.forEach(item => {
                const itemTotal = item.preco * item.quantidade;
                total += itemTotal;

                // Cria o HTML para cada item
                const itemHTML = `
                <div class="cart-item">
                    <img src="${item.imagem}" alt="${item.nome}">
                    <div class="cart-item-details">
                        <strong>${item.nome}</strong>
                        <small>Quantidade: ${item.quantidade}</small>
                    </div>
                    <div class="cart-item-actions">
                        <span class="item-price">R$ ${itemTotal.toFixed(2).replace('.', ',')}</span>
                    </div>
                </div>
            `;
                itemsContainer.innerHTML += itemHTML;
            });

            // Atualiza o valor total
            cartTotalEl.innerText = total.toFixed(2).replace('.', ',');

        } else {
            itemsContainer.innerHTML = '<p>Seu carrinho está vazio.</p>';
            cartTotalEl.innerText = '0,00';
        }

        // Mostra o modal
        document.getElementById('modal-carrinho').style.display = 'flex';
    }

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
    // Função para abrir o modal de compra e preencher com os dados do produto
    function abrirModalCompra(produto) {
        document.getElementById('modal-produto-nome').innerText = produto.nome;
        document.getElementById('modal-produto-descricao').innerText = produto.descricao;
        document.getElementById('modal-produto-preco').innerText = parseFloat(produto.preco).toFixed(2).replace('.', ',');
        document.getElementById('modal-produto-img').src = produto.imagem;
        document.getElementById('produto_id').value = produto.id;

        // Mostra o modal
        document.getElementById('modal-compra').style.display = 'flex';
    }

    function fecharModalCompra() {
        document.getElementById('modal-compra').style.display = 'none';
        // Reseta o modal para a primeira etapa
        document.getElementById('modal-etapa-1').style.display = 'block';
        document.getElementById('modal-etapa-2').style.display = 'none';
    }

    async function avancarParaPagamento() {
        // Coleta os dados do formulário
        const form = document.getElementById('form-compra');
        const formData = new FormData(form);

        // Envia os dados para o servidor via AJAX/Fetch
        const response = await fetch('api/processa_pedido.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Esconde a primeira etapa e mostra a segunda
            document.getElementById('modal-etapa-1').style.display = 'none';
            document.getElementById('modal-etapa-2').style.display = 'block';

            // Mostra a seção de pagamento correta
            if (result.metodo === 'pix') {
                document.getElementById('pagamento-pix').style.display = 'block';
                document.getElementById('pagamento-cartao').style.display = 'none';
                // Aqui você usaria uma biblioteca para gerar o QR Code a partir de result.pix_data
                document.getElementById('pix-qrcode').innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${result.pix_data}" alt="PIX QR Code">`;
            } else if (result.metodo === 'cartao') {
                document.getElementById('pagamento-pix').style.display = 'none';
                document.getElementById('pagamento-cartao').style.display = 'block';
                // Aqui você iniciaria a integração com a API de cartão (Stripe, etc.)
            }
        } else {
            alert('Erro: ' + result.message);
        }
    }
    // Funções para o modal de pedidos
    function fecharModalPedidos() {
        document.getElementById('modal-pedidos').style.display = 'none';
    }

    async function abrirModalPedidos() {
        // Busca os pedidos do usuário no backend
        const response = await fetch('api/buscar_pedidos.php');
        const result = await response.json();

        const listaContainer = document.getElementById('pedidos-lista');
        listaContainer.innerHTML = ''; // Limpa a lista antiga

        if (result.success && result.pedidos.length > 0) {
            result.pedidos.forEach(pedido => {
                // Formata a data para o padrão brasileiro
                const dataPedido = new Date(pedido.data_pedido).toLocaleDateString('pt-BR', {
                    day: '2-digit', month: '2-digit', year: 'numeric'
                });

                // Cria o HTML para cada pedido
                const pedidoHTML = `
                <div class="pedido-item">
                    <div class="pedido-header">
                        <span>Pedido #${pedido.id} - ${dataPedido}</span>
                        <span class="status">${pedido.status_entrega}</span>
                    </div>
                    <div class="pedido-body">
                        <p><strong>Valor Total:</strong> R$ ${parseFloat(pedido.valor_total).toFixed(2).replace('.', ',')}</p>
                        <p><strong>Endereço:</strong> ${pedido.endereco_entrega}</p>
                    </div>
                </div>
            `;
                listaContainer.innerHTML += pedidoHTML;
            });
        } else {
            listaContainer.innerHTML = '<p>Você ainda não fez nenhum pedido.</p>';
        }

        // Mostra o modal
        document.getElementById('modal-pedidos').style.display = 'flex';
    }



    async function adicionarAoCarrinho(produtoId) {
        const badge = document.getElementById('cart-count-badge');
        const cartIcon = document.querySelector('.cart-icon-link');

        // Prepara os dados para enviar ao backend
        const formData = new FormData();
        formData.append('produto_id', produtoId);

        try {
            // Envia o ID do produto para o script PHP
            const response = await fetch('api/adicionar_carrinho.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Atualiza o número no contador do carrinho
                badge.textContent = result.novaContagem;
                badge.setAttribute('data-count', result.novaContagem);

                // Adiciona uma animação para feedback visual
                cartIcon.classList.add('shake');
                setTimeout(() => {
                    cartIcon.classList.remove('shake');
                }, 500); // Duração da animação

            } else {
                alert("Erro ao adicionar o produto ao carrinho.");
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
            alert("Ocorreu um erro de comunicação. Tente novamente.");
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const parallaxBg = document.querySelector('.parallax-bg');
        const products = document.querySelectorAll('.product');

        // Opções para o IntersectionObserver (defini que 50% do elemento precisa estar visível)
        const options = {
            root: null, // O viewport
            threshold: 0.5,
        };

        // Cria o observador
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Se o produto está visível
                    const newBg = entry.target.getAttribute('data-background');
                    parallaxBg.style.backgroundImage = newBg;
                }
            });
        }, options);

        // Observa cada um dos produtos
        products.forEach(product => {
            observer.observe(product);
        });

        // Função para definir a imagem inicial do fundo
        function setInitialBackground() {
            if (products.length > 0) {
                const firstProductBg = products[0].getAttribute('data-background');
                parallaxBg.style.backgroundImage = firstProductBg;
            }
        }

        // Chama a função para garantir que o fundo comece com a imagem do primeiro produto
        setInitialBackground();
    });

</script>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="app/js/bootstrap.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
</body>

</html>