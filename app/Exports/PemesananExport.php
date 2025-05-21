<?php

namespace App\Exports;

use App\Models\Pemesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemesananExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $status;
    
    protected $isTemplate;

    public function __construct($startDate = null, $endDate = null, $status = null, $isTemplate = false)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->isTemplate = $isTemplate;
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // If it's a template, return empty collection with a sample row
        if ($this->isTemplate) {
            return collect([
                [
                    'id' => '',
                    'user' => ['name' => '', 'email' => 'email_pengguna@example.com'],
                    'kendaraan' => ['nama' => '', 'nomor_plat' => 'AB 1234 CD'],
                    'driver' => ['nama' => 'Nama Driver (Opsional)'],
                    'tujuan' => 'Bandung',
                    'tanggal_pemesanan' => now(),
                    'tanggal_mulai' => now()->addDay(),
                    'tanggal_selesai' => now()->addDays(2),
                    'status' => 'pending',
                    'catatan' => 'Catatan opsional',
                    'persetujuan' => [
                        [
                            'approver' => ['email' => 'approver@example.com'],
                            'status' => 'pending',
                            'catatan' => '',
                        ]
                    ]
                ]
            ]);
        }
        
        // Otherwise, return actual data
        $query = Pemesanan::with(['user', 'kendaraan', 'driver', 'persetujuan']);
        
        if ($this->startDate) {
            $query->whereDate('tanggal_mulai', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('tanggal_selesai', '<=', $this->endDate);
        }
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Pemesan',
            'Email Pemesan',
            'Kendaraan',
            'Nomor Plat',
            'Driver',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Tujuan',
            'Keperluan',
            'Status',
            'Tanggal Pemesanan',
            'Status Persetujuan',
            'Disetujui/Ditolak Pada',
            'Catatan Persetujuan'
        ];
    }
    
    /**
     * @param mixed $pemesanan
     * @return array
     */
    public function map($pemesanan): array
    {
        return [
            $pemesanan->id,
            $pemesanan->user->name,
            $pemesanan->user->email,
            $pemesanan->kendaraan->nama,
            $pemesanan->kendaraan->nomor_plat,
            $pemesanan->driver ? $pemesanan->driver->nama : 'Tidak ada driver',
            $pemesanan->tanggal_mulai->format('d/m/Y H:i'),
            $pemesanan->tanggal_selesai->format('d/m/Y H:i'),
            $pemesanan->tujuan,
            $pemesanan->keperluan,
            $pemesanan->status,
            $pemesanan->created_at->format('d/m/Y H:i'),
            $pemesanan->persetujuan ? $pemesanan->persetujuan->status : 'Belum dibuat',
            $pemesanan->persetujuan && $pemesanan->persetujuan->disetujui_pada ? $pemesanan->persetujuan->disetujui_pada->format('d/m/Y H:i') : '-',
            $pemesanan->persetujuan ? $pemesanan->persetujuan->catatan : '-'
        ];
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
