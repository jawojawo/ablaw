<div class="modal fade modal-lg" id="getCourtBranchesModal" tabindex="-1" aria-labelledby="getCourtBranchesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="getCourtBranchesModalLabel">CourtBranches</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="search">Search
                    <div class="input-group mb-4">
                        <select class="form-select" id="search-region">
                            <option selected="selected" value="">All Region</option>

                            @foreach ($regions as $region)
                                <option value="{{ $region->region }}">{{ $region->region }}</option>
                            @endforeach
                        </select>
                        <input type="search" id="search-city" placeholder="City/Municipality" class="form-control">
                        <select class="form-select" id="search-type">
                            <option selected="selected" value="">All Court Type</option>
                            @foreach ($courtTypes as $courtType)
                                <option value="{{ $courtType->type }}">{{ $courtType->type }}</option>
                            @endforeach
                        </select>
                        <input type="search" id="search-branch" placeholder="Branch" class="form-control">
                        <button class="btn btn-primary px-3 search-btn" type="button"><i
                                class="bi bi-search"></i></button>
                    </div>
                </div>

                <table id ="courtBranch-list" class="table table-striped table-bordered table-hover">
                    <thead class="text-capitalize">
                        <tr>
                            <th>#</th>
                            <th>region/province</th>
                            <th>city/municipality</th>
                            <th>court type</th>
                            <th>branch</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <ul class="pagination"></ul>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let hearingCardId;

        function loadCourtBranches(page = 1, region = '', city = '', type = '', branch = '') {
            $.ajax({
                url: `{{ route('ajax.court-branches') }}?page=${page}`,
                method: 'GET',
                data: {
                    region: region,
                    city: city,
                    type: type,
                    branch: branch
                },
                success: function(data) {
                    $('#courtBranch-list tbody').empty();
                    $('#getCourtBranchesModal .pagination').empty();
                    if (data.courtBranches.data.length === 0) {
                        $('#courtBranch-list tbody').append(
                            '<div class="list-group-item text-danger text-center">No Court Branches Found</div>'
                        );
                    } else {
                        $.each(data.courtBranches.data, function(index, courtBranch) {
                            $('#courtBranch-list tbody').append(`
                                <tr class="courtBranch-row" data-courtBranch='${JSON.stringify(courtBranch)}'>
                                    <td>${courtBranch.id}</td>
                                    <td>${courtBranch.region}</td>
                                    <td>${courtBranch.city}</td>
                                    <td>${courtBranch.type}</td>
                                    <td>${courtBranch.branch}</td>
                                </tr>
                            `);
                        });
                    }

                    if (data.courtBranches.links) {
                        $.each(data.courtBranches.links, function(index, link) {
                            link.label = link.label.replace('&laquo; Previous', '‹');
                            link.label = link.label.replace('Next &raquo;', '›');
                            if (link.url) {
                                $('#getCourtBranchesModal .pagination').append(`
                                    <li class="page-item">
                                        <a href="#" class="page-link ${link.active ? 'active' : ''}" data-page="${link.url.split('page=')[1]}">
                                            ${link.label}
                                        </a>
                                    </li>
                                `);
                            } else {
                                $('#getCourtBranchesModal .pagination').append(
                                    `<li class="page-item disabled" aria-disabled="true"><span class="page-link">${link.label}</span></li>`
                                );
                            }
                        });
                    }
                },
                error: function() {
                    $('#courtBranch-list tbody').append(
                        '<div class="list-group-item text-danger">Error loading court branches</div>'
                    );
                }
            });
        }

        $('#getCourtBranchesModal').on('show.bs.modal', function(e) {
            // Clear any previous search inputs
            $('#search-region').val('');
            $('#search-city').val('');
            $('#search-type').val('');
            $('#search-branch').val('');
            loadCourtBranches();
        });

        // Pagination links
        $(document).on('click', '#getCourtBranchesModal .page-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            var region = $('#search-region').val();
            var city = $('#search-city').val();
            var type = $('#search-type').val();
            var branch = $('#search-branch').val();
            loadCourtBranches(page, region, city, type, branch);
        });

        // Search button click
        $('#getCourtBranchesModal .search-btn').on('click', function() {
            var region = $('#search-region').val();
            var city = $('#search-city').val();
            var type = $('#search-type').val();
            var branch = $('#search-branch').val();
            loadCourtBranches(1, region, city, type, branch);
        });

        // Row click event
        $(document).on('click', '.courtBranch-row', function() {
            var courtBranchData = $(this).attr('data-courtBranch');
            var courtBranch = JSON.parse(courtBranchData);
            selectCourtBranch(courtBranch, hearingCardId);
            $("#getCourtBranchesModal").modal('hide');
        });
        $(document).on("click", ".courtBranchSearch", function() {
            hearingCardId = $(this).data('id')
        });
    });
</script>
