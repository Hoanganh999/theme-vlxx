<div id="video-{{$movie->id}}" class="video-item">
    <a title="{{$movie->name}}"
        href="{{$movie->getUrl()}}">
        <img class="video-image lazyload" src="{{$movie->getPosterUrl()}}"
            data-original="{{$movie->getPosterUrl()}}"
            alt="{{$movie->name}}" width="640" height="480" style="">
        <div class="ribbon">Vietsub</div>
    </a>
    <div class="video-name">
        <a title="{{$movie->name}}"
            href="{{$movie->getUrl()}}">{{$movie->name}}</a>
    </div>
</div>
