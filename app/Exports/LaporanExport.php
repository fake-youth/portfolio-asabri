<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $data;
    protected $type;

    public function __construct(Collection $data, string $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Return collection of data
     */
    public function collection()
    {
        return $this->data->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Judul' => $item->judul,
                'Tanggal Laporan' => $item->tanggal_laporan->format('d/m/Y'),
                'Diupload Oleh' => $item->uploader->name,
                'Tanggal Upload' => $item->created_at->format('d/m/Y H:i'),
                'File Path' => $item->file_path,
            ];
        });
    }

    /**
     * Define headings
     */
    public function headings(): array
    {
        return [
            'No',
            'Judul Laporan',
            'Tanggal Laporan',
            'Diupload Oleh',
            'Tanggal Upload',
            'File Path',
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '003A70']
                ],
            ],
        ];
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        return ucfirst($this->type);
    }
}

// Command untuk install: composer require maatwebsite/excel