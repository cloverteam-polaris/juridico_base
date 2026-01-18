<div class="pcoded-main-container">
    <div class="row">
        <div class="col-sm-12">
            <div class="card"> 
                <div class="card-header">
                    <h5>Modulos disponibles:</h5>
                </div><!-- card-header -->
                <div class="card-body">        
                    <div class="row">
                            <?php 
                         
                                foreach($modulos as $key => $value){
                                    $url = "";
                                    $mostrar = 0;
                                    //valida si el modulo al que tiene permiso esta activo y trae la url del modulo par aponerla en el boton.
                                    foreach($modulos_info as $modinfo){
                                        if($modinfo->modulo == $key && $value == 1){
                                            $mostrar = 1;
                                            $url = $modinfo->url;
                                        }    
                                    }

                                   if($mostrar == 1){    
                                ?>
                                <div class="col-sm-4">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <?php echo $key; ?>
                                        </div><!-- card-header -->
                                        <div class="card-body">
                                            <a href="<?php echo ROOT_URL.$url; ?>" class="btn btn-gradient-success">Abrir</a>
                                            <a href="<?php echo ROOT_URL.$url; ?>" class="btn btn-gradient-warning" target="_blank">Abrir Nueva Pestaña</a>
                                        </div>
                                    </div><!-- card bg-light -->    
                                </div><!-- col-sm-4 -->
                                <?php }//end of if validation
                                 }//End Foreach items ?>
                    </div><!-- row -->          
                </div><!-- card-body -->
            </div><!-- CARd -->
        </div><!-- col-sm-12 --> 
    </div><!-- ROW -->
</div><!-- pcoded-main-container -->