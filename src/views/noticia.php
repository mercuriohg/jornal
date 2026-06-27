<?php
require_once __DIR__ . '/../controller/NewsController.php';

$id = $_GET['id'] ?? '';
$news = NewsController::getNewsById($id);

if (!$news) {
    header('Location: /noticias');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($news['title']) ?></title>
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


   <main class="news-page">
       <section class="news-detail">
           <article class="news-header">
               <?php if (!empty($news['image'])): ?>
                   <img src="<?= htmlspecialchars($news['image']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
               <?php endif; ?>
               <div class="hero-content">
                   <span><?= htmlspecialchars(ucfirst($news['tag'])) ?></span>
                   <h1><?= htmlspecialchars($news['title']) ?></h1>
                       <small>Publicado em <?= date('d/m/Y', $news['created_at']) ?></small>
               </div>
           </article>

           <article class="news-body">
               <p class="news-summary"><?= nl2br(htmlspecialchars($news['summary'])) ?></p>
               <?php if (!empty($news['content'])): ?>
                   <div class="news-content"><?= nl2br(htmlspecialchars($news['content'])) ?></div>
               <?php endif; ?>

               <?php if (!empty($news['attachments'])): ?>
                   <div class="attachment-list">
                       <h3>Anexos</h3>
                       <ul>
                           <?php foreach ($news['attachments'] as $attachment): ?>
                               <li><a href="<?= htmlspecialchars($attachment) ?>" target="_blank"><?= htmlspecialchars(basename($attachment)) ?></a></li>
                           <?php endforeach; ?>
                       </ul>
                   </div>
               <?php endif; ?>

               <a class="back-link" href="/noticias">Voltar para Notícias</a>
           </article>
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
