<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <div class="block-options pull-right">
            <div class="btn-group">


            </div>
        </div>
        <h2><?php echo __("backend/main.setup_title"); ?></h2>

    </div>
    <!-- END Example Title -->

    <form action="<?php echo route('backend/setup/save') ?>" method="POST">
        <h4>Основные настройки</h4>


        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">


            <table class="table">
                <tr>
                    <td><?php echo __("backend/main.setup_css"); ?></td>
                    <td>
                        <select class="form-control" name="css" required>
                            <option <?php
                            if (isset($config['APP_BACKEND_CSS']) and $config['APP_BACKEND_CSS'] == "amethyst.css") {
                                echo 'selected';
                            }
                            ?> value="amethyst.css">amethyst.css
                            </option>
                            <option <?php
                            if (isset($config['APP_BACKEND_CSS']) and $config['APP_BACKEND_CSS'] == "classy.css") {
                                echo 'selected';
                            }
                            ?> value="classy.css">classy.css
                            </option>
                            <option <?php
                            if (isset($config['APP_BACKEND_CSS']) and $config['APP_BACKEND_CSS'] == "creme.css") {
                                echo 'selected';
                            }
                            ?> value="creme.css">creme.css
                            </option>
                            <option <?php
                            if (isset($config['APP_BACKEND_CSS']) and $config['APP_BACKEND_CSS'] == "flat.css") {
                                echo 'selected';
                            }
                            ?> value="flat.css">flat.css
                            </option>
                            <option <?php
                            if (isset($config['APP_BACKEND_CSS']) and $config['APP_BACKEND_CSS'] == "passion.css") {
                                echo 'selected';
                            }
                            ?> value="passion.css">passion.css
                            </option
                            <option <?php
                            if (isset($config['APP_BACKEND_CSS']) and $config['APP_BACKEND_CSS'] == "social.css") {
                                echo 'selected';
                            }
                            ?> value="social.css">social.css
                            </option
                        </select></td>
                </tr>
                <tr>
                    <td>Яндекс токен для Яндекс.Диска <a href="https://github.com/leonied7/yandex-disk-api"
                                                         target="_blank"><i class="fa fa-question"> </i></a></td>
                    <td><input class="form-control" type="text" name="YANDEX_API_WEB_DAV_TOKEN"
                               value="<?php echo env('YANDEX_API_WEB_DAV_TOKEN'); ?>" required></td>
                </tr>
                <tr>
                    <td>Папка где хранить файлы в яндекс диске(пример /freelance/)</td>
                    <td><input class="form-control" type="text" name="YANDEX_FOLDER"
                               value="<?php echo env('YANDEX_FOLDER'); ?>"/></td>
                </tr>
                <tr>
                    <td>Telegram Bot Token</td>
                    <td><input class="form-control" type="text" name="TELEGRAM_BOT_TOKEN"
                               value="<?php echo env('TELEGRAM_BOT_TOKEN'); ?>"/></td>
                </tr>
                <tr>
                    <td>Telegram Bot Username</td>
                    <td><input class="form-control" type="text" name="TELEGRAM_BOT_USERNAME"
                               value="<?php echo env('TELEGRAM_BOT_USERNAME'); ?>"/></td>
                </tr>
                <tr>
                    <td>URL фронта</td>
                    <td><input class="form-control" type="text" name="FRONT_URL"
                               value="<?php echo env('FRONT_URL'); ?>"/></td>
                </tr>
                <tr>
                    <td><?php echo __("backend/main.setup_copyright"); ?></td>
                    <td>
                        <input type="text" class="form-control" value="<?php
                        if (isset($config['APP_BACKEND_COPYRIGHT_TITLE'])) {
                            echo $config['APP_BACKEND_COPYRIGHT_TITLE'];
                        }
                        ?>" name="name" required>
                    </td>
                </tr>
                <tr>
                    <td><?php echo __("backend/main.setup_link"); ?></td>
                    <td>
                        <input type="text" class="form-control" value="<?php
                        if (isset($config['APP_BACKEND_COPYRIGHT_LINK'])) {
                            echo $config['APP_BACKEND_COPYRIGHT_LINK'];
                        }
                        ?>" name="link" required>
                    </td>
                </tr>

                <tr>
                    <td><?php echo __("backend/main.status_site"); ?></td>
                    <td><select name='disabled' class='form-control'>
                            <option value='false'><?php echo __("backend/main.enable"); ?></option>
                            <option <?php
                            if (isset($config['APP_DISABLED']) and $config['APP_DISABLED'] == true) {
                                echo "selected";
                            }
                            ?> value='true'><?php echo __("backend/main.disable"); ?></option>
                        </select></td>
                </tr>
                <tr>
                    <td><?php echo __("backend/main.debug_mode"); ?></td>
                    <td><select name='APP_DEBUG' class='form-control'>
                            <option value='false'><?php echo __("backend/main.disable"); ?></option>
                            <option <?php
                            if (isset($config['APP_DEBUG']) and $config['APP_DEBUG'] == true) {
                                echo "selected";
                            }
                            ?> value='true'><?php echo __("backend/main.enable"); ?></option>
                        </select></td>
                </tr>
                <tr>
                    <td><?php echo __("backend/main.disable_message"); ?></td>
                    <td>
                    <textarea class='form-control' name='disable_message'><?php
                        if (isset($config['APP_DISABLED_MESSAGE'])) {
                            echo $config['APP_DISABLED_MESSAGE'];
                        }
                        ?></textarea>
                    </td>

                </tr>


                <tr>
                    <td>
                        <?php echo csrf_field(); ?>

                    </td>
                    <td>
                        <button type="submit" class="btn btn-submit"><?php echo __(
                                "backend/main.setup_save"
                            ); ?></button>
                    </td>
                </tr>

            </table>
    </form>

</div>
