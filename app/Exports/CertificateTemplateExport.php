<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class CertificateTemplateExport implements FromCollection
{
    public function collection(): \Illuminate\Support\Collection
    {
        // Return a collection with the required headers and one sample row
        return collect([
            [
                'Full Name',
                'Birth Name',
                'Matric Number',
                'Faculty',
                'Programme',
                'Department',
                'School',
                'Qualification',
                'Award',
                'Class',
                'Graduation Date',
                'Session',
                'Certificate Number',
            ],
            [
                'John Doe',
                'John Birth Name',
                'HND/CS/2024/0001',
                'Faculty of Science',
                'Computer Science',
                'Information Tech',
                'School of Applied Sciences',
                'Higher National Diploma',
                'HND',
                'Upper Credit',
                '2025-11-15',
                '2024/2025',
                'CERT-2025-0001',
            ]
        ]);
    }
}