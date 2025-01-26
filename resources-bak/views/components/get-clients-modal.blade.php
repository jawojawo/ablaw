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
                          <input type="search" placeholder="first name" aria-label="First name"
                              class="form-control search-first-name">
                          <input type="search" placeholder="last name" aria-label="Last name"
                              class="form-control search-last-name">
                          <button class="btn btn-primary px-3 search-btn" type="button"><i
                                  class="bi bi-search"></i></button>
                      </div>
                  </div>

                  <table id ="client-list" class="table table-striped table-bordered table-hover">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone</th>
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
          function loadClients(page = 1, firstName = '', lastName = '') {
              $.ajax({
                  url: `{{ route('ajax.clients') }}?page=${page}`,
                  method: 'GET',
                  data: {
                      first_name: firstName,
                      last_name: lastName
                  },
                  success: function(data) {
                      $('#client-list tbody').empty();
                      $('#getClientsModal .pagination').empty();
                      if (data.data.length === 0) {
                          $('#client-list tbody').append(
                              '<div class="list-group-item text-danger text-center">No Clients Found</div>'
                          );
                      }
                      $.each(data.data, function(index, client) {
                          $('#client-list tbody').append(`
                          <tr class="client-row" data-client='${JSON.stringify(client)}'>
                            <td>${client.id}</td>
                            <td >
                                ${client.first_name} ${client.last_name} ${client.suffix ? client.suffix : ''}
                            </td>
                            <td>${client.email}</td>
                            <td><span class="text-secondary">+63</span> ${client.phone}</td>
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
                      $('#client-list tbody').append(
                          '<div class="list-group-item text-danger">Error loading clients</div>');
                  }
              });
          }

          $('#getClientsModal').on('show.bs.modal', function(e) {
              $('#getClientsModal .search-first-name').val('');
              $('#getClientsModal .search-last-name').val('');
              loadClients();
          });

          $(document).on('click', '#getClientsModal .page-link', function(e) {
              e.preventDefault();
              var page = $(this).data('page');
              var firstName = $('#getClientsModal .search-first-name').val();
              var lastName = $('#getClientsModal .search-last-name').val();
              loadClients(page, firstName, lastName);
          });
          $('#getClientsModal .search-btn').on('click', function() {
              var firstName = $('#getClientsModal .search-first-name').val();
              var lastName = $('#getClientsModal .search-last-name').val();
              loadClients(1, firstName, lastName);
          });
          $(document).on('click', '#getClientsModal .client-row', function() {
              var clientData = $(this).attr('data-client');
              var client = JSON.parse(clientData);
              selectClient(client);
              $("#getClientsModal").modal('hide');
          });

      });
  </script>
