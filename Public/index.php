<?php
session_start(); // Inicia a sessão

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

    // Armazena a mensagem de sucesso na sessão
    $_SESSION['message'] = "Product deleted successfully!";
    header('Location: index.php');
    exit();
}

// Handle Add New Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['edit_id'])) {
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];

    $query = "INSERT INTO produtos (nome, valor, quantidade, descricao) VALUES (:nome, :valor, :quantidade, :descricao)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->execute();

    // Armazena a mensagem de sucesso na sessão
    $_SESSION['message'] = "Product added successfully!";
    header('Location: index.php');
    exit();
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
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

    // Armazena a mensagem de sucesso na sessão
    $_SESSION['message'] = "Product updated successfully!";
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
    <link rel="stylesheet" href="<?php "../../assets/css/style.css" ?>">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Crud-POO</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary flex-end bg-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        adicionar item
                    </button>
                </div>
            </div>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Adicionar Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="index.php">
                            <div class="mb-3">
                                <label for="nome">Name</label>
                                <input type="text" name="nome" class="form-control" id="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="valor">Price</label>
                                <input type="text" name="valor" class="form-control" id="valor" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantidade">Quantity</label>
                                <input type="text" name="quantidade" class="form-control" id="quantidade" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao">Description</label>
                                <textarea name="descricao" class="form-control" id="descricao" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <h1>Product List</h1>
        <table class="table-striped table table-hover table-bordered">
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
                            <a href="#" class="btn btn-danger" onclick="confirmDelete(<?php echo $prod['id']; ?>)">Delete</a>
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

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?delete_id=" + id;
                }
            });
        }

        <?php if (isset($_SESSION['message'])): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo $_SESSION['message']; ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </script>
</body>

</html>
<style>

</style>