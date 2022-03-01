$(document).ready(function() {
    $(".project_of_client").change(function() {
        let checked;
        let project_id = $(this).attr("id");
        let client = $(this).val();
        if (this.checked) {
            checked = true;
        } else {
            checked = false;
        }
        console.log(checked);
        jQuery.ajax({
            url: homeurl + "/client/projects",
            method: "get",
            data: {
                project_id: project_id,
                checked: checked,
                client: client
            },
            success: function(data) {
                $(".loading-icon-container")
                    .addClass("d-none")
                    .find(".fa-sync")
                    .removeClass("rotating");
                // window.location.reload();
                console.log(data);
            },
            error: function(data) {
                console.log(data);
                //window.location.reload();
            }
        });
    });
});

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});

$(function() {
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
});

$(".btn.btn-secondary.domain").click(function() {
    $("#competitor").val($(this).text());
    $(".btn.btn-secondary.domain.active").removeClass("active");
    $(this).addClass("active");
    let t_class = $(this).attr("data-id");
    console.log($("#positions_table_" + t_class).hasClass("d-none"));
    $(".table.table-sm.positions_table").addClass("d-none");
    $(".table.table-sm.positions_table").removeClass("positions_table");
    if ($("#positions_table_" + t_class).hasClass("d-none")) {
        $("#positions_table_" + t_class).removeClass("d-none");
        $("#positions_table_" + t_class).addClass("positions_table");
    }
    console.log("clicked");
});

$(".filter_ok_btn").click(function() {
    $("#filter_init").val("main");
    $("#competitor").val("self");
});

$(document).ready(function() {
    $(".sort_date").click(function() {
        $("#filter_init").val("date");
        if ($("#sort_date").val() != $(this).data("sort")) {
            $("#sort_type").val("null");
        }

        $("#sort_date").val($(this).data("sort"));
        $("#filter_ok_btn").trigger("click");
    });
});

function sort_color(str) {
    if (str == "asc") {
        return "green";
    }
    if (str == "desc") {
        return "red";
    }
    return "white";
}

