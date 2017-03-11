@extends('layouts.master')

@section('content')
    <section>

        {!! $model->content !!}

        <div class="row">
            <?php $i = 0; ?>

            @foreach(File::allFiles('uploads/reviews') as $file)
                @if($i % 4 == 0 && $i != 0) {!! '</div><br><div class="row">' !!} @endif

                <div class="col-md-3 col-sm-4 col-xs-6">
                    <a href="{{ asset((string) $file) }}" data-lightbox="group1">
                        <img src="{{ url((string) $file) }}" alt="" style="width: 100%; margin-bottom: 20px;">
                    </a>
                </div>

                <?php $i++; ?>
            @endforeach
        </div>
    </section>
@endsection