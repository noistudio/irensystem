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

    <!-- Example Content -->

    <h4>Информация о посте</h4>
    <table class="table">


        <tr>
            <td>Категория</td>
            <td><?php echo $work->category->name; ?></td>
        </tr>

        <tr>
            <td>Дата начала</td>
            <td><?php echo $work->date_start; ?></td>
        </tr>
        <tr>
            <td>Дата завершения</td>
            <td><?php echo $work->date_end; ?></td>
        </tr>


        <tr>
            <td>Пользователь</td>
            <td>
                <a target="_blank"
                   href="{pathadmin}content/manage/update/proj_users/<?php echo $work->user->last_id; ?>"><?php echo $work->user->name ?></a>
            </td>
        </tr>


    </table>
    <form method="POST" action="<?php echo route("backend.work.save", $work->last_id); ?>">
        <?php

        $one_editorjs = new \content\fields\Oneeditorjs(json_encode($work->json), "json");
        echo $one_editorjs->get();
        echo csrf_field();
        ?>
        <p>
            <button class="btn btn-danger save_btn_editor_js" type="submit">Сохранить</button>
        </p>

    </form>


</div>
