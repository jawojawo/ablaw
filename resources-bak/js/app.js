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
    $(".date-time-picker").flatpickr({
        altInput: true,
        altFormat: "M j, Y h:i K",
        dateFormat: "Y-m-d H:i",
        defaultDate: "today",
        enableTime: true,
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
})();
