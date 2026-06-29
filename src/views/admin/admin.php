<?php
require_once __DIR__ . '/../../controller/NewsController.php';
$newsList = NewsController::getLatestNews(8);
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$editId = $_GET['edit'] ?? '';
$editNews = $editId ? NewsController::getNewsById($editId) : null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="/assets/style/index.css">
</head>
<body>
    <main class="admin-dashboard">
        <section class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <h1>Painel de publicações</h1>
                    <p>Olá <?php echo htmlspecialchars($_SESSION['username'] ?? 'Visitante'); ?></p>
                </div>
                <div id="logout">
                    <a class="logout-link" href="/logout">Sair</a>
                    <a class="edit-link" href="/noticias"> Ver Noticia</a>
                   <a class="edit-link" href="/admin-members"> + Adicionar Membro</a>

                </div>
            </div>

            <?php if ($error === 'missing'): ?>
                <div class="alert alert-error">Por favor, preencha título, resumo e tag.</div>
            <?php elseif ($error === 'notfound'): ?>
                <div class="alert alert-error">Notícia não encontrada.</div>
            <?php elseif ($success === '1'): ?>
                <div class="alert alert-success">Notícia publicada com sucesso!</div>
            <?php elseif (isset($_GET['updated'])): ?>
                <div class="alert alert-success">Notícia atualizada com sucesso!</div>
            <?php elseif (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Notícia excluída com sucesso!</div>
            <?php endif; ?>

            <form class="admin-form" action="/save-news" method="post" enctype="multipart/form-data">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" placeholder="Título da notícia" required>

                <label for="summary">Resumo</label>
                <textarea id="summary" name="summary" placeholder="Breve descrição" rows="4" required></textarea>

                <label for="content">Texto da matéria</label>
                <textarea id="content" name="content" placeholder="Conteúdo completo da notícia" rows="6"></textarea>

                <label for="tag">Tag</label>
                <input type="text" id="tag" name="tag" placeholder="Ex: esportes, projetos, cultura" required>

                <label for="type">Tipo de publicação</label>
                <select id="type" name="type" required>
                    <option value="noticia">Notícia</option>
                    <option value="evento">Evento</option>
                    <option value="aviso">Aviso</option>
                </select>

                <label for="image">URL da imagem (opcional)</label>
                <input type="url" id="image" name="image" placeholder="https://...">

                <label for="image_file">Upload da imagem/banner (opcional)</label>
                <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp">

                <label for="attachments">Anexos (PDF, imagem, edital)</label>
                <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                <button type="submit">Publicar notícia</button>
            </form>
        </section>

        <?php if ($editNews): ?>
            <section class="admin-edit">
                <h2>Editar notícia</h2>
                <form class="admin-form" action="/update-news?id=<?= urlencode($editNews['id']) ?>" method="post" enctype="multipart/form-data">
                    <label for="title-edit">Título</label>
                    <input type="text" id="title-edit" name="title" value="<?= htmlspecialchars($editNews['title']) ?>" required>

                    <label for="summary-edit">Resumo</label>
                    <textarea id="summary-edit" name="summary" rows="4" required><?= htmlspecialchars($editNews['summary']) ?></textarea>

                    <label for="content-edit">Texto da matéria</label>
                    <textarea id="content-edit" name="content" rows="6"><?= htmlspecialchars($editNews['content'] ?? '') ?></textarea>

                    <label for="tag-edit">Tag</label>
                    <input type="text" id="tag-edit" name="tag" value="<?= htmlspecialchars($editNews['tag']) ?>" required>

                    <label for="type-edit">Tipo de publicação</label>
                    <select id="type-edit" name="type" required>
                        <option value="noticia" <?= (htmlspecialchars($editNews['type'] ?? 'noticia') === 'noticia') ? 'selected' : '' ?>>Notícia</option>
                        <option value="evento" <?= (htmlspecialchars($editNews['type'] ?? 'noticia') === 'evento') ? 'selected' : '' ?>>Evento</option>
                        <option value="aviso" <?= (htmlspecialchars($editNews['type'] ?? 'noticia') === 'aviso') ? 'selected' : '' ?>>Aviso</option>
                    </select>

                    <label for="image-edit">URL da imagem (opcional)</label>
                    <input type="url" id="image-edit" name="image" value="<?= htmlspecialchars($editNews['image']) ?>">

                    <label for="image-file-edit">Upload da imagem/banner (substitui a imagem atual)</label>
                    <input type="file" id="image-file-edit" name="image_file" accept="image/jpeg,image/png,image/webp">

                    <label for="attachments-edit">Enviar anexos adicionais</label>
                    <input type="file" id="attachments-edit" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                    <?php if (!empty($editNews['attachments'])): ?>
                        <div class="attachment-list">
                            <p>Anexos atuais:</p>
                            <ul>
                                <?php foreach ($editNews['attachments'] as $attachment): ?>
                                    <li><a href="<?= htmlspecialchars($attachment) ?>" target="_blank"><?= htmlspecialchars(basename($attachment)) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <button type="submit">Salvar alterações</button>
                </form>
            </section>
        <?php endif; ?>

        <section class="admin-list">
            <h2>Últimas notícias publicadas</h2>
            <?php if (empty($newsList)): ?>
                <p>Não há notícias publicadas ainda.</p>
            <?php else: ?>
                <div class="news-grid admin-news-grid">
                    <?php foreach ($newsList as $news): ?>
                        <article class="card admin-card">
                            <?php if (!empty($news['image'])): ?>
                                <img src="<?= htmlspecialchars($news['image']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                            <?php else: ?>
                                <img src="/assets/img/noticia.jpg" alt="Imagem da notícia">
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="tag-badge"><?= htmlspecialchars(ucfirst($news['tag'])) ?></span>
                                <h3><?= htmlspecialchars($news['title']) ?></h3>
                                <p><?= htmlspecialchars($news['summary']) ?></p>
                                <div class="admin-actions">
                                    <a class="edit-link" href="/admin?edit=<?= urlencode($news['id']) ?>">Editar</a>
                                    <a class="delete-link" href="/delete-news?id=<?= urlencode($news['id']) ?>" onclick="return confirm('Excluir esta notícia?');">Excluir</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>