import { v4 as uuidv4 } from "uuid";
import * as bootstrap from "bootstrap";
// window.updateHiddenValue = (element) => {
//     const inputGroup = element.closest(".money-group-con");
//     if (inputGroup) {
//         const wholeInput = inputGroup.querySelector(".whole-part");
//         const decimalInput = inputGroup.querySelector(".decimal-part");
//         const hiddenInput = inputGroup.querySelector(".hiddenMoneyInp");
//         if (wholeInput && decimalInput && hiddenInput) {
//             let wholeValue = parseInt(wholeInput.value) || 0;
//             let decimalValue = parseInt(decimalInput.value) || 0;
//             decimalValue = ("0" + decimalValue).slice(-2);
//             hiddenInput.value = `${wholeValue}.${decimalValue}`;
//         }
//     }
// };
window.getRandom = () => {
    return uuidv4();
};
window.reInitDatePicker = (container) => {
    const lastPicker = $(`${container} .addFormCon`)
        .last()
        .find(".date-picker");

    if (lastPicker.hasClass("date-time-picker")) {
        lastPicker.flatpickr({
            altInput: true,
            altFormat: "M j, Y h:i K",
            dateFormat: "Y-m-d H:i",
            defaultDate: "today",
            enableTime: true,
        });
    } else {
        lastPicker.flatpickr({
            altInput: true,
            altFormat: "M j, Y",
            dateFormat: "Y-m-d",
            defaultDate: "today",
        });
    }
};

window.toggleColumn = (e, i) => {
    const table = "#" + e.closest("table").id;
    const hiddenColumns = JSON.parse(localStorage.getItem(table)) || [];

    if (e.checked) {
        $(`${table} td:nth-child(${i}), th:nth-child(${i})`).show();
        hiddenColumns.splice(hiddenColumns.indexOf(i), 1);
    } else {
        $(`${table} td:nth-child(${i}), th:nth-child(${i})`).hide();
        if (!hiddenColumns.includes(i)) hiddenColumns.push(i);
    }

    localStorage.setItem(table, JSON.stringify(hiddenColumns));
    checkIfHiddenColumnEmpty(table);
};

window.loadHiddenColumn = (table) => {
    const hiddenColumns = JSON.parse(localStorage.getItem(table)) || [];
    hiddenColumns.forEach((i) => {
        $(`${table} td:nth-child(${i}), th:nth-child(${i})`).hide();
        const checkbox = document.querySelector(`${table}ColumnCheckbox${i}`);
        if (checkbox) checkbox.checked = false;
    });
    checkIfHiddenColumnEmpty(table);
};
window.checkIfHiddenColumnEmpty = (table) => {
    const hiddenColumns = JSON.parse(localStorage.getItem(table)) || [];
    $(`${table} .red-toggle`).toggle(hiddenColumns.length > 0);
};
document.addEventListener("DOMContentLoaded", () => {
    const tables = document.querySelectorAll(".with-toggle-column-table");
    tables.forEach((table) => loadHiddenColumn("#" + table.id));
});
window.fetchAjaxTable = (
    table,
    container,
    page,
    fetchExtraInfo = false,
    id = 0
) => {
    const currentUrlParams = new URLSearchParams(window.location.search);
    const excluded_case_ids = currentUrlParams.getAll("excluded_case_ids[]");
    const queryParams = new URLSearchParams({
        table: table,
        [`${table}-page`]: page,
        id: id,
    });

    excluded_case_ids.forEach((id) => {
        queryParams.append("excluded_case_ids[]", id);
    });
    const $container = $(container);
    const spinnerOverlay = `
    <div class="spinner-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-light bg-opacity-75">
        ${
            window.spinner ||
            `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>`
        }
    </div>`;
    $container.css("position", "relative").append(spinnerOverlay);
    $.ajax({
        // url: `?table=${table}&${table}-page=${page}&id=${id}`,
        url: `?${queryParams.toString()}`,
        success: function (data) {
            $container.find(".spinner-overlay").remove();
            $(container).html(data);
            if (fetchExtraInfo) fetchAjaxTable("extra-info", "#extraInfo");
        },
        error: function (xhr, status, error) {
            $container.find(".spinner-overlay").remove();
            console.log("Error loading page: ", error);
        },
    });
};

// window.showToast = (header, message, type) => {
//     const delayTime = type === "error" ? 0 : 5000;
//     switch (type) {
//         case "success":
//             type = "text-bg-success";
//             break;
//         case "error":
//             type = "text-bg-danger";
//             break;
//         default:
//             type = "text-bg-primary";
//             break;
//     }

//     const toastElement = document.querySelector("#liveToast").cloneNode(true);
//     toastElement.id = ""; // Clear ID for uniqueness

//     // Set header and message content
//     toastElement.querySelector(".toast-header-text").innerText = header;
//     toastElement.querySelector(".toast-header").classList.add(type);
//     toastElement.querySelector(".toast-body").innerText = message;
//     toastElement.querySelector(".toast-header-time").innerText =
//         new Date().toLocaleTimeString();

//     // Append to the toast container
//     document.querySelector(".toast-container").append(toastElement);

//     // Initialize and show the toast
//     const toast = new bootstrap.Toast(toastElement, {
//         //animation: true,
//         autohide: delayTime ? true : false,
//         delay: delayTime,
//     });
//     toast.show();

