
console.log("schedule")
import flatpickr from "flatpickr";
flatpickr("#myTime", {
    enableTime: true,
    noCalendar: true,


});

//$('[data-day="MONDAY"]').addClass('hoverday');
$( ".days i" ).click(function() {
    let myDays = $("#myDay").val();
    if($(this).hasClass('hoverday')){
        $(this).toggleClass('hoverday');

        myDays = myDays.replace($(this).data('day') ,'');
        myDays = remove_b_l(myDays,',');


    }else{
        $(this).toggleClass('hoverday');
        myDays =   remove_b_l(myDays,',') + ',' + $(this).data('day');
        $('.days').append(`<input  value="${$(this).data('day')}" name="${$(this).data('day')}" type="hidden">`);
    }
    myDays = myDays.replace(',,' ,',');
    $("#myDay").val(remove_b_l(myDays,','));

    console.log($("#myDay").val())
});
let queryGroupID;
//add_region_button

//999999999
// $( document ).ready(function() {
//     $( ".dropdown a" ).trigger( "click" );
// });

function remove_b_l(str,char){
    if(str[0]==char[0]){
        str = str.substring(1, str.length);
    }
    if(str[str.length - 1]==char[0]){
        str = str.substring(0, str.length - 1);
    }
    return str;
}
async function date_to_database(arr){
    const result = await $.ajax({
        type: 'post',
        url: `date_to_database`,
        data: {
            _token: $('[name="_token"]').val(),
            type:arr['type'],
            dates:arr['dates'],
            time:arr['time'],
            project_id: arr['project_id'],
            uuid: arr['uuid']
        },
    })
    return result;
}
$('body')

    .on('click', '#get_position_button', function() {
        console.log("schedule: "+ $("#myDate").val())
        let type_of_schedule = $('#dropdownMenuButtonSchedule').text().trim();
        let dates ;
        let type ;
        let arr = [];
        if(type_of_schedule == 'По дням месяца'){
            //type =
            if($("#everymonth").is(':checked'))
            {
                type = 'everymonth';
            }else{
                type = 'fixed_date';
            }
            dates = $("#myDate").val();
        }else{
            type = 'weekly';
            dates = $("#myDay").val();
        }
        $("#dates").val(dates);
        $("#type").val(type);

        //console.log(arr);

        //console.log('kk: '+$('#dropdownMenuButtonSchedule').text().trim());
        if(dates==""){
            alert('пожалуйста выберите дату');
        }else if($('#plan_name').val()==""){
            alert('пожалуйста придумайте название плана');
        }else{
            populateModalContent({
                        action: 'refresh-popup-container1',
                        callback: () => {
                            $('#main-modal .modal-dialog').css('max-width', '70%');
                            $('#main-modal .modal-footer .btn-primary').on('click', () => {
                                $('.query-group-content--container1 .title[data-id="'+ queryGroupID +'"]').parent().fadeOut();
                                $('#main-modal').modal('hide');
                            })
                        }
                    });


        }
    })


    .on('click', '.search-setup-container .add-region--button1', function() {
        console.log(this)
        let searchContainer = $(this).parents('ul.list-group').attr('data-search-list');
        populateModalContent({
            action: 'append-region',
            callback: () => {
                    $("#main-modal .modal-footer .btn-primary.mk").on('click', () => {
                    let val = $('#main-modal select').val();
                    let regionName = $("#main-modal select option:selected").text();
                        console.log(searchContainer);
                        console.log("region "+regionName);
                        console.log("val "+val);
                    $('.modal-content--refresh-popup-container1 ul.list-group.' + searchContainer + '-setup').append(`
                    <li class="list-group-item region--list">
                        <div class="d-flex w-100 justify-content-between">
                            <span>${regionName}</span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" data-region-id="${val}" checked="checked">
                            </div>
                        </div>
                    </li>`);
                    populateModalContent({
                        action: 'refresh-popup-container1',
                        callback: () => {
                            $('#main-modal .modal-dialog').css('max-width', '70%');
                            $(document).on("click", "#main-modal .modal-footer .btn-primary", function(event){
                                $('.query-group-content--container1 .title[data-id="'+ queryGroupID +'"]').parent().fadeOut();
                                //$('#main-modal').modal('hide');
                            })

                        }
                    });
                })
            }
        });
    })


