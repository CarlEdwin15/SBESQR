<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentAddress;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\DefaultValueBinder;
use Carbon\Carbon;
use Exception;

class StudentsImport extends DefaultValueBinder implements
    OnEachRow,
    WithChunkReading,
    WithBatchInserts,
    WithCustomCsvSettings,
    WithCustomValueBinder
{
    public int $imported = 0;
    public int $skipped = 0;
    public int $duplicates = 0;
    public array $errors = [];

    public function onRow(Row $row): void
    {
        $excelRow = $row->getIndex();
        $r = $row->toArray();

        // Skip title (row 1), header (row 2), and empty rows
        if ($excelRow <= 2 || count(array_filter($r)) === 0) return;

        $headers = [
            'lrn',
            'first_name',
            'middle_name',
            'last_name',
            'extension_name',
            'dob',
            'sex',
            'place_of_birth',
            'house_no',
            'street_name',
            'barangay',
            'municipality_city',
            'province',
            'zip_code'
        ];

        $data = [];
        foreach ($headers as $index => $key) {
            $data[$key] = trim((string)($r[$index] ?? ''));
        }

        // Required fields
        if (empty($data['lrn']) || empty($data['first_name']) || empty($data['last_name'])) {
            $this->skipped++;
            $this->errors[] = "Row {$excelRow}: Missing required field(s) — LRN, First Name, and Last Name are required.";
            return;
        }

        // Normalize LRN
        $data['lrn'] = ltrim(trim($data['lrn']), "'\"");
        if (preg_match('/^[0-9]\.?[0-9]*E[+-]?[0-9]+$/i', $data['lrn'])) {
            $data['lrn'] = number_format((float)$data['lrn'], 0, '', '');
        }

        // Validate LRN pattern
        if (!preg_match('/^112828\d{6}$/', $data['lrn'])) {
            $this->skipped++;
            $this->errors[] = "Row {$excelRow}: Invalid LRN '{$data['lrn']}'.";
            return;
        }

        // Validate sex
        $sex = strtolower($data['sex']);
        if (!in_array($sex, ['male', 'female'])) {
            $this->skipped++;
            $this->errors[] = "Row {$excelRow}: Invalid Sex value '{$data['sex']}'.";
            return;
        }

        // Check duplicates
        if (Student::where('student_lrn', $data['lrn'])->exists()) {
            $this->duplicates++;
            $this->errors[] = "Row {$excelRow}: Duplicate LRN '{$data['lrn']}' — already exists.";
            return;
        }

        // Convert DOB safely
        $dob = null;
        try {
            if (is_numeric($data['dob'])) {
                $dob = Carbon::instance(ExcelDate::excelToDateTimeObject($data['dob']))->format('Y-m-d');
            } elseif (!empty($data['dob'])) {
                $dob = Carbon::parse($data['dob'])->format('Y-m-d');
            }
        } catch (Exception $e) {
            $dob = null;
        }

        // Normalize text fields
        $textFields = [
            'first_name',
            'middle_name',
            'last_name',
            'extension_name',
            'place_of_birth',
            'street_name',
            'barangay',
            'municipality_city',
            'province'
        ];

        foreach ($textFields as $field) {
            $data[$field] = $this->normalizeText($data[$field]);
        }

        // Transactional insert
        DB::beginTransaction();
        try {
            $address = StudentAddress::create([
                'house_no' => $data['house_no'] ?: null,
                'street_name' => $data['street_name'] ?: null,
                'barangay' => $data['barangay'] ?: null,
                'municipality_city' => $data['municipality_city'] ?: null,
                'province' => $data['province'] ?: null,
                'country' => 'Philippines',
                'pob' => $data['place_of_birth'] ?: null,
                'zip_code' => $data['zip_code'] ?: null,
            ]);

            Student::create([
                'student_lrn' => $data['lrn'],
                'student_fName' => $data['first_name'],
                'student_mName' => $data['middle_name'] ?: null,
                'student_lName' => $data['last_name'],
                'student_extName' => $data['extension_name'] ?: null,
                'student_dob' => $dob,
                'student_sex' => $sex,
                'qr_code' => uniqid('QR'),
                'address_id' => $address->id,
            ]);

            DB::commit();
            $this->imported++;
        } catch (Exception $e) {
            DB::rollBack();
            $this->skipped++;
            $this->errors[] = "Row {$excelRow}: Database error — " . $e->getMessage();
        }
    }

    /**
     * Capitalize the first letter of each word, normalize spacing
     */
    private function normalizeText(string $text): string
    {
        $text = trim($text);
        $text = preg_replace('/\s+/', ' ', $text); // remove extra spaces
        return mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
    }

    public function chunkSize(): int
    {
        return 500;
    }
    public function batchSize(): int
    {
        return 500;
    }

    public function getCsvSettings(): array
    {
        return ['input_encoding' => 'UTF-8', 'delimiter' => ',', 'enclosure' => '"', 'escape_character' => '\\'];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'A') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }
}
