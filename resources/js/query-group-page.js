const queryGroupContainer = ".query-group-content--container";
const queryContainer = ".query-content--container";
let isEditingGroup = false,
    isEditingQuery = false;
let previousGroupID, queryID, queryGroupID;
let queryList = {};

$(document).ready(function () {
    //Query Group Scripts

    if ($(queryGroupContainer).length > 0) {
        let activeQueryGroupID = localStorage.getItem("active_query_group_id");
        let firstGroupTitle =
            $(
                queryGroupContainer +
                    ' .list-group-item .title[data-id="' +
                    activeQueryGroupID +
                    '"]'
            ).length > 0
                ? $(
                      queryGroupContainer +
                          ' .list-group-item .title[data-id="' +
                          activeQueryGroupID +
                          '"]'
                  )
                : $(
                      queryGroupContainer +
                          " .list-group-item:first .title:first"
                  );

        $(queryGroupContainer + " .title").removeClass("active");
        firstGroupTitle.addClass("active");
        if (firstGroupTitle.hasClass("text-muted")) {
            $(".archive-query-group--link").attr(
                "title",
                "Разархивация группы"
            );
        } else {
            $(".archive-query-group--link").attr("title", "Архивация группы");
        }

        if (firstGroupTitle.attr("data-id") == "0") {
            $(".icon-group .btn").addClass("disabled");
        } else {
            $(
                "#queryGroupTabs .nav-link .icon-group .btn, .query-content--container .store-query--link, .query-content--container .mass-store-query--link"
            ).removeClass("disabled");
        }
        updateGroupQuery(() => {
            queryGroupID = firstGroupTitle.attr("data-id");
            localStorage.setItem("active_query_group_id", queryGroupID);
            getQueriesByGroupID();
        });
    }

    $("body")
        .on("click", '.store-query-group--link:not(".disabled")', () => {
            createGroupQuery();
        })
        .on("click", '.edit-query-group--link:not(".disabled")', () => {
            window.location = homeurl + "/query-group/edit/" + queryGroupID;
        })
        .on("click", '.target-query-group--link:not(".disabled")', () => {
            targetGroupQuery();
        })
        .on("click", ".destroy-query-group--link:not('.disabled')", () => {
            populateModalContent({
                action: "destroy-query-group",
                callback: () => {
                    $("#main-modal .modal-footer .btn-primary").on(
                        "click",
                        () => {
                            jQuery.ajax({
                                url:
                                    homeurl +
                                    "/query-group/destroy/" +
                                    queryGroupID,
                                method: "get",
                                success: function (result) {
                                    if (result == "1") {
                                        $(
                                            '.query-group-content--container .title[data-id="' +
                                                queryGroupID +
                                                '"]'
                                        )
                                            .parent()
                                            .fadeOut();
                                        $("#main-modal").modal("hide");
                                        $(queryContainer + " .tab-pane").html(
                                            '<ul class="list-group__nested"></ul>'
                                        );
                                    } else {
                                        window.location.reload();
                                    }
                                },
                            });
                        }
                    );
                },
            });
        })
        .on("click", '.archive-query-group--link:not(".disabled")', () => {
            console.log(queryGroupID);
            populateModalContent({
                action: $(
                    '.query-group-list--container .title[data-id="' +
                        queryGroupID +
                        '"]'
                ).hasClass("text-muted")
                    ? "unarchive-query-group"
                    : "archive-query-group",
                callback: () => {
                    $("#main-modal .modal-footer .btn-primary").on(
                        "click",
                        () => {
                            window.location =
                                homeurl +
                                "/query-group/archive/" +
                                queryGroupID;
                        }
                    );
                },
            });
        })
        .on("click", ".list-group__nested .fa-plus", function (e) {
            updateGroupQuery();
            $(this)
                .closest(".list-group-item")
                .removeClass("closed")
                .addClass("open");
            $(this).removeClass("fa-plus").addClass("fa-minus");
        })
        .on("click", ".list-group__nested .fa-minus", function (e) {
            $(this)
                .closest(".list-group-item")
                .removeClass("open")
                .addClass("closed");
            $(this).removeClass("fa-minus").addClass("fa-plus");
        })
        .on("click", queryGroupContainer + " .title", function (e) {
            $(queryGroupContainer + " .title").removeClass("active");
            $(this).addClass("active");
            if ($(this).hasClass("text-muted")) {
                $(".archive-query-group--link").attr(
                    "title",
                    "Разархивация группы"
                );
            } else {
                $(".archive-query-group--link").attr(
                    "title",
                    "Архивация группы"
                );
            }

            if ($(this).attr("data-id") == "0") {
                $(".icon-group .btn").addClass("disabled");
            } else {
                $(
                    "#queryGroupTabs .nav-link .icon-group .btn, .query-content--container .store-query--link, .query-content--container .mass-store-query--link"
                ).removeClass("disabled");
            }

            updateGroupQuery(() => {
                queryGroupID = $(this).attr("data-id");
                localStorage.setItem("active_query_group_id", queryGroupID);
                getQueriesByGroupID();
            });
        })
        .on("click", ".editable", function (e) {
            e.stopPropagation();
        })
        .on("dblclick", queryGroupContainer + " .editable", function (e) {
            $(this).attr("contenteditable", true);
            isEditingGroup = true;
            $(queryGroupContainer + " .title").removeClass("active");
            queryGroupID = $(this)
                .closest(queryGroupContainer + " .title")
                .addClass("active")
                .attr("data-id");
            getQueriesByGroupID();
            $(
                "#queryGroupTabs .nav-link .icon-group .btn, .query-content--container .store-query--link, .query-content--container .mass-store-query--link"
            ).removeClass("disabled");
        })
        .on("keypress", queryGroupContainer + " .editable", function (e) {
            //enter key
            if (e.which === 13) {
                e.preventDefault();
                updateGroupQuery();
            }
        })
        .on("keyup", queryGroupContainer + " .editable", function (e) {
            //escape key
            if (e.which === 27) {
                isEditingGroup = false;
                $(this).text($(this).attr("data-name"));
                $(queryGroupContainer + " .editable").removeAttr(
                    "contenteditable"
                );
            }
        });

    //Query Scripts
    $("body")
        .on("click", '.store-query--link:not(".disabled")', () => {
            $(queryContainer + " .title").removeClass("active");
            isEditingQuery = true;
            queryID = 0;

            $(".query-content--container .tab-content ul")
                .prepend(`<li class="list-group-item open">
                <input type="checkbox" value="-1" data-query-id="-1" class="query-checkbox form-check-input ml-2 mt-3">
                <div class="title active" data-id="-1">
				<span class="editable ml-4" data-name="" contenteditable=true></span>
			</div></li>`);
            $(
                ".query-content--container .editable[contenteditable=true]"
            ).focus();
        })
        .on("click", '.mass-store-query--link:not(".disabled")', () => {
            populateModalContent({
                action: "mass-store-query",
                callback: () => {
                    $("#main-modal .modal-footer .btn-primary").on(
                        "click",
                        () => {
                            $("#main-modal form")
                                .append(
                                    '<input type="hidden" name="query_group_id" value="' +
                                        queryGroupID +
                                        '">'
                                )
                                .submit();
                        }
                    );
                },
            });
        })
        .on("click", '.edit-query--link:not(".disabled")', () => {
            window.location = homeurl + "/query/edit/" + queryID;
        })
        .on("click", '.region-query--link:not(".disabled")', function () {
            populateModalContent({
                action: "assign-query-region",
                callback: () => {
                    $("#main-modal .modal-footer .btn-primary").on(
                        "click",
                        () => {
                            let queryIDs = [];
                            let regionID = $(
                                '#main-modal select[name="region_id"]'
                            ).val();
                            if (
                                $(queryContainer + " .query-checkbox:checked")
                                    .length > 0
                            ) {
                                $(
                                    queryContainer + " .query-checkbox:checked"
                                ).each(function () {
                                    queryIDs.push($(this).val());
                                });
                            } else {
                                queryIDs = [
                                    queryID
                                        ? queryID
                                        : $(this).attr("data-query-id"),
                                ];
                            }
                            $("#main-modal form")
                                .append(
                                    '<input type="hidden" name="query_id" value="' +
                                        queryIDs.join() +
                                        '">'
                                )
                                .submit();
                        }
                    );
                },
            });
        })
        .on("click", '.archive-query--link:not(".disabled")', function () {
            populateModalContent({
                action: "archive-query",
                callback: () => {
                    $("#main-modal .modal-footer .btn-primary").on(
                        "click",
                        () => {
                            let queryIDs = [];
                            if (
                                $(queryContainer + " .query-checkbox:checked")
                                    .length > 0
                            ) {
                                $(
                                    queryContainer + " .query-checkbox:checked"
                                ).each(function () {
                                    queryIDs.push($(this).val());
                                });
                            } else {
                                queryIDs = [
                                    queryID
                                        ? queryID
                                        : $(this).attr("data-query-id"),
                                ];
                            }
                            window.location =
                                homeurl +
                                "/query/archive/?query_id=" +
                                queryIDs;
                        }
                    );
                },
            });
        })
        .on("click", ".destroy-query--link:not('.disabled')", function () {
            populateModalContent({
                action: "destroy-query",
                callback: () => {
                    $("#main-modal .modal-footer .btn-primary").on(
                        "click",
                        () => {
                            let queryIDs = [];
                            if (
                                $(queryContainer + " .query-checkbox:checked")
                                    .length > 0
                            ) {
                                $(
                                    queryContainer + " .query-checkbox:checked"
                                ).each(function () {
                                    queryIDs.push($(this).val());
                                });
                            } else {
                                queryIDs = [
                                    queryID
                                        ? queryID
                                        : $(this).attr("data-query-id"),
                                ];
                            }
                            window.location =
                                homeurl +
                                "/query/destroy/?query_id=" +
                                queryIDs;
                        }
                    );
                },
            });
        })
        .on("click", queryContainer + " .title", function (e) {
            manageQuerySelection("span");

            $(this).addClass("active");
            updateQuery(() => {
                queryID = $(this).attr("data-id");
            });
        })
        .on("dblclick", queryContainer + " .editable", function (e) {
            manageQuerySelection("span");

            $(this).attr("contenteditable", true);
            isEditingQuery = true;
            queryID = $(this)
                .closest(queryContainer + " .title")
                .addClass("active")
                .attr("data-id");
        })
        .on("keypress", queryContainer + " .editable", function (e) {
            //enter key
            if (e.which === 13) {
                e.preventDefault();
                updateQuery();
            }
        })
        .on("keyup", queryContainer + " .editable", function (e) {
            //escape key
            if (e.which === 27) {
                isEditingGroup = false;
                $(this).text($(this).attr("data-name"));
                $(queryContainer + " .editable").removeAttr("contenteditable");
            }
        })
        .on("keyup", queryContainer + " .query-search--input", function (e) {
            filterQueriesBySearch();
        })
        .on("change", queryContainer + " #select-all--checkbox", function (e) {
            manageQuerySelection("all");
        })
        .on("change", queryContainer + " .query-checkbox", function (e) {
            manageQuerySelection("checkbox");
        });
});

