<?php

use yii\db\Migration;

class m230104_123456_fill_my_table extends Migration
{
    public function safeUp()
    {
        $faker = \Faker\Factory::create();
        
        // Создаем массив для batch insert
        $data = [];
        
        // Генерируем 50 записей
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'title' => $faker->company,
                'description' => $faker->sentence,
                'content' => $faker->paragraph,
                'category_id'=>$faker->numberBetween(22, 43)
                // Добавьте другие поля в соответствии с вашей таблицей
            ];
        }
        
        // Вставляем данные
        $this->batchInsert('article', [
            'title',
            'description',
            'content',
            'category_id'
            // Перечислите все колонки
        ], $data);
    }

    public function safeDown()
    {
        // Очищаем таблицу
        $this->truncateTable('article');
    }
}