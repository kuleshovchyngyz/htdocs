$(document).ready(function () {
    // Отображение количестов выделенных элементов

    $(".select__project--checkbox").change(function () {
        let check = $("input", this).is(":checked");
        let checkFilter = $("input").filter(":checked");
        let countCheck = checkFilter.length;

        // Количество выбранный проектов
        if (check) {
            $(this).css({
                "background-color": "#717171",
                opacity: "1",
            });
        } else {
            $(this).css({
                "background-color": "#f6f6f6",
                opacity: "0",
            });
        }
        $(".select__project--count span").text(countCheck);
        //

        // Отключение группового архивирование при выборе уже архивированных проектов
        let checkArray = [];
        let checkArrayName = [];
        checkFilter.each(function () {
            checkArray.push($(this).data("active"));
            checkArrayName.push($(this).data("name"));
        });
        console.log(checkArray);
        if (checkArray.includes(0) && checkArray.includes(1)) {
            $(".select__project-all-archive").addClass("all-archive-disable");
        } else {
            $(".select__project-all-archive").removeClass(
                "all-archive-disable"
            );
        }
        //

        if (checkArray.includes(0)) {
            $(".archive-all-modal .form-title").text("Восстановление проекта");
            $(
                " .archive-all-modal .modal-form__content--archive .archive__name"
            ).html(checkArrayName.join());
            $(".archive-all-modal .modal-form__content--archive--btn").text(
                "Восстановить"
            );
        } else if (checkArray.includes(1)) {
            $(".archive-all-modal .form-title").text("Архивация проекта");
            $(
                ".archive-all-modal .modal-form__content--archive .archive__name"
            ).html(checkArrayName.join());
            $(".archive-all-modal .modal-form__content--archive--btn").text(
                "В архив"
            );
        }
    });

    //

    // Функция добавление данных в модальное окно
    function getModalProject(
        className,
        dataId,
        dataName,
        dataUrl,
        dataRoute,
        fucnName,
        active
    ) {
        $(className).click(function () {
            let id = $(this).data(dataId);
            let name = $(this).data(dataName);
            let url = $(this).data(dataUrl);
            let actionRoute = $(this).data(dataRoute);
            let archive = $(this).data(active);

            if (id) {
                $(`.${fucnName}modal`).attr("id", `${fucnName}project${id}`);
            }
            if (actionRoute) {
                $(`.${fucnName}modal form`).attr("action", actionRoute);
            }
            if (name) {
                if (fucnName === "edit") {
                    $(`.${fucnName}__name`).val(name);
                } else {
                    $(`.${fucnName}__name`).text(name);
                }
            }
            if (url) {
                $(`.${fucnName}__url`).val(url);
            }

            if (archive === 0) {
                $(".archivemodal .form-title").text("Восстановление проекта");
                $(".modal-form__content--archive").html(
                    `Вы уверены, что хотите перенести проект <span>"${name}"</span> в архив?`
                );
                $(".modal-form__content--archive--btn").text("Восстановить");
            } else {
                $(".archivemodal .form-title").text("Архивация проекта");
                $(".modal-form__content--archive").html(
                    ` Вы уверены, что хотите перенести проект <span>"${name}"</span> в архив?`
                );
                $(".modal-form__content--archive--btn").text("В архив");
            }
        });
    }

    getModalProject(
        ".select__project--edit",
        "id",
        "name",
        "url",
        "route",
        "edit"
    );

    getModalProject(
        ".select__project--setting-dropdown-delete",
        "id",
        "name",
        "",
        "route",
        "delete"
    );

    getModalProject(
        ".select__project--archive",
        "id",
        "name",
        "",
        "route",
        "archive",
        "active"
    );
    //

    // Функция для удаление и архивирования проектов
    function allDeleteArchive(formName, btnName) {
        const multDelete = {
            form: $(formName),
            inputs: $("input[type='checkbox']"),
            btn: $(btnName),
            init() {
                this.btn.on("click", { self: this }, function (e) {
                    e.preventDefault();
                    let self = e.data.self;
                    self.setIds();
                    self.send();
                });
            },
            setIds() {
                let checked = this.inputs.filter(":checked");

                if (checked.length <= 0) return;

                checked.each((i, e) => {
                    this.form.append(
                        `<input type="hidden" name="ids[]" value="${$(
                            e
                        ).val()}">`
                    );
                });
            },
            send() {
                this.form.submit();
            },
        };
        multDelete.init();
    }
    allDeleteArchive("#multDelite", "#all-delete-project-btn");
    allDeleteArchive("#multarchive", "#all-archive-project-btn");

    //
});
