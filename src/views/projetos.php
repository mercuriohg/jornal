<?php
require_once __DIR__ . '/../controller/NewsController.php';
$newsList = NewsController::getNewsByTag('projetos');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos</title>
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


   <main id="main-content">
        <section id="journal-main">
            <article class="hero">
                <div class="hero-content">
                    <span>PROJETOS</span>
                    <h1>Notícias de projetos</h1>
                    <p>Veja as últimas iniciativas publicadas pelo grêmio.</p>
                </div>
            </article>

            <div class="news-grid">
                <?php if (empty($newsList)): ?>
                    <div class="card">
                        <div class="card-content">
                            <h3>Sem notícias de projetos</h3>
                            <p>Publique novas notícias no painel administrativo para aparecer aqui.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($newsList as $news): ?>
                        <article class="card">
                            <?php if (!empty($news['image'])): ?>
                                <img src="<?= htmlspecialchars($news['image']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                            <?php else: ?>
                                <img src="/assets/img/noticia.jpg" alt="Imagem da notícia">
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="tag-badge"><?= htmlspecialchars(ucfirst($news['tag'])) ?></span>
                                <h3><?= htmlspecialchars($news['title']) ?></h3>
                                <p><?= htmlspecialchars($news['summary']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
   </main>

    <?php include 'components/footer.php'; ?>
    <script> 
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
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