let queryGroupID;
$(document).ready(function() {
    $.datepicker.regional["ru"] = {
        closeText: "Закрыть",
        prevText: "Пред",
        nextText: "След",
        currentText: "Сегодня",
        monthNames: [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ],
        monthNamesShort: [
            "Янв",
            "Фев",
            "Мар",
            "Апр",
            "Май",
            "Июн",
            "Июл",
            "Авг",
            "Сен",
            "Окт",
            "Ноя",
            "Дек"
        ],
        dayNames: [
            "воскресенье",
            "понедельник",
            "вторник",
            "среда",
            "четверг",
            "пятница",
            "суббота"
        ],
        dayNamesShort: ["вск", "пнд", "втр", "срд", "чтв", "птн", "сбт"],
        dayNamesMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        weekHeader: "Нед",
        dateFormat: "dd.mm.yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ""
    };
    $.datepicker.setDefaults($.datepicker.regional["ru"]);
    //$('select').selectpicker();

    $("#toggle-sidebar").on("click", function() {
        $(".page-wrapper").toggleClass("hide-sidebar");
    });

    $(".destroy-project--link").on("click", function() {
        let projectID = $(this).attr("data-project-id");
        populateModalContent({
            action: "destroy-project",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl + "/project/destroy/" + projectID;
                    }
                );
            }
        });
    });

    $(".archive-project--link").on("click", function() {
        let projectID = $(this).attr("data-project-id");
        populateModalContent({
            action: "archive-project",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl + "/project/archive/" + projectID;
                    }
                );
            }
        });
    });

    //Project Region script

    $(".destroy-project-region--link").on("click", function() {
        let projectID = $(this).attr("data-project-region-id");
        populateModalContent({
            action: "destroy-project-region",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl + "/project/region/destroy/" + projectID;
                    }
                );
            }
        });
    });

    $(".destroy-project-competitor--link").on("click", function() {
        let competitorID = $(this).attr("data-project-competitor-id");
        console.log(competitorID);
        populateModalContent({
            action: "destroy-competitor",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl +
                            "/project/competitor/destroy/" +
                            competitorID;
                    }
                );
            }
        });
    });

    $(".destroy-project-schedule--link").on("click", function() {
        let competitorID = $(this).attr("data-project-competitor-id");
        console.log(competitorID);
        populateModalContent({
            action: "destroy-schedule",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl +
                            "/project/schedule/delete/" +
                            competitorID;
                    }
                );
            }
        });
    });

    $(".archive-project-region--link").on("click", function() {
        let projectID = $(this).attr("data-project-region-id");
        populateModalContent({
            action: "archive-project-region",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl + "/project/region/archive/" + projectID;
                    }
                );
            }
        });
    });

    $(".archive-competitor--link").on("click", function() {
        let projectID = $(this).attr("data-competitor-id");
        populateModalContent({
            action: "archive-project-competitor",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl +
                            "/project/competitor/archive/" +
                            projectID;
                    }
                );
            }
        });
    });

    //Position page Scripts
    var SelectedDates = {};
    $(".datepicker").datepicker({
        beforeShowDay: function(date) {
            let tempPositionDates = POSITION_DATES;

            let idx1 = -1;
            tempPositionDates.forEach((item, index) => {
                let tempDate =
                    date.getYear() +
                    "-" +
                    date.getMonth() +
                    "-" +
                    date.getDate();
                let tempSelectedDate =
                    item.getYear() +
                    "-" +
                    item.getMonth() +
                    "-" +
                    item.getDate();
                if (tempDate == tempSelectedDate) {
                    idx1 = index;
                }
            });
            return idx1 >= 0 ? [true, "selected-date", ""] : [true, "", ""];
        },
        changeMonth: true,
        dateFormat: "dd.mm.yy"
    });

    $(".project-filter--selectbox").on("change", function() {
        window.location = homeurl + "/project/select/" + $(this).val();
    });

    $("body")
        .on(
            "change",
            ".search-setup-container #all-query-group, .search-setup-container .yandex-search, .search-setup-container .google-search",
            function() {
                if ($(this).is(":checked")) {
                    $(this)
                        .parents("ul")
                        .find("input[type=checkbox]")
                        .prop("checked", true);
                } else {
                    $(this)
                        .parents("ul")
                        .find("input[type=checkbox]")
                        .prop("checked", false);
                }
            }
        )
        .on("click", ".search-setup-container .add-region--button", function() {
            console.log(this);
            var searchContainer = $(this)
                .parents("ul.list-group")
                .attr("data-search-list");
            //console.log(searchContainer);
            populateModalContent({
                action: "append-region",
                callback: () => {
                    $("#main-modal .modal-footer .btn-primary").on(
                        "click",
                        () => {
                            let val = $("#main-modal select").val();
                            let regionName = $(
                                "#main-modal select option:selected"
                            ).text();
                            $(
                                ".modal-content--refresh-popup-container ul.list-group." +
                                    searchContainer +
                                    "-setup"
                            ).append(`
                    <li class="list-group-item region--list">
                        <div class="d-flex w-100 justify-content-between">
                            <span>${regionName}</span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" data-region-id="${val}" checked="checked">
                            </div>
                        </div>
                    </li>`);
                            populateModalContent({
                                action: "refresh-popup-container",
                                callback: () => {
                                    $("#main-modal .modal-dialog").css(
                                        "max-width",
                                        "70%"
                                    );
                                    $(
                                        "#main-modal .modal-footer .btn-primary"
                                    ).on("click", () => {
                                        $(
                                            '.query-group-content--container .title[data-id="' +
                                                queryGroupID +
                                                '"]'
                                        )
                                            .parent()
                                            .fadeOut();
                                        $("#main-modal").modal("hide");
                                    });
                                }
                            });
                        }
                    );
                }
            });
        })
        .on("click", ".refresh-position--button__popup", function() {
            console.log(queryGroupID);
            populateModalContent({
                action: "refresh-popup-container",
                callback: () => {
                    $("#main-modal .modal-dialog").css("max-width", "70%");
                    $("#main-modal .modal-footer .btn-primary").on(
                        "click",
                        () => {
                            $(
                                '.query-group-content--container .title[data-id="' +
                                    queryGroupID +
                                    '"]'
                            )
                                .parent()
                                .fadeOut();
                            $("#main-modal").modal("hide");
                        }
                    );
                }
            });
        })
        .on("click", ".refresh-position--button", function() {
            var r = confirm("Вы точно хотите обновить выделенные позиции");
            if (r == true) {
                refreshPositions(this);
            }
        })
        .on("click", ".refresh-position--button1", function() {
            var r = confirm("Вы точно хотите обновить выделенные позиции");
            if (r == true) {
                let arr = {};
                arr.type = $("#type").val();
                arr.dates = $("#dates").val();
                arr.project_id = $("#project-id").val();
                arr.time = $("#myTime").val();
                arr.uuid = $("#uuid").val();
                if ($("#task-id").length > 0) {
                    console.log("gggg" + $("#task-id").val());
                    arr.task_id = $("#task-id").val();
                }
                refreshPositions1(this, arr);
            }
        })
        .on("click", ".refresh-all-position--button", function() {
            var r = confirm("Вы точно хотите обновить все позиции");
            if (r == true) {
                refreshAllPositions(this);
            }
        })
        .on("click", ".refresh-all-position--button1", function() {
            var r = confirm("Вы точно хотите обновить все позиции");
            if (r == true) {
                let arr = {};
                arr.type = $("#type").val();
                arr.dates = $("#dates").val();
                arr.project_id = $("#project-id").val();
                arr.time = $("#myTime").val();
                arr.uuid = $("#uuid").val();
                if ($("#task-id").length > 0) {
                    console.log("gggg" + $("#task-id").val());
                    arr.task_id = $("#task-id").val();
                }
                //console.log(arr);
                refreshAllPositions1(this, arr);
            }
        })

        .on(
            "click",
            "ul.list-group.query-group-setup .list-group-item-main",
            function() {
                var _this = $(this);

                $("#angledown1").toggleClass("d-none");
                $("#angleright1").toggleClass("d-none");
                _this
                    .parent()
                    .find(".list-group-item.query-group--list")
                    .slideToggle(1);
            }
        )
        .on(
            "click",
            "ul.yandex-setup .list-group-item.bg-info.list-group-item-main",
            function() {
                var _this = $(this);
                $("#angledown2").toggleClass("d-none");
                $("#angleright2").toggleClass("d-none");
                _this
                    .parent()
                    .find(".list-group-item.region--list")
                    .slideToggle(1);
            }
        )
        .on(
            "click",
            "ul.google-setup .list-group-item.bg-info.list-group-item-main",
            function() {
                var _this = $(this);
                $("#angledown3").toggleClass("d-none");
                $("#angleright3").toggleClass("d-none");
                _this
                    .parent()
                    .find(".list-group-item.region--list")
                    .slideToggle(1);
            }
        );

    //Region script
    $(".destroy-region--link").on("click", function() {
        let regionID = $(this).attr("data-region-id");
        populateModalContent({
            action: "destroy-region",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl + "/region/destroy/" + regionID;
                    }
                );
            }
        });
    });
    //Region script
    $(".destroy-client--link").on("click", function() {
        let clientID = $(this).attr("data-client-id");
        populateModalContent({
            action: "destroy-client",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl + "/client/destroy/" + clientID;
                    }
                );
            }
        });
    });

    $(".archive-region--link").on("click", function() {
        let regionID = $(this).attr("data-region-id");
        populateModalContent({
            action: "archive-region",
            callback: () => {
                $("#main-modal .modal-footer .btn-primary").on(
                    "click",
                    function() {
                        window.location =
                            homeurl + "/region/archive/" + regionID;
                    }
                );
            }
        });
    });
});

