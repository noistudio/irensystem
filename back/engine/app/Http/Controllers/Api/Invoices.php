<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Invoice;
use App\Notify;
use App\Review;

class Invoices extends Controller
{

    public function stats()
    {
        $user = request()->user();
        $result_rub_developer = array();
        $result_rub_developer['pay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->Where("developer_id", $user->last_id);

        })->where("ispay", 1)->where("currency", "RUB")->sum("sum");
        $result_rub_developer['notpay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->orWhere("developer_id", $user->last_id);

        })->where("ispay", 0)->where("currency", "RUB")->sum("sum");


        $result_rub_client = array();
        $result_rub_client['pay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->Where("client_id", $user->last_id);

        })->where("ispay", 1)->where("currency", "RUB")->sum("sum");
        $result_rub_client['notpay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->orWhere("client_id", $user->last_id);

        })->where("ispay", 0)->where("currency", "RUB")->sum("sum");


        $result_usd_developer = array();
        $result_usd_developer['pay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->Where("developer_id", $user->last_id);

        })->where("ispay", 1)->where("currency", "USD")->sum("sum");
        $result_usd_developer['notpay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->orWhere("developer_id", $user->last_id);

        })->where("ispay", 0)->where("currency", "USD")->sum("sum");


        $result_usd_client = array();
        $result_usd_client['pay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->Where("client_id", $user->last_id);

        })->where("ispay", 1)->where("currency", "USD")->sum("sum");
        $result_usd_client['notpay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->orWhere("client_id", $user->last_id);

        })->where("ispay", 0)->where("currency", "USD")->sum("sum");

        $result_euro_developer = array();
        $result_euro_developer['pay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->Where("developer_id", $user->last_id);

        })->where("ispay", 1)->where("currency", "EURO")->sum("sum");
        $result_euro_developer['notpay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->orWhere("developer_id", $user->last_id);

        })->where("ispay", 0)->where("currency", "EURO")->sum("sum");


        $result_euro_client = array();
        $result_euro_client['pay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->Where("client_id", $user->last_id);

        })->where("ispay", 1)->where("currency", "EURO")->sum("sum");
        $result_euro_client['notpay'] = Invoice::query()->where(function ($query) use ($user) {
            $query->orWhere("client_id", $user->last_id);

        })->where("ispay", 0)->where("currency", "EURO")->sum("sum");

        $result = array('rub_developer' => $result_rub_developer, 'rub_client' => $result_rub_client,
            'usd_developer' => $result_usd_developer, 'usd_client' => $result_usd_client,
            'euro_developer' => $result_euro_developer, 'euro_client' => $result_euro_client

        );
        return $result;
    }

    public function get($id)
    {

        $user_id = request()->user()->last_id;
        $invoice = Invoice::query()->with("developer", "client")->where('last_id', $id)->where(function ($query) use ($user_id) {
            $query->orWhere("client_id", $user_id);
            $query->orWhere("developer_id", $user_id);
        })->first();

        if (!$invoice) {
            return array('type' => 'error', 'message' => 'Счет не найден!');
        }
        return array('type' => 'success', 'user' => request()->user(), 'invoice' => $invoice);
    }

    public function setPay($id)
    {

        $user_id = request()->user()->last_id;
        $invoice = Invoice::query()->where('last_id', $id)->where('client_pay', 0)->where(function ($query) use ($user_id) {
            $query->where("client_id", $user_id);
        })->first();
        if (!$invoice) {
            return array('type' => 'error', 'message' => 'Счет не найден!');
        }

        $invoice->client_pay = 1;
        $invoice->save();
        Notify::createInvoiceClientPay($invoice);

        return array('type' => 'success');
    }

    public function sendreview($id)
    {

        $user = request()->user();
        $form = request()->post();
        $invoice = Invoice::query()->where('last_id', $id)->where('ispay', 1)->where(function ($query) use ($user) {
            $query->orWhere(function ($query) use ($user) {
                $query->where("developer_id", $user->last_id);
                $query->where("developer_is_review", 0);
            });
            $query->orWhere(function ($query) use ($user) {
                $query->where("client_id", $user->last_id);
                $query->where("client_is_review", 0);
            });
        })->first();
        if (!$invoice) {
            return array('type' => 'error', 'message' => 'Счет не найден!');
        }

        if (!(isset($form['review']) and is_string($form['review']) and strlen($form['review']) > 1)) {
            return array('type' => 'error', 'message' => 'Заполните текст отзыва!');
        }

        if (!(isset($form['rating']) and is_numeric($form['rating']) and (int)$form['rating'] > 0 and $form['rating'] < 6)) {
            return array('type' => 'error', 'message' => 'Вы не заполнили рейтинг!');
        }

        $review = new Review();
        $review->invoice_id = $invoice->last_id;
        $review->who_send = $user->last_id;
        $review->review = $form['review'];
        $review->rating = $form['rating'];
        if ($user->last_id == $invoice->developer_id) {
            $review->user_id = $invoice->client_id;
            $invoice->developer_is_review = 1;
        }
        if ($user->last_id == $invoice->client_id) {
            $review->user_id = $invoice->developer_id;
            $invoice->client_is_review = 1;
        }

        $review->save();
        $invoice->save();


        $invoice->ispay = 1;
        $invoice->save();
        Notify::createInvoiceFinishPay($invoice);

        return $this->get($id);
    }

    public function finishPay($id)
    {

        $user_id = request()->user()->last_id;
        $invoice = Invoice::query()->where('last_id', $id)->where('ispay', 0)->where(function ($query) use ($user_id) {
            $query->where("developer_id", $user_id);
        })->first();
        if (!$invoice) {
            return array('type' => 'error', 'message' => 'Счет не найден!');
        }

        $invoice->ispay = 1;
        $invoice->save();
        Notify::createInvoiceFinishPay($invoice);

        return array('type' => 'success');
    }

    public function all()
    {

        $user_id = request()->user()->last_id;
        $invoices = Invoice::query()->where(function ($query) use ($user_id) {
            $query->orWhere("client_id", $user_id);
            $query->orWhere("developer_id", $user_id);
        })->get();

        return array('type' => 'success', 'user' => request()->user(), 'invoices' => $invoices);
    }

}