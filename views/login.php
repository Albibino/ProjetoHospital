<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - Administração</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="index.php?action=login">
        <div>
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <button type="submit">Entrar</button>
        </div>
    </form>
</body>
</html>
