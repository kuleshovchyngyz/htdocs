$(document).ready(function () {
    $(".modal-form__content--summary-date input").flatpickr({
        mode: "range",
        dateFormat: "Y-m-d",
        locale: {
            rangeSeparator: "/",
        },
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            // Utilize dayElem.dateObj, which is the corresponding Date

            // dummy logic
            if (Math.random() < 0.15)
                dayElem.innerHTML += "<span class='event'></span>";
            else if (Math.random() > 0.85)
                dayElem.innerHTML += "<span class='event busy'></span>";
        },
    });
});
