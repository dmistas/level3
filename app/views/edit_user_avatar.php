<?php

$this->layout('layout', ['title' => 'Загрузить аватар']) ?>
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-image'></i> Загрузить аватар
    </h1>

</div>
<form action="/edit/media/<?= $user['id']?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Текущий аватар</h2>
                    </div>
                    <div class="panel-content">
                        <div class="form-group">
                            <img src="<?= $user['avatar'] ?>" alt="" class="img-responsive" width="200">
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="token" value="<?php echo $token; ?>">
                            <label class="form-label" for="example-fileinput">Выберите аватар</label>
                            <input type="file" id="example-fileinput" name="avatar" class="form-control-file">
                        </div>


                        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                            <button class="btn btn-warning">Загрузить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>