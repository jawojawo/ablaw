import "./bootstrap";
import * as bootstrap from "bootstrap";
import * as Popper from "@popperjs/core";
import Inputmask from "inputmask";

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
(() => {
    "use strict";

    const forms = document.querySelectorAll(".needs-validation");

    Array.from(forms).forEach((form) => {
        form.addEventListener(
            "submit",
            (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add("was-validated");
            },
            false
        );
    });
    $(".date-picker").flatpickr({
        altInput: true,
        altFormat: "M j, Y",
        dateFormat: "Y-m-d",
        defaultDate: "today",
    });
    $(".date-picker-default").flatpickr({
        altInput: true,
        altFormat: "M j, Y",
        dateFormat: "Y-m-d",
    });
    $(".date-time-picker-default").flatpickr({
        altInput: true,
        altFormat: "M j, Y h:i K",
        dateFormat: "Y-m-d H:i",
        enableTime: true,
    });
    $(".date-time-picker").flatpickr({
        altInput: true,
        altFormat: "M j, Y h:i K",
        dateFormat: "Y-m-d H:i",
        defaultDate: "today",
        enableTime: true,
    });

    $(".date-range-picker").flatpickr({
        mode: "range",
        altInput: true,
        altFormat: "M j, Y",
        dateFormat: "Y-m-d",
    });

    $(".popover-hover").each(function () {
        $(this).popover({
            trigger: "hover",
            placement: "top",
            html: true,
            container: $(this).closest(".has-popover"),
        });
    });

    $(".popover-click").popover({
        sanitizeFn: (content) => {
            const defaultWhitelist = {
                ...bootstrap.Tooltip.Default.allowList,
                table: [],
                tr: [],
                td: [],
                th: [],
            };

            return sanitizeHtml(content, {
                allowedTags: Object.keys(defaultWhitelist),
            });
        },
        html: true,
        container: "main",
        sanitize: false,
        trigger: "focus",
        //placement: "top",
        html: true,
    });
    $(".popover-th").each(function () {
        const el = $(this);
        $(this).popover({
            html: true,
            trigger: "focus",
            placement: "top",
            container: el.closest("th"),
        });
    });
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    // $(document).ready(function () {
    Inputmask({
        alias: "currency",
        groupSeparator: ",",
        digits: 2,
        rightAlign: false,

        removeMaskOnSubmit: true,
    }).mask(".input-money");
    // });
    $(document).on("shown.bs.dropdown", ".table .dropdown", function (e) {
        const $parentRow = $(this).closest("tr");
        $parentRow.addClass("tr-current").siblings("tr").addClass("tr-blur");
    });

    $(document).on("hide.bs.dropdown", ".table .dropdown", function (e) {
        $("tr").removeClass("tr-current tr-blur");
    });

    const $accordion = $("#caseAccord");
    const $accordionBtn = $(".accordion-button.cases");
    const storageKey = "caseAccordState";

    // Restore accordion state from localStorage
    const savedState = localStorage.getItem(storageKey);
    if (savedState === "open") {
        $accordion.addClass("show");
        $accordionBtn.removeClass("collapsed");
    } else {
        $accordion.removeClass("show");
        $accordionBtn.addClass("collapsed");
    }

    // Listen for accordion toggle and save the state
    $accordion.on("hidden.bs.collapse", function () {
        localStorage.setItem(storageKey, "closed");
    });

    $accordion.on("shown.bs.collapse", function () {
        localStorage.setItem(storageKey, "open");
    });

    $(document).on("click", ".contacts-con .remove-contact-form", function (e) {
        const contactFormCon = $(this).closest(".contact-form-con");

        contactFormCon.fadeOut(200, (e) => {
            contactFormCon.remove();
        });
    });
    $(document).on("click", ".contact-type-select", function (e) {
        const value = $(this).data("value");
        $(this)
            .closest("ul")
            .find("input[name='contact_type[]'],input[name='contact_type']")
            .val(value);
        $(this)
            .closest(".btn-group")
            .find(".phone-email-select")
            .html(
                value == "phone"
                    ? '<i class="bi bi-phone"></i>'
                    : '<i class="bi bi-envelope"></i>'
            );
    });
    // $(document).on("click", ".add-contact-btn", function (e) {
    //     const container = $(this).closest(".contacts-con");
    //     const contactForm = $(`
    //         <li class="list-group-item contact-form-con">
    //         <div class="  ">
    //             <div class="card-header text-bg-primary d-flex justify-content-between">
    //                 <span>Contact Info</span>
    //                <button type="button" class="btn-close btn-close-white remove-contact-form" aria-label="Close"></button>
    //             </div>
    //             <div class="card-body">
    //                 <div class="mb-2">
    //                     <div class="input-group mb-3">
    //                         <span class="input-group-text p-0">
    //                             <div class="btn-group">
    //                                 <button type="button" class="btn dropdown-toggle phone-email-select" data-bs-toggle="dropdown" aria-expanded="false">
    //                                     <i class="bi bi-phone"></i>
    //                                 </button>
    //                                 <ul class="dropdown-menu text-center" style="min-width:60px !important">
    //                                     <li><a class="dropdown-item contact-type-select phone d-flex justify-content-between align-items-center" href="#" data-value="phone"><i class="bi bi-phone pe-2"></i> <span>Phone</span></a></li>
    //                                     <li><a class="dropdown-item contact-type-select email d-flex justify-content-between align-items-center" href="#" data-value="email"><i class="bi bi-envelope pe-2"></i><span>Email</span></a></li>
    //                                     <input type="hidden" name="contact_type[]" value="phone">
    //                                 </ul>
    //                             </div>
    //                         </span>
    //                         <input type="text" class="form-control" placeholder="Value" name="contact_value[]" required>
    //                         <div class="invalid-feedback ">
    //                             Contact Value is required.
    //                         </div>
    //                     </div>
    //                 </div>
    //                 <div>
    //                     <input type="text" class="form-control" name="contact_label[]" placeholder="Label" />
    //                 </div>
    //             </div>
    //         </div>
    //     </li>
    //         `);
    $(document).on("click", ".add-contact-btn", function (e) {
        const container = $(this).closest(".card").find(".contacts-con");
        const contactForm = $(`
            <li class="list-group-item contact-form-con position-relative">
                <div>
                    <div>
                        <div class="input-group mb-2">
                            <span class="input-group-text p-0">
                                <div class="btn-group">
                                    <button type="button" class="btn dropdown-toggle phone-email-select" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-phone"></i>
                                    </button>
                                    <ul class="dropdown-menu text-center" style="min-width:60px !important">
                                        <li><a class="dropdown-item contact-type-select phone d-flex justify-content-between align-items-center" href="#" data-value="phone"><i class="bi bi-phone pe-2"></i> <span>Phone</span></a></li>
                                        <li><a class="dropdown-item contact-type-select email d-flex justify-content-between align-items-center" href="#" data-value="email"><i class="bi bi-envelope pe-2"></i><span>Email</span></a></li>
                                        <input type="hidden" name="contact_type[]" value="phone">
                                    </ul>
                                </div>
                            </span>
                            <input type="text" class="form-control" placeholder="Value" name="contact_value[]" required>
                            <span class="input-group-text p-0 text-bg-danger">
                                <button type="button" class="remove-contact-form btn text-white">
                                    <i class="bi bi-x"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div>
                        <input type="text" class="form-control" name="contact_label[]" placeholder="Label" />
                    </div>
                </div>
        </li>
            `);
        contactForm.hide();

        container.append(contactForm);
        contactForm.fadeIn();
    });
    window.dataListInputInit = (input) => {
        // Create the <ul> element dynamically
        const dataListContainer = document.createElement("ul");
        dataListContainer.classList.add("custom-datalist-options");
        dataListContainer.classList.add("shadow-lg");
        dataListContainer.style.display = "none";
        //console.log(input);
        // input.parentNode.insertBefore(dataListContainer, $(".editable-input"));
        input.parentNode.insertBefore(dataListContainer, input.nextSibling);

        const dataList = JSON.parse(input.dataset.datalist || "[]");

        // Populate the <ul> with the datalist options
        dataList.forEach((item) => {
            const li = document.createElement("li");
            li.textContent = item;
            li.classList.add("custom-datalist-item");
            dataListContainer.appendChild(li);

            // Add click event for selection
            li.addEventListener("click", () => {
                input.value = li.textContent;
                dataListContainer.style.display = "none";
            });
        });

        // Show/hide the datalist options
        input.addEventListener("focus", () => {
            dataListContainer.style.display = "block";
        });

        input.addEventListener("blur", () => {
            setTimeout(() => (dataListContainer.style.display = "none"), 200); // Delay to allow click
        });

        // Filter the options based on user input
        input.addEventListener("input", () => {
            const filter = input.value.toLowerCase();
            Array.from(dataListContainer.children).forEach((li) => {
                if (li.textContent.toLowerCase().includes(filter)) {
                    li.style.display = "";
                } else {
                    li.style.display = "none";
                }
            });
        });
    };
    document.querySelectorAll(".custom-datalist-input").forEach((input) => {
        window.dataListInputInit(input);
    });
    // document
    //     .querySelectorAll(".visually-toggling")
    //     .forEach(function (checkbox) {
    //         const label = document.querySelector(`label[for="${checkbox.id}"]`);
    //         label.addEventListener("click", function (e) {
    //             e.preventDefault();

    //             // Toggle the visual class on the label
    //             const currentClass = Array.from(label.classList).find(
    //                 (cls) =>
    //                     cls.startsWith("btn-outline-") || cls.startsWith("btn-")
    //             );

    //             if (currentClass) {
    //                 if (currentClass.startsWith("btn-outline-")) {
    //                     // If it's an outline style, toggle to the filled variant
    //                     const filledClass = currentClass.replace(
    //                         "btn-outline-",
    //                         "btn-"
    //                     );
    //                     label.classList.remove(currentClass);
    //                     label.classList.add(filledClass);
    //                 } else if (currentClass.startsWith("btn-")) {
    //                     // If it's a filled style, toggle to the outline variant
    //                     const outlineClass = currentClass.replace(
    //                         "btn-",
    //                         "btn-outline-"
    //                     );
    //                     label.classList.remove(currentClass);
    //                     label.classList.add(outlineClass);
    //                 }
    //             }

    //             // Optionally, log the actual state for debugging
    //             checkbox.checked = !checkbox.checked;
    //             console.log(
    //                 checkbox.id,
    //                 " Checkbox actual state:",
    //                 checkbox.checked
    //             );
    //         });
    //     });
})();
