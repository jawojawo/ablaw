@props(['refresh' => false])
<div class="modal fade" id="ViewBillingDeposistModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered " style="max-width:700px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="capture">
                    <span class="billingIdHeader  text-primary  px-3"></span> Bill
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="billingDepositsTable">
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $('#ViewBillingDeposistModal').on('show.bs.modal', function(e) {
        var id = $(e.relatedTarget).data('billing-id')
        $(this).find('.billingIdHeader').text('#' + id)

        //const exportButton = $('#exportPdfBillingBtn');
        //exportButton.prop('disabled', true);

        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings.url.includes('billing-deposits')) {
                // exportButton.prop('disabled',
                //     false);
                $(document).off('ajaxComplete');
            }
        });


        window.fetchAjaxTable('billing-deposits', '#billingDepositsTable', 1, false, id);
    });
</script>
