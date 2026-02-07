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
                                    <h5>Lista de Usuarios</h5>

                                </div>
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-hover table-bordered" style="table-layout: fixed;">
                                            <thead>
                                                <tr>
                                                    <th>Usuario</th>
                                                    <th>Nombre</th>
                                                    <th>Documento</th>
                                                    <th>Correo</th>
                                                    <th>Perfil</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>


                                                </tr>
                                            </thead>
                                            <tbody style="color: white;">
                                                <?php foreach ($usuarios as $usuario):
                                                ?>
                                                    <tr>
                                                        <td><?= $usuario->idusuario ?></td>
                                                        <td><?= $usuario->nombre ?></td>
                                                        <td><?= $usuario->documento ?></td>
                                                        <td><?= $usuario->correo ?></td>
                                                        <td><?= $usuario->perfil ?></td>
                                                        <td><?= $usuario->estado ?></td>

                                                        <td class="text-center" style="padding: 0px;">
                                                            <a href="<?= base_url('usuarios/editarUsuario/' . $usuario->idusuario) ?>"
                                                                class="btn btn-primary btn-sm">
                                                                Editar
                                                            </a>
                                                            <a href="<?= base_url('usuarios/eliminarUsuario/' . $usuario->idusuario) ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('¿Está seguro de que desea eliminar este usuario?');">
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