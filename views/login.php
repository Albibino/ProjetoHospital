<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - Administração</title>
    <link rel="stylesheet" type="text/css" href="css/styles6.css">
</head>
<body>
    <main>
        <h1>Login</h1>
        <form method="POST" action="index.php?action=login">
            <div>
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Entrar</button>
            </div>
        </form>
    </main>
</body>
</html>