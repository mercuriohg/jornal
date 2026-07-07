<?php
require_once __DIR__ . '/../controller/NewsController.php';
$searchQuery = trim($_GET['q'] ?? '');
$newsList = $searchQuery !== '' ? NewsController::searchNews($searchQuery) : NewsController::readNews();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias</title>
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
    <?php include __DIR__ . '/components/header.php'; ?>
    <?php include __DIR__ . '/components/sidebar.php'; ?>


   <main id="main-content">
        <section id="journal-main">
            <article class="hero">
                <div class="hero-content">
                    <span>NOTÍCIAS</span>
                    <h1>Todas as notícias publicadas</h1>
                    <p>Veja as últimas publicações e navegue por tags específicas.</p>
                </div>
            </article>

            <form class="news-search-form" action="/noticias" method="get">
                <input type="search" name="q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Buscar por jogos, esportes, projetos, tags...">
                <button type="submit"><i class="fas fa-search"></i> Buscar</button>
            </form>

            <?php if ($searchQuery !== ''): ?>
                <div class="search-info">
                    <p>Mostrando <?= count($newsList) ?> resultado(s) para "<?= htmlspecialchars($searchQuery) ?>".</p>
                </div>
            <?php endif; ?>

            <div class="news-grid">
                <?php if (empty($newsList)): ?>
                    <article class="card">
                        <img src="/assets/img/noticia.jpg" alt="Sem notícias">
                        <h3>Sem notícias publicadas</h3>
                        <p>Quando uma notícia for publicada no painel, ela aparecerá aqui.</p>
                    </article>
                <?php else: ?>
                    <?php foreach ($newsList as $news): ?>
                        <a class="card-link" href="/noticia?id=<?= urlencode($news['id']) ?>">
                            <article class="card<?= empty($news['image']) ? ' card-no-image' : '' ?>">
                                <?php if (!empty($news['image'])): ?>
                                    <img src="<?= htmlspecialchars($news['image']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <span class="tag-badge"><?= htmlspecialchars(ucfirst($news['tag'])) ?></span>
                                    <h3><?= htmlspecialchars($news['title']) ?></h3>
                                    <p><?= htmlspecialchars($news['summary']) ?></p>
                                </div>
                            </article>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
   </main>
    <?php include __DIR__ . '/components/duvidas.php'; ?>

    <?php include __DIR__ . '/components/footer.php'; ?>
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