function refreshAllPositions(thisButton) {
    let data = {};
    $(".loading-icon-container")
        .removeClass("d-none")
        .find(".fa-sync")
        .addClass("rotating");
    data.action = "all";
    data.query_group = [];
    $(
        '.modal-content--refresh-popup-container .query-group--list input[type="checkbox"]'
    ).each(function() {
        data.query_group.push($(this).attr("data-query-group-id"));
    });

    data.yandex = [];
    $(
        '.modal-content--refresh-popup-container .yandex-setup .region--list input[type="checkbox"]'
    ).each(function() {
        data.yandex.push($(this).attr("data-region-id"));
    });

    data.google = [];
    $('#main-modal .google-setup .region--list input[type="checkbox"]').each(
        function() {
            data.google.push($(this).attr("data-region-id"));
        }
    );
    data.filter = [];
    data.filter = "byall";
    console.log("all");
    //console.log(data);
    if (Object.keys(data).length > 0) {
        jQuery.ajax({
            //url: homeurl + "/position/get-all-positions",
            url: homeurl + "/project/schedule/schedule-all-positions",
            method: "post",
            data: {
                project_id: $("#project-id").val(),
                data
            },
            success: function(data) {
                $(".loading-icon-container")
                    .addClass("d-none")
                    .find(".fa-sync")
                    .removeClass("rotating");
                // window.location.reload();
                console.log(data);
            },
            error: function(data) {
                console.log(data);
                //window.location.reload();
            }
        });
    }
}

