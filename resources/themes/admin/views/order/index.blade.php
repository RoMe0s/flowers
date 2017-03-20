@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="orders-table">
                        <script>
                            var datatableСallbacks = {
                                fnRowCallback: function(row, data, displayIndex, displayIndexFull)
                                {
                                    if(data[2])
                                    {
                                        $('td', row).find('select').select2(window.select2Options);

                                        $('td', row).closest('tr').addClass("order-status-" + $('td:eq(6)', row).find('select').val());
                                    }

                                    $(row).find('td').not(':eq(6), :eq(7)').css("cursor", "pointer");

                                    $(row).find('td').not(':eq(6), :eq(7)').on("mouseover", function(){
                                       $(this).closest('tr').css("opacity", "0.7");
                                    });

                                    $(row).find('td').not(':eq(6), :eq(7)').on("mouseout", function () {
                                       $(this).closest('tr').css("opacity", "1");
                                    });

                                    $(row).find('td').not(':eq(6), :eq(7)').on("click", function() {
                                        window.location.href = '/admin/order/' + $(this).closest('tr').attr('id');
                                    });

                                },

                                fnFooterCallback: function (nFoot, aData, iStart, iEnd, aiDisplay) {

                                    _select = $('select', nFoot);

                                    _select.select2(window.select2Options);

                                    $table = _select.closest('table').DataTable(/*{
                                        deferRender: true,
                                        ajax : {
                                            url: window.location.href,
                                            type: "GET",
                                            data: {
                                                draw: true,
                                                status: _select.val()
                                            }
                                        }
                                    }*/);

                                    _select.on('change', function () {

                                        _val = $(this).val();

                                        $table.ajax.url(window.location.href + '?status=' + _val).load();

                                    });
                                }
                            }
                        </script>
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"], ['bStateSave' => true])
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.fio')],
                                ['text' => trans('labels.total'). '(' . trans('labels.rub') . ')'],
                                ['text' => trans('labels.prepay')],
                                ['text' => trans('labels.delivery date')],
                                ['text' => trans('labels.created_at')],
                                ['text' => trans('labels.status')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 6]],
                                ['text' => Form::select(null, $statuses, null, ['class' => 'select-2 input-sm form-control', 'placeholder' => trans('labels.status')])],
                                ['attr' => ['colspan' => 1]]
                            ])
                            ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop