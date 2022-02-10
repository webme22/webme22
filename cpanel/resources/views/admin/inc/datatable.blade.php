<!-- Required datatable js-->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{asset('plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables/jszip.min.js')}}"></script>
<script src="{{asset('plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

<script>
    let datatable = $('#datatable-buttons').DataTable(
        {dom:"Bflrtip",
            buttons:[{extend:"copy",className:"btn-primary"},{extend:"csv",className:"btn-primary"},
                {extend:"excel",className:"btn-primary"},{extend:"pdf",className:"btn-primary"},
                {extend:"print",className:"btn-primary"}],
            responsive:!0,
            "order": [[ 1, "asc" ]],
            columnDefs: [ { orderable: false, targets: [0, -1] }]
        });
    datatable.on('page', function () {
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                let all = $('div.checkbox input[type=checkbox]:visible');
                let all_length = all.length - 1;
                let checked_length = $('div.checkbox input[type=checkbox]:visible:checked').length;
                if (all_length == checked_length){
                    all.prop('checked', true);
                }
                else {
                    $('#checkbox--1').prop('checked', false);
                }
                $("input[type=checkbox][data-toggle^=toggle]").bootstrapToggle();

                let checked = $('div.checkbox input[type=checkbox]:visible:checked');
                let checked_vals = [];
                checked.each(function (){
                    let val = $(this).attr('data-value');
                    if (parseInt(val) !== -1) {checked_vals.push(val);}
                });
                $('input[name=bulk_delete]').val(JSON.stringify(checked_vals));

            });
        });

    });
    datatable.on('order', function () {
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                $("input[type=checkbox][data-toggle^=toggle]").bootstrapToggle();

            });
        });
    })
</script>