//     // Optional: Remove toast from DOM after it hides
//     toastElement.addEventListener("hidden.bs.toast", () => {
//         toastElement.remove();
//     });
// };
window.showToast = (header, message, type) => {
    const delayTime = type === "error" ? 0 : 5000;
    const countdownDuration = delayTime / 1000;
    let timeLeft = countdownDuration;

    // Determine the type class
    switch (type) {
        case "success":
            type = "text-bg-success";
            break;
        case "error":
            type = "text-bg-danger";
            break;
        default:
            type = "text-bg-primary";
            break;
    }

    // Clone the toast template and set up content
    const toastElement = document.querySelector("#liveToast").cloneNode(true);
    toastElement.id = ""; // Clear ID for uniqueness
    toastElement.querySelector(".toast-header-text").innerText = header;
    toastElement.querySelector(".toast-header").classList.add(type);
    toastElement.querySelector(".toast-body").innerText = message;
    toastElement.querySelector(".toast-header-time").innerText =
        new Date().toLocaleTimeString();

    // Countdown element in muted footer text
    const footerElement = document.createElement("div");

    footerElement.className = "toast-footer text-muted px-2 py-1 text-end";
    footerElement.style.fontSize = "0.85rem"; // Make footer text a bit smaller
    footerElement.innerText = timeLeft > 0 ? `${timeLeft}s` : "";

    // Append footer to toast element
    toastElement.append(footerElement);
    document.querySelector(".toast-container").append(toastElement);

    // Initialize the toast
    const toast = new bootstrap.Toast(toastElement, {
        autohide: delayTime ? true : false,
        delay: delayTime,
    });
    toast.show();

    // Update countdown every second if delayTime is set
    if (delayTime) {
        const countdownInterval = setInterval(() => {
            timeLeft -= 1;
            footerElement.innerText = timeLeft > 0 ? `${timeLeft}s` : "";

            // Stop interval when time runs out
            if (timeLeft <= 0) clearInterval(countdownInterval);
        }, 1000);

        // Remove toast and clear interval on hide
        toastElement.addEventListener("hidden.bs.toast", () => {
            clearInterval(countdownInterval);
            toastElement.remove();
        });
    }
};

// Error handler function remains the same
window.toastError = (xhr) => {
    let errorMessage = "An error occurred";
    if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
    } else if (xhr.responseText) {
        errorMessage = xhr.responseText;
    }
    window.showToast("Error", errorMessage, "error");
};

window.toastError = (xhr) => {
    let errorMessage = "An error occurred";
    if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
    } else if (xhr.responseText) {
        errorMessage = xhr.responseText;
    }
    window.showToast("Error", errorMessage, "error");
};
window.statusBadge = (status) => {
    switch (status) {
        case "open":
            return "<span class='badge  text-bg-primary '>Active</span>";

        case "in_progress":
            return "<span class='badge  text-bg-info '>In Progress</span>";

        case "settled":
            return "<span class='badge  text-bg-warning '>Withdrawn</span>";

        case "won":
            return "<span class='badge  text-bg-success '>Won</span>";

        case "lost":
            return "<span class='badge  text-bg-danger '>Lost</span>";

        case "closed":
            return "<span class='badge  text-bg-secondary '>Closed</span>";

        default:
            return "";
    }
};
window.editableInput = (value, name, classNames = "") => {
    return $("<input>", {
        type: "text",
        name: name,
        class: "form-control edit input " + classNames,
        value: value,
    });
};
window.editableTextArea = (value, name) => {
    return $("<textarea>", {
        name: name,
        rows: 3,
        class: "form-control edit textarea",
        text: value,
    });
};
window.editableSelect = (values, name, selectedValue = "") => {
    const $select = $("<select>", {
        name: name,
        class: "form-select edit select",
    });
    values.forEach((value) => {
        $("<option>", {
            value: value,
            text: window.strHeadline(value),
            selected: value === selectedValue,
        }).appendTo($select);
    });

    return $select;
};
window.formatDate = (dateString) => {
    const date = new Date(dateString);
    const options = {
        month: "short",
        day: "numeric",
        year: "numeric",
    };
    return date.toLocaleDateString("en-US", options);
};
window.formatDateTime = (dateString) => {
    const date = new Date(dateString);
    const options = {
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    };
    return date.toLocaleDateString("en-US", options);
};
window.formatTime = (dateString) => {
    const date = new Date(dateString);
    const options = {
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    };
    return date.toLocaleTimeString("en-US", options);
};
window.formatMonth = (dateString) => {
    const date = new Date(dateString);
    const options = {
        month: "long",
    };
    return date.toLocaleDateString("en-US", options);
};
window.spinner = spinner =
    '<div class="spinner-border spinner-border-sm me-2" role="status"><span class="visually-hidden">Loading...</span></div>';
// $(document).on('click', '.ajax-table .pagination a', function(e) {
//     e.preventDefault();
//     var page = $(this).attr('href').split('page=')[1];
//     fetchAdminFees(page);
// });
window.formReset = (form) => {};
window.strHeadline = (str) => {
    return str
        .toLowerCase()
        .replace(/_/g, " ")
        .replace(/(?:^|\s|-)\S/g, (match) => match.toUpperCase());
};
// $(document).on("submit", "form", function (e) {

//     const inputElements = $(this).find("input.input-money");
//     inputElements.inputmask.remove();

// });

// inputElements.forEach((input) => {
//     input.inputmask.remove();
// });
