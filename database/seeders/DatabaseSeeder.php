<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Teacher;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $students = [
    ['name' => 'Ali Ahmed',       'email' => 'ali@gmail.com',       'phone' => '03001234567', 'roll_no' => '1',  'class_id' => 2],
    ['name' => 'Ahmed Khan',      'email' => 'ahmed@gmail.com',     'phone' => '03001234568', 'roll_no' => '2',  'class_id' => 2],
    ['name' => 'Usman Ali',       'email' => 'usman@gmail.com',     'phone' => '03001234569', 'roll_no' => '3',  'class_id' => 3],
    ['name' => 'Hassan Raza',     'email' => 'hassan@gmail.com',    'phone' => '03001234570', 'roll_no' => '4',  'class_id' => 3],
    ['name' => 'Bilal Hussain',   'email' => 'bilal@gmail.com',     'phone' => '03001234571', 'roll_no' => '5',  'class_id' => 5],
    ['name' => 'Kamran Sheikh',   'email' => 'kamran@gmail.com',    'phone' => '03001234572', 'roll_no' => '6',  'class_id' => 5],
    ['name' => 'Tariq Mahmood',   'email' => 'tariq@gmail.com',     'phone' => '03001234573', 'roll_no' => '7',  'class_id' => 6],
    ['name' => 'Zubair Ahmad',    'email' => 'zubair@gmail.com',    'phone' => '03001234574', 'roll_no' => '8',  'class_id' => 6],
    ['name' => 'Fahad Malik',     'email' => 'fahad@gmail.com',     'phone' => '03001234575', 'roll_no' => '9',  'class_id' => 7],
    ['name' => 'Imran Qureshi',   'email' => 'imran@gmail.com',     'phone' => '03001234576', 'roll_no' => '10', 'class_id' => 7],
    ['name' => 'Saad Butt',       'email' => 'saad@gmail.com',      'phone' => '03001234577', 'roll_no' => '11', 'class_id' => 8],
    ['name' => 'Hamza Iqbal',     'email' => 'hamza@gmail.com',     'phone' => '03001234578', 'roll_no' => '12', 'class_id' => 8],
    ['name' => 'Junaid Shah',     'email' => 'junaid@gmail.com',    'phone' => '03001234579', 'roll_no' => '13', 'class_id' => 9],
    ['name' => 'Rizwan Hayat',    'email' => 'rizwan@gmail.com',    'phone' => '03001234580', 'roll_no' => '14', 'class_id' => 9],
    ['name' => 'Naveed Aslam',    'email' => 'naveed@gmail.com',    'phone' => '03001234581', 'roll_no' => '15', 'class_id' => 10],
    ['name' => 'Faisal Javed',    'email' => 'faisal@gmail.com',    'phone' => '03001234582', 'roll_no' => '16', 'class_id' => 10],
    ['name' => 'Asad Mehmood',    'email' => 'asad@gmail.com',      'phone' => '03001234583', 'roll_no' => '17', 'class_id' => 11],
    ['name' => 'Waqar Zaidi',     'email' => 'waqar@gmail.com',     'phone' => '03001234584', 'roll_no' => '18', 'class_id' => 11],
    ['name' => 'Shahid Niazi',    'email' => 'shahid@gmail.com',    'phone' => '03001234585', 'roll_no' => '19', 'class_id' => 12],
    ['name' => 'Adnan Siddiqui',  'email' => 'adnan@gmail.com',     'phone' => '03001234586', 'roll_no' => '20', 'class_id' => 12],
    ['name' => 'Fatima Malik',    'email' => 'fatima@gmail.com',    'phone' => '03001234587', 'roll_no' => '21', 'class_id' => 13],
    ['name' => 'Ayesha Khan',     'email' => 'ayesha@gmail.com',    'phone' => '03001234588', 'roll_no' => '22', 'class_id' => 13],
    ['name' => 'Sana Butt',       'email' => 'sana@gmail.com',      'phone' => '03001234589', 'roll_no' => '23', 'class_id' => 14],
    ['name' => 'Rabia Hussain',   'email' => 'rabia@gmail.com',     'phone' => '03001234590', 'roll_no' => '24', 'class_id' => 14],
    ['name' => 'Nadia Ahmed',     'email' => 'nadia@gmail.com',     'phone' => '03001234591', 'roll_no' => '25', 'class_id' => 2],
    ['name' => 'Hira Qureshi',    'email' => 'hira@gmail.com',      'phone' => '03001234592', 'roll_no' => '26', 'class_id' => 3],
    ['name' => 'Maryam Shah',     'email' => 'maryam@gmail.com',    'phone' => '03001234593', 'roll_no' => '27', 'class_id' => 5],
    ['name' => 'Zainab Ali',      'email' => 'zainab@gmail.com',    'phone' => '03001234594', 'roll_no' => '28', 'class_id' => 6],
    ['name' => 'Amna Raza',       'email' => 'amna@gmail.com',      'phone' => '03001234595', 'roll_no' => '29', 'class_id' => 7],
    ['name' => 'Saima Iqbal',     'email' => 'saima@gmail.com',     'phone' => '03001234596', 'roll_no' => '30', 'class_id' => 8],
    ['name' => 'Umer Farooq',     'email' => 'umer@gmail.com',      'phone' => '03001234597', 'roll_no' => '31', 'class_id' => 9],
    ['name' => 'Danish Raza',     'email' => 'danish@gmail.com',    'phone' => '03001234598', 'roll_no' => '32', 'class_id' => 10],
    ['name' => 'Yasir Nawaz',     'email' => 'yasir@gmail.com',     'phone' => '03001234599', 'roll_no' => '33', 'class_id' => 11],
    ['name' => 'Kashif Mehmood',  'email' => 'kashif@gmail.com',    'phone' => '03001234600', 'roll_no' => '34', 'class_id' => 12],
    ['name' => 'Shoaib Akhtar',   'email' => 'shoaib@gmail.com',    'phone' => '03001234601', 'roll_no' => '35', 'class_id' => 13],
    ['name' => 'Babar Azam',      'email' => 'babar@gmail.com',     'phone' => '03001234602', 'roll_no' => '36', 'class_id' => 14],
    ['name' => 'Shaheen Shah',    'email' => 'shaheen@gmail.com',   'phone' => '03001234603', 'roll_no' => '37', 'class_id' => 2],
    ['name' => 'Naseem Shah',     'email' => 'naseem@gmail.com',    'phone' => '03001234604', 'roll_no' => '38', 'class_id' => 3],
    ['name' => 'Mohammad Rizwan', 'email' => 'rizwan2@gmail.com',   'phone' => '03001234605', 'roll_no' => '39', 'class_id' => 5],
    ['name' => 'Shadab Khan',     'email' => 'shadab@gmail.com',    'phone' => '03001234606', 'roll_no' => '40', 'class_id' => 6],
    ['name' => 'Haris Rauf',      'email' => 'haris@gmail.com',     'phone' => '03001234607', 'roll_no' => '41', 'class_id' => 7],
    ['name' => 'Fakhar Zaman',    'email' => 'fakhar@gmail.com',    'phone' => '03001234608', 'roll_no' => '42', 'class_id' => 8],
    ['name' => 'Imam Ul Haq',     'email' => 'imam@gmail.com',      'phone' => '03001234609', 'roll_no' => '43', 'class_id' => 9],
    ['name' => 'Abdullah Shafiq', 'email' => 'abdullah@gmail.com',  'phone' => '03001234610', 'roll_no' => '44', 'class_id' => 10],
    ['name' => 'Salman Agha',     'email' => 'salman@gmail.com',    'phone' => '03001234611', 'roll_no' => '45', 'class_id' => 11],
    ['name' => 'Iftikhar Ahmed',  'email' => 'iftikhar@gmail.com',  'phone' => '03001234612', 'roll_no' => '46', 'class_id' => 12],
    ['name' => 'Asif Ali',        'email' => 'asif@gmail.com',      'phone' => '03001234613', 'roll_no' => '47', 'class_id' => 13],
    ['name' => 'Khushdil Shah',   'email' => 'khushdil@gmail.com',  'phone' => '03001234614', 'roll_no' => '48', 'class_id' => 14],
    ['name' => 'Zahid Mahmood',   'email' => 'zahid@gmail.com',     'phone' => '03001234615', 'roll_no' => '49', 'class_id' => 2],
    ['name' => 'Usman Qadir',     'email' => 'usman2@gmail.com',    'phone' => '03001234616', 'roll_no' => '50', 'class_id' => 3],
];
Student::truncate();
Student::insert($students);

