<div class="flex">
    <div class="w-12 flex-1">
    <h1 class="text-blue-500 text-2xl font-bold"><?php echo e($count); ?></h1>
</div>
    <div class="w-128 flex-1">
    <button class="p-1 mt-1 bg-sky-500 px-4 py-2 rounded" wire:click="increment" type>Tambah</button>
</div>
    <div class="w-32 flex-1">
    <button wire:click="decrement">Kurang</button>
</div>
<div class="w-64 flex-1">
    <input type="text" wire:model.live="name"placeholder="Masukkan Nama">
    <p>Nama Anda: <?php echo e($nama); ?></p>
</div>
<div x-data="{ open: <?php if ((object) ('isOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isOpen'->value()); ?>')<?php echo e('isOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isOpen'); ?>')<?php endif; ?> }">
  <button @click="open = !open">Toggle</button>
  <div x-show="open">Panel Tampil</div>
</div>
</div>

<?php /**PATH C:\laragon\www\workshop-tall\resources\views/livewire/counter.blade.php ENDPATH**/ ?>