function filterQueriesBySearch() {
    let searchText = $(queryContainer + " .query-search--input").val();
    $(queryContainer + " #select-all--checkbox").prop("checked", false);
    manageQuerySelection("all");

    $(
        `${queryContainer} .list-group-item,
		${queryContainer} .table-group-item`
    ).each(function () {
        if ($(this).text().indexOf(searchText) < 0) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
}

function manageQuerySelection(selectionType) {
    if (selectionType === "checkbox") {
        queryID = null;
        $(queryContainer + " .title").removeClass("active");
        $(queryContainer + " .nav-link .btn").removeClass("disabled");
        if ($(queryContainer + " .query-checkbox:checked").length > 0) {
            $(queryContainer + " .edit-query--link").addClass("disabled");
        } else {
            $(queryContainer + " .nav-link .btn").removeClass("disabled");
            $(queryContainer + " .edit-query--link").addClass("disabled");
            $(queryContainer + " .destroy-query--link").addClass("disabled");
            $(queryContainer + " .archive-query--link").addClass("disabled");
        }
        if (queryGroupID == 0) {
            $(
                queryContainer + " .store-query--link, .mass-store-query--link"
            ).addClass("disabled");
        }
    } else if (selectionType === "all") {
        $(queryContainer + " .title").removeClass("active");
        if (queryGroupID != 0) {
            $(queryContainer + " .nav-link .btn").removeClass("disabled");
        }

        $(queryContainer + " .query-checkbox").prop("checked", false);

        if ($(queryContainer + " #select-all--checkbox").is(":checked")) {
            $(queryContainer + " .query-checkbox:visible").each(function () {
                $(this).prop("checked", true);
            });
            $(queryContainer + " .edit-query--link").addClass("disabled");
        } else {
            $(queryContainer + " .edit-query--link").addClass("disabled");
            $(queryContainer + " .destroy-query--link").addClass("disabled");
            $(queryContainer + " .archive-query--link").addClass("disabled");
        }
    } else if (selectionType === "span") {
        $(queryContainer + " .title").removeClass("active");
        //$(queryContainer + " .query-checkbox").prop('checked', false);
        $(queryContainer + " .nav-link .btn").removeClass("disabled");
        if (queryGroupID == 0) {
            $(
                queryContainer + " .store-query--link, .mass-store-query--link"
            ).addClass("disabled");
        }
    }
}

function getQueriesByGroupID() {
    //checking if already get results from ajax
    if (previousGroupID != queryGroupID && !queryList[queryGroupID]) {
        jQuery.ajax({
            url: homeurl + "/query/list/" + queryGroupID,
            method: "get",
            dataType: "json",
            success: function (data) {
                let content = "";
                if (data.length > 0) {
                    data.forEach((item) => {
                        content += `<li class="list-group-item open">
                              
                                <div class="title ${
                                    item.is_active == 0 ? "text-muted" : ""
                                }" data-id="${item.id}">

                                <input type="checkbox" value="${
                                    item.id
                                }" data-query-id="${
                            item.id
                        }" class="query-checkbox form-check-input">
                                    <span class="editable ml-4" data-name="${
                                        item.name
                                    }">${item.name}</span>
                                    <span class="list-group-region">${
                                        item.region_name ?? ""
                                    }</span>
                                </div>
                		</li>`;
                    });
                    queryList[queryGroupID] =
                        '<ul class="list-group__nested">' + content + "</ul>";
                    $(queryContainer + " .tab-pane").html(
                        queryList[queryGroupID]
                    );
                } else {
                    $(queryContainer + " .tab-pane").html(
                        '<ul class="list-group__nested"></ul>'
                    );
                }
                filterQueriesBySearch();
            },
            error: function (data) {
                var json = $.parseJSON(data);
            },
        });
    }
    if (previousGroupID != queryGroupID && queryList[queryGroupID]) {
        $(queryContainer + " .tab-pane").html(queryList[queryGroupID]);
        filterQueriesBySearch();
    }
    previousGroupID = queryGroupID;
}

function createGroupQuery() {
    populateModalContent({
        action: "store-query-group",
        callback: () => {
            if (queryGroupID) {
                $(
                    '#main-modal select[name="parent_group_id"] option[value="' +
                        queryGroupID +
                        '"]'
                ).prop("selected", true);
            }
            $("#main-modal .modal-footer .btn-primary").on("click", () => {
                jQuery.ajax({
                    url: homeurl + "/query-group/store",
                    method: "post",
                    data: {
                        name: $('#main-modal input[name="name"]').val(),
                        project_id: $(".project-name--button").attr(
                            "data-project-id"
                        ),
                        parent_group_id: $(
                            '#main-modal select[name="parent_group_id"]'
                        ).val(),
                        region_id: $(
                            '#main-modal select[name="region_id"]'
                        ).val(),
                    },
                    success: function (result) {
                        window.location.reload();
                    },
                });
            });
        },
    });
}
$("i#target_path").tooltip();
function targetGroupQuery() {
    populateModalContent({
        action: "target-query-group",
        callback: () => {
            if (queryGroupID) {
                $(
                    '#main-modal select[name="parent_group_id"] option[value="' +
                        queryGroupID +
                        '"]'
                ).prop("selected", true);
            }
            $("#main-modal .modal-footer .btn-primary").on("click", () => {
                // console.log({
                //     name: $('#main-modal input[name="name"]').val(),
                //     parent_group_id: $(
                //         '#main-modal select[name="parent_group_id"]'
                //     ).val(),
                // });

                let target = $("#target_path_input").val();
                let _token = $('meta[name="csrf-token"]').attr("content");
                jQuery.ajax({
                    url: homeurl + "/query-group/addtarget/" + queryGroupID,
                    method: "post",
                    data: {
                        _token: _token,
                        target_path: target,
                    },
                    success: function (result) {
                        console.log(result);

                        //redirect()->route('query-group.index', [ $queryGroup] )->with('success_message', [__('Query Group is updated')]
                        //window.location.href = "{{ route('show-all-prescription')->with('success_message', [__('Query Group is updated')]}}";
                        // window.location.href = '?success_message="sf"';
                        //window.location.reload();
                        let message = result[result.length - 1];
                        //console.log(message);
                        result.splice(-1, 1);
                        $("div.mk").remove();
                        if (target != "") {
                            $("nav.navbar").after(
                                '<div class="alert alert-success alert-dismissible fade show mk">' +
                                    message +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            );
                        } else {
                            $("nav.navbar").after(
                                '<div class="alert alert-success alert-dismissible fade show mk">' +
                                    message +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            );
                        }
                        $("#main-modal").modal("hide");
                        // console.log(queryGroupID);
                        result.forEach(function (key) {
                            $(`div.target_path_${key["id"]}`)
                                .children()
                                .remove();
                            if (target != "") {
                                $(`div.target_path_${key["id"]}`).append(
                                    `${target}`
                                );
                            }
                        });
                    },
                });
            });
        },
    });
}

function updateGroupQuery(callback) {
    let editingElement = $(
        queryGroupContainer + " .editable[contenteditable=true]"
    );
    //rename query group
    if (
        isEditingGroup == true &&
        editingElement.text() != editingElement.attr("data-name")
    ) {
        jQuery.ajax({
            url: homeurl + "/query-group/ajax-rename/" + queryGroupID,
            method: "post",
            data: {
                name: editingElement.text(),
            },
            success: function (result) {
                if (result == "1") {
                    editingElement.attr("data-name", editingElement.text());
                } else {
                    editingElement.text(editingElement.attr("data-name"));
                }
                if (typeof callback === "function") {
                    callback();
                }
                $(queryGroupContainer + " .editable").removeAttr(
                    "contenteditable"
                );
                isEditingGroup = false;
            },
        });
    } else {
        isEditingGroup = false;
        $(queryGroupContainer + " .editable").removeAttr("contenteditable");
        if (typeof callback === "function") {
            callback();
        }
    }
}

function updateQuery(callback) {
    let editingElement = $(queryContainer + " .editable[contenteditable=true]");
    //rename query
    if (
        isEditingQuery == true &&
        editingElement.text() != editingElement.attr("data-name")
    ) {
        jQuery.ajax({
            url: homeurl + "/query/store-update",
            method: "post",
            data: {
                id: queryID,
                name: editingElement.text(),
                query_group_id: queryGroupID,
            },
            success: function (result) {
                if (result > 0) {
                    editingElement.attr("data-name", editingElement.text());
                    editingElement.parent().attr("data-id", result);
                    editingElement
                        .parent()
                        .siblings('input[type="checkbox"]')
                        .val(result);
                    editingElement
                        .parent()
                        .siblings('input[type="checkbox"]')
                        .attr("data-query-id", result);
                } else {
                    editingElement.text(editingElement.attr("data-name"));
                }
                if (typeof callback === "function") {
                    callback();
                }
                $(queryContainer + " .editable").removeAttr("contenteditable");
                isEditingQuery = false;
                queryList[queryGroupID] = $(
                    queryContainer + " .tab-pane"
                ).html();
            },
        });
    } else {
        isEditingQuery = false;
        $(queryContainer + " .editable").removeAttr("contenteditable");
        if (typeof callback === "function") {
            callback();
        }
    }
}
