@if ($type == 'error')
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 my-2 rounded relative alert-box" role="alert">
        <strong class="font-bold">Error: </strong>
        <span class="block sm:inline">{{ $message }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer close-alert">
            &times;
        </span>
    </div>
@elseif ($type == 'success')
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 my-2 rounded relative alert-box" role="alert">
        <strong class="font-bold">Sukses: </strong>
        <span class="block sm:inline">{{ $message }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer close-alert">
            &times;
        </span>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.close-alert').forEach(function (btn) {
            btn.addEventListener('click', function () {
                btn.parentElement.style.display = 'none';
            });
        });
    });
</script>

