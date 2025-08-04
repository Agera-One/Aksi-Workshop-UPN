<div>
<form wire:submit.prevent="tambah">
  <input wire:model="judul" type="text">
  <button type="submit">Tambah</button>
</form>

<ul>
  <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $todos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li><?php echo e($todo->judul); ?></li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
</ul>
</div><?php /**PATH C:\laragon\www\workshop-tall\resources\views/livewire/todo-list.blade.php ENDPATH**/ ?>