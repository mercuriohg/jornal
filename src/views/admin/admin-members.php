<?php
require_once __DIR__ . '/../../controller/MemberController.php';
$members = MemberController::getMembers();
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$editId = $_GET['edit'] ?? '';
$editMember = $editId ? MemberController::getMemberById($editId) : null;
$roles = [
    'Presidente',
    'Vice-Presidente',
    'Diretora Administrativa',
    'Vice-Diretor Administrativo',
    'Secretária Geral',
    'Tesoureira',
    'Vice-Tesoureira',
    'Diretor de Assuntos Estudantis',
    'Vice-Diretor de Assuntos Estudantis',
    'Diretor de Relações Públicas',
    'Vice-Diretora de Relações Públicas',
    'Diretor de Esporte e Cultura',
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Membros</title>
    <link rel="stylesheet" href="/assets/style/index.css">
</head>
<body>
    <main class="admin-dashboard">
        <section class="admin-panel">
            <div class="admin-panel-header">
                <div>
                    <h1>Painel de Membros</h1>
                    <p>Olá <?php echo htmlspecialchars($_SESSION['username'] ?? 'Visitante'); ?></p>
                </div>
                <div id="logout">
                    <a class="logout-link" href="/logout">Sair</a>
                    <a class="edit-link" href="/membros">Ver membros</a>
                    <a class="edit-link" href="/admin"> + Adicionar Noticia</a>
                </div>
            </div>

            <?php if ($error === 'missing'): ?>
                <div class="alert alert-error">Preencha nome, cargo e biografia.</div>
            <?php elseif ($error === 'notfound'): ?>
                <div class="alert alert-error">Membro não encontrado.</div>
            <?php elseif ($success === '1'): ?>
                <div class="alert alert-success">Membro adicionado com sucesso!</div>
            <?php elseif (isset($_GET['updated'])): ?>
                <div class="alert alert-success">Membro atualizado com sucesso!</div>
            <?php elseif (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Membro excluído com sucesso!</div>
            <?php endif; ?>

            <form class="admin-form" action="/save-member" method="post" enctype="multipart/form-data">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" placeholder="Nome do membro" required>

                <label for="role">Cargo</label>
                <select id="role" name="role" required>
                    <option value="">Selecione um cargo</option>
                    <?php foreach ($roles as $roleOption): ?>
                        <option value="<?= htmlspecialchars($roleOption) ?>"><?= htmlspecialchars($roleOption) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="bio">Biografia</label>
                <textarea id="bio" name="bio" placeholder="Digite a biografia do membro" rows="5" required></textarea>

                <label for="photo">URL da foto (opcional)</label>
                <input type="url" id="photo" name="photo" placeholder="https://...">

                <label for="photo_file">Upload da foto (opcional)</label>
                <input type="file" id="photo_file" name="photo_file" accept="image/jpeg,image/png,image/webp">

                <button type="submit">Adicionar membro</button>
            </form>
        </section>

        <?php if ($editMember): ?>
            <section class="admin-edit">
                <h2>Editar membro</h2>
                <form class="admin-form" action="/update-member?id=<?= urlencode($editMember['id']) ?>" method="post" enctype="multipart/form-data">
                    <label for="name-edit">Nome</label>
                    <input type="text" id="name-edit" name="name" value="<?= htmlspecialchars($editMember['name']) ?>" required>

                    <label for="role-edit">Cargo</label>
                    <select id="role-edit" name="role" required>
                        <?php foreach ($roles as $roleOption): ?>
                            <option value="<?= htmlspecialchars($roleOption) ?>" <?= $editMember['role'] === $roleOption ? 'selected' : '' ?>><?= htmlspecialchars($roleOption) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="bio-edit">Biografia</label>
                    <textarea id="bio-edit" name="bio" rows="5" required><?= htmlspecialchars($editMember['bio']) ?></textarea>

                    <label for="photo-edit">URL da foto (opcional)</label>
                    <input type="url" id="photo-edit" name="photo" value="<?= htmlspecialchars($editMember['photo']) ?>">

                    <label for="photo-file-edit">Upload da foto (substitui a atual)</label>
                    <input type="file" id="photo-file-edit" name="photo_file" accept="image/jpeg,image/png,image/webp">

                    <button type="submit">Salvar alterações</button>
                </form>
            </section>
        <?php endif; ?>

        <section class="admin-list">
            <h2>Membros cadastrados</h2>
            <?php if (empty($members)): ?>
                <p>Não há membros cadastrados.</p>
            <?php else: ?>
                <div class="news-grid admin-news-grid">
                    <?php foreach ($members as $member): ?>
                        <article class="card admin-card">
                            <?php if (!empty($member['photo'])): ?>
                                <img src="<?= htmlspecialchars($member['photo']) ?>" alt="<?= htmlspecialchars($member['name']) ?>">
                            <?php else: ?>
                                <img src="/assets/img/noticia.jpg" alt="Foto de <?= htmlspecialchars($member['name']) ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="tag-badge"><?= htmlspecialchars($member['role']) ?></span>
                                <h3><?= htmlspecialchars($member['name']) ?></h3>
                                <p><?= htmlspecialchars(mb_strimwidth($member['bio'], 0, 120, '...')) ?></p>
                                <div class="admin-actions">
                                    <a class="edit-link" href="/admin-members?edit=<?= urlencode($member['id']) ?>">Editar</a>
                                    <a class="delete-link" href="/delete-member?id=<?= urlencode($member['id']) ?>" onclick="return confirm('Excluir este membro?');">Excluir</a>
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