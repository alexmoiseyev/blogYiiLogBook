<?php

use yii\db\Migration;

class m230103_123456_fill_my_table extends Migration
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
                // Добавьте другие поля в соответствии с вашей таблицей
            ];
        }
        
        // Вставляем данные
        $this->batchInsert('category', [
            'title',
            // Перечислите все колонки
        ], $data);
    }

    public function safeDown()
    {
        // Очищаем таблицу
        $this->truncateTable('category');
    }
}