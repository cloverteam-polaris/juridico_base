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
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">

                        <div class="row">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Lista de tipos de microetapas</h5>

                                </div>
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover table-bordered" style="table-layout: fixed;">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Tipo de proceso</th>
                                                    <th>Macroetapa<th> 
                                                    <th>Microetapa<th>                                                                           
                                                    <th>Dias de notificacion</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color: white;">
                                                <?php foreach ($microetapas as $macro):
                                                ?>
                                                    <tr>
                                                        <td><?= $macro->idsubetapa ?></td>
                                                        <td><?= $macro->idtipoproceso ?></td>
                                                          <td><?= $macro->macroetapa ?></td>

                                                        <td><?= $macro->descripcion ?></td>
                                                        <td><?= $macro->diasnotificacion ?></td>
                                                        <td class="text-center" style="padding: 0px;">
                                                            <a href="<?= base_url('usuarios/editarUsuario/' . $macro->idsubetapa) ?>"
                                                                class="btn btn-primary btn-sm">
                                                                Editar
                                                            </a>
                                                            <a href="<?= base_url('usuarios/eliminarUsuario/' . $macro->idsubetapa) ?>"
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