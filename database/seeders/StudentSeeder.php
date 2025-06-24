<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentAddress;
use App\Models\ParentInfo;
use App\Models\Classes;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Kindergarten - Section A
        $classKinder = Classes::firstOrCreate([
            'grade_level' => 'kindergarten',
            'section' => 'A',
        ]);

        $studentsKinder = [
            [
                'lrn' => '112828123456',
                'fName' => 'Carl Edwin',
                'mName' => 'Vasquez',
                'lName' => 'Conde',
                'dob' => '2012-03-01',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123457',
                'fName' => 'Anna Marie',
                'mName' => 'Lopez',
                'lName' => 'Santos',
                'dob' => '2012-04-12',
                'sex' => 'Female',
            ],
            [
                'lrn' => '112828123458',
                'fName' => 'John Paul',
                'mName' => 'Reyes',
                'lName' => 'Dela Cruz',
                'dob' => '2012-05-23',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123459',
                'fName' => 'Maria Clara',
                'mName' => 'Garcia',
                'lName' => 'Torres',
                'dob' => '2012-06-15',
                'sex' => 'Female',
            ],
            [
                'lrn' => '112828123460',
                'fName' => 'Miguel',
                'mName' => 'Santos',
                'lName' => 'Ramos',
                'dob' => '2012-07-10',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123461',
                'fName' => 'Sophia',
                'mName' => 'Dela Cruz',
                'lName' => 'Mendoza',
                'dob' => '2012-08-19',
                'sex' => 'Female',
            ],
            [
                'lrn' => '112828123462',
                'fName' => 'Gabriel',
                'mName' => 'Reyes',
                'lName' => 'Fernandez',
                'dob' => '2012-09-25',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123463',
                'fName' => 'Isabella',
                'mName' => 'Torres',
                'lName' => 'Gonzales',
                'dob' => '2012-10-30',
                'sex' => 'Female',
            ],
            [
                'lrn' => '112828123464',
                'fName' => 'Lucas',
                'mName' => 'Garcia',
                'lName' => 'Santiago',
                'dob' => '2012-11-11',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123465',
                'fName' => 'Emma',
                'mName' => 'Ramos',
                'lName' => 'Villanueva',
                'dob' => '2012-12-21',
                'sex' => 'Female',
            ],
        ];

        foreach ($studentsKinder as $student) {
            $address = StudentAddress::create([
                'house_no' => rand(100, 999),
                'street_name' => 'Main St',
                'barangay' => 'Barangay Uno',
                'municipality_city' => 'Sample City',
                'province' => 'Sample Province',
                'zip_code' => '1000',
                'country' => 'Philippines',
                'pob' => 'Sample City',
            ]);

            $parent = ParentInfo::create([
                'father_fName' => 'Juan',
                'father_mName' => 'Dela',
                'father_lName' => 'Cruz',
                'father_phone' => '0917' . rand(1000000, 9999999),
                'mother_fName' => 'Maria',
                'mother_mName' => 'Santos',
                'mother_lName' => 'Reyes',
                'mother_phone' => '0918' . rand(1000000, 9999999),
                'emergCont_fName' => 'Pedro',
                'emergCont_mName' => 'Lopez',
                'emergCont_lName' => 'Gomez',
                'emergCont_phone' => '0920' . rand(1000000, 9999999),
            ]);

            Student::create([
                'student_lrn' => $student['lrn'],
                'student_fName' => $student['fName'],
                'student_mName' => $student['mName'],
                'student_lName' => $student['lName'],
                'student_extName' => null,
                'student_dob' => $student['dob'],
                'student_sex' => $student['sex'],
                'student_photo' => null,
                'qr_code' => Str::uuid(),
                'class_id' => $classKinder->id,
                'address_id' => $address->id,
                'parent_id' => $parent->id,
            ]);
        }

        // Grade 1 - Section A
        $classGrade1 = Classes::firstOrCreate([
            'grade_level' => 'grade1',
            'section' => 'A',
        ]);

        $studentsGrade1 = [
            [
                'lrn' => '112828123466',
                'fName' => 'Matthew',
                'mName' => 'Santos',
                'lName' => 'Cruz',
                'dob' => '2011-01-15',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123467',
                'fName' => 'Olivia',
                'mName' => 'Garcia',
                'lName' => 'Lopez',
                'dob' => '2011-02-20',
                'sex' => 'Female',
            ],
            [
                'lrn' => '112828123468',
                'fName' => 'Ethan',
                'mName' => 'Torres',
                'lName' => 'Reyes',
                'dob' => '2011-03-10',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123469',
                'fName' => 'Ava',
                'mName' => 'Dela Cruz',
                'lName' => 'Santos',
                'dob' => '2011-04-05',
                'sex' => 'Female',
            ],
            [
                'lrn' => '112828123470',
                'fName' => 'Noah',
                'mName' => 'Fernandez',
                'lName' => 'Gonzales',
                'dob' => '2011-05-18',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123471',
                'fName' => 'Mia',
                'mName' => 'Ramos',
                'lName' => 'Villanueva',
                'dob' => '2011-06-22',
                'sex' => 'Female',
            ],
            [
                'lrn' => '112828123472',
                'fName' => 'James',
                'mName' => 'Santiago',
                'lName' => 'Garcia',
                'dob' => '2011-07-30',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123473',
                'fName' => 'Charlotte',
                'mName' => 'Mendoza',
                'lName' => 'Torres',
                'dob' => '2011-08-14',
                'sex' => 'Female',
            ],
            [
                'lrn' => '112828123474',
                'fName' => 'Benjamin',
                'mName' => 'Reyes',
                'lName' => 'Santos',
                'dob' => '2011-09-09',
                'sex' => 'Male',
            ],
            [
                'lrn' => '112828123475',
                'fName' => 'Amelia',
                'mName' => 'Lopez',
                'lName' => 'Dela Cruz',
                'dob' => '2011-10-27',
                'sex' => 'Female',
            ],
        ];

        foreach ($studentsGrade1 as $student) {
            $address = StudentAddress::create([
                'house_no' => rand(100, 999),
                'street_name' => 'Main St',
                'barangay' => 'Barangay Uno',
                'municipality_city' => 'Sample City',
                'province' => 'Sample Province',
                'zip_code' => '1000',
                'country' => 'Philippines',
                'pob' => 'Sample City',
            ]);

            $parent = ParentInfo::create([
                'father_fName' => 'Juan',
                'father_mName' => 'Dela',
                'father_lName' => 'Cruz',
                'father_phone' => '0917' . rand(1000000, 9999999),
                'mother_fName' => 'Maria',
                'mother_mName' => 'Santos',
                'mother_lName' => 'Reyes',
                'mother_phone' => '0918' . rand(1000000, 9999999),
                'emergCont_fName' => 'Pedro',
                'emergCont_mName' => 'Lopez',
                'emergCont_lName' => 'Gomez',
                'emergCont_phone' => '0920' . rand(1000000, 9999999),
            ]);

            Student::create([
                'student_lrn' => $student['lrn'],
                'student_fName' => $student['fName'],
                'student_mName' => $student['mName'],
                'student_lName' => $student['lName'],
                'student_extName' => null,
                'student_dob' => $student['dob'],
                'student_sex' => $student['sex'],
                'student_photo' => null,
                'qr_code' => Str::uuid(),
                'class_id' => $classGrade1->id,
                'address_id' => $address->id,
                'parent_id' => $parent->id,
            ]);
        }
    }
}
