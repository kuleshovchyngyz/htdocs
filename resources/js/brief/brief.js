$(document).ready(function () {
//tops tabs switch
    $(".topTabs span").on('click', function(event){
        event.stopPropagation();
        event.stopImmediatePropagation();
        if($(this).attr('class')!=null){

            let active = $(this).parent().find('span.active');
            let old = active.attr('class').replace(' active','');

            active.removeClass('active');
            $(this).addClass('active');
            let top = $(this).attr('class').replace(' active','');
            $('summary__charts--region-content').find('div.')

            $('div.'+ top).removeClass('d-none');
            $('div.'+ old).addClass('d-none');
        }

    });



    // Скрывать и показывать блок с Топами в модальном окне
    const breifHideShowChekboxTop = () => {
        $(".modal-form__content--summary-top-checkbox").hide();
        $("#modal-type-widget").change(function () {
            let breifSelectAdd = $(this).val();
            if (breifSelectAdd === "Топы") {
                $(".modal-form__content--summary-top-checkbox").show();
            } else {
                $(".modal-form__content--summary-top-checkbox").hide();
            }
        });
    };
    //  -----------------------------------

    //  Ставит и убирает флажочки на всех чекбоксах
    const breifHideShowAllCheckbox = () => {
        $("#summary__checkbox-top-all").click(function () {
            if ($(this).is(":checked")) {
                $(".summary__check").prop("checked", true);
            } else {
                $(".summary__check").prop("checked", false);
            }
        });
    };
    //  -----------------------------------

    breifHideShowChekboxTop(); //Скрывать и показывать блок с Топами в модальном окне
    breifHideShowAllCheckbox(); //  Ставит и убирает флажочки на всех чекбоксах
});
