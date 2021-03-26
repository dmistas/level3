<?php

use Delight\Auth\Role;

$this->layout('layout', ['title' => 'Редактирование']) ?>
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-lock'></i> Безопасность
    </h1>
</div>

<form
    <?php if ($auth->hasRole(Role::ADMIN)): ?>
        action="/admin/edit/security/<?= $user['id'] ?>"
    <?php else: ?>
        action="/edit/security"
    <?php endif; ?>
        method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Обновление эл. адреса и пароля</h2>
                    </div>
                    <div class="panel-content">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <!-- email -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Email</label>
                            <input type="text" id="simpleinput" name="email" class="form-control"
                                   value="<?= $user['email'] ?>">
                        </div>


                        <?php if ($auth->hasRole(Role::ADMIN)): ?>
                            <!-- password -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Пароль</label>
                                <input type="password" id="simpleinput" name="password" class="form-control">
                            </div>
                            <!-- password confirmation-->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Подтверждение пароля</label>
                                <input type="password" id="simpleinput" name="password_confirm" class="form-control">
                            </div>
                        <?php else: ?>
                            <!-- current password -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Текущий пароль</label>
                                <input type="password" id="simpleinput" name="password" class="form-control">
                            </div>
                            <!-- new password -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Новый пароль</label>
                                <input type="password" id="simpleinput" name="password_new" class="form-control">
                            </div>
                        <?php endif; ?>


                        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                            <button class="btn btn-warning">Изменить</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>