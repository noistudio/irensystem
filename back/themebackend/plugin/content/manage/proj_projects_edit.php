<ol class="breadcrumb">
    <li><a href="{pathadmin}content/tables/index"><?php echo __("backend/content.all_tables") ?></a></li>
    <li><a href="{pathadmin}content/manage/<?php echo $table->name; ?>"><?php echo $table->title; ?></a></li>
    <li class="active"><?php echo __("backend/content.edit_document") ?></li>
</ol>
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <div class="block-options pull-right">
            <div class="btn-group">


            </div>
        </div>
        <h2><?php echo $table->title; ?></h2>
    </div>
    <!-- END Example Title -->

    <h4>Информация о проекте</h4>
    <table class="table">
        <?php
        if ($project->main_project) {
            ?>
            <tr>
                <td>Главный проект</td>
                <td>
                    <a href="{pathadmin}content/manage/update/proj_projects/<?php echo $project->main_project->last_id; ?>"><?php echo $project->main_project->name_project; ?></a>
                </td>
            </tr>
            <?php
        }
        ?>
        <?php
        if ($category_project) {
            ?>
            <tr>
                <td>Категория проекта</td>
                <td><?php echo $category_project->name; ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Название</td>
            <td><?php echo $project->name_project; ?></td>
        </tr>
        <tr>
            <td>Статус</td>
            <td><?php echo $project->status_row->title; ?></td>
        </tr>
        <tr>
            <td>Клиент</td>
            <td>
                <a target="_blank"
                   href="{pathadmin}content/manage/update/proj_users/<?php echo $project->client->last_id; ?>"><?php echo $project->client->name ?></a>
            </td>
        </tr>

        <?php
        if ($project->developer) {
            ?>
            <tr>
                <td>Исполнитель</td>
                <td>
                    <a target="_blank"
                       href="{pathadmin}content/manage/update/proj_users/<?php echo $project->developer->last_id; ?>"><?php echo $project->developer->name ?></a>
                </td>
            </tr>
            <?php
        }
        ?>

    </table>
    <form method="POST" action="<?php echo route("backend.project.save", $project->last_id); ?>">
        <?php

        $one_editorjs = new \content\fields\Oneeditorjs($project->json, "json");
        echo $one_editorjs->get();
        echo csrf_field();
        ?>
        <p>
            <button class="btn btn-danger save_btn_editor_js" type="submit">Сохранить</button>
        </p>

    </form>

    <h4>Фриланс Предложения</h4>
    <table class="table">
        <?php
        if (count($offers)) {
            foreach ($offers as $offer) {
                ?>
                <tr>
                    <td>
                        <a href="{pathadmin}content/manage/update/proj_users/<?php echo $offer->developer->last_id; ?>"><?php echo $offer->developer->name; ?></a>
                    </td>
                    <td><?php echo $offer->price; ?><?php echo $offer->currency; ?> </td>
                    <td><?php echo $offer->date_end; ?></td>
                    <td><?php echo $offer->comment; ?></td>
                    <td>
                        <a href="{pathadmin}content/manage/delete/proj_project_offers/<?php echo $offer->last_id; ?>">[x]</a>
                    </td>
                </tr>
                <?php
                if (count($offer->comments)) {
                    foreach ($offer->comments as $comment) {
                        ?>
                        <tr>
                            <td></td>
                            <td>Комментарий:</td>

                            <td>
                                <a href="{pathadmin}content/manage/update/proj_users/<?php echo $comment->user->last_id; ?>"><?php echo $comment->user->name; ?></a>
                            </td>

                            <td><?php echo $comment->comment; ?></td>
                            <td>
                                <a href="{pathadmin}content/manage/delete/proj_project_offers_comments/<?php echo $comment->last_id; ?>">[x]</a>
                            </td>

                        </tr>
                        <?php
                    }
                }
            }
        }
        ?>
    </table>
    <h4>Задачи</h4>
    <table class="table">
        <thead>
        <tr>
            <th>Название</th>
            <th>Статус</th>
            <th>Клиент</th>
            <th>Исполнитель</th>

            <th></th>
        </tr>
        </thead>
        <?php
        if (count($tasks)) {
            foreach ($tasks as $task) {
                ?>
                <tr>
                    <td>
                        <a href="{pathadmin}content/manage/update/proj_projects/<?php echo $task->last_id; ?>"><?php echo $task->name_project; ?></a>
                    </td>
                    <td><?php echo $task->status_row->title; ?></td>
                    <td>
                        <a href="{pathadmin}content/manage/update/proj_users/<?php echo $task->client->last_id; ?>"><?php echo $task->client->name; ?></a>
                    </td>
                    <td>
                        <a href="{pathadmin}content/manage/update/proj_users/<?php echo $task->developer->last_id; ?>"><?php echo $task->developer->name; ?></a>
                    </td>


                    <td>
                        <a href="{pathadmin}content/manage/delete/proj_projects/<?php echo $task->last_id; ?>">[x]</a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>


    <h4>Счета</h4>
    <table class="table">
        <thead>
        <tr>
            <th>Название</th>
            <th>Цена</th>
            <th>Покупатель</th>
            <th>Продавец</th>
            <th>Подтверждено клиентом?</th>
            <th>Работа завершена</th>
            <th>Финальный счет</th>
            <th></th>
        </tr>
        </thead>
        <?php
        if (count($project_invoices)) {
            foreach ($project_invoices as $inv) {
                ?>
                <tr>
                    <td><?php echo $inv->title; ?></td>
                    <td><?php echo $inv->sum; ?><?php echo $inv->currency; ?></td>
                    <td>
                        <a href="{pathadmin}content/manage/update/proj_users/<?php echo $inv->client->last_id; ?>"><?php echo $inv->client->name; ?></a>
                    </td>
                    <td>
                        <a href="{pathadmin}content/manage/update/proj_users/<?php echo $inv->developer->last_id; ?>"><?php echo $inv->developer->name; ?></a>
                    </td>
                    <td><?php if ($inv->is_approve_client == 1) {
                            echo 'Да';
                        } else {
                            echo 'Нет';
                        } ?></td>
                    <td><?php if ($inv->is_finish == 1) {
                            echo 'Да';
                        } else {
                            echo 'Нет';
                        } ?></td>
                    <td><?php if ($inv->final_invoice_id > 0) { ?> #<?php echo $inv->final_invoice_id; ?>

                            <a href="{pathadmin}content/manage/delete/proj_invoices/<?php echo $inv->last_id; ?>">[x]</a>
                        <?php } ?></td>

                    <td>
                        <a href="{pathadmin}content/manage/delete/proj_project_invoices/<?php echo $inv->last_id; ?>">[x]</a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <h4>Комментарии</h4>
    <table class="table">
        <?php
        if (count($comments)) {
            foreach ($comments as $comment) {
                ?>
                <tr>
                    <td><?php echo $comment->created_at; ?></td>
                    <td>
                        <a href="{pathadmin}content/manage/update/proj_users/<?php echo $comment->user->last_id; ?>"><?php echo $comment->user->name; ?></a>
                    </td>

                    <td><?php echo $comment->comment; ?></td>
                    <td>
                        <a href="{pathadmin}content/manage/delete/proj_project_comments/<?php echo $comment->last_id; ?>">[x]</a>
                    </td>
                </tr>
                <?php
                if (count($comment->comments)) {
                    foreach ($comment->comments as $comment) {
                        ?>
                        <tr>
                            <td></td>


                            <td>
                                <a href="{pathadmin}content/manage/update/proj_users/<?php echo $comment->user->last_id; ?>"><?php echo $comment->user->name; ?></a>
                            </td>

                            <td><?php echo $comment->comment; ?></td>
                            <td>
                                <a href="{pathadmin}content/manage/delete/proj_project_comments_comments/<?php echo $comment->last_id; ?>">[x]</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
        }
        ?>
    </table>

</div>
