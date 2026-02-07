<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">

                        <div class="row">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Editar Usuario</h5>

                                </div>
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <form id="formEditarUsuario" action="<?= base_url('usuarios/editUser') ?>" method="post" novalidate>
                                                <div class="row">

                                                    <div class="col-sm-6">

                                                        <div class="form-group">
                                                            <label for="usuarioEdit">Usuario</label>
                                                            <input type="text" class="form-control" id="usuarioEdit" name="usuario" value="<?= esc($usuario[0]->usuario ?? '') ?>" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="contrasenaEdit">Contraseña</label>
                                                            <input type="password" class="form-control" id="contrasenaEdit" name="contrasena" placeholder="Dejar en blanco para no cambiar">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="confirmar_contrasenaEdit">Confirmar Contraseña</label>
                                                            <input type="password" class="form-control" id="confirmar_contrasenaEdit" name="confirmar_contrasena" placeholder="Confirme la contraseña">
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="nombreEdit">Nombre</label>
                                                            <input type="text" class="form-control" id="nombreEdit" name="nombre" value="<?= esc($usuario[0]->nombre ?? '') ?>" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                    </div>

                                                    <div class="col-sm-6">

                                                        <div class="form-group">
                                                            <label for="documentoEdit">Documento</label>
                                                            <input type="text" class="form-control" id="documentoEdit" name="documento" value="<?= esc($usuario[0]->documento ?? '') ?>" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="correoEdit">Correo</label>
                                                            <input type="email" class="form-control" id="correoEdit" name="correo" value="<?= esc($usuario[0]->correo ?? '') ?>" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="telefonoEdit">Teléfono</label>
                                                            <input type="text" class="form-control" id="telefonoEdit" name="telefono" value="<?= esc($usuario[0]->telefono ?? '') ?>" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="tipo_perfilEdit">Tipo de perfil</label>
                                                            <select class="form-control" name="tipo_perfil" id="tipo_perfilEdit">
                                                                <option value="">Seleccione un perfil</option>
                                                                <?php foreach ($perfiles as $perfil): ?>
                                                                    <option value="<?= $perfil->idperfil ?>" <?= ((int)$perfil->idperfil === (int)$usuario[0]->idperfil) ? 'selected' : '' ?>>
                                                                        <?= esc($perfil->descripcion) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                    </div>

                                                    <!-- ID oculto para edición -->
                                                    <input type="hidden" name="idusuario" value="<?= esc($usuario[0]->idusuario ?? '') ?>">

                                                    <div class="col-12 mt-3">
                                                        <button type="button" id="btnEditarUsuario" class="btn btn-primary">
                                                            Guardar cambios
                                                        </button>
                                                    </div>

                                                </div>
                                            </form>

                                        </div>
                                    </div><!-- row -->
                                </div><!-- card-body -->
                            </div><!-- CARd -->
                        </div><!-- col-sm-12 -->
                    </div><!-- row -->
                </div><!-- card-body -->
            </div><!-- CARd -->
        </div><!-- col-sm-12 -->
    </div><!-- row -->
</div><!-- card-body -->