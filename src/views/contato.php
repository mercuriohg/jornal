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
    <?php include __DIR__ . '/components/header.php'; ?>
    <?php include __DIR__ . '/components/sidebar.php'; ?>
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
        <section class="teachers-section">
    <div class="container">

        <h2>Docentes</h2>
        <p class="subtitle">
            Email dos docentes do IFRS Campus Rolante.
        </p>
          <form class="search-form" action="/contato" method="get">
            <input type="search" name="docente" placeholder="Buscar docentes..." value="<?= htmlspecialchars($_GET['docente'] ?? '') ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <div class="teachers-grid">
            
            <?php
            $docentes = [
                ['nome' => 'Adriana Regina Corrent', 'cargo' => 'Professora EBTT', 'area' => 'Agroindústria', 'email' => 'adriana.corrent@rolante.ifrs.edu.br'],
                ['nome' => 'Alécio Vaneli Gaigher Marely', 'cargo' => 'Professor EBTT', 'area' => 'Português / Inglês', 'email' => 'alecio.marely@rolante.ifrs.edu.br'],
                ['nome' => 'Aline Beatriz Schuh', 'cargo' => 'Professora EBTT', 'area' => 'Administração', 'email' => 'aline.schuh@rolante.ifrs.edu.br'],
                ['nome' => 'Ana Maria Mrás', 'cargo' => 'Professora EBTT', 'area' => 'Matemática', 'email' => 'ana.mras@rolante.ifrs.edu.br'],
                ['nome' => 'Andressa Minussi Pereira Dau', 'cargo' => 'Professora EBTT', 'area' => 'Veterinária / Zootecnia', 'email' => 'andressa.pereira@rolante.ifrs.edu.br'],
                ['nome' => 'Cláudia Dias Zettermann', 'cargo' => 'Professora EBTT', 'area' => 'Veterinária', 'email' => 'claudia.zettermann@rolante.ifrs.edu.br'],
                ['nome' => 'Diane Blank Bencke', 'cargo' => 'Professora EBTT', 'area' => 'Português / Inglês', 'email' => 'diane.bencke@rolante.ifrs.edu.br'],
                ['nome' => 'Edgar Henrique de Castro', 'cargo' => 'Professor EBTT', 'area' => 'Geografia', 'email' => 'edgar.castro@rolante.ifrs.edu.br'],
                ['nome' => 'Errol Fernando Zepka Pereira Júnior', 'cargo' => 'Professor EBTT', 'area' => 'Administração', 'email' => 'fernando.zepka@rolante.ifrs.edu.br'],
                ['nome' => 'Fábio Castilhos Figueiredo', 'cargo' => 'Professor EBTT', 'area' => 'Letras – Português / Inglês', 'email' => 'fabio.castilhos@rolante.ifrs.edu.br'],
                ['nome' => 'Fábio Rios Kwecko', 'cargo' => 'Professor EBTT', 'area' => 'Administração', 'email' => 'fabio.kwecko@rolante.ifrs.edu.br'],
                ['nome' => 'Fábio Zschornack', 'cargo' => 'Professor EBTT', 'area' => 'Informática, Banco de Dados e Prog. Web', 'email' => 'fabio.zschornack@rolante.ifrs.edu.br'],
                ['nome' => 'Fernando Gonçalves de Gonçalves', 'cargo' => 'Professor EBTT', 'area' => 'Sociologia', 'email' => 'fernando.goncalves@rolante.ifrs.edu.br'],
                ['nome' => 'Fernando Luis Hillebrand', 'cargo' => 'Professor EBTT', 'area' => 'Topografia e Desenho Técnico', 'email' => 'fernando.hillebrand@rolante.ifrs.edu.br'],
                ['nome' => 'Frederico Schardong', 'cargo' => 'Professor EBTT', 'area' => 'Informática', 'email' => 'frederico.schardong@rolante.ifrs.edu.br'],
                ['nome' => 'Gabriel Marchesan', 'cargo' => 'Professor EBTT', 'area' => 'Arquitetura de Computadores e Rede', 'email' => 'gabriel.marchesan@rolante.ifrs.edu.br'],
                ['nome' => 'Gabriela Gava Sonai', 'cargo' => 'Professora EBTT', 'area' => 'Química', 'email' => 'gabriela.sonai@rolante.ifrs.edu.br'],
                ['nome' => 'Gabriela Javornik Barroso', 'cargo' => 'Professora EBTT', 'area' => 'Zootecnia / Medicina Veterinária', 'email' => 'gabriela.barroso@rolante.ifrs.edu.br'],
                ['nome' => 'Gustavo dos Santos Rodrigues', 'cargo' => 'Professor EBTT', 'area' => 'Letras – Português / Inglês', 'email' => 'gustavo.rodrigues@rolante.ifrs.edu.br'],
                ['nome' => 'Ilisandro Pesente', 'cargo' => 'Professor EBTT', 'area' => 'Matemática', 'email' => 'ilisandro.pesente@rolante.ifrs.edu.br'],
                ['nome' => 'Ione dos Santos Canabarro Araújo', 'cargo' => 'Professora EBTT', 'area' => 'Física', 'email' => 'ione.araujo@rolante.ifrs.edu.br'],
                ['nome' => 'Ismael Bernardo Pereira', 'cargo' => 'Professor EBTT', 'area' => 'Letras – Português / Inglês', 'email' => 'ismael.pereira@rolante.ifrs.edu.br'],
                ['nome' => 'Jacques André Grings', 'cargo' => 'Professor EBTT', 'area' => 'Administração', 'email' => 'jacques.grings@rolante.ifrs.edu.br', 'observacao' => 'Afastamento para qualificação até 04/2027'],
                ['nome' => 'Jeferson Mateus Dariva', 'cargo' => 'Professor EBTT', 'area' => 'Agropecuária', 'email' => 'jeferson.dariva@rolante.ifrs.edu.br'],
                ['nome' => 'Jesus Rosemar Borges', 'cargo' => 'Professor EBTT', 'area' => 'Agropecuária', 'email' => 'jesus.borges@rolante.ifrs.edu.br'],
                ['nome' => 'Josmael Corso', 'cargo' => 'Professor EBTT', 'area' => 'Biologia', 'email' => 'josmael.corso@rolante.ifrs.edu.br'],
                ['nome' => 'Joyce Moura Borowski', 'cargo' => 'Professora EBTT', 'area' => 'Química', 'email' => 'joyce.borowski@rolante.ifrs.edu.br'],
                ['nome' => 'Julian da Silva Lima', 'cargo' => 'Professor EBTT', 'area' => 'Matemática', 'email' => 'julian.lima@rolante.ifrs.edu.br'],
                ['nome' => 'Karina Rodrigues Lorenzatto', 'cargo' => 'Professora EBTT', 'area' => 'Biologia', 'email' => 'karina.lorenzatto@rolante.ifrs.edu.br'],
                ['nome' => 'Lauri Miranda Silva', 'cargo' => 'Professora EBTT', 'area' => 'História', 'email' => 'lauri.silva@rolante.ifrs.edu.br'],
                ['nome' => 'Leandro Mendes Nogueira', 'cargo' => 'Professor EBTT', 'area' => 'Geografia', 'email' => 'leandro.nogueira@rolante.ifrs.edu.br'],
                ['nome' => 'Letícia Martins de Martins', 'cargo' => 'Professora EBTT', 'area' => 'Administração', 'email' => 'leticia.martins@rolante.ifrs.edu.br', 'observacao' => 'Diretora Geral do IFRS Campus Rolante'],
                ['nome' => 'Luciana Lopes de Freitas', 'cargo' => 'Professor EBTT', 'area' => 'Ciências Contábeis', 'email' => 'luciana.freitas@rolante.ifrs.edu.br'],
                ['nome' => 'Luciano Nascimento Corsino', 'cargo' => 'Professor EBTT', 'area' => 'Educação Física', 'email' => 'luciano.corsino@rolante.ifrs.edu.br'],
                ['nome' => 'Luiz Antonio Teffili', 'cargo' => 'Professor EBTT', 'area' => 'Ciências Contábeis', 'email' => 'luiz.teffili@rolante.ifrs.edu.br'],
                ['nome' => 'Márcio Correia Vasconcelos', 'cargo' => 'Professor EBTT', 'area' => 'Direito', 'email' => 'marcio.vasconcelos@rolante.ifrs.edu.br'],
                ['nome' => 'Marco Antoni', 'cargo' => 'Professor EBTT', 'area' => 'Arquitetura e Rede de Computadores', 'email' => 'marco.antoni@rolante.ifrs.edu.br'],
                ['nome' => 'Médelin Marques da Silva', 'cargo' => 'Professora EBTT', 'area' => 'Agronomia', 'email' => 'medelin.silva@rolante.ifrs.edu.br'],
                ['nome' => 'Mônica Grazieli Marquet', 'cargo' => 'Professora EBTT', 'area' => '', 'email' => 'monica.marquet@rolante.ifrs.edu.br'],
                ['nome' => 'Myllena Camargo de Oliveira', 'cargo' => 'Professora EBTT', 'area' => 'Educação Física', 'email' => 'myllena.camargo@rolante.ifrs.edu.br'],
                ['nome' => 'Rodrigo Belinaso Guimarães', 'cargo' => 'Professor EBTT', 'area' => 'Ciências Sociais', 'email' => 'rodrigo.guimaraes@rolante.ifrs.edu.br'],
                ['nome' => 'Roselei Haag', 'cargo' => 'Professor EBTT Substituto', 'area' => 'Administração', 'email' => 'roselei.haag@rolante.ifrs.edu.br'],
                ['nome' => 'Sabrina Favaretto Antunes', 'cargo' => 'Professora EBTT', 'area' => 'Artes, Música', 'email' => 'sabrina.antunes@rolante.ifrs.edu.br'],
                ['nome' => 'Simone Ossani', 'cargo' => 'Professora EBTT', 'area' => 'Matemática', 'email' => 'simone.ossani@rolante.ifrs.edu.br'],
                ['nome' => 'Taíse Tatiana Quadros da Silva', 'cargo' => 'Professora EBTT', 'area' => 'História', 'email' => 'taise.silva@rolante.ifrs.edu.br'],
                ['nome' => 'Thiago Cruz da Silva', 'cargo' => 'Professor EBTT', 'area' => 'Filosofia', 'email' => 'thiago.silva@rolante.ifrs.edu.br'],
                ['nome' => 'Vanessa Alves Marques Perius', 'cargo' => 'Professora EBTT', 'area' => 'Letras / Espanhol', 'email' => 'vanessa.perius@rolante.ifrs.edu.br'],
                ['nome' => 'Vinicius Dornelles Valent', 'cargo' => 'Professor EBTT', 'area' => 'Administração', 'email' => 'vinicius.valent@rolante.ifrs.edu.br'],
                ['nome' => 'Victor da Cruz Peres', 'cargo' => 'Professor EBTT', 'area' => 'Informática – Programação', 'email' => 'victor.peres@rolante.ifrs.edu.br'],
            ];

            $termoBusca = trim($_GET['docente'] ?? '');
            $normalizar = static function (string $valor): string {
                $valor = mb_strtolower($valor, 'UTF-8');
                return iconv('UTF-8', 'ASCII//TRANSLIT', $valor) ?: $valor;
            };

            $docentes = array_values(array_filter($docentes, function ($docente) use ($termoBusca, $normalizar): bool {
                if ($termoBusca === '') {
                    return true;
                }

                return str_contains($normalizar($docente['nome']), $normalizar($termoBusca));
            }));
            if (empty($docentes)):
                echo '<p class="subtitle">Nenhum docente encontrado para a busca realizada.</p>';
            endif;

            foreach ($docentes as $docente):
            ?>

          
            <article class="teacher-card">
                <h3><?= htmlspecialchars($docente['nome']) ?></h3>

                <span class="area">
                    <?= htmlspecialchars(trim(($docente['cargo'] ?? '') . (!empty($docente['area']) ? ' • ' . $docente['area'] : ''))) ?>
                </span>

                <?php if (!empty($docente['observacao'])): ?>
                    <span class="area">
                        <?= htmlspecialchars($docente['observacao']) ?>
                    </span>
                <?php endif; ?>

                <a href="mailto:<?= htmlspecialchars($docente['email']) ?>">
                    <?= htmlspecialchars($docente['email']) ?>
                </a>
            </article>
            <?php endforeach; ?>

        </div>

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