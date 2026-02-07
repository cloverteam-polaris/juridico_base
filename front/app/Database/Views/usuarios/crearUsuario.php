<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">

                        <div class="row">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Crear Usuario</h5>

                                </div>
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <form id="formCrearUsuario" action="<?= base_url('usuarios/saveUser') ?>" method="post">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="usuario">Usuario</label>
                                                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese el nombre de usuario" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="contrasena">Contraseña</label>
                                                            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese la contraseña" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="confirmar_contrasena">Confirmar Contraseña</label>
                                                            <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Confirme su contraseña" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre</label>
                                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del usuario" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="documento">Documento</label>
                                                            <input type="text" class="form-control" id="documento" name="documento" placeholder="Ingrese el documento del usuario" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="correo">Correo</label>
                                                            <input type="text" class="form-control" id="correo" name="correo" placeholder="Ingrese el correo del usuario" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="telefono">Telefono</label>
                                                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese el telefono del usuario" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nombre">Tipo de perfil</label>
                                                            <select class="form-control" name="tipo_perfil" id="tipo_perfil">
                                                                <option value="">Seleccion un perfil</option>
                                                                    <?php foreach ($perfiles as $perfil): ?>
                                                                        <option value="<?= $perfil->idperfil ?>">
                                                                            <?= esc($perfil->descripcion) ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </select>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                    </div>
                                                    <button type="button" id="btnCrearUsuario" class="btn btn-primary">
                                                        Crear usuario
                                                    </button>
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