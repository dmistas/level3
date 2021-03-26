<?php
$this->layout('layout', ['title' => 'Редактирование']) ?>
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-plus-circle'></i> Редактировать
    </h1>

</div>
<form action="/edit/profile/<?= $user['id']?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Общая информация</h2>
                    </div>
                    <div class="panel-content">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <!-- username -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Имя</label>
                            <input type="text" id="simpleinput" name="username" class="form-control" value="<?= $user['username']?$user['username']:''?>">
                        </div>

                        <!-- title -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Место работы</label>
                            <input type="text" id="simpleinput" name="job_title" class="form-control" value="<?= $user['job_title']?$user['job_title']:''?>">
                        </div>

                        <!-- tel -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Номер телефона</label>
                            <input type="text" id="simpleinput" name="phone" class="form-control" value="<?= $user['phone']?$user['phone']:''?>">
                        </div>

                        <!-- address -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Адрес</label>
                            <input type="text" id="simpleinput" name="address" class="form-control" value="<?= $user['address']?$user['address']:''?>">
                        </div>
                        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                            <button type="submit" class="btn btn-warning">Редактировать</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>