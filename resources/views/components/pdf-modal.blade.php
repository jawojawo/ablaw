<div class="modal fade modal-lg" id="pdfViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered position-relative" style="height:calc(100% - 56px)">
        <div class="modal-content h-100">
            <div class="card h-100">
                <div class="card-header">

                </div>
                <div class="card-body h-100 position-relative">
                    <iframe id="pdfIframe" class="h-100 w-100" src=""></iframe>
                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary download-print-btn" id="downloadPdfBtn"
                        disabled>Download</button>
                    <button type="submit" class="btn btn-primary download-print-btn" id="printPdfBtn"
                        disabled>Print</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        let route;
        let header;
        let fileName
        $('#pdfViewModal').on('show.bs.modal', function(e) {
            route = $(e.relatedTarget).data('route')
            header = $(e.relatedTarget).data('header')
            fileName = $(e.relatedTarget).data('file-name')
            $(this).find(".card-header").text(header)
            $("#pdfIframe").parent().append(window.spinnerOverlay)
            $("#pdfIframe").attr("src", route);
            $('iframe').on('load', function() {
                $("#pdfIframe").parent().find(".spinner-overlay").remove();
                $(".download-print-btn").attr("disabled", false)
            });
        });

        $('#pdfViewModal').on('hide.bs.modal', function(e) {
            $("#pdfIframe").attr("src", '');
            $(".download-print-btn").attr("disabled", true)
        })
        $('#printPdfBtn').on('click', function(e) {
            document.getElementById("pdfIframe").contentWindow.print();

        })
        $('#downloadPdfBtn').on('click', function() {
            // const downloadUrl = route + '?pdf=download';
            const url = new URL(route);
            url.searchParams.set('pdf', 'download');
            const downloadUrl = url.toString();
            $("#downloadPdfBtn").prop('disabled', true).prepend(window.spinner);
            var req = new XMLHttpRequest();
            req.open("get", downloadUrl, true);
            req.responseType = "blob";
            req.onload = function(event) {
                var blob = req.response;
                if (req.status === 200) {
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = fileName;
                    link.click();
                    $("#downloadPdfBtn").prop('disabled', false).find('.spinner-border').remove();
                }
            };

            req.send();
        });
    });
</script>
