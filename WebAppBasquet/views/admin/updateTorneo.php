<?php
    require_once(__DIR__ . "/template/header.php");
    require_once(__DIR__ . "/../../controllers/torneosController.php");
    $objTorneosController = new torneosController();
    $lstTorneo = $objTorneosController->readOneTorneo($_GET['id']);
?>
<div class="mx-auto p-5">
    <div class="card">
    <div class="card-header">
        ACTUALIZAR INFORMACIÓN DEL TORNEO.
    </div>
    <div class="card-body">
        <form action="updateTorneoProcess.php" method="post">
            <input type="hidden" name="txtId" value="<?= $lstTorneo['id'] ?>">
            <div class="mb-3">
                <label for="nombreTorneo" class="form-label">NOMBRE DEL TORNEO (ID: <?= $lstTorneo['id'] ?>)</label>
                <input type="text" class= "form-control" name="txtNombreTorneo" id="nombreTorneo" value="<?= $lstTorneo['nombreTorneo'] ?>">
            </div>
            <div class="mb-3">
                <label for="organizador" class="form-label">ORGANIZADOR (nombre completo)</label>
                <input type="text" name="txtOrganizador" id="organizador" class="form-control" value="<?= $lstTorneo['organizador'] ?>">
            </div>
            <div class="mb-3">
                <label for="patrocinador" class="form-label">PATROCINADOR(ES)</label>
                <textarea name="txtPatrocinador" id="patrocinador" cols="30" rows="2" class="form-control"><?= $lstTorneo['patrocinadores'] ?></textarea>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="sede" class="form-label">SEDE (cancha)</label>
                    <input type="text" name="txtSede" id="sede" class="form-control" value="<?= $lstTorneo['sede'] ?>">
                </div>
                <div class="col mb-3">
                    <label for="categoria" class="form-label">CATEGORÍA</label>
                    <input list="lstCategorias" name="txtCategoria" id="categoria" class="form-control" value="<?= $lstTorneo['categoria'] ?>">
                    <datalist id="lstCategorias">
                        <option value="1ra. fuerza">
                        <option value="2da. fuerza">
                        <option value="Veteranos">
                        <option value="Libre">
                        <option value="Juvenil">
                        <option value="Femenil">
                        <option value="Empresarial">
                        <option value="Infantil">
                        <option value="Minibasket">
                    </datalist>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="premio1" class="form-label">PREMIO 1ER. LUGAR</label>
                    <input type="text" name="txtPremio1" id="premio1" class="form-control" value="<?= $lstTorneo['premio1'] ?>">
                </div>
                <div class="col mb-3">
                    <label for="premio2" class="form-label">PREMIO 2DO. LUGAR</label>
                    <input type="text" name="txtPremio2" id="premio2" class="form-control" value="<?= $lstTorneo['premio2'] ?>">
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="premio3" class="form-label">PREMIO 3ER. LUGAR</label>
                    <input type="text" name="txtPremio3" id="premio3" class="form-control" value="<?= $lstTorneo['premio3'] ?>">
                </div>
                <div class="col mb-3">
                    <label for="otroPremio" class="form-label">OTRO PREMIO (CAMPEÓN CANASTERO)</label>
                    <input type="text" name="txtOtroPremio" id="otroPremio" class="form-control" value="<?= $lstTorneo['otroPremio'] ?>">
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="readOneTorneos.php?id=<?= $lstTorneo['id'] ?>" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
    <div class="card-footer text-body-secondary">
        ACTUALIZAR TORNEO.
    </div>
    </div>
</div>

<?php
    require_once(__DIR__ . "/template/footer.php");
?>
