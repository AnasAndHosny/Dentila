<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PatientReportExport implements FromArray, WithHeadings, WithStrictNullComparison, WithStyles, WithDefaultStyles, WithColumnWidths
{
    use Exportable;

    public function __construct(private array $report, private string $type = 'excel')
    {
        //
    }

    public function array(): array
    {
        return $this->report;
    }

    public function headings(): array
    {
        if ($this->type === 'pdf') {
            return [
                'From',
                'To',
                'New',
                'Returning',
                'Visits',
                'Sched. Appts',
                'Canceled Appts',
                'Completed',
                'InProgress',
                'Avg Rating',
                'Avg Visits',
            ];
        }

        return [
            'From',
            'To',
            'New Patients',
            'Returning Patients',
            'Total Visits',
            'Scheduled Appointments',
            'Canceled Appointments',
            'Completed Treatments',
            'InProgress Treatments',
            'Avg Patient Rating',
            'Avg Visits per Patient',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->report) + 1;

        // landscape في حالة PDF
        if ($this->type === 'pdf') {
            $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getParent()->getDefaultStyle()->getFont()->setSize(8);
        }

        // الهيدر
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true, 'size' => ($this->type === 'pdf' ? 10 : 12), 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $this->type === 'pdf' ? '444444' : '4F81BD'],
            ],
        ]);

        // Borders
        $sheet->getStyle("A1:K{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // الصف الأخير (المجاميع)
        $sheet->getStyle("A{$lastRow}:K{$lastRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2'],
            ],
        ]);

        return [];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
    }

    public function columnWidths(): array
    {
        if ($this->type === 'pdf') {
            return [
                'A' => 12,  // From
                'B' => 12,  // To
                'C' => 12,  // New
                'D' => 14,  // Returning
                'E' => 12,  // Visits
                'F' => 16,  // Sched. Appts
                'G' => 16,  // Canceled Appts
                'H' => 14,  // Completed
                'I' => 14,  // InProgress
                'J' => 14,  // Avg Rating
                'K' => 16,  // Avg Visits
            ];
        }

        return [
            'A' => 12,
            'B' => 12,
            'C' => 15,
            'D' => 18,
            'E' => 15,
            'F' => 22,
            'G' => 22,
            'H' => 20,
            'I' => 20,
            'J' => 18,
            'K' => 20,
        ];
    }
}
