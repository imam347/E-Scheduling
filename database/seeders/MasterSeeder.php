<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\Lesson;
use App\Models\Major;
use App\Models\Room;
use App\Models\Teacher;
use App\Models\Time;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
//     Major::create([
//       'name'=>'Teknik Komputer dan Jaringan',
//       'code'=>'1',
//   ]);
//     Major::create([
//       'name'=>'Akuntansi',
//       'code'=>'2',
//   ]);
    
//   Teacher::create([
//       'name'=>'Ruhiyat Purnama, S.Pd',
//       'code'=>'201',
//   ]);

//   Teacher::create([
//       'name'=>'Andy Johaeri, S.Pd',
//       'code'=>'202',
//   ]);
//   Teacher::create([
//       'name'=>'Dikdik Hasbi As Siddiqi, S.Pd',
//       'code'=>'203',
//   ]);
//   Teacher::create([
//       'name'=>'Emma Dewi Maryam, S.Pd',
//       'code'=>'204',
//   ]);
//   Teacher::create([
//       'name'=>'Raden Salianti, S.Pd',
//       'code'=>'205',
//   ]);
//   Teacher::create([
//       'name'=>'Yanyarani, S.Pd',
//       'code'=>'206',
//   ]);
//   Teacher::create([
//       'name'=>'Nani Rosmawati, S.Pd',
//       'code'=>'207',
//   ]);
//   Teacher::create([
//       'name'=>'Lina Karlina, S.Pd',
//       'code'=>'208',
//   ]); 
//   Teacher::create([
//       'name'=>'Feby Anugrah Saputra, M.MPd',
//       'code'=>'209',
//   ]);
//   Teacher::create([
//       'name'=>'Lilis Karlina, S.Pd',
//       'code'=>'210',
//   ]);
//   Teacher::create([
//       'name'=>'Nuni Handini, S.Pd',
//       'code'=>'211',
//   ]);
//   Teacher::create([
//       'name'=>'Rendi Hanibal, S.Pd',
//       'code'=>'212',
//   ]);
//   Teacher::create([
//       'name'=>'Dini Hadianti, S.Pd',
//       'code'=>'213',
//   ]);
//   Teacher::create([
//       'name'=>'Agus Mulyana, S.Pd',
//       'code'=>'214',
//   ]);
//   Teacher::create([
//       'name'=>'Li Hidayat, S.Pd',
//       'code'=>'215',
//   ]);
//   Teacher::create([
//       'name'=>'Siti Aisyah, S.Pd',
//       'code'=>'216',
//   ]);
//   Teacher::create([
//       'name'=>'Yana, S.Pd',
//       'code'=>'217',
//   ]);
//   Teacher::create([
//       'name'=>'Rissa Selliana, ST',
//       'code'=>'218',
//   ]);
//   Teacher::create([
//       'name'=>'Kristian Supriyatin, S,Pd',
//       'code'=>'219',
//   ]);
//   Teacher::create([
//       'name'=>'Abdul Rojak, S.Pd.i',
//       'code'=>'220',
//   ]);
//   Teacher::create([
//       'name'=>'Bayu Rohmat Desplantikan Sukmana, S.Kom',
//       'code'=>'221',
//   ]);
//   Teacher::create([
//       'name'=>'Mandra Gaisha Irtantri, S.Pd',
//       'code'=>'222',
//   ]);
//   Teacher::create([
//       'name'=>'Rangga Dwi Putra, S.Pd',
//       'code'=>'223',
//   ]);
//   Teacher::create([
//       'name'=>'Sekar Milasari, S.Pd',
//       'code'=>'224',
//   ]);
//   Teacher::create([
//       'name'=>'Eko Darmawan, S.Pd',
//       'code'=>'225',
//   ]);

