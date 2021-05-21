<ol class="breadcrumb">
    <li><a href="{pathadmin}content/tables/index"><?php echo __("backend/content.all_tables") ?></a></li>

    <li class="active"><?php echo $table->title; ?></li>
</ol>


<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <div class="block-options pull-right">

        </div>
        <div class="  pull-left">
            <?php
            if (\admins\models\AdminAuth::isRoot()) {
                ?>
                <a href="{pathadmin}content/tables/edit/<?php echo $table->name; ?>" class="btn   btn-danger"><i
                            class="fa fa-cogs"></i><?php echo __("backend/content.table_config") ?> </a>

                <?php
            }
            ?>
        </div>
        <h2><?php echo $table->title; ?></h2>
    </div>


    <!-- END Example Title -->
    <form action="{pathadmin}content/manage/ops/<?php echo $table->name; ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="block ">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-primary" type="submit" name="op" value="enable"><i
                                class="fa fa-eye"></i> <?php echo __("backend/blocks.enable"); ?></button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" type="submit" name="op" value="disable"><i
                                class="fa fa-eye-slash"></i> <?php echo __("backend/blocks.disable"); ?></button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-danger" type="submit" name="op" value="delete"><i
                                class="fa fa-remove"></i> <?php echo __("backend/blocks.delete"); ?></button>
                </div>
            </div>
        </div>


        <?php
        if (count($rows)) {
            foreach ($rows as $row) {
                ?>
                <div class="block ">
                    <div class="block-title">
                        <?php
                        if (count($fields)) {
                            foreach ($fields as $field) {
                                $val = "";
                                if (isset($row[$field['name']."_val"])) {
                                    $val = $row[$field['name']."_val"];
                                }

                                $field['obj']->_set($val);
                                ?>
                                <p>&nbsp;<strong><?php echo $field['title']; ?>
                                        :</strong><?php echo $field['obj']->renderValue(); ?></p>
                                <?php
                            }
                        }
                        ?>

                    </div>
                    <div class="row">
                        <div class="col-xs-1">
                            <input type="checkbox" name="ids[]" value="<?php echo $row['last_id']; ?>">

                        </div>
                        <div class="col-xs-1">
                            <a href="{pathadmin}content/manage/enable/<?php echo $table->name; ?>/<?php echo $row['last_id']; ?>">
                                <?php
                                if ((int)$row['enable'] == 1) {
                                    ?>
                                    <i class="fa fa-eye"></i>
                                    <?php
                                } else {
                                    ?>
                                    <i class="fa fa-eye-slash"></i>
                                    <?php
                                }
                                ?>
                            </a>
                        </div>


                        <div class="col-xs-offset-1 col-xs-4 ">
                            <a class="btn btn-primary"
                               href="{pathadmin}content/manage/update/<?= $table->name; ?>/<?= $row['last_id']; ?>"><i
                                        class="fa fa-pencil-square-o"></i><?php echo __("backend/content.editing") ?>
                            </a>
                        </div>
                        <div class="col-xs-3 pull-right"><a class="deleteerror btn btn-danger"
                                                            data-msg="<?php echo __("backend/content.want_delete") ?>"
                                                            href="{pathadmin}content/manage/delete/<?= $table->name; ?>/<?= $row['last_id']; ?>"><i
                                        class="fa fa-remove"></i></a></div>
                    </div>

                </div>


                <?php
            }
        }
        ?>


        <!-- Example Content -->

    </form>
    <?php echo $pages; ?>
</div>
