<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Questões</title>
</head>
<body>
    <h1>Lista de Questões</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Texto</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questoes as $questao): ?>
                <tr>
                    <td><?= htmlspecialchars($questao['id']) ?></td>
                    <td><?= htmlspecialchars($questao['texto']) ?></td>
                    <td><?= htmlspecialchars($questao['tipo']) ?></td>
                    <td>
                        <a href="index.php?action=editar_questao&id=<?= $questao['id'] ?>">Editar</a>
                        <a href="index.php?action=deletar_questao&id=<?= $questao['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esta questão?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>