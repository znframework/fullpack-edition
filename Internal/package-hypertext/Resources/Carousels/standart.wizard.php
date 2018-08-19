<div id="{{ $carouselId }}" class="carousel slide" data-ride="carousel">

  @if( isset($carouseIndicators) )
  <ol class="carousel-indicators">
    @foreach( $carouselImages as $key => $image )
    {[$active = $key === 0 ? ' class="active"' : NULL]}
    <li data-target="#{{ $carouselId }}" data-slide-to="{{$key}}"{{$active}}></li>
    @endforeach
  </ol>
  @endif
  <div class="carousel-inner">
    @foreach( $carouselImages as $key => $image )
    {[$active = $key === 0 ? ' active' : NULL]}
    <div class="carousel-item{{$active}}">
      <img class="d-block w-100" src="{{ URL::base($image) }}" alt="{{ ZN\Filesystem::removeExtension(ZN\Datatype::divide($image, '/', -1)) }}">
    </div>
    @endforeach
  </div>
  <a class="carousel-control-prev" href="#{{ $carouselId }}" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">{{ $carouselPrevName ?: 'Previous' }}</span>
  </a>
  <a class="carousel-control-next" href="#{{ $carouselId }}" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">{{ $carouselNextName ?: 'carouselNextName' }}</span>
  </a>
</div>
