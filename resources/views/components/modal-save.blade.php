<!-- resources/views/components/modal.blade.php -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="confirmModalLabel">Konfirmasi Penyimpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center text-center">
                <i class="bi bi-exclamation-triangle modal-danger-save mb-3"></i>
                <p>Apakah Anda yakin ingin menyimpan data transaksi ini secara permanen?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gray-800 text-white hover:bg-gray-400 hover:text-black" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn bg-red-800 text-white hover:bg-red-400 hover:text-black	" id="confirmSaveBtn">Ya, Simpan</button>
            </div>
        </div>
    </div>
</div>
