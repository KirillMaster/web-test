<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InstituteTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('institute')->delete();

        DB::table('institute')->insert(array(
            'id' => 1,
            'name' => 'ИНСТИТУТ ИНФОРМАЦИОННЫХ ТЕХНОЛОГИЙ И УПРАВЛЕНИЯ В ТЕХНИЧЕСКИХ СИСТЕМАХ',
            'description' => 'Институт информационных технологий и управления в технических системах — 
            ведущий образовательный и научно-исследовательский центр в области IT-индустрии в Крыму,
             осуществляющий фундаментальную  подготовку  по программам  бакалавриата, магистратуры и 
             аспирантуры  в области информатики и вычислительной техники, информационных систем и технологий, 
             компьютеризированных систем автоматического управления.'));
    }
}