function make_this_month_dates(input="") {
        if(input==""){
            return "";
        }
        var d = new Date();
        var m = d.getMonth();
        var y = d.getFullYear();
        var arr = input.split(",");
        let result = [];
        for (let i = 0; i < arr.length; i++) {
            result.push(y + '-' + (m + 1) + '-' + get_day(arr[i]));
        }
        console.log(result);
        return result;
}

$(document).on('click', '#everymonth', function () {
    if ($(this).is(":checked")) {

        let DateAccomm;
        var d = new Date();
        var m = d.getMonth();
        var y = d.getFullYear();
        var int_d = new Date(y, m,1);
        var int_d1 = new Date(y, m+1,1);
        console.log(int_d)
        DateAccomm = flatpickr("#myDate", {
            mode: "multiple",
            dateFormat: "Y-m-d",
            minDate: int_d,
            maxDate: new Date(int_d1 - 1),
            inline: true
        });
        $("#myDate").val("");

    } else {
        $("#myDate").val("");
        flatpickr("#myDate", {
            mode: "multiple",
            dateFormat: "Y-m-d",
            minDate: "today",

            inline: true
        });

    }
});

function fixed_default_dates(){
    let input = $('#Selected_dates').val();
    let arr = input.split(',');
    let result = [];
    for (let i=0;i<arr.length;i++){
        result.push(arr[i]);
    }
    return result;
}

$( document ).ready(function() {
    if ($('#editpage_load').length > 0) {
        console.log('yes')
        if ($('#editpage_load').val() == 'weekly') {
            week_days();
            console.log('week_days')
        } else if ($('#editpage_load').val() == 'everymonth') {
            console.log($('#editpage_load').val())
            show_month_dates();
        } else {

            $('.days').children().hide();
            $('.days').append(`<div class="check_class">  <input id="everymonth" name="everymonth" class ="mt-4" type="checkbox" > <span>Каждый месяц</span></div>`);
            console.log('fixeddate')
            console.log( fixed_default_dates())
            if ($('#month_dates').is(":checked")) {

                let DateAccomm;
                var d = new Date();
                var m = d.getMonth();
                var y = d.getFullYear();
                var int_d = new Date(y, m, 1);
                var int_d1 = new Date(y, m + 1, 1);
                console.log('fixed date:')
                console.log( fixed_default_dates())
                console.log(int_d)
                DateAccomm = flatpickr("#myDate", {
                    mode: "multiple",
                    dateFormat: "Y-m-d",
                    minDate: int_d,

                    maxDate: new Date(int_d1 - 1),
                    inline: true
                });
                $("#myDate").val("");

            } else {
                $("#myDate").val("");
                flatpickr("#myDate", {
                    mode: "multiple",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    defaultDate:fixed_default_dates(),
                    inline: true
                });

            }
        }
    } else {
        console.log("no")
    }
});

function show_month_dates(){
    console.log("show_month_dates")
    console.log("show_month_dates")
    console.log($('#Selected_dates').val())
    var d = new Date();
    var m = d.getMonth();
    var y = d.getFullYear();
    var int_d = new Date(y, m,1);
    var int_d1 = new Date(y, m+1,1);
    console.log(int_d)
   flatpickr("#myDate", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        minDate: int_d,
       defaultDate:make_this_month_dates($('#Selected_dates').val()),
        maxDate: new Date(int_d1 - 1),
        inline: true
    });
    $('.days').children().hide();
    $('.days').append(`<div class="check_class">  <input id="everymonth" name="everymonth" class ="mt-4" type="checkbox" checked> <span>Каждый месяц</span></div>`);
    $("#myDate").hide();
    //$("#myDate").val('');
}
function week_days(){
    flatpickr("#myDate", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        // defaultDate: ["2016-10-20", "2016-11-04"]
    });
    console.log($('#Selected_dates').val())
    if($('#Selected_dates').val()!=null){
        let arr = ($('#Selected_dates').val()).split(',');
        for(let i=0;i<arr.length;i++){
            $(`*[data-day="${arr[i]}"]`).addClass('hoverday');
        }
    }

    $('.check_class').remove();
    console.log($('#Selected_dates').val());
    $('.days').children().show();
    $("#myDate").hide();
    $("#myDate").val("");
}
$('.dropdown a').click(function () {

    $('#dropdownMenuButtonSchedule').text($(this).text());
    let type = $('#dropdownMenuButtonSchedule').text().trim();
    if(type == 'По дням месяца'){
        show_month_dates();

    }else{
      week_days();
    }
});

