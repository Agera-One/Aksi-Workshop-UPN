<div>
<form wire:submit.prevent="tambah">
  <input wire:model="judul" type="text">
  <button type="submit">Tambah</button>
</form>

<ul>
  @foreach($todos as $todo)
    <li>{{ $todo->judul }}</li>
  @endforeach
</ul>
</div>