$teachers = [
    ['name' => 'Ali Hassan',       'email' => 'ali.hassan@school.com',       'phone' => '03011234501'],
    ['name' => 'Ahmed Raza',       'email' => 'ahmed.raza@school.com',       'phone' => '03011234502'],
    ['name' => 'Usman Tariq',      'email' => 'usman.tariq@school.com',      'phone' => '03011234503'],
    ['name' => 'Bilal Khan',       'email' => 'bilal.khan@school.com',       'phone' => '03011234504'],
    ['name' => 'Kamran Ahmed',     'email' => 'kamran.ahmed@school.com',     'phone' => '03011234505'],
    ['name' => 'Tariq Hussain',    'email' => 'tariq.hussain@school.com',    'phone' => '03011234506'],
    ['name' => 'Zubair Shah',      'email' => 'zubair.shah@school.com',      'phone' => '03011234507'],
    ['name' => 'Fahad Mehmood',    'email' => 'fahad.mehmood@school.com',    'phone' => '03011234508'],
    ['name' => 'Imran Malik',      'email' => 'imran.malik@school.com',      'phone' => '03011234509'],
    ['name' => 'Saad Qureshi',     'email' => 'saad.qureshi@school.com',     'phone' => '03011234510'],
    ['name' => 'Fatima Zahra',     'email' => 'fatima.zahra@school.com',     'phone' => '03011234511'],
    ['name' => 'Ayesha Siddiqui',  'email' => 'ayesha.siddiqui@school.com', 'phone' => '03011234512'],
    ['name' => 'Sana Malik',       'email' => 'sana.malik@school.com',       'phone' => '03011234513'],
    ['name' => 'Rabia Naz',        'email' => 'rabia.naz@school.com',        'phone' => '03011234514'],
    ['name' => 'Nadia Khan',       'email' => 'nadia.khan@school.com',       'phone' => '03011234515'],
    ['name' => 'Hira Baig',        'email' => 'hira.baig@school.com',        'phone' => '03011234516'],
    ['name' => 'Maryam Iqbal',     'email' => 'maryam.iqbal@school.com',     'phone' => '03011234517'],
    ['name' => 'Zainab Hussain',   'email' => 'zainab.hussain@school.com',   'phone' => '03011234518'],
    ['name' => 'Amna Butt',        'email' => 'amna.butt@school.com',        'phone' => '03011234519'],
    ['name' => 'Saima Nawaz',      'email' => 'saima.nawaz@school.com',      'phone' => '03011234520'],
];
Teacher::truncate();
Teacher::insert($teachers);

DB::statement('SET FOREIGN_KEY_CHECKS=1');
}


}
