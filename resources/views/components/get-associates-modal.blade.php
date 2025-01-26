  <div class="modal fade modal-lg" id="getAssociatesModal" tabindex="-1" aria-labelledby="getAssociatesModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="getAssociatesModalLabel">Associates</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="search">Search
                      <div class="input-group mb-4">
                          <input type="search" placeholder="Associate's Name"
                              class="form-control search-name search-inp">
                          <button class="btn btn-primary px-3" id="search-btn" type="button"><i
                                  class="bi bi-search"></i></button>
                      </div>
                  </div>

                  <table id ="associate-list" class="table table-bordered  table-hover">
                      <thead>
                          <tr>
                              <th class="table-td-min text-center">#</th>
                              <th>Name</th>
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
          function loadAssociates(page = 1, name = '') {
              $('#associate-list tbody').html(
                  `<td colspan="100" class="text-danger text-center"><div class="p-4"><div class="spinner-border" role="status">
  <span class="visually-hidden">Loading...</span>
</div></div></td>`
              );
              $.ajax({
                  url: `{{ route('ajax.associates') }}?page=${page}`,
                  method: 'GET',
                  data: {
                      name: name,

                  },
                  success: function(data) {
                      $('#associate-list tbody').empty();
                      $('#getAssociatesModal .pagination').empty();
                      if (data.data.length === 0) {
                          $('#associate-list tbody').append(
                              '<div class="list-group-item text-danger text-center">No Associates Found</div>'
                          );
                      }
                      $.each(data.data, function(index, associate) {
                          $('#associate-list tbody').append(`
                          <tr class="associate-row" data-associate=\'${JSON.stringify(associate).replace(/'/g, '&#39;').replace(/"/g, '&quot;')}\'>
                            <td>${associate.id}</td>
                            <td >
                                ${associate.name}
                            </td>
                        </tr>
                    `);
                      });

                      if (data.links) {
                          $.each(data.links, function(index, link) {
                              link.label = link.label.replace('&laquo; Previous', '‹');
                              link.label = link.label.replace('Next &raquo;', '›');
                              if (link.url) {
                                  $('#getAssociatesModal .pagination').append(`
                                <li class="page-item">
                                <a href="#" class="page-link ${link.active ? 'active':''}" data-page="${link.url.split('page=')[1]}">
                                    ${link.label} 
                                </a>
                                </li>
                            `);
                              } else {
                                  $('#getAssociatesModal .pagination').append(
                                      `<li class="page-item disabled" aria-disabled="true"><span class="page-link">${link.label}</span></li>`
                                  );
                              }
                          });
                      }
                  },
                  error: function() {
                      $('#associate-list tbody').html(
                          '<td colspan="100" class="text-center"><div class="text-danger">Error loading associates</div></td>'
                      );
                  }
              });
          }

          $('#getAssociatesModal').on('show.bs.modal', function(e) {
              $('#getAssociatesModal .search-name').val('');
              loadAssociates();
          });

          $(document).on('click', '.page-link', function(e) {
              e.preventDefault();
              var page = $(this).data('page');
              var name = $('#getAssociatesModal .search-name').val();

              loadAssociates(page, name);
          });
          $('#search-btn').on('click', function() {
              var name = $('#getAssociatesModal .search-name').val();
              loadAssociates(1, name);
          });
          $(document).on('click', '.associate-row', function() {
              var associateData = $(this).attr('data-associate');
              var associate = JSON.parse(associateData);
              selectAssociate(associate);
              $("#getAssociatesModal").modal('hide');
          });

      });
  </script>
