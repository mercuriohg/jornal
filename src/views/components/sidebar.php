<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAdminLoggedIn = !empty($_SESSION['username']);
?>

<i class="fas fa-bars" id="menu-toggle"></i>
<nav id="sidebar">
    <ul>
        <li><a href="/">Início</a></li>
        <li><a href="/noticias">Notícias</a></li>
        <li><a href="/esportes">Esportes</a></li>
        <li><a href="/projetos">Projetos</a></li>
        <li><a href="/calendario">Calendário</a></li>
        <li><a href="/contato">Contato</a></li>
        <li><a href="/editais">Editais</a></li>
        <li><a href="/membros">Membros</a></li>
        <?php if ($isAdminLoggedIn): ?>
            <li><a href="/admin">Painel Admin</a></li>
        <?php endif; ?>
    </ul>
</nav>