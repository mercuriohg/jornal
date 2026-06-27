<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Grêmio Estudantil</title>
    <link rel="stylesheet" href="/assets/style/index.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
      integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body>

    <main class="login-main">
        <section class="login-card">
            <div class="login-brand">
                <span>Área restrita</span>
                <h1>Login do administrador</h1>
                <p>Acesse o painel para publicar notícias, projetos e eventos.</p>
            </div>

            <form class="login-form" action="/login" method="post">
                <label for="username">Usuário ou Email</label>
                <input type="text" id="username" name="username" placeholder="admin@exemplo.com ou Usuário" required>

                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>

                <button type="submit">Entrar</button>
            </form>
        </section>
    </main>

</body>
</html>
