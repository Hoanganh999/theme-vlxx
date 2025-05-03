<div id="video-{{$movie->id}}" class="video-item">
    <a title="{{$movie->name}}"
        href="{{$movie->getUrl()}}">
        <img class="video-image lazyload" src="{{$movie->getPosterUrl()}}"
    data-original="{{$movie->getPosterUrl()}}"
    alt="{{$movie->name}}"
    style="width: 200px; height: 1200px; object-fit: cover; display: block;">
        <div class="ribbon">{{$movie->language}}</div>
    </a>
    <div class="video-name">
        <a title="{{$movie->name}}"
            href="{{$movie->getUrl()}}">{{$movie->name}}</a>
    </div>
</div>
