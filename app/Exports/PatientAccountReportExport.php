<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PatientAccountReportExport implements FromArray, WithHeadings, WithStrictNullComparison, WithStyles, WithDefaultStyles, WithEvents
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
        return [
            'From',
            'To',
            'Total Accounts',
            'With Due Balance',
            'Clear Balance',
            'Total Balance',
            'Average Balance',
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        // ستايل رأس الجدول
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'], // أزرق جميل
            ],
        ];

        // نطبّق الستايل على الصف الأول (الهيدر)
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // نكبّر الأعمدة شوي
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setWidth(20);
        }

        // ✅ لون الصفوف بشكل متناوب (striping)
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $fillColor = $row % 2 === 0 ? 'F2F2F2' : 'FFFFFF'; // رمادي فاتح أو أبيض
            $sheet->getStyle("A{$row}:G{$row}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($fillColor);
        }

        return [];
    }

    public function defaultStyles(\PhpOffice\PhpSpreadsheet\Style\Style $defaultStyle)
    {
        return [
            'font' => ['size' => 11],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ✅ توزيع الأعمدة بالنسبة للعرض الكلي
                if ($this->type === 'pdf') {
                    $sheet->getColumnDimension('A')->setWidth(12);
                    $sheet->getColumnDimension('B')->setWidth(12);
                    $sheet->getColumnDimension('C')->setWidth(12);
                    $sheet->getColumnDimension('D')->setWidth(16);
                    $sheet->getColumnDimension('E')->setWidth(16);
                    $sheet->getColumnDimension('F')->setWidth(18);
                    $sheet->getColumnDimension('G')->setWidth(18);
                }

                // ✅ توزيع الأعمدة (Excel)
                if ($this->type === 'excel') {
                    $sheet->getColumnDimension('A')->setWidth(15); // From
                    $sheet->getColumnDimension('B')->setWidth(15); // To
                    $sheet->getColumnDimension('C')->setWidth(18); // Total Accounts
                    $sheet->getColumnDimension('D')->setWidth(20); // With Due Balance
                    $sheet->getColumnDimension('E')->setWidth(18); // Clear Balance
                    $sheet->getColumnDimension('F')->setWidth(20); // Total Balance
                    $sheet->getColumnDimension('G')->setWidth(20); // Average Balance

                    // ✅ إضافة إطار (Borders) للجدول
                    $sheet->getStyle('A1:G' . $sheet->getHighestRow())
                        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    // ✅ تنسيق الأرقام المالية (Currency Style)
                    $currencyColumns = ['E', 'F', 'G']; // الأعمدة المالية
                    foreach ($currencyColumns as $col) {
                        $sheet->getStyle("{$col}2:{$col}" . $sheet->getHighestRow())
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.00'); // رقم عشري مع فواصل
                    }
                }

                // ✅ النصوص
                $sheet->getStyle('A1:G' . $sheet->getHighestRow())
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setShrinkToFit(true);
            },
        ];
    }
}
