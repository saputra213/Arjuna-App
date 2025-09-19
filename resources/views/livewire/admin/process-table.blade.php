@if($order)
<div>
  <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
    Proses Order: {{ $order->brand }} ({{ $order->jumlah_order }} pcs)
  </h2>

  <table class="w-full border border-gray-200 dark:border-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-900">
      <tr>
        <th class="px-4 py-2">Tanggal</th>
        <th class="px-4 py-2">Departemen</th>
        <th class="px-4 py-2">Target</th>
        <th class="px-4 py-2">Output</th>
        <th class="px-4 py-2">Status</th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      @foreach($processes as $p)
      <tr>
        <td class="px-4 py-2">{{ $p->tanggal }}</td>
        <td class="px-4 py-2">{{ ucfirst($p->departemen) }}</td>
        <td class="px-4 py-2 text-center">{{ $p->target_harian }}</td>
        <td class="px-4 py-2 text-center">
          <input type="number"
            wire:change="updateOutput({{ $p->id }}, $event.target.value)"
            value="{{ $p->output_harian }}"
            class="w-20 rounded-md border-gray-300 text-center dark:bg-gray-700 dark:text-white" />
        </td>
        <td class="px-4 py-2 text-center">
          @if($p->status === 'ok')
            <span class="bg-green-200 text-green-800 px-2 py-1 rounded">✅ Sesuai</span>
          @else
            <span class="bg-red-200 text-red-800 px-2 py-1 rounded">❌ Minus</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
