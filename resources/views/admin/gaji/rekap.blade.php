<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Rekap Gaji Karyawan') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
        <div class="p-6 lg:p-8">

          {{-- Filter Bulan & Tahun --}}
          <form method="GET" action="{{ route('admin.gaji.rekap') }}" class="mb-6 flex gap-4">
            <div>
              <label class="block text-sm text-gray-700 dark:text-gray-300">Bulan</label>
              <select name="bulan" class="mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @for ($m = 1; $m <= 12; $m++)
                  <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                  </option>
                @endfor
              </select>
            </div>

            <div>
              <label class="block text-sm text-gray-700 dark:text-gray-300">Tahun</label>
              <select name="tahun" class="mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @for ($y = now()->year - 5; $y <= now()->year; $y++)
                  <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>
                    {{ $y }}
                  </option>
                @endfor
              </select>
            </div>

            <div class="flex items-end">
              <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Tampilkan
              </button>
            </div>
          </form>

          {{-- Tabel Rekap --}}
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">No</th>
                  <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Nama</th>
                  <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Divisi</th>
                  <th class="px-4 py-2 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Total Gaji</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($rekap as $index => $r)
                  <tr>
                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $index+1 }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $r['nama'] }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $r['divisi'] ?? '-' }}
                    <br>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $r['jabatan'] ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-2 text-sm text-right text-gray-900 dark:text-gray-100">
                      Rp {{ number_format($r['total_gaji'], 0, ',', '.') }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                      Tidak ada data gaji untuk periode ini.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>
