<div class="well">
    <div class="block alert alert-success">
        <div class="block-title">
            <h2>IrenSystem v1.3 <a href="https://github.com/noistudio/irensystem" target="_blank"><i
                            class="fa fa-github"></i></a></h2>

        </div>


    </div>


    <div class="block alert alert-warning">
        <div class="block-title">
            <h2>Настройка CRON</h2>

        </div>
        <div class="row">
            <p>Добавьте в cron команду, которая будет выгружать файлы на яндекс.диск</p>
            <p><textarea class="form-control" readonly>* * * * * cd <?php echo $_SERVER['DOCUMENT_ROOT']."/engine"; ?> && php artisan schedule:run >> /dev/null 2>&1</textarea>
            </p>
        </div>

    </div>
    <?php
    if (count($about) == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Добавьте одну страницу о сайте</h2>
            </div>
            <div class="row">
                <p>Вам нужно заполнить страницу о сайте. Это главная страница проекта, без нее не будет ничего
                    работать.</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/about">Перейти к заполнению</a></p>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    if (count($services) == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Заполните категории проектов!</h2>
            </div>
            <div class="row">
                <p>Для функционирования продукта вам необходимо заполнить категории проектов</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/proj_categorys">Перейти к заполнению
                        категорий проектов</a></p>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    if (count($services) > 0 and count($user_services) == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Назначьте хотя бы одного ответственного на категорию проекта</h2>
            </div>
            <div class="row">
                <p>У вас добавлена категория проекта, но чтобы исполнитель увидел проект его нужно добавить
                    ответственным за
                    категорию проекта. Сделайте это, иначе ничего не заработает )</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/proj_users_categorys">Перейти к
                        назначению
                        ответственного</a></p>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    if ($count_status_search == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Добавьте статус Ищет разработчика</h2>
            </div>
            <div class="row">
                <p>Вам необходимо создать хотя бы один статус типа ищет разработчика. Без этого проектная система не
                    сможет
                    функционировать</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/proj_statuses">Перейти к созданию
                        статуса
                        типа Ищет разработчика</a></p>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    if ($count_status_price == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Добавьте статус Утверждение цены</h2>
            </div>
            <div class="row">
                <p>Вам необходимо создать хотя бы один статус типа Утверждение цены. Без этого проектная система не
                    сможет
                    функционировать</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/proj_statuses">Перейти к созданию
                        статуса
                        типа Утверждение цены</a></p>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    if ($count_status_work == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Добавьте статус Проект в работе</h2>
            </div>
            <div class="row">
                <p>Вам необходимо создать хотя бы один статус типа Проект в работе. Без этого проектная система не
                    сможет
                    функционировать</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/proj_statuses">Перейти к созданию
                        статуса
                        типа Проект в работе</a></p>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    if ($count_status_work == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Добавьте статус Проект завершен</h2>
            </div>
            <div class="row">
                <p>Вам необходимо создать хотя бы один статус типа Проект завершен. Без этого проектная система не
                    сможет
                    функционировать</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/proj_statuses">Перейти к созданию
                        статуса
                        типа Проект завершен</a></p>
            </div>
        </div>
        <?php
    }
    ?>
    <?php
    if ($blog_category_count == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Добавьте категорию для блога</h2>
            </div>
            <div class="row">
                <p>Вам необходимо создать категорию для блога которая будет показываться на главной.</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/blog_categorys">Перейти к созданию
                        категории для блога</a></p>
            </div>
        </div>
        <?php
    }

    ?>
    <?php
    if ($blog_category_count > 0 and $blog_access == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Добавьте администратора для категории блога</h2>
            </div>
            <div class="row">
                <p>Вам необходимо выдать права для администрирования блога .</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/blog_categorys_access">Перейти к выдаче
                        админских прав для категории блога </a></p>
            </div>
        </div>
        <?php
    }

    ?>
    <?php
    if ($portfolio_category == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Добавьте категорию для портфолио</h2>
            </div>
            <div class="row">
                <p>Вам необходимо создать категорию для портфолио</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/add/proj_portfolio_categorys">Перейти к
                        созданию категории для портфолио </a></p>
            </div>
        </div>
        <?php
    }

    ?>
    <?php
    if ($count_developers == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Выдайте права разработчикам</h2>
            </div>
            <div class="row">
                <p>Вам необходимо выбрать пользователей которых вы назначите разработчиками. По умолчанию у
                    разработчиков
                    будет доступ только к управлению своим портфолио</p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/index/proj_users">Перейти к
                        выдаче прав разработчикам </a></p>
            </div>
        </div>
        <?php
    }

    ?>

    <?php
    if ($count_team == 0) {
        ?>
        <div class="block alert alert-warning">
            <div class="block-title">
                <h2>Выберите разработчиков которых вы хотите показывать в блоке команда</h2>
            </div>
            <div class="row">
                <p>Вам необходимо выбрать из всех разработчиков людей которые будут отображаться на главной странице в
                    разделе команда </p>

                <p><a class="btn btn-danger" href="{pathadmin}content/manage/index/proj_users">Перейти к выбору
                        разработчиков для отображения в блоке команда </a></p>
            </div>
        </div>
        <?php
    }

    ?>
    <div class="row">
        <div class="block col-md-4">
            <div class="block-title">
                <h2>Новые Записи </h2>
            </div>
            <div class="row">
                <?php
                if (count($blog_posts)) {
                    foreach ($blog_posts as $post) {
                        ?>
                        <p>
                            <a href="{pathadmin}content/manage/update/blog_posts/<?php echo $post->last_id; ?>">Запись <?php echo $post->last_id; ?></a>
                        </p>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="block col-md-6">
            <div class="block-title">
                <h2>Новые проекты </h2>
            </div>
            <div class="row">
                <div class="row">
                    <table class="table ">
                        <?php
                        if (count($projects)) {
                            foreach ($projects as $project) {
                                ?>
                                <tr>
                                    <td style="padding-left:20px;">
                                        <a
                                                href="{pathadmin}content/manage/update/proj_projects/<?php echo $project->last_id; ?>"> <?php echo $project->name_project; ?></a>
                                    </td>
                                    <td><?php echo $project->status_row->title; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="block col-md-4">
            <div class="block-title">
                <h2>Новые Комментарии к записям </h2>
            </div>
            <div class="row">
                <table class="">
                    <?php
                    if (count($blog_last_comments)) {
                        foreach ($blog_last_comments as $comment) {
                            ?>
                            <tr>
                                <td>
                                    <a href="{pathadmin}content/manage/update/proj_users/<?php echo $comment->user_id; ?>"><?php echo $comment->user->name; ?></a>
                                </td>
                                <td>
                                    <a href="{pathadmin}content/manage/update/blog_posts/<?php echo $comment->post_id; ?>"><?php echo $comment->comment; ?></a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

        <div class="col-md-2"></div>
        <div class="block col-md-4">
            <div class="block-title">
                <h2>Новые Комментарии 2-го уровня к записям </h2>
            </div>
            <div class="row">
                <table class="">
                    <?php
                    if (count($blog_last_subcomments)) {
                        foreach ($blog_last_subcomments as $comment) {
                            ?>
                            <tr>
                                <td>
                                    <a href="{pathadmin}content/manage/update/proj_users/<?php echo $comment->user_id; ?>"><?php echo $comment->user->name; ?></a>
                                </td>
                                <td>
                                    <a href="{pathadmin}content/manage/update/blog_posts/<?php echo $comment->post_id; ?>"><?php echo $comment->comment; ?></a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div class="row">

        <?php
        if (count($developers)) {
            ?>
            <div class="col-md-4 block">
                <div class="block-title">
                    <h2>Исполнители</h2>
                </div>
                <div class="row">
                    <?php
                    foreach ($developers as $developer) {
                        if (isset($developer->username) and is_string($developer->username) and strlen(
                                $developer->username
                            ) > 0) {
                            ?>
                            <p> @<?php echo $developer['username']; ?> <a
                                        href="{pathadmin}content/manage/update/proj_users/<?php echo $developer->last_id; ?>"><?php echo $developer->name; ?></a>
                            </p>
                            <?php
                        } else {
                            ?>
                            <p <span class="btn btn-danger">*</span> @<?php echo $developer['username']; ?> <a
                                    href="{pathadmin}content/manage/update/proj_users/<?php echo $developer->last_id; ?>"><?php echo $developer->name; ?></a>
                            </p>
                            <?php
                        }
                        ?>

                        <?php
                    }
                    ?>
                    <p><span class="btn btn-danger">*</span> - таким знаком помечены исполнители которые должны в своем
                        телеграм аккаунте проставить логин</p>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="col-md-1"></div>
        <?php
        if (count($developers_by_category)) {
            ?>
            <div class="col-md-5 block">
                <div class="block-title">
                    <h2>Исполнители в категориях</h2>
                </div>
                <div class="row">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Исполнитель</th>
                            <th>Категория</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($developers_by_category as $dev) {
                            ?>
                            <tr>
                                <td><a
                                            href="{pathadmin}content/manage/update/proj_users/<?php echo $dev->user->last_id; ?>"><?php echo $dev->user->name; ?></a>
                                </td>
                                <td><a
                                            href="{pathadmin}content/manage/update/proj_categorys/<?php echo $dev->category->last_id; ?>"><?php echo $dev->category->name; ?></a>
                                </td>

                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

</div>