//   Lesson::create([
//       'name'=>'Kompetensi Keahlian TKJ',
//       'code'=>'301',
//       'sks'=>'4'
//   ]);
//   Lesson::create([
//       'name'=>'Kompetensi Keahlian AK',
//       'code'=>'302',
//       'sks'=>'4'
//   ]);
//   Lesson::create([
//       'name'=>'Matematika',
//       'code'=>'303',
//       'sks'=>'4'
//   ]);
//   Lesson::create([
//       'name'=>'Bahasa Indonesia',
//       'code'=>'304',
//       'sks'=>'4'
//   ]);
//   Lesson::create([
//       'name'=>'Bahasa Inggris',
//       'code'=>'305',
//       'sks'=>'4'
//   ]);
//   Lesson::create([
//       'name'=>'IPA',
//       'code'=>'306',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Seni Rupa',
//       'code'=>'307',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Projek IPAS',
//       'code'=>'308',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Pendidikan Pancasila',
//       'code'=>'309',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Pendidikan Pancasila',
//       'code'=>'309',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Sejarah',
//       'code'=>'310',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'PPKn',
//       'code'=>'311',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'KKPI',
//       'code'=>'312',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Dasar Pogram Keahlian',
//       'code'=>'313',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Pendidikan Agama Islam &Budi Pekerti',
//       'code'=>'314',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Informatika',
//       'code'=>'315',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Program Kreatif & Kewirausahaan',
//       'code'=>'316',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Penjasorkes',
//       'code'=>'317',
//       'sks'=>'2'
//   ]);
//   Lesson::create([
//       'name'=>'Bahasa Sunda',
//       'code'=>'318',
//       'sks'=>'2'
//   ]);

//   Room::create([
//     'code'=>'111',
//     'name'=>'X TKJ 1',
//     'major_id'=>'1'
//   ]);
//   Room::create([
//     'code'=>'112',
//     'name'=>'X TKJ 2',
//     'major_id'=>'1'
//   ]);
//   Room::create([
//     'code'=>'121',
//     'name'=>'XI TKJ 1',
//     'major_id'=>'1'
//   ]);
//   Room::create([
//     'code'=>'122',
//     'name'=>'XI TKJ 2',
//     'major_id'=>'1'
//   ]);
//   Room::create([
//     'code'=>'131',
//     'name'=>'XII TKJ',
//     'major_id'=>'1'
//   ]);
//   Room::create([
//     'code'=>'211',
//     'name'=>'X AK',
//     'major_id'=>'2'
//   ]);
//   Room::create([
//     'code'=>'221',
//     'name'=>'XI AK',
//     'major_id'=>'2'
//   ]);
//   Room::create([
//     'code'=>'231',
//     'name'=>'XII AK',
//     'major_id'=>'2'
//   ]);

//   Day::create([
//     'name'=>'Senin',
//     'code'=>'41',
//   ]);
//   Day::create([
//     'name'=>'Selasa',
//     'code'=>'42',
//   ]);
//   Day::create([
//     'name'=>'Rabu',
//     'code'=>'43',
//   ]);
//   Day::create([
//     'name'=>'Kamis',
//     'code'=>'44',
//   ]);
//   Day::create([
//     'name'=>'Jumat',
//     'code'=>'45',
//   ]);
//   Day::create([
//     'name'=>'Sabtu',
//     'code'=>'46',
//   ]);

  Time::create([
    'code'=>'61',
    'range_min'=>'06:45:00',
    'range_max'=>'07:25:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'62',
    'range_min'=>'07:25:00',
    'range_max'=>'08:05:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'63',
    'range_min'=>'08:05:00',
    'range_max'=>'08:45:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'64',
    'range_min'=>'08:45:00',
    'range_max'=>'09:25:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'65',
    'range_min'=>'09:40:00',
    'range_max'=>'10:20:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'66',
    'range_min'=>'10:20:00',
    'range_max'=>'11:00:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'67',
    'range_min'=>'10:20:00',
    'range_max'=>'11:00:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'68',
    'range_min'=>'11:00:00',
    'range_max'=>'11:40:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'69',
    'range_min'=>'11:40:00',
    'range_max'=>'12:20:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'70',
    'range_min'=>'12:35:00',
    'range_max'=>'13:15:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'71',
    'range_min'=>'13:15:00',
    'range_max'=>'13:55:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'72',
    'range_min'=>'13:55:00',
    'range_max'=>'14:35:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'73',
    'range_min'=>'13:55:00',
    'range_max'=>'14:35:00',
    'sks'=>'2',
  ]);
  Time::create([
    'code'=>'74',
    'range_min'=>'14:35:00',
    'range_max'=>'15:15:00',
    'sks'=>'2',
  ]);

  }
}
