<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{

    public $timestamps = true;
    public $table = "proj_notifys";
    public $primaryKey = "last_id";

    protected $casts = [
        'json' => 'array'
    ];

    public function who()
    {
        return $this->hasOne(User::class, "last_id", "who_send_id");
    }

    static function createInviteUser($project, $user)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "projects";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = "Вас пригласили в проект " . $project->name_project;
        $json['buttons'] = array(
            array('title' => 'Принять приглашение', 'type' => 'load_project', 'params' => array('id' => $project->last_id)),
            array('title' => 'Отказаться', 'type' => 'remove_notify', 'params' => array('id' => $project->last_id))
        );


        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        $new_notify->user_id = $user->last_id;
        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));

            $messageText = $json['title'] . "\n\n Чтобы принять приглашение просто перейдите по ссылке \n\n" . $json['url'] . "\n\n Если вам это не интересно просто проигнорируйте данное уведомление";
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }
    }

    static function createInvoiceFinishPay($invoice)
    {
        $json = array();
        $json['type'] = "invoices";
        $json['url'] = env('FRONT_URL') . "#/invoice/" . $invoice['last_id'];
        $json['title'] = "Счет " . $invoice->title . " закрыт. ";
        $json['buttons'] = array(
            array('title' => 'Оставить отзыв', 'type' => 'load_invoice', 'params' => array('invoice_id' => $invoice->last_id))

        );
        $new_notify = new Notify();
        $new_notify->who_send_id = $invoice->client_id;
        $new_notify->user_id = $invoice->developer_id;

        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $invoice->developer_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'] . "\n\n" . " Оставьте отзыв";
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }


        $json = array();
        $json['type'] = "invoices";
        $json['url'] = env('FRONT_URL') . "#/invoice/" . $invoice['last_id'];
        $json['title'] = "Счет " . $invoice->title . " закрыт.";
        $json['buttons'] = array(
            array('title' => 'Оставить отзыв', 'type' => 'load_invoice', 'params' => array('invoice_id' => $invoice->last_id))

        );
        $new_notify = new Notify();
        $new_notify->who_send_id = $invoice->developer_id;
        $new_notify->user_id = $invoice->client_id;

        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $invoice->client_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'] . "\n\n" . " Оставьте отзыв";
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }


    }

    static function createInvoiceClientPay($invoice)
    {

        $json = array();
        $json['type'] = "invoices";
        $json['url'] = env('FRONT_URL') . "#/invoice/" . $invoice->last_id;
        $json['title'] = "Клиент оплатил счет " . $invoice->title;
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_invoice', 'params' => array('invoice_id' => $invoice->last_id))

        );
        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        $new_notify->user_id = $invoice->developer_id;

        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $invoice->developer_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'];
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }
    }

    static function createCompleteInvoice($invoice, $project_invoice, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "invoices";
        $json['url'] = env('FRONT_URL') . "#/invoice/" . $invoice->last_id;
        $json['title'] = $project->name_project . "условия по счету выполнены";
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id, 'finish_invoice_id' => $invoice->last_id, 'invoice_id' => $project_invoice->last_id))

        );
        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        $new_notify->user_id = $invoice->developer_id;

        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $invoice->developer_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'];
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }
    }

    static function createNewStatus($status, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "projects";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = "Установлен новый статус " . $status->title . "в " . $project->name_project;
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id))

        );


        $users = $project->getUsers();
        foreach ($users as $user) {
            if ($user->last_id != request()->user()->last_id) {
                $new_notify = new Notify();
                $new_notify->who_send_id = request()->user()->last_id;
                $new_notify->user_id = $user->last_id;
                $new_notify->isread = 0;
                $new_notify->json = $json;
                $new_notify->save();

                try {
                    $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));

                    $messageText = $json['title'] . "\n\n" . $json['url'];
                    $bot->sendMessage($user->telegram_id, $messageText);
                } catch (\Exception $e) {

                }
            }
        }
    }

    static function createApproveInvoice($invoice, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "invoices";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = $project->name_project . "условия по счету подтверждены";
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id, 'invoice_id' => $invoice->last_id))

        );
        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        $new_notify->user_id = $invoice->developer_id;

        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $invoice->developer_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'];
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }
    }

    static function createUpdateProject($project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "projects";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = $project->name_project . " информация обновлена";
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id))

        );

        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        if ($project->developer_id == request()->user()->last_id) {
            $new_notify->user_id = $project->client_id;


        } else {
            $new_notify->user_id = $project->developer_id;
        }

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $new_notify->user_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'];
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }

        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();
    }

    static function createAddTask($task, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "projects";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = "В проекте  " . $project->name_project . " создали новую задачу";
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $task->last_id))

        );

        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        if ($task->developer_id == request()->user()->last_id) {
            $new_notify->user_id = $task->client_id;

            try {
                $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
                $user = User::query()->where("last_id", $task->client_id)->first();
                $messageText = $json['title'] . "\n\n" . $json['url'];
                $bot->sendMessage($user->telegram_id, $messageText);
            } catch (\Exception $e) {

            }
        } else {
            $new_notify->user_id = $task->developer_id;

            try {
                $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
                $user = User::query()->where("last_id", $task->developer_id)->first();
                $messageText = $json['title'] . "\n\n" . $json['url'];
                $bot->sendMessage($user->telegram_id, $messageText);
            } catch (\Exception $e) {

            }
        }

        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();
    }

    static function createAddInvoice($invoice, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "projects";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = "Исполнитель создал счет " . $project->name_project;
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id, 'invoice_id' => $invoice->last_id))

        );

        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        $new_notify->user_id = $project->client_id;
        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $project->client_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'];
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }

    }

    static function createSendSubComment($subcomment, $comment, $project)
    {

        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "comments";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = "В " . $project->name_project . " появился ответ на  комментарий ";
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id))

        );


        $users = $project->getUsers();
        foreach ($users as $user) {
            if ($user->last_id != request()->user()->last_id) {
                $new_notify = new Notify();
                $new_notify->who_send_id = request()->user()->last_id;
                $new_notify->user_id = $user->last_id;
                $new_notify->isread = 0;
                $new_notify->json = $json;
                $new_notify->save();
                try {
                    $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));

                    $messageText = $json['title'] . "\n\n" . $json['url'];
                    $bot->sendMessage($user->telegram_id, $messageText);
                } catch (\Exception $e) {

                }
            }
        }

    }

    static function createSendComment($comment, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "comments";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = "В " . $project->name_project . " появился комментарий";
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id))

        );


        $users = $project->getUsers();
        foreach ($users as $user) {
            if ($user->last_id != request()->user()->last_id) {
                $new_notify = new Notify();
                $new_notify->who_send_id = request()->user()->last_id;
                $new_notify->user_id = $user->last_id;
                $new_notify->isread = 0;
                $new_notify->json = $json;
                $new_notify->save();

                try {
                    $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));

                    $messageText = $json['title'] . "\n\n" . $json['url'];
                    $bot->sendMessage($user->telegram_id, $messageText);
                } catch (\Exception $e) {

                }
            }
        }

    }

    static function createChooseOffer($offer, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);

        $json = array();
        $json['type'] = "freelance";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = "Вас выбрали исполнителем в проекте " . $project->name_project;
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id, 'offer_id' => $offer->last_id))

        );

        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        $new_notify->user_id = $offer->developer_id;
        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $offer->developer_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'];
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }
    }

    static function createNewCommentOffer($comment, $offer, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);
        $json = array();
        $json['type'] = "comments";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        if (request()->user()->last_id == $offer->developer_id) {
            $json['title'] = "Появился новый комментарий от исполнителя в проекте " . $project->name_project;
        }
        if (request()->user()->last_id == $project->client_id) {
            $json['title'] = "Появился новый комментарий от клиента в проекте " . $project->name_project;
        }
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id, 'offer_id' => $offer->last_id))

        );

        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        if (request()->user()->last_id == $offer->developer_id) {
            $new_notify->user_id = $project->client_id;

            try {
                $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
                $user = User::query()->where("last_id", $project->client_id)->first();
                $messageText = $json['title'] . "\n\n" . $json['url'];
                $bot->sendMessage($user->telegram_id, $messageText);
            } catch (\Exception $e) {

            }
        }
        if (request()->user()->last_id == $project->client_id) {
            $new_notify->user_id = $offer->developer_id;

            try {
                $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
                $user = User::query()->where("last_id", $offer->developer_id)->first();
                $messageText = $json['title'] . "\n\n" . $json['url'];
                $bot->sendMessage($user->telegram_id, $messageText);
            } catch (\Exception $e) {

            }
        }
        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();
    }

    static function createNewOfferNotify($offer, $project)
    {
        $project->name_project = preg_replace("/\s|&nbsp;/", ' ', $project->name_project);

        $json = array();
        $json['type'] = "freelance";
        $json['url'] = env('FRONT_URL') . "#/project/" . $project->last_id;
        $json['title'] = "Исполнитель отправил предложение в проект " . $project->name_project;
        $json['buttons'] = array(
            array('title' => 'Посмотреть', 'type' => 'load_project', 'params' => array('id' => $project->last_id, 'offer_id' => $offer->last_id))

        );

        $new_notify = new Notify();
        $new_notify->who_send_id = request()->user()->last_id;
        $new_notify->user_id = $project->client_id;
        $new_notify->isread = 0;
        $new_notify->json = $json;
        $new_notify->save();

        try {
            $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_TOKEN'));
            $user = User::query()->where("last_id", $project->client_id)->first();
            $messageText = $json['title'] . "\n\n" . $json['url'];
            $bot->sendMessage($user->telegram_id, $messageText);
        } catch (\Exception $e) {

        }


    }
}