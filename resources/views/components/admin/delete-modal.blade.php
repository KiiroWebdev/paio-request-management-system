<div class="modal fade"
     id="{{ $id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-trash text-danger"></i>

                    Delete Confirmation

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                {{ $slot }}

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <form action="{{ $action }}"
                      method="POST">

                    @csrf

                    @method('DELETE')

                    <button
                        class="btn btn-danger">

                        <i class="bi bi-trash"></i>

                        Delete

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>