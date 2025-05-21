<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PemesananRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'tujuan' => ['required', 'string', 'max:255', 'min:5'],
            'keperluan' => ['required', 'string', 'max:1000', 'min:10'],
            'jumlah_penumpang' => ['required', 'integer', 'min:1'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ];

        // Tambahan validasi: jika kendaraan diinput, kapasitas kendaraan harus cukup
        if ($this->filled('kendaraan_id') && $this->filled('jumlah_penumpang')) {
            $rules['jumlah_penumpang'][] = function ($attribute, $value, $fail) {
                $kendaraan = \App\Models\Kendaraan::find($this->input('kendaraan_id'));
                if ($kendaraan && $value > $kendaraan->kapasitas) {
                    $fail("Jumlah penumpang melebihi kapasitas kendaraan ({$kendaraan->kapasitas} orang).");
                }
            };
        }

        // Validasi konflik jadwal (kecuali untuk update pada pemesanan yang sama)
        $pemesananId = $this->route('pemesanan') ? $this->route('pemesanan')->id : null;
        
        if ($this->filled('tanggal_mulai') && $this->filled('tanggal_selesai') && $this->filled('kendaraan_id')) {
            $rules['kendaraan_id'][] = function ($attribute, $value, $fail) use ($pemesananId) {
                $tanggalMulai = \Carbon\Carbon::parse($this->input('tanggal_mulai'));
                $tanggalSelesai = \Carbon\Carbon::parse($this->input('tanggal_selesai'));
                
                $query = \App\Models\Pemesanan::where('kendaraan_id', $value)
                    ->where(function($query) use($tanggalMulai, $tanggalSelesai) {
                        $query->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                            ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                            ->orWhere(function($q) use($tanggalMulai, $tanggalSelesai) {
                                $q->where('tanggal_mulai', '<=', $tanggalMulai)
                                    ->where('tanggal_selesai', '>=', $tanggalSelesai);
                            });
                    })
                    ->where('status', '!=', 'ditolak');
                
                if ($pemesananId) {
                    $query->where('id', '!=', $pemesananId);
                }
                
                if ($query->exists()) {
                    $fail('Kendaraan ini sudah dipesan pada waktu tersebut.');
                }
            };
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kendaraan_id.required' => 'Kendaraan harus dipilih.',
            'kendaraan_id.exists' => 'Kendaraan yang dipilih tidak valid.',
            'driver_id.exists' => 'Driver yang dipilih tidak valid.',
            'tujuan.required' => 'Tujuan harus diisi.',
            'tujuan.min' => 'Tujuan minimal 5 karakter.',
            'tujuan.max' => 'Tujuan maksimal 255 karakter.',
            'keperluan.required' => 'Keperluan harus diisi.',
            'keperluan.min' => 'Keperluan minimal 10 karakter.',
            'keperluan.max' => 'Keperluan maksimal 1000 karakter.',
            'jumlah_penumpang.required' => 'Jumlah penumpang harus diisi.',
            'jumlah_penumpang.integer' => 'Jumlah penumpang harus berupa angka.',
            'jumlah_penumpang.min' => 'Jumlah penumpang minimal 1 orang.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh tanggal yang sudah lewat.',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ];
    }
}
