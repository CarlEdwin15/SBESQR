<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class StudentsImport implements ToCollection
{
    public int $imported = 0;
    public int $skipped = 0;
    public int $duplicates = 0;

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            Log::warning('Excel file is empty.');
            return;
        }

        // Step 1: Find the header row
        $headerRowIndex = $rows->search(function ($row) {
            return collect($row)->contains(function ($cell) {
                return strtolower(trim($cell)) === 'lrn';
            });
        });

        if ($headerRowIndex === false) {
            Log::error('No header row containing "lrn" found.');
            return;
        }

        $headerRow = $rows[$headerRowIndex]->toArray();

        // Step 2: Normalize headers
        $headers = [];
        foreach ($headerRow as $index => $header) {
            $headers[$index] = strtolower(trim($header));
        }

        // Step 3: Process all rows after the header
        for ($i = $headerRowIndex + 1; $i < $rows->count(); $i++) {
            $row = $rows[$i]->toArray();
            $r = [];

            // Map header to value
            foreach ($headers as $index => $key) {
                if (!empty($key)) {
                    $r[$key] = trim((string)($row[$index] ?? ''));
                }
            }

            // Flexible column aliases
            $aliases = [
                'lrn' => ['lrn', 'student_lrn', 'learner reference no', 'learner_ref_no'],
                'first_name' => ['first_name', 'firstname', 'first name'],
                'middle_name' => ['middle_name', 'middlename', 'middle name'],
                'last_name' => ['last_name', 'lastname', 'last name'],
                'extension_name' => ['extension_name', 'suffix', 'ext'],
                'dob' => ['dob', 'birthdate', 'date_of_birth'],
                'sex' => ['sex', 'gender'],
                'place_of_birth' => ['place_of_birth', 'birthplace'],
                'house_no' => ['house_no', 'house number'],
                'street_name' => ['street_name', 'street'],
                'barangay' => ['barangay', 'brgy'],
                'municipality_city' => ['municipality_city', 'city', 'municipality'],
                'province' => ['province', 'prov'],
                'zip_code' => ['zip_code', 'zipcode', 'zip'],
            ];

            $data = [];
            foreach ($aliases as $mainKey => $possibleKeys) {
                foreach ($possibleKeys as $alias) {
                    if (array_key_exists($alias, $r)) {
                        $data[$mainKey] = $r[$alias];
                        break;
                    }
                }
            }

            // Required field validation
            if (empty($data['lrn']) || empty($data['first_name']) || empty($data['last_name'])) {
                $this->skipped++;
                Log::warning("Skipped row {$i}: missing required fields", $data);
                continue;
            }

            $sex = strtolower($data['sex'] ?? '');
            if (!in_array($sex, ['male', 'female'])) {
                $this->skipped++;
                Log::warning("Skipped row {$i}: invalid sex value", $data);
                continue;
            }

            if (Student::where('student_lrn', $data['lrn'])->exists()) {
                $this->duplicates++;
                Log::info("Duplicate LRN found: {$data['lrn']}");
                continue;
            }

            // 🧠 Fix: Robust DOB conversion
            $dob = null;
            if (!empty($data['dob'])) {
                try {
                    // Case 1: Excel numeric date serial
                    if (is_numeric($data['dob'])) {
                        $dob = Carbon::instance(ExcelDate::excelToDateTimeObject($data['dob']))->format('Y-m-d');
                    }
                    // Case 2: String formats (YYYY-MM-DD, MM/DD/YYYY, etc.)
                    else {
                        $dob = Carbon::parse($data['dob'])->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    Log::warning("Invalid DOB format at row {$i}: {$data['dob']}");
                    $dob = null;
                }
            }

            // Step 4: Insert into DB
            DB::beginTransaction();
            try {
                $address = StudentAddress::create([
                    'house_no' => $data['house_no'] ?? null,
                    'street_name' => $data['street_name'] ?? null,
                    'barangay' => $data['barangay'] ?? null,
                    'municipality_city' => $data['municipality_city'] ?? null,
                    'province' => $data['province'] ?? null,
                    'country' => 'Philippines',
                    'pob' => $data['place_of_birth'] ?? null,
                    'zip_code' => $data['zip_code'] ?? null,
                ]);

                Student::create([
                    'student_lrn' => $data['lrn'],
                    'student_fName' => $data['first_name'],
                    'student_mName' => $data['middle_name'] ?? null,
                    'student_lName' => $data['last_name'],
                    'student_extName' => $data['extension_name'] ?? null,
                    'student_dob' => $dob, // ✅ fixed
                    'student_sex' => $sex,
                    'qr_code' => uniqid('QR'),
                    'address_id' => $address->id,
                ]);

                DB::commit();
                $this->imported++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->skipped++;
                Log::error("Error importing row {$i}: " . $e->getMessage(), $data);
            }
        }
    }
}
