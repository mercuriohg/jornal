<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatos do Grêmio</title>
    <link rel="stylesheet" href="/assets/style/index.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
      integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="icon" href="/assets/img/malal_icon.ico" type="image/x-icon">
</head>
<body>
    <?php include 'components/header.php'; ?>
    <?php include 'components/sidebar.php'; ?>
     <main id="contate">
        <h2>Formas de mensagem do Grêmio Estudantil</h2>
     </main>
    <?php include 'components/footer.php'; ?>
    <script> 
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            
            // Opcional: Transforma o ícone de barras em um "X" ao abrir
            menuToggle.classList.toggle('fa-bars');
            menuToggle.classList.toggle('fa-times');
        });
    </script>
    <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>


</body>
</html>