    var start_html = '<div class="div_list hidden-md hidden-lg container" style="padding: 0px !important;">%s</div>',
            div_html = {
                'headers': '<div class="text-center headers" style=" display: table;width: 100%;"><img src="%s" class="img-rounded"><h4>%s</h4></div>',
                'content_data': '<div class="row row_list" style="margin: 0px !important;"><div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 content_tr">%s</div><div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 pull-left content_td">%s</div></div>',
                'content_data_row': '<div class="row row_list content_data_row" style="margin: 0px !important;"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content_tr">%s</div></div>',
                'controls': '<div class="controls" style=" display: table;width: 100%;">%s</div>'
            };


    $(document).ready(function () {

        var $trim = function (str) {
            str = new String(str);
            return str.replace(/^\s+|\s+$/g, '');
        };

        var $sprintf = function (format, etc) {
            var text_format = new String(format);
            var parameters = arguments;
            var i = 1;
            return text_format.replace(/%((%)|s)/g, function (b) {
                return $trim(b[2] || parameters[i++]);
            });
        };

        $('table').each(function (table_index) {
            var tbody_html_list = [], th_list = [], i = 0;

            $('table:eq(' + table_index + ') thead tr th').each(function (th_index) {
                var $_content_tr = $('table:eq(' + table_index + ') thead tr th').eq(th_index).text();
                th_list[i] = $_content_tr;
                i++;
            });
//            console.clear();
//            console.log(th_list);

            i = 0;
            $('table:eq(' + table_index + ') tbody tr td').each(function (td_index) {
                var $_content_tr = (Boolean(th_list[i]) != false ? th_list[i] : '');

                var $_content_td = $('table:eq(' + table_index + ') tbody tr td').eq(td_index).html();
                if ($trim($_content_tr) == '') {
                    tbody_html_list[td_index] = $sprintf(div_html.content_data_row, $_content_td) + '<hr>';
                } else {
                    tbody_html_list[td_index] = $sprintf(div_html.content_data, $_content_tr, $_content_td) + '<hr>';
                }
                i++;
//                console.log(table_index);
                if (i == ($('table:eq(' + table_index + ') tbody tr:eq(0) td').length)) {
                    i = 0;
                }

            });

            $('table').eq(table_index).addClass('hidden-xs');
            $('table').eq(table_index).addClass('hidden-sm');
            var table_html = $sprintf(start_html, tbody_html_list.join(' '));
            $('table').eq(table_index).after(table_html);
            tbody_html_list = [];
        });



//        $('#cssmenu ul li').each(function (link_index) {
//            var link_html_list = [], link_list = [], i = 0;
//
//            if ($('#cssmenu ul li').hasClass('has-sub')) {
//                $item_title = $('#cssmenu ul li a:eq(0)');
//                console.log($item_title);
//            }
//
////            var $_content_tr = (Boolean(th_list[i]) != false ? th_list[i] : '');
////            var $_content_td = $('cssmenu').eq(td_index).html();
////            i++;
//        });





    });