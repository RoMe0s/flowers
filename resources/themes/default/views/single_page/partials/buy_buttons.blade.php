@if($present instanceof \App\Models\Product)
    @include('partials.content.product', ['model' => $present])
@elseif($present instanceof \App\Models\Set)
    @include('partials.content.set', ['model' => $present])
@elseif($present instanceof \App\Models\Bouquet)
    @include('partials.content.bouquet', ['model' => $present])
@endif