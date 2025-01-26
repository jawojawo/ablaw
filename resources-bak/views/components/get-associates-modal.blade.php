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
                          <input type="search" placeholder="first name" aria-label="First name"
                              class="form-control search-first-name search-inp">
                          <input type="search" placeholder="last name" aria-label="Last name"
                              class="form-control search-last-name">
                          <button class="btn btn-primary px-3" id="search-btn" type="button"><i
                                  class="bi bi-search"></i></button>
                      </div>
                  </div>

                  <table id ="associate-list" class="table table-striped table-bordered table-hover">
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
          function loadAssociates(page = 1, firstName = '', lastName = '') {
              $.ajax({
                  url: `{{ route('ajax.associates') }}?page=${page}`,
                  method: 'GET',
                  data: {
                      first_name: firstName,
                      last_name: lastName
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
                          <tr class="associate-row" data-associate='${JSON.stringify(associate)}'>
                            <td>${associate.id}</td>
                            <td >
                                ${associate.first_name} ${associate.last_name} ${associate.suffix ? associate.suffix : ''}
                            </td>
                            <td>${associate.email}</td>
                            <td><span class="text-secondary">+63</span> ${associate.phone}</td>
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
                      $('#associate-list tbody').append(
                          '<div class="list-group-item text-danger">Error loading associates</div>'
                      );
                  }
              });
          }

          $('#getAssociatesModal').on('show.bs.modal', function(e) {
              $('#getAssociatesModal .search-first-name').val('');
              $('#getAssociatesModal .search-last-name').val('');
              loadAssociates();
          });

          $(document).on('click', '.page-link', function(e) {
              e.preventDefault();
              var page = $(this).data('page');
              var firstName = $('#getAssociatesModal .search-first-name').val();
              var lastName = $('#getAssociatesModal .search-last-name').val();
              loadAssociates(page, firstName, lastName);
          });
          $('#search-btn').on('click', function() {
              var firstName = $('#getAssociatesModal .search-first-name').val();
              var lastName = $('#getAssociatesModal .search-last-name').val();
              loadAssociates(1, firstName, lastName);
          });
          $(document).on('click', '.associate-row', function() {
              var associateData = $(this).attr('data-associate');
              var associate = JSON.parse(associateData);
              selectAssociate(associate);
              $("#getAssociatesModal").modal('hide');
          });

      });
  </script>
