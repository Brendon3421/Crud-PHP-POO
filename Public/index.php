<?php

use DatabaseNamespace\Database;
use DatabaseNamespace\Insert;

require '../Classes/Database.php';
require '../Classes/insert.php';

$database = new Database();
$db = $database->connect(); 

$insert = new Insert($db);

// Handle Delete
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM produtos WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header('Location: index.php');
    exit();
}

// Handle Edit
if (isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];

    $query = "UPDATE produtos SET nome = :nome, valor = :valor, quantidade = :quantidade, descricao = :descricao WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header('Location: index.php');
    exit();
}

$product = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $query = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

$insert->setTable('produtos');
$products = $insert->getAll();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Product List</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $prod): ?>
                <tr>
                    <td><?php echo htmlspecialchars($prod['id']); ?></td>
                    <td><?php echo htmlspecialchars($prod['nome']); ?></td>
                    <td><?php echo htmlspecialchars($prod['valor']); ?></td>
                    <td><?php echo htmlspecialchars($prod['quantidade']); ?></td>
                    <td><?php echo htmlspecialchars($prod['descricao']); ?></td>
                    <td>
                        <a href="?edit_id=<?php echo $prod['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="?delete_id=<?php echo $prod['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($product): ?>
        <!-- Edit Form -->
        <h2>Edit Product</h2>
        <form method="POST">
            <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($product['nome']); ?>">
            </div>
            <div class="mb-3">
                <label>Price</label>
                <input type="text" name="valor" class="form-control" value="<?php echo htmlspecialchars($product['valor']); ?>">
            </div>
            <div class="mb-3">
                <label>Quantity</label>
                <input type="text" name="quantidade" class="form-control" value="<?php echo htmlspecialchars($product['quantidade']); ?>">
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="descricao" class="form-control"><?php echo htmlspecialchars($product['descricao']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    <?php endif; ?>

</div>
</body>
</html>
