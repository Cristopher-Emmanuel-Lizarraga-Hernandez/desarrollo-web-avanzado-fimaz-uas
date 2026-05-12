<?php
    require_once(__DIR__ . "/template/header.php");
    require_once(__DIR__ . "/../../controllers/torneosController.php");
    $objTorneosController = new torneosController();
    $lstTorneo = $objTorneosController->readOneTorneo($_GET['id']);
?>
<div class="mx-auto p-5">
    <div class="card">
    <div class="card-header">
        INFORMACION DEL TORNEO.
    </div>
    <div class="card-body">
        <form>
            <div class="mb-3">
                <label for="nombreTorneo" class="form-label">NOMBRE DEL TORNEO (ID: <?= $lstTorneo['id'] ?>)</label>
                <input type="text" class= "form-control" id="nombreTorneo" value="<?= $lstTorneo['nombreTorneo'] ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="organizador" class="form-label">ORGANIZADOR (nombre completo)</label>
                <input type="text" id="organizador" class="form-control" value="<?= $lstTorneo['organizador'] ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="patrocinador" class="form-label">PATROCINADOR(ES)</label>
                <textarea id="patrocinador" cols="30" rows="2" class="form-control" readonly><?= $lstTorneo['patrocinadores'] ?></textarea>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="sede" class="form-label">SEDE (cancha)</label>
                    <input type="text" id="sede" class="form-control" value="<?= $lstTorneo['sede'] ?>" readonly>
                </div>
                <div class="col mb-3">
                    <label for="categoria" class="form-label">CATEGORÍA</label>
                    <input type="text" id="categoria" class="form-control" value="<?= $lstTorneo['categoria'] ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="premio1" class="form-label">PREMIO 1ER. LUGAR</label>
                    <input type="text" id="premio1" class="form-control" value="<?= $lstTorneo['premio1'] ?>" readonly>
                </div>
                <div class="col mb-3">
                    <label for="premio2" class="form-label">PREMIO 2DO. LUGAR</label>
                    <input type="text" id="premio2" class="form-control" value="<?= $lstTorneo['premio2'] ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="premio3" class="form-label">PREMIO 3ER. LUGAR</label>
                    <input type="text" id="premio3" class="form-control" value="<?= $lstTorneo['premio3'] ?>" readonly>
                </div>
                <div class="col mb-3">
                    <label for="otroPremio" class="form-label">OTRO PREMIO (CAMPEÓN CANASTERO)</label>
                    <input type="text" id="otroPremio" class="form-control" value="<?= $lstTorneo['otroPremio'] ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="usuario" class="form-label">USUARIO</label>
                    <input type="text" id="usuario" class="form-control" value="<?= $lstTorneo['usuario'] ?>" readonly>
                </div>
                <div class="col mb-3">
                    <label for="contrasena" class="form-label">CONTRASEÑA</label>
                    <input type="text" id="contrasena" class="form-control" value="<?= $lstTorneo['contrasena'] ?>" readonly>
                </div>
            </div>
            <div class="col-12">
                <a href="readAllTorneos.php" class="btn btn-success">REGRESAR</a>
            </div>
        </form>
    </div>
    <div class="card-footer text-body-secondary">
        DETALLE DE TORNEOS.
    </div>
    </div>
</div>

<?php
    require_once(__DIR__ . "/template/footer.php");
?>
