<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Rekap Gaji') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
        <div class="p-6 lg:p-8">
          
          {{-- ✅ Tombol + Form Persentase dalam satu baris --}}
          <div class="flex gap-3 items-end mb-6 flex-wrap">
            <a href="{{ route('admin.gaji.create') }}" 
               class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
              + Tambah Gaji
            </a>

            <form action="{{ route('admin.gaji.generate') }}" method="POST">
              @csrf
              <button type="submit" 
                      class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                  Rekap Gaji Karyawan
              </button>
            </form>

            {{-- ✅ Form Persentase Gaji --}}
            <form action="{{ route('admin.gaji.persentase') }}" method="POST" class="flex items-end gap-2">
              @csrf
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Persentase</label>
                <input type="number" name="persentase" 
                       value="{{ old('persentase', $gaji->first()->persentase ?? 100) }}" 
                       min="0" max="100"
                       class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm 
                              focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm 
                              dark:bg-gray-700 dark:text-white">
              </div>
              <input type="hidden" name="periode" value="{{ $gaji->first()->periode ?? '' }}">
              <button type="submit" 
                      class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Terapkan
              </button>
            </form>
          </div>

          {{-- ✅ Tabel Rekap Gaji --}}
          <table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
              <tr>
                <th class="border px-4 py-2">No</th>
                <th class="border px-4 py-2">Nama</th>
                <th class="border px-4 py-2">Periode</th>
                <th class="border px-4 py-2">Divisi & Jabatan</th>
                <th class="border px-4 py-2">Gaji Pokok</th>
                <th class="border px-4 py-2">Tunjangan</th>
                <th class="border px-4 py-2">Potongan</th>
                <th class="border px-4 py-2">Persentase</th>
                <th class="border px-4 py-2">Total</th>
                <th class="border px-4 py-2">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($gaji as $row)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                <td class="border px-4 py-2">{{ $row->user->name }}</td>
                <td class="border px-4 py-2">{{ $row->periode }}</td>
                <td class="border px-4 py-2">
                    {{ $row->user->division->name ?? '-' }} – 
                    {{ $row->user->jobTitle->name ?? '-' }}
                </td>
                <td class="border px-4 py-2">Rp {{ number_format($row->gaji_pokok, 0, ',', '.') }}</td>
                <td class="border px-4 py-2">Rp {{ number_format($row->tunjangan, 0, ',', '.') }}</td>
                <td class="border px-4 py-2">Rp {{ number_format($row->potongan, 0, ',', '.') }}</td>
                <td class="border px-4 py-2">{{ $row->persentase }}%</td>
                <td class="border px-4 py-2 font-semibold">
                  @if($row->total_gaji < 0)
                      <span class="text-red-600">- Rp {{ number_format(abs($row->total_gaji), 0, ',', '.') }}</span>
                  @else
                      Rp {{ number_format($row->total_gaji, 0, ',', '.') }}
                  @endif
                </td>
                <td class="border px-4 py-2">
                  <a href="{{ route('admin.gaji.edit', $row->id) }}" 
                     class="px-2 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Edit</a>
                  <form action="{{ route('admin.gaji.destroy', $row->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-2 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700" 
                            onclick="return confirm('Yakin hapus?')">
                      Hapus
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
