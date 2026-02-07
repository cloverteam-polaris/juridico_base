
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
                                    <h5>Lista de tipos de microetapas</h5>
   <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#agregarMicroetapa">Agregar microetapa</button>

                                </div>
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover table-bordered" >
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Tipo de proceso</th>
                                                    <th>Macroetapa</th> 
                                                    <th>Microetapa</th>                                                                           
                                                    <th>Dias de notificacion</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color: white;">
                                                <?php foreach ($microetapas as $macro):
                                                ?>
                                                    <tr>
                                                        <td><?= $macro->idmicroetapa ?></td>
                                                        <td><?= $macro->nombre_tipo_proceso ?></td>
                                                        <td><?= $macro->nombre_macroetapa ?></td>
                                                        <td><?= $macro->descripcion ?></td>
                                                        <td><?= $macro->diasrevision ?></td>
                                                        <td class="text-center" style="padding: 0px;">
                                                            <button 
                                                                    type="button" 
                                                                    class="btn btn-primary btn-sm btnEditarMicroetapa"
                                                                    data-id="<?= $macro->idmicroetapa ?>"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editarMicroetapa">
                                                                    Editar Microetapa
                                                                    </button>
                                                            <a href="<?= base_url('administracion/eliminarMicroetapa/' . $macro->idmicroetapa) ?>"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('¿Está seguro de que desea eliminar esta microetapa?');">
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




<div id="agregarMicroetapa" class="modal fade" tabindex="-1" aria-labelledby="agregarTipoProcesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="agregarTipoProcesoLabel">Agregar Microetapa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="formMacroetapa" action="<?= base_url('administracion/agregarMicroetapa') ?>" method="post">
          <div class="form-group">
            <label for="tipoProcesoM">Tipo de proceso</label>
            <select name="tipoProceso" id="tipoProcesoM" class="form-control" required>
              <option value="">Elija un tipo de proceso</option>
              <?php foreach ($tiposProcesos as $tipo):?>
                <option value="<?= $tipo->idtipoproceso ?>"><?= $tipo->descripcion ?></option>
              <?php endforeach; ?>
            </select>
            <div class="invalid-feedback"></div>
          </div>

          <div class="form-group">
            <label for="macroetapa">Macroetapa</label>
            <select name="macroetapa" id="macroetapa" class="form-control" required>
              <option value="">Elija la macroetapa</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>

          <div class="form-group">
            <label for="ordenMicroetapa">Orden microetapa</label>
            <select name="ordenMicroetapa" id="ordenMicroetapa" class="form-control" required>
              <option value="1">Etapa Inicial</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
            <div class="form-group">
            <label for="nombreMicroetapa">Nombre de microetapa</label>
            <input type="text" class="form-control" id="nombreMicroetapa" name="nombreMicroetapa" placeholder="Ej: Microetapa demanda" required>
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
        <button type="submit" class="btn btn-primary">Guardar Microetapa</button>
      </div>
</form>
    </div>
  </div>
</div>


<div id="editarMicroetapa" class="modal fade" tabindex="-1" aria-labelledby="agregarTipoProcesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="agregarTipoProcesoLabel">Editar microetapa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="formEditarMacroetapa" action="<?= base_url('administracion/editMicroetapa') ?>" method="post">
          
        <div class="form-group">
            <label for="nombreTipoProceso">Tipo de proceso</label>
           <select name="tipoProceso" id="tipoProcesoEditMi" class="form-control" disabled>
            <option value="">Elija un tipo de proceso</option>
            <?php foreach ($tiposProcesos as $tipo):?>
                    <option value="<?= $tipo->idtipoproceso ?>"><?= $tipo->descripcion ?></option>
            <?php endforeach; ?>
           </select>
            <div class="invalid-feedback"></div>
          </div>
        
        <div class="form-group">
            <label for="ordenMacroproceso">Macroetapa</label>
            <select name="macroetapa" id="macroetapaEditMi" class="form-control">
              <option value="">Elija la macroetapa</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
           <div class="form-group">
            <label for="ordenMicroetapaEdit">Orden de la microetapa</label>
            <select name="ordenMicroetapa" id="ordenMicroetapaEdit" class="form-control" required>
              <option value="">Etapa inicial</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
            <div class="form-group">
            <label for="nombreMicroetapa">Nombre de microetapa</label>
            <input type="text" class="form-control" id="nombreMicroetapaEdit" name="nombreMicroetapa" placeholder="Ej: Microetapa demanda" required>
            <div class="invalid-feedback"></div>
          </div>
            <div class="form-group">
            <label for="diasNotificacion">Dias de notificacion</label>
            <input type="text" class="form-control" id="diasNotificacionEdit" name="diasNotificacion" placeholder="Ej: 10" required>
            <div class="invalid-feedback"></div>
          </div>
        <input type="hidden" id="idMicroetapaEdit" name="idMicroetapa">

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Actualizar ordenMicroetapa</button>
      </div>
</form>
    </div>
  </div>
</div>
