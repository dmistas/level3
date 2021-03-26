<?php
$this->layout('layout', ['title' => 'Add user']) ?>
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-plus-circle'></i> Добавить пользователя
    </h1>
</div>
<?php if (isset($errors) && !empty($errors)): ?>
    <div class="col-lg-4">

        <div class="alert alert-danger" role="alert">
            <?php foreach ($errors as $error): ?>
            <?php foreach ($error as $item): ?>
            <p><?= $item ?></p>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<form action="/admin/add-user" method="post" enctype="multipart/form-data">
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
                            <input type="text" id="simpleinput" name="username" class="form-control">
                        </div>

                        <!-- title -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Место работы</label>
                            <input type="text" id="simpleinput" name="job_title" class="form-control">
                        </div>

                        <!-- tel -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Номер телефона</label>
                            <input type="text" id="simpleinput" name="phone" class="form-control">
                        </div>

                        <!-- address -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Адрес</label>
                            <input type="text" id="simpleinput" name="address" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Безопасность и Медиа</h2>
                    </div>
                    <div class="panel-content">
                        <!-- email -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Email</label>
                            <input type="text" id="simpleinput" name="email" class="form-control">
                        </div>

                        <!-- password -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Пароль</label>
                            <input type="text" id="simpleinput" name="password" class="form-control">
                        </div>


                        <!-- status -->
                        <div class="form-group">
                            <label class="form-label" for="example-select">Выберите статус</label>
                            <select class="form-control" id="example-select" name="status">
                                <option value="1">Онлайн</option>
                                <option value="2">Отошел</option>
                                <option value="3">Не беспокоить</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="example-fileinput">Загрузить аватар</label>
                            <input type="file" id="example-fileinput" name="avatar" class="form-control-file">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Социальные сети</h2>
                    </div>
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- vk -->
                                <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                    <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#4680C2"></i>
                                                        <i class="fab fa-vk icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                    </div>
                                    <input type="text" name="vk" class="form-control border-left-0 bg-transparent pl-0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- telegram -->
                                <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                    <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#38A1F3"></i>
                                                        <i class="fab fa-telegram icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                    </div>
                                    <input type="text" name="telegram"
                                           class="form-control border-left-0 bg-transparent pl-0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- instagram -->
                                <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                    <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#E1306C"></i>
                                                        <i class="fab fa-instagram icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                    </div>
                                    <input type="text" name="instagram"
                                           class="form-control border-left-0 bg-transparent pl-0">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                <button type="submit" class="btn btn-success">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>