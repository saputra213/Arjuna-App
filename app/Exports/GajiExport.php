<?php

namespace App\Exports;

use App\Models\Gaji;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GajiExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithCustomStartCell, WithColumnFormatting
{
    // ✅ Tambahkan properti
    protected $periode;

    // ✅ Konstruktor isi periode
    public function __construct($periode = null)
    {
        if ($periode) {
            if (preg_match('/^\d{4}-\d{2}$/', $periode)) {
                // format "2025-09" → jadi "SEPTEMBER 2025"
                $this->periode = strtoupper(Carbon::createFromFormat('Y-m', $periode)->format('F Y'));
            } else {
                $this->periode = strtoupper($periode);
            }
        } else {
            $this->periode = strtoupper(Carbon::now()->format('F Y'));
        }
    }

    public function startCell(): string
    {
        return 'A4'; // header tabel mulai baris 4
    }

    public function collection()
    {
        return Gaji::with('user')
            ->where('periode', $this->periode)
            ->get()
            ->map(function ($row) {
                return [
                    optional($row->user)->name ?? '-', // Nama Karyawan
                    $row->periode,
                    (float) $row->gaji_pokok,
                    (float) $row->tunjangan,
                    (float) $row->potongan,
                    (float) $row->total_gaji,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Periode',
            'Gaji Pokok',
            'Tunjangan',
            'Potongan',
            'Total Gaji',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4CAF50']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'LAPORAN REKAP GAJI KARYAWAN');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Periode
                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'Periode: ' . $this->periode);
                $sheet->getStyle('A2')->getFont()->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Border tabel
                $highestRow = $sheet->getHighestRow();
                $range = "A4:F{$highestRow}";
                $sheet->getStyle($range)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // TOTAL di bawah tabel
                $totalRow = $highestRow + 1;

                // kalau data ada di bawah header (>=5)
                if ($highestRow >= 5) {
                    $sheet->setCellValue("E{$totalRow}", "TOTAL");
                    $sheet->setCellValue("F{$totalRow}", "=SUM(F5:F{$highestRow})");
                } else {
                    $sheet->setCellValue("E{$totalRow}", "TOTAL");
                    $sheet->setCellValue("F{$totalRow}", 0);
                }

                $sheet->getStyle("E{$totalRow}:F{$totalRow}")->getFont()->setBold(true);

            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function export()
{
    return Excel::download(new GajiExport, 'data_gaji.xlsx');
}
}
