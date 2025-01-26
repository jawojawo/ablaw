  <div class="modal fade modal-lg" id="getClientsModal" tabindex="-1" aria-labelledby="getClientsModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="getClientsModalLabel">Clients</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="search">Search
                      <div class="input-group mb-4">
                          <input type="search" placeholder="Client's Name" class="form-control search-name">

                          <button class="btn btn-primary px-3 search-btn" type="button"><i
                                  class="bi bi-search"></i></button>
                      </div>
                  </div>

                  <table id ="client-list" class="table table-bordered table-hover">
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
          function loadClients(page = 1, name = '') {
              $('#client-list tbody').html(
                  `<td colspan="100" class="text-danger text-center"><div class="p-4"><div class="spinner-border" role="status">
  <span class="visually-hidden">Loading...</span>
</div></div></td>`
              );
              $.ajax({
                  url: `{{ route('ajax.clients') }}?page=${page}`,
                  method: 'GET',
                  data: {
                      name: name,
                  },
                  success: function(data) {
                      $('#client-list tbody').empty();
                      $('#getClientsModal .pagination').empty();
                      if (data.data.length === 0) {
                          $('#client-list tbody').append(
                              '<td colspan="100" class="text-danger text-center"><div class="p-4">No Clients Found</div></td>'
                          );
                      }
                      $.each(data.data, function(index, client) {
                          $('#client-list tbody').append(`
                            <tr class="client-row" data-client=\'${JSON.stringify(client).replace(/'/g, '&#39;').replace(/"/g, '&quot;')}\'>
                                <td class="text-center">${client.id}</td>
                                <td>
                                    ${client.name}
                                </td>
                               
                            </tr>
                        `);
                      });

                      if (data.links) {
                          $.each(data.links, function(index, link) {
                              link.label = link.label.replace('&laquo; Previous', '‹');
                              link.label = link.label.replace('Next &raquo;', '›');
                              if (link.url) {
                                  $('#getClientsModal .pagination').append(`
                                <li class="page-item">
                                <a href="#" class="page-link ${link.active ? 'active':''}" data-page="${link.url.split('page=')[1]}">
                                    ${link.label} 
                                </a>
                                </li>
                            `);
                              } else {
                                  $('#getClientsModal .pagination').append(
                                      `<li class="page-item disabled" aria-disabled="true"><span class="page-link">${link.label}</span></li>`
                                  );
                              }
                          });
                      }
                  },
                  error: function() {
                      $('#client-list tbody').html(
                          '<td colspan="100" class="text-center"><div class="text-danger">Error loading clients</div></td>'
                      );
                  }
              });
          }

          $('#getClientsModal').on('show.bs.modal', function(e) {
              $('#getClientsModal .search-name').val('');
              loadClients();
          });

          $(document).on('click', '#getClientsModal .page-link', function(e) {
              e.preventDefault();
              var page = $(this).data('page');
              var name = $('#getClientsModal .search-name').val();
              loadClients(page, name);
          });
          $('#getClientsModal .search-btn').on('click', function() {
              var name = $('#getClientsModal .search-name').val();
              loadClients(1, name);
          });

          $(document).on('click', '#getClientsModal .client-row', function() {
              var clientData = $(this).data('client');
              console.log(clientData)
              //var client = JSON.parse(clientData);
              selectClient(clientData);
              $("#getClientsModal").modal('hide');
          });

      });
  </script>