function refreshAllPositions1(thisButton, arr) {
    console.log(arr);

    let data = {};
    $(".loading-icon-container")
        .removeClass("d-none")
        .find(".fa-sync")
        .addClass("rotating");
    data.action = "all";
    data.query_group = [];
    $(
        '.modal-content--refresh-popup-container1 .query-group--list input[type="checkbox"]'
    ).each(function() {
        data.query_group.push($(this).attr("data-query-group-id"));
    });

    data.yandex = [];
    $(
        '.modal-content--refresh-popup-container1 .yandex-setup .region--list input[type="checkbox"]'
    ).each(function() {
        data.yandex.push($(this).attr("data-region-id"));
    });

    data.google = [];
    $('#main-modal .google-setup .region--list input[type="checkbox"]').each(
        function() {
            data.google.push($(this).attr("data-region-id"));
        }
    );
    data.filter = [];
    data.filter = "byall";
    console.log("all");
    console.log(data);

    if (Object.keys(data).length > 0) {
        jQuery.ajax({
            url: homeurl + "/project/schedule/schedule-all-positions",
            method: "post",
            data: {
                project_id: $("#project-id").val(),
                schedule: arr,
                name: $("#plan_name").val(),
                data
            },
            success: function(data) {
                console.log(data);
                //window.location.href = homeurl + "/project/schedule/" + $('#project-id').val();
            },
            error: function(data) {
                console.log(data);
                //window.location.reload();
            }
        });
    }
}

function refreshPositions1(thisButton, arr) {
    let data = {};
    $(".loading-icon-container")
        .removeClass("d-none")
        .find(".fa-sync")
        .addClass("rotating");
    data.action = "selected";
    data.query_group = [];
    $('#main-modal .query-group--list input[type="checkbox"]').each(function() {
        if ($(this).is(":checked")) {
            data.query_group.push($(this).attr("data-query-group-id"));
        }
    });

    if ($("#main-modal .yandex-search").is(":checked")) {
        data.yandex = [];
        $(
            '#main-modal .yandex-setup .region--list input[type="checkbox"]'
        ).each(function() {
            if ($(this).is(":checked")) {
                data.yandex.push($(this).attr("data-region-id"));
            }
        });
    }
    if ($("#main-modal .google-search").is(":checked")) {
        data.google = [];
        $(
            '#main-modal .google-setup .region--list input[type="checkbox"]'
        ).each(function() {
            if ($(this).is(":checked")) {
                data.google.push($(this).attr("data-region-id"));
            }
        });
    }
    data.filter = [];
    data.filter = "byfilter";

    console.log(data.google);
    if (Object.keys(data).length > 0) {
        jQuery.ajax({
            url: homeurl + "/project/schedule/schedule-all-positions",
            method: "post",
            data: {
                project_id: $("#project-id").val(),
                schedule: arr,
                name: $("#plan_name").val(),
                data
            },
            success: function(data) {
                $(".loading-icon-container")
                    .addClass("d-none")
                    .find(".fa-sync")
                    .removeClass("rotating");
                //window.location.reload();
                // window.location.href = homeurl + "/project/schedule/" + $('#project-id').val();
                console.log(data);
            },
            error: function(data) {
                //window.location.reload();
                console.log(data);
            }
        });
    }
}

