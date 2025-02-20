<?php

use yii\db\Migration;

class m230101_123456_fill_my_table extends Migration
{
    public function safeUp()
    {
        $faker = \Faker\Factory::create();
        
        // Создаем массив для batch insert
        $data = [];
        
        // Генерируем 50 записей
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $faker->phoneNumber,
                'isAdmin' => 0,
                'image' => 0,
                // Добавьте другие поля в соответствии с вашей таблицей
            ];
        }
        
        // Вставляем данные
        $this->batchInsert('user', [
            'name',
            'email',
            'password',
            'isAdmin',
            'image'
            // Перечислите все колонки
        ], $data);
    }

    public function safeDown()
    {
        // Очищаем таблицу
        $this->truncateTable('user');
    }
}