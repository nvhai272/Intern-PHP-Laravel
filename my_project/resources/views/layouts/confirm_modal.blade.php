<div class="modal fade" id="{{ $modalId ?? 'confirmModal' }}" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="modalConfirmForm" onsubmit="event.preventDefault(); document.getElementById('{{ $form }}').submit();">
          <div class="modal-header">
            <h5 class="modal-title">{{ $title ?? 'Xác nhận' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>{{ $slot ?? 'Bạn chắc không?' }}</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Confirm</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
