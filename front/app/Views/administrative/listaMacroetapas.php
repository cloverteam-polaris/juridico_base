
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
                                <div class="card-header  d-flex justify-content-between align-items-center">
                                     <h5 class="mb-0">Lista de macroetapas</h5>

   <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#agregarMacroetapa">Agregar macroetapa</button>
                                </div>
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover table-bordered" style="table-layout: fixed;">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Tipo de proceso</th>
                                                    <th>Macroetapa</th>  
                                                     <th>Dias de notificacion</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color: white;">
                                                <?php foreach ($macroetapas as $macro):
                                                ?>
                                                    <tr>
                                                        <td><?= $macro->idmacroetapa ?></td>
                                                        <td><?= $macro->nombre_tipoproceso ?></td>
                                                        <td><?= $macro->descripcion ?></td>
                                                        <td><?= $macro->diasnotificacion ?></td>
                                                        <td class="text-center" style="padding: 0px;">
                                                              <button 
                                                                    type="button" 
                                                                    class="btn btn-primary btn-sm btnEditarMacroetapa"
                                                                    data-id="<?= $macro->idmacroetapa ?>"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editarMacroetapa">
                                                                    Editar Macroetapa
                                                                    </button>
                                                            <a href="<?= base_url('administracion/eliminarMacroetapa/' . $macro->idmacroetapa) ?>"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('¿Está seguro de que desea eliminar esta macroetapa?');">
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






<div id="agregarMacroetapa" class="modal fade" tabindex="-1" aria-labelledby="agregarTipoProcesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="agregarTipoProcesoLabel">Agregar Macroetapa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="formMacroetapa" action="<?= base_url('administracion/agregarMacroetapa') ?>" method="post">
          <div class="form-group">
            <label for="tipoProceso">Tipo de proceso</label>
            <select name="tipoProceso" id="tipoProceso" class="form-control" required>
              <option value="">Elija un tipo de proceso</option>
              <?php foreach ($tiposProcesos as $tipo):?>
                <option value="<?= $tipo->idtipoproceso ?>"><?= $tipo->descripcion ?></option>
              <?php endforeach; ?>
            </select>
            <div class="invalid-feedback"></div>
          </div>

          <div class="form-group">
            <label for="ordenMacroproceso">Orden Macroproceso</label>
            <select name="ordenMacroproceso" id="ordenMacroproceso" class="form-control" required>
              <option value="">Etapa Inicial</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
            <div class="form-group">
            <label for="nombreMacroetapa">Nombre de macroetapa</label>
            <input type="text" class="form-control" id="nombreMacroetapa" name="nombreMacroetapa" placeholder="Ej: Macroetapa demanda" required>
            <div class="invalid-feedback"></div>
          </div>
            <div class="form-group">
            <label for="diasNotificacion">Dias de notificacion</label>
            <input type="text" class="form-control" id="diasNotificacion" name="diasNotificacion" placeholder="Ej: 10" required>
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


<div id="editarMacroetapa" class="modal fade" tabindex="-1" aria-labelledby="agregarTipoProcesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="agregarTipoProcesoLabel">Editar macroetapa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="formEditarMacroetapa" action="<?= base_url('administracion/editMacroetapa') ?>" method="post">
          <div class="form-group">
            <label for="nombreTipoProceso">Tipo de proceso</label>
           <select name="tipoProceso" id="tipoProcesoEdit" class="form-control" required disabled>
            <option value="">Elija un tipo de proceso</option>
            <?php foreach ($tiposProcesos as $tipo):?>
                    <option value="<?= $tipo->idtipoproceso ?>"><?= $tipo->descripcion ?></option>
            <?php endforeach; ?>
           </select>
            <div class="invalid-feedback"></div>
          </div>
          <div class="form-group">
            <label for="ordenMacroproceso">Orden Macroproceso</label>
            <select name="ordenMacroproceso" id="ordenMacroprocesoEdit" class="form-control" required>
              <option value="1">Etapa Inicial</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
            <div class="form-group">
            <label for="nombreMacroetapa">Nombre de macroetapa</label>
            <input type="text" class="form-control" id="nombreMacroetapaEdit" name="nombreMacroetapa" placeholder="Ej: Macroetapa demanda" required>
            <div class="invalid-feedback"></div>
          </div>
            <div class="form-group">
            <label for="diasNotificacion">Dias de notificacion</label>
            <input type="text" class="form-control" id="diasNotificacionEdit" name="diasNotificacion" placeholder="Ej: 10" required>
            <div class="invalid-feedback"></div>
          </div>
        <input type="hidden" id="idMacroetapaEdit" name="idMacroetapa">

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Actualizar Macroetapa</button>
      </div>
</form>
    </div>
  </div>
</div>
