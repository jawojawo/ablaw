@php
    $uniqueId = Str::uuid()->toString();
@endphp
<div class="mb-2">
    <div class="nav-item">
        <span>Note</span>
        <button class="btn btn-primary collapse-btn" type="button" data-bs-toggle="collapse"
            data-bs-target="#{{ $uniqueId }}" aria-expanded="false">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>
</div>
<div class="row mb-4 collapse" id = "{{ $uniqueId }}">
    <div class=" col-12">
        <div class="card">
            <div class="card-body ">
                <div class="row notes-con">
                    <div class="col-12 pb-2 ">
                        <div class="rounded position-relative add-note-btn" style="height:50px">
                            <div class="position-absolute top-50 start-50 translate-middle"><i
                                    class="bi bi-plus-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
