<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membros</title>
    <link rel="stylesheet" href="/assets/style/index.css">
    <link rel="stylesheet" href="/assets/style/members.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
      integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="icon" href="/assets/img/malal_icon.ico" type="image/x-icon">

</head>
<body id="body-member">
    <?php include __DIR__ . '/components/header.php'; ?>
    <?php include __DIR__ . '/components/sidebar.php'; ?>
    <main id="members">
        <h2>Membros do Grêmio Estudantil</h2>
        <div id="container-member">
            <?php
            require_once __DIR__ . '/../controller/MemberController.php';
            $members = MemberController::getMembers();
            if (empty($members)):
            ?>
                <p>Nenhum membro cadastrado ainda. Faça login no admin para incluir membros.</p>
            <?php else: ?>
                <?php foreach ($members as $member): ?>
                    <article class="member-card">
                        <?php if (!empty($member['photo'])): ?>
                            <img src="<?= htmlspecialchars($member['photo']) ?>" alt="<?= htmlspecialchars($member['name']) ?>">
                        <?php endif; ?>
                        <div class="member-content">
                            <span class="member-role"><?= htmlspecialchars($member['role']) ?></span>
                            <h3><?= htmlspecialchars($member['name']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($member['bio'])) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <?php include __DIR__ . '/components/duvidas.php'; ?>

    <?php include __DIR__ . '/components/footer.php'; ?>
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