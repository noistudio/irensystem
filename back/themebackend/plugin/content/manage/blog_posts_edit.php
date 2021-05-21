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
            <td><?php echo $post->category_post->title; ?></td>
        </tr>


        <tr>
            <td>Пользователь</td>
            <td>
                <a target="_blank"
                   href="{pathadmin}content/manage/update/proj_users/<?php echo $post->user->last_id; ?>"><?php echo $post->user->name ?></a>
            </td>
        </tr>


    </table>
    <form method="POST" action="<?php echo route("backend.post.save", $post->last_id); ?>">
        <?php

        $one_editorjs = new \content\fields\Oneeditorjs(json_encode($post->content), "json");
        echo $one_editorjs->get();
        echo csrf_field();
        ?>
        <p>
            <button class="btn btn-danger save_btn_editor_js" type="submit">Сохранить</button>
        </p>

    </form>

    <h4>Комментарии</h4>
    <table class="table">
        <?php
        if (count($post->comments)) {
            foreach ($post->comments as $comment) {
                ?>
                <tr>
                    <td><?php echo $comment->created_at; ?></td>
                    <td>
                        <a href="{pathadmin}content/manage/update/proj_users/<?php echo $comment->user->last_id; ?>"><?php echo $comment->user->name; ?></a>
                    </td>

                    <td><?php echo $comment->comment; ?></td>
                    <td>
                        <a href="{pathadmin}content/manage/delete/blog_posts_comments/<?php echo $comment->last_id; ?>">[x]</a>
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
                                <a href="{pathadmin}content/manage/delete/blog_posts_comments_comments/<?php echo $comment->last_id; ?>">[x]</a>
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
