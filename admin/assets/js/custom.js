$(document).ready(function () {
    var e = $("#custom_tbl_dt");
    e.dataTable({
        "bStateSave": true,
        "language": {
            "aria": {
                "sortAscending": ": Used to order Ascending",
                "sortDescending": ": Used to order Descending"
            },
            "emptyTable": "لا يوجد بيانات",
            "info": "عرض _START_ من _END_ الى _TOTAL_ عناصر",
            "infoEmpty": "لا يوجد بيانات",
            "infoFiltered": "(تنقيه _MAX_ من الاجمالى)",
            "lengthMenu": " _MENU_ عنصر",
            "search": "البحث:",
            "zeroRecords": "لا يوجد بيانات تشابه البحث"
        },
        buttons: [{
                extend: "print",
                className: "btn dark btn-outline",
                text: "طباعه",
                exportOptions: {
                    columns: '.show-print'
                }

            }, {
                extend: "copy",
                className: "btn red btn-outline",
                text: "نسخ",
                exportOptions: {
                    columns: '.show-print'
                }
            }, {
                extend: "excel",
                className: "btn yellow btn-outline ",
                text: "اكسل",
                exportOptions: {
                    columns: '.show-print'
                }
            }, {
                extend: "colvis",
                className: "btn dark btn-outline",
                text: "المكونات"
            }],
        responsive: !0,
        "columnDefs": [
            {// set default column settings
                "orderable": false,
                "searchable": false,
//                        "targets": [lstone],
                "className": 'hide-print'
            },
            {// set default column settings
                "orderable": false,
                "searchable": false,
//                        "targets": [belastone],
                "className": 'hide-print'
            },
            {// set default column settings
                "orderable": false,
                "searchable": false,
//                        "targets": [thrdone],
                "className": 'hide-print'
            },
            {targets: '_all', "className": 'show-print'}],
        order: [
            [0, "asc"]
        ],
        lengthMenu: [
            [5, 10, 15, 20, -1],
            [5, 10, 15, 20, "All"]
        ],
        pageLength: 10,
        dom: "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>"
    });

    var nestmenu = function () {
        $('#nestable_list_2').nestable();

        $('#nestable_list_menu > button').on('click', function (e) {

            var actionn = $(this).attr("data-action");
            //var target = $(e.target),
            // action = target.data('action');
            if (actionn === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (actionn === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });

    }
    $("#divOne").click(function () {
        $(".getTabs h2").removeClass("activeTab");
        $(this).addClass("activeTab");
        $("#rowOne").fadeIn();
        $("#rowTwo").hide();
        $("#rowThree").hide();
        $("#rowFour").hide();
        $("#rowFive").hide();
    });

    $("#divTwo").click(function () {
        $(".getTabs h2").removeClass("activeTab");
        $(this).addClass("activeTab");
        $("#rowOne").hide();
        $("#rowTwo").fadeIn();
        $("#rowThree").hide();
        $("#rowFour").hide();
        $("#rowFive").hide();
    });

    $("#divThree").click(function () {
        $(".getTabs h2").removeClass("activeTab");
        $(this).addClass("activeTab");
        $("#rowOne").hide();
        $("#rowTwo").hide();
        $("#rowThree").fadeIn();
        $("#rowFour").hide();
        $("#rowFive").hide();
    });

    $("#divFour").click(function () {
        $(".getTabs h2").removeClass("activeTab");
        $(this).addClass("activeTab");
        $("#rowOne").hide();
        $("#rowTwo").hide();
        $("#rowThree").hide();
        $("#rowFour").fadeIn();
        $("#rowFive").hide();
    });

    $("#divFive").click(function () {
        $(".getTabs h2").removeClass("activeTab");
        $(this).addClass("activeTab");
        $("#rowOne").hide();
        $("#rowTwo").hide();
        $("#rowThree").hide();
        $("#rowFour").hide();
        $("#rowFive").fadeIn();
    });

    $("#visitMore").click(function () {
        $("#visitForm").slideToggle();
    });

    $(".visitMore").click(function () {
        $(".visitForm").slideToggle();
    });



});