function refreshPositions(thisButton) {
    let data = {};
    $(".loading-icon-container")
        .removeClass("d-none")
        .find(".fa-sync")
        .addClass("rotating");
    data.action = "selected";
    data.query_group = [];
    $('#main-modal .query-group--list input[type="checkbox"]').each(function() {
        if ($(this).is(":checked")) {
            data.query_group.push($(this).attr("data-query-group-id"));
        }
    });

    if ($("#main-modal .yandex-search").is(":checked")) {
        data.yandex = [];
        $(
            '#main-modal .yandex-setup .region--list input[type="checkbox"]'
        ).each(function() {
            if ($(this).is(":checked")) {
                data.yandex.push($(this).attr("data-region-id"));
            }
        });
    }
    if ($("#main-modal .google-search").is(":checked")) {
        data.google = [];
        $(
            '#main-modal .google-setup .region--list input[type="checkbox"]'
        ).each(function() {
            if ($(this).is(":checked")) {
                data.google.push($(this).attr("data-region-id"));
            }
        });
    }
    data.filter = [];
    data.filter = "byfilter";
    console.log(data);
    if (Object.keys(data).length > 0) {
        jQuery.ajax({
            //url: homeurl + "/position/get-all-positions",
            url: homeurl + "/project/schedule/schedule-all-positions",
            method: "post",
            data: {
                project_id: $("#project-id").val(),
                data
            },
            success: function(data) {
                $(".loading-icon-container")
                    .addClass("d-none")
                    .find(".fa-sync")
                    .removeClass("rotating");
                //window.location.reload();
                console.log(data);
            },
            error: function(data) {
                //window.location.reload();
                console.log(data);
            }
        });
    }
}

function refreshPosition(searchMode, thisButton) {
    populateModalContent({
        action: "refresh-" + searchMode + "-position",
        callback: () => {
            let parentRow = $(thisButton).closest(".query-row");
            let project_id = $("#project-id").val();
            let query_id = parentRow.attr("data-query-id");
            let region_id = parentRow.find(".region--selectbox").val();
            $("#main-modal .modal-footer .btn-primary").on("click", () => {
                console.log("inside Ok Button");
                $("#main-modal").modal("hide");
                parentRow.find(".action-button-container").addClass("d-none");
                parentRow
                    .find(".loading-icon-container")
                    .removeClass("d-none")
                    .find(".fa-sync")
                    .addClass("rotating");
                jQuery.ajax({
                    url: homeurl + "/position/get-position",
                    method: "post",
                    data: {
                        method: searchMode,
                        project_id,
                        query_id,
                        region_id
                    },
                    success: function(data) {
                        if (data == "1") {
                            parentRow
                                .find(".action-button-container")
                                .removeClass("d-none");
                            parentRow
                                .find(".loading-icon-container")
                                .addClass("d-none")
                                .find(".fa-sync")
                                .removeClass("rotating");
                        }
                        window.location.reload();
                    },
                    error: function(data) {
                        var json = $.parseJSON(data);
                        alert(json.error);
                    }
                });
            });
        }
    });
}
