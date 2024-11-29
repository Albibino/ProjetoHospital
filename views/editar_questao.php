<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Questão</title>
</head>
<body>
    <h1>Editar Questão</h1>
    <form method="POST" action="index.php?action=atualizar_questao">
        <input type="hidden" name="id" value="<?= htmlspecialchars($questao['id']) ?>">
        <div>
            <label for="texto">Texto da Questão:</label>
            <textarea id="texto" name="texto" rows="4" cols="50" required><?= htmlspecialchars($questao['texto']) ?></textarea>
        </div>
        <div>
            <label for="tipo">Tipo de Questão:</label>
            <select id="tipo" name="tipo" required>
                <option value="slider" <?= $questao['tipo'] === 'slider' ? 'selected' : '' ?>>Slider</option>
                <option value="texto" <?= $questao['tipo'] === 'texto' ? 'selected' : '' ?>>Texto</option>
            </select>
        </div>
        <div>
            <button type="submit">Salvar Alterações</button>
        </div>
    </form>
</body>
</html>
