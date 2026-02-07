<?php if (session()->getFlashdata('success')): ?>
<script>
    alert("<?= session()->getFlashdata('success'); ?>");
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
    alert("<?= session()->getFlashdata('error'); ?>");
</script>
<?php endif; ?>

<script>
  const BASE_URL = "<?= base_url() ?>";
</script>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">

                        <div class="row">
                            <div class="card">
                               <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Lista de tipos de procesos</h5>

   <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#agregarTipoProceso">Agregar Tipo Proceso</button>
</div>
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover table-bordered" style="table-layout: fixed;">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Tipo de proceso</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color: white;">
                                                <?php foreach ($tiposProcesos as $tipo):
                                                ?>
                                                    <tr>
                                                        <td><?= $tipo->idtipoproceso ?></td>
                                                        <td><?= $tipo->descripcion ?></td>
                                                        <td class="text-center" style="padding: 0px;">
                                                               <button 
                                                                    type="button" 
                                                                    class="btn btn-primary btn-sm btnEditarTipoProceso"
                                                                    data-id="<?= $tipo->idtipoproceso ?>"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editarTipoProceso">
                                                                    Editar Tipo Proceso
                                                                    </button>
                                                            <a href="<?= base_url('administracion/eliminarTipoProceso/' . $tipo->idtipoproceso) ?>"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('¿Está seguro de que desea eliminar este tipo de proceso?');">
                                                                Eliminar
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row -->
                    </div><!-- card-body -->
                </div><!-- CARd -->
            </div><!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- card-body -->
</div><!-- CARd -->

<div id="agregarTipoProceso" class="modal fade" tabindex="-1" aria-labelledby="agregarTipoProcesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="agregarTipoProcesoLabel">Agregar Tipo Proceso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="formTipoProceso" action="<?= base_url('administracion/agregarTipoProceso') ?>" method="post">
          <div class="form-group">
            <label for="nombreTipoProceso">Nombre tipo proceso</label>
            <input type="text" class="form-control" id="nombreTipoProceso" name="nombre" placeholder="Ej: Proceso ejecutivo" required>
            <div class="invalid-feedback"></div>
          </div>
        
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar Tipo proceso</button>
      </div>
</form>
    </div>
  </div>
</div>


<div id="editarTipoProceso" class="modal fade" tabindex="-1" aria-labelledby="agregarTipoProcesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="agregarTipoProcesoLabel">Editar Tipo Proceso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="formTipoProceso" action="<?= base_url('administracion/editTipoProceso') ?>" method="post">
          <div class="form-group">
            <label for="nombreTipoProcesoEdit">Nombre tipo proceso</label>
            <input type="text" class="form-control" id="nombreTipoProcesoEdit" name="nombre" placeholder="Ej: Proceso ejecutivo" required>
            <div class="invalid-feedback"></div>
          </div>
        <input type="hidden" id="edit_idtipoproceso" name="idTipo">

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Actualizar Tipo proceso</button>
      </div>
</form>
    </div>
  </div>
</div>
