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
        <h2>Contate-nos via email:</h2>
        <?php $cw_error = $_GET['error'] ?? ''; $cw_success = $_GET['success'] ?? ''; ?>
        <?php $contact_to = trim(getenv('CONTACT_EMAIL_TO') ?: getenv('MAIL_TO') ?: 'arthur.gomesevero6@gmail.com'); ?>
        <?php if ($cw_error === 'missing_email' || $cw_error === 'email_no_dest'): ?>
            <div class="alert alert-error">Por favor, escreva uma mensagem. Caso o envio falhe, tente novamente mais tarde.</div>
        <?php elseif ($cw_error === 'email_failed'): ?>
            <div class="alert alert-error">Falha ao enviar a mensagem. O servidor de e-mail respondeu com um erro. </div>
        <?php elseif ($cw_success === 'email_sent'): ?>
            <div class="alert alert-success">Mensagem enviada com sucesso! Em breve entraremos em contato.</div>
        <?php endif; ?>

        <form action="/send-email" method="post" class="contact-form">
            <label for="name">Nome</label>
            <input type="text" id="name" name="name" placeholder="Seu nome" required>

            <label for="email">Seu e-mail para entrarmos em contato</label>
            <input type="email" id="email" name="email" placeholder="seu@exemplo.com" required>

            <label for="message">Mensagem</label>
            <textarea id="message" name="message" rows="5" required placeholder="Escreva sua dúvida ou sugestão..."></textarea>

            <button type="submit">Enviar por e-mail</button>
        </form>
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

        const form = document.querySelector(".contact-form");

        form.addEventListener("submit", () => {
        const btn = form.querySelector("button");

        btn.disabled = true;
        btn.textContent = "Enviando...";
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