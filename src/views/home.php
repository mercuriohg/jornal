<?php
require_once __DIR__ . '/../controller/NewsController.php';
$latestNews = NewsController::getNewsByType('noticia', 3);
$eventNews = NewsController::getNewsByType('evento', 3);
$noticeNews = NewsController::getNewsByType('aviso', 3);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grêmio Estudantil</title>
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
    <form class="search-form" action="/noticias" method="get">
            <input type="search" name="q" placeholder="Buscar por jogos, esportes, projetos..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
    </form>
    <?php include 'components/sidebar.php'; ?>

   <main id="main-home">

    <aside id="content-aside">

        <h2>Últimas Notícias</h2>
         <div id="logo-if">
            <img src="/assets/img/if-logo-vertical.png" height="100">
        </div>

        <?php if (empty($latestNews)): ?>
            <div class="mini-news">
                <img src="/assets/img/noticia.jpg">
                <div>
                    <h4>Sem notícias publicadas</h4>
                    <small>Publique no painel administrativo</small>
                </div>
            </div>
        <?php else: ?>
            <?php foreach (array_slice($latestNews, 0, 2) as $news): ?>
                <div class="mini-news<?= empty($news['image']) ? ' mini-news-no-image' : '' ?>">
                    <?php if (!empty($news['image'])): ?>
                        <img src="<?= htmlspecialchars($news['image']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                    <?php endif; ?>
                    <div>
                        <h4><?= htmlspecialchars($news['title']) ?></h4>
                        <small><?= date('d/m/Y', $news['created_at']) ?> • <?= htmlspecialchars(ucfirst($news['tag'])) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </aside>

    <section id="journal-main">

        <?php if (!empty($latestNews)): ?>
            <?php $featured = $latestNews[0]; ?>
            <article class="hero">
                <?php if (!empty($featured['image'])): ?>
                    <img src="<?= htmlspecialchars($featured['image']) ?>" alt="<?= htmlspecialchars($featured['title']) ?>">
                <?php endif; ?>

                <div class="hero-content">
                    <span><?= htmlspecialchars(ucfirst($featured['tag'])) ?></span>
                    <h1><?= htmlspecialchars($featured['title']) ?></h1>
                    <p><?= htmlspecialchars($featured['summary']) ?></p>
                    <a class="hero-button" href="/noticia?id=<?= urlencode($featured['id']) ?>">Ler matéria</a>
                </div>
            </article>
        <?php endif; ?>

        <div class="news-grid">
            <?php if (empty($latestNews)): ?>
                <article class="card">
                    <img src="/assets/img/noticia.jpg">
                    <h3>Sem notícias publicadas</h3>
                    <p>Publique notícias no painel para aparecer aqui.</p>
                </article>
            <?php else: ?>
                <?php foreach ($latestNews as $news): ?>
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

    <aside id="curiosidades">

        <div class="widget">

            <h3>📅 Próximos Eventos</h3>

            <?php if (!empty($eventNews)): ?>
                <ul>
                    <?php foreach ($eventNews as $event): ?>
                        <li>
                            <a href="/noticia?id=<?= urlencode($event['id']) ?>"><?= htmlspecialchars($event['title']) ?></a>
                            <br>
                            <small><?= date('d/m/Y', $event['created_at']) ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Publique eventos no painel para exibí-los aqui.</p>
            <?php endif; ?>

        </div>

        <div class="widget">

            <h3>📢 Avisos</h3>

            <?php if (!empty($noticeNews)): ?>
                <ul>
                    <?php foreach ($noticeNews as $notice): ?>
                        <li>
                            <a href="/noticia?id=<?= urlencode($notice['id']) ?>"><?= htmlspecialchars($notice['title']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Publique avisos no painel para mostrar comunicados aqui.</p>
            <?php endif; ?>

        </div>

    </aside>

</main>

<section id="galeria"></section>

<section id="calendario"></section>
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