function addMonths(date, months) {
    var d = date.getDate();
    date.setMonth(date.getMonth()+months);
    if (date.getDate() != d) {
        date.setDate(0);
    }
    let year = date.getFullYear();
    let month = date.getMonth()+1;
    let dt = date.getDate();
    if (dt < 10) {
        dt = '0' + dt;
    }
    if (month < 10) {
        month = '0' + month;
    }
    return year+'-' + month + '-'+dt;

}

function date_to_string(arr){
    if(arr.length>0){
        let str_arr = [];
        for (let i=0; i<arr.length;i++){
            var date = arr[i];
            let year = date.getFullYear();
            let month = date.getMonth()+1;
            let dt = date.getDate();
            if (dt < 10) {
                dt = '0' + dt;
            }
            if (month < 10) {
                month = '0' + month;
            }
            str_arr.push(year+'-' + month + '-'+dt);
        }
        return str_arr;
    }
    return [];
}

function get_day(date) {
    return date.split("-")[2];
}
function get_year(date) {
    return date.split("-")[0];
}
function get_month(date) {
    return date.split("-")[1];
}

function addMonths1(date, months) {

}
function set_default_dates(arr) {
    //arr = date_to_string(arr);
    console.log("input: " + date_to_string(arr));
    let result = [];
    for (let i = 0; i < arr.length; i++) {
        for (let k = 1; k <= 12; k++) {
            result.push(string_to_date(addMonths(arr[i], 1)));
        }
    }
    return result;
}
function set_enabled_dates(arr){
    //arr = date_to_string(arr);
    console.log("input: "+date_to_string(arr));
    let result = [];
    for(let i=0;i<arr.length;i++){
        for(let k=1;k<=12;k++){
            result.push(string_to_date(addMonths(arr[i],1)));
        }
    }


    // console.log("result:"+result);
    //result = date_to_string(result);
    console.log("result:"+result);
    let day;
    var d = new Date();
    var n = d.getMonth();
    var y = d.getFullYear();
    var some_arr = [];
    for (let j=1;j<32;j++){
        day = j;
        if (j < 10) {
            day = '0' + day;
        }
        some_arr.push(string_to_date(y+'-' + (n) + '-'+day));
    }
    console.log(some_arr)
    console.log(some_arr.concat(result)) ;
    return some_arr.concat(result);
}
function string_to_date(str){
    return new Date(get_year(str),get_month(str),get_day(str));
}
flatpickr("#myDate", {
    onChange: function(selectedDates, dateStr, instance) {
        console.log(777)
    }
});
$("#myDate").hide();



function refreshAllPositions1(thisButton) {
    let data = {};
    $('.loading-icon-container').removeClass('d-none').find('.fa-sync').addClass('rotating');
    data.action = 'all';
    data.query_group = [];
    $('.modal-content--refresh-popup-container1 .query-group--list input[type="checkbox"]').each(function() {
        data.query_group.push($(this).attr('data-query-group-id'));
    });

    data.yandex = [];
    $('.modal-content--refresh-popup-container1 .yandex-setup .region--list input[type="checkbox"]').each(function(){
        data.yandex.push($(this).attr('data-region-id'));
    });

    data.google = [];
    $('#main-modal .google-setup .region--list input[type="checkbox"]').each(function(){
        data.google.push($(this).attr('data-region-id'));
    });
    data.filter = [];
    data.filter = "byall";
    console.log("all");
    console.log($('#project-id').val());
    if (Object.keys(data).length > 0) {
        jQuery.ajax({
            url: homeurl + "/position/get-all-positions",
            method: 'get',
            data: {
                project_id: $('#project-id').val(),
                data
            },
            success: function(data) {
                $('.loading-icon-container').addClass('d-none').find('.fa-sync').removeClass('rotating');
                // window.location.reload();
                console.log(data);
            },
            error: function(data){
                console.log(data);
                //window.location.reload();
            }
        });
    }
}
