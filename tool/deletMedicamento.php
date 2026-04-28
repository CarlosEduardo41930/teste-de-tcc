<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
   <!-- Botão simples -->
<button onclick="confirmarExclusao(123)" class="btn-delete">
    🗑️ Deletar
</button>

<script>
function confirmarExclusao(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você não poderá reverter isso!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, deletar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Redireciona para o PHP
            window.location.href = `deletar.php?id=${id}`;
        }
    })
}
</script>
<!-- ===================================================================================================================================== -->
<style>
.custom-confirm {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.custom-confirm.active {
    display: flex;
}

.confirm-box {
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    max-width: 400px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.confirm-box h3 {
    color: #e74c3c;
    margin-bottom: 15px;
}

.confirm-box p {
    color: #666;
    margin-bottom: 25px;
}

.confirm-box button {
    padding: 10px 25px;
    margin: 5px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-confirm {
    background: #e74c3c;
    color: white;
}

.btn-cancel {
    background: #95a5a6;
    color: white;
}
</style>

<!-- Modal Customizado -->
<div class="custom-confirm" id="customConfirm">
    <div class="confirm-box">
        <h3>⚠️ Confirmar Exclusão</h3>
        <p>Tem certeza que deseja deletar este item? Esta ação não pode ser desfeita.</p>
        <button class="btn-confirm" id="btnYes">Sim, Deletar</button>
        <button class="btn-cancel" onclick="fecharConfirm()">Cancelar</button>
    </div>
</div>

<script>
let deleteUrl = '';

function confirmarExclusaoo(id) {
    deleteUrl = `deletar.php?id=${id}`;
    document.getElementById('customConfirm').classList.add('active');
}

function fecharConfirm() {
    document.getElementById('customConfirm').classList.remove('active');
}

document.getElementById('btnYes').onclick = function() {
    window.location.href = deleteUrl;
};

// Fecha ao clicar fora
document.getElementById('customConfirm').onclick = function(e) {
    if (e.target === this) fecharConfirm();
};
</script>

<!-- Uso -->
<button onclick="confirmarExclusaoo(123)">🗑️ Deletar</button>



<!-- ================================================================================================================================== -->
 <!-- Botão que abre o modal -->
<button type="button" class="btn btn-danger" 
        data-bs-toggle="modal" 
        data-bs-target="#confirmDeleteModal"
        data-id="123">
    🗑️ Deletar
</button>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠️ Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja deletar este item?</p>
                <p class="text-muted small">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Sim, Deletar</a>
            </div>
        </div>
    </div>
</div>

<script>
// Passa o ID para o botão de confirmação
const confirmDeleteModal = document.getElementById('confirmDeleteModal');
confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    confirmBtn.href = `deletar.php?id=${id}`;
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>