<div id="{{ $carouselId }}" class="carousel slide" data-ride="carousel">

  @if( isset($carouseIndicators) )
  {[$index = 0]}
  <ol class="carousel-indicators">
    @foreach( $carouselImages as $key => $image )
    {[
    $active = $index === 0 ? ' class="active"' : NULL
    ]}
    <li data-target="#{{ $carouselId }}" data-slide-to="{{$index}}"{{$active}}></li>
    {[$index++]}
    @endforeach
  </ol>
  @endif

  {[$index = 0]}
  <div class="carousel-inner">
    @foreach( $carouselImages as $key => $image )
      {[
        $active = $index === 0 ? ' active' : NULL;

        if( ! is_numeric($key) )
        {
          $attr  = $image;
          $image = $key;
        }
        else
        {
          $attr = NULL;
        }
      ]}
      <div class="carousel-item{{$active}}">
        <img class="{{ $attr['class'] ?? NULL }}" src="{{ URL::base($image) }}" alt="{{ $attr['alt'] ?? ZN\Filesystem::removeExtension(ZN\Datatype::divide($image, '/', -1)) }}">
        @if( $caption = ($attr['caption'] ?? NULL) )
        <div class="carousel-caption d-none d-md-block">
          <h5>{{$caption[0] ?? NULL}}</h5>
          <p>{{$caption[1] ?? NULL}}</p>
        </div>
        @endif
      </div>
      {[$index++]}
    @endforeach
  </div>

  <a class="carousel-control-prev" href="#{{ $carouselId }}" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#{{ $carouselId }}" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
</div>
