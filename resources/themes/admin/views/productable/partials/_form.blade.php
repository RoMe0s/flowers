@include('productable.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs"> 
                <li class="active">
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="general">
                    @include('productable.tabs.general')
                </div>
            </div>
        </div>

    </div>
</div>

@include('productable.partials._buttons')
