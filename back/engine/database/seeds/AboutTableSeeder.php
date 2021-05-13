<?php

use Illuminate\Database\Seeder;

class AboutTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \Illuminate\Support\Facades\DB::table('about')->insertGetId(
            [
                'enable' => 1,
                'sort' => 1,
                'title' => 'О сайте TEST',
                'header' => '                            <div class="col-lg-6">
                <h1 class="display-3  text-white">Artemdev.ru
                  <span>делаю невозможное возможным</span>
                </h1>
                <p class="lead  text-white">Здесь рождаются качественные web проекты.</p>

              </div>    
',
                'content' => '',


            ]
        );
    }
}
