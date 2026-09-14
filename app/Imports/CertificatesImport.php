<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class CertificatesImport implements ToCollection
{
    public function collection(Collection $rows): void
    {
        // We just need this class to satisfy the Type Hint in Excel::toArray()
        // The actual processing logic is handled in CertificateImportService
    }
}