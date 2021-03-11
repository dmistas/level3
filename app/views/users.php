<?php
$this->layout('layout', ['title' => 'Users'])
?>
<!--    users-->
<?= flash()?>
<div class="row" id="js-contacts">
    <?php foreach ($users as $user):?>
    <div class="col-xl-4">
        <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="oliver kopyov">
            <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                <div class="d-flex flex-row align-items-center">
                                <span class="status status-success mr-3">
                                    <span class="rounded-circle profile-image d-block "
                                          style="background-image:url('img/demo/avatars/avatar-b.png'); background-size: cover;"></span>
                                </span>
                    <div class="info-card-text flex-1">
                        <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info"
                           data-toggle="dropdown" aria-expanded="false">
                            <?= $user['username'] ?>
                            <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                            <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="edit.html">
                                <i class="fa fa-edit"></i>
                                Редактировать</a>
                            <a class="dropdown-item" href="security.html">
                                <i class="fa fa-lock"></i>
                                Безопасность</a>
                            <a class="dropdown-item" href="status.html">
                                <i class="fa fa-sun"></i>
                                Установить статус</a>
                            <a class="dropdown-item" href="media.html">
                                <i class="fa fa-camera"></i>
                                Загрузить аватар
                            </a>
                            <a href="#" class="dropdown-item" onclick="return confirm('are you sure?');">
                                <i class="fa fa-window-close"></i>
                                Удалить
                            </a>
                        </div>
                        <span class="text-truncate text-truncate-xl">IT Director, Gotbootstrap Inc.</span>
                    </div>
                    <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse"
                            data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                        <span class="collapsed-hidden">+</span>
                        <span class="collapsed-reveal">-</span>
                    </button>
                </div>
            </div>
            <div class="card-body p-0 collapse show">
                <div class="p-3">
                    <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                        <i class="fas fa-mobile-alt text-muted mr-2"></i> +1 317-456-2564</a>
                    <a href="mailto:oliver.kopyov@smartadminwebapp.com" class="mt-1 d-block fs-sm fw-400 text-dark">
                        <i class="fas fa-mouse-pointer text-muted mr-2"></i> oliver.kopyov@smartadminwebapp.com</a>
                    <address class="fs-sm fw-400 mt-4 text-muted">
                        <i class="fas fa-map-pin mr-2"></i> 15 Charist St, Detroit, MI, 48212, USA
                    </address>
                    <div class="d-flex flex-row">
                        <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#4680C2">
                            <i class="fab fa-vk"></i>
                        </a>
                        <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#38A1F3">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:#E1306C">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>