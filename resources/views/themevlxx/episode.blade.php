@extends('themes::themevlxx.layout')

@section('content')
    <style>
        #player-wrapper {
            position: absolute;
            /* Position it absolutely within the parent */
            top: 0;
            left: 0;
            width: 100%;
            /* Full width of the parent */
            height: 100%;
            /* Full height of the parent */
        }
    </style>
    <div id="container">
        <h2 id="page-title" class="breadcrumb" style="text-transform: none;">{{ $currentMovie->name }}</h2>
        <div id="video" data-id="{{ $currentMovie->id }}">
            <div class="video-player">
                <div id="player-wrapper" style="aspect-ratio: 16/9"></div>
            </div>
            <div id="video-actions">
                @foreach ($currentMovie->episodes->where('slug', $episode->slug)->where('server', $episode->server) as $server)
                    <a onclick="chooseStreamingServer(this)" data-type="{{ $server->type }}" data-id="{{ $server->id }}"
                        data-link="{{ $server->link }}" class="streaming-server video-server bt_normal">
                        #{{ $loop->iteration }}
                    </a>
                @endforeach
                <div class="video-stats" style="float: right">
                    <span class="rating" id="rating-percentage">{{ $currentMovie->getRatingStar() }}</span>
                    <span class="views"><span>{{ vlxx_format_view($currentMovie->view_total) }}</span></span>
                </div>
            </div>
            <div class="clear"></div>
            <div class="video-info">
                <span class="video-code">{{ $currentMovie->origin_name }}</span>
                <span class="video-link">{{ $currentMovie->language }}</span>
            </div>
            <div class="clear"></div>
            
    
            <h4 class="title-h cor4">Danh sách tập</h4>

@foreach ($currentMovie->episodes->sortBy([['server', 'asc']])->groupBy('server') as $server => $data)
    <!-- Server tab -->
    <div class="server-tabs">
        <button class="server-tab active">
            <i class="fa fa-server"></i> {{ $server }}
        </button>
    </div>

    <!-- Danh sách tập -->
    <div class="player-list-box">
        <div class="anthology-list select-a">
            <div class="anthology-list-box">
                <ul class="episode-list">
                    @foreach ($data->sortBy('name', SORT_NATURAL)->groupBy('name') as $name => $item)
                        <li class="episode-item {{ $item->contains($episode) ? 'active' : '' }}">
                            <a href="{{ $item->sortByDesc('type')->first()->getUrl() }}">
                                <span>{{ $name }}</span>
                                @if ($item->contains($episode))
                                    <em class="playing"><i></i><i></i><i></i><i></i></em>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endforeach

<style>
/* Server tabs */
.server-tabs {
    margin: 15px 0 10px;
}
.server-tab {
    padding: 8px 16px;
    border: none;
    border-radius: 18px;
    background: #222;
    color: #bbb;
    font-size: 14px;
    cursor: pointer;
    transition: 0.2s;
}
.server-tab:hover {
    background: #b93f35;
    color: #fff;
}
.server-tab.active {
    background: #b93f35;
    color: #fff;
    font-weight: bold;
}

/* Danh sách tập */
.episode-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 0;
    margin: 0;
    list-style: none;
}
.episode-item a {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 60px;
    padding: 8px 12px;
    border-radius: 6px;
    background: #1a1a1a;
    border: 1px solid #fff; /* viền trắng */
    color: #ccc;
    font-size: 14px;
    text-decoration: none;
    transition: 0.2s;
}
.episode-item a:hover {
    background: #b93f35;
    color: #fff;
    transform: translateY(-2px);
}
.episode-item.active a {
    background: #b93f35;
    color: #fff;
    font-weight: bold;
}

/* Icon sóng tập đang phát */
.playing {
    display: flex;
    margin-left: 6px;
}
.playing i {
    display: block;
    width: 3px;
    height: 12px;
    margin: 0 1px;
    background: #fff;
    border-radius: 2px;
    animation: equalizer 1s infinite ease-in-out;
}
.playing i:nth-child(2) { animation-delay: 0.2s; }
.playing i:nth-child(3) { animation-delay: 0.4s; }
.playing i:nth-child(4) { animation-delay: 0.6s; }

@keyframes equalizer {
    0%, 40%, 100% { transform: scaleY(0.3); }
    20% { transform: scaleY(1); }
}
</style>
            <style>
.video-content {
    position: relative;
    max-width: 600px;
    color: #ddd;
    font-size: 16px;
}

/* đoạn mô tả có hiệu ứng mờ */
.video-description {
    position: relative;
    overflow: hidden;
    max-height: 6em; /* khoảng 3 dòng */
    line-height: 1.5em;
    transition: max-height 0.3s ease;
}
.video-description::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3em;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
}
.video-description.expanded {
    max-height: none;
}
.video-description.expanded::after {
    display: none;
}

/* nút xem thêm */
.toggle-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 10px auto 0;
    padding: 6px 16px;
    cursor: pointer;
    border: none;
    border-radius: 20px;
    background: transparent;
    color: #fff;
    font-size: 15px;
    font-weight: 500;
    transition: background 0.2s ease;
}
.toggle-btn:hover {
    background: rgba(255,255,255,0.1);
}

/* icon mũi tên */
.toggle-btn svg {
    width: 16px;
    height: 16px;
    margin-right: 6px;
    transition: transform 0.3s ease;
    fill: #fff;
}
.toggle-btn.expanded svg {
    transform: rotate(180deg);
}
</style>

<div class="video-content">
    <div class="video-description">
        {!! $currentMovie->content !!}
    </div>
    <button class="toggle-btn">
        <svg viewBox="0 0 24 24">
            <path d="M7 10l5 5 5-5z"/>
        </svg>
        <span class="text">Xem thêm</span>
    </button>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const desc = document.querySelector(".video-description");
    const btn = document.querySelector(".toggle-btn");
    const text = btn.querySelector(".text");

    btn.addEventListener("click", function () {
        const expanded = desc.classList.toggle("expanded");
        btn.classList.toggle("expanded", expanded);
        text.innerText = expanded ? "Rút gọn" : "Xem thêm";
    });
});
</script>
                <div class="video-tags">
                    <div class="actress-tag">
                        {!! count($currentMovie->actors)
                            ? $currentMovie->actors->map(function ($actor) {
                                    return '<a href="' . $actor->getUrl() . '" tite="Diễn viên ' . $actor->name . '">' . $actor->name . '</a>';
                                })->implode(' ')
                            : 'Đang cập nhật' !!}
                    </div>
                    <div class="category-tag">
                        {!! $currentMovie->categories->map(function ($category) {
                                return '<a href="' . $category->getUrl() . '" tite="' . $category->name . '">' . $category->name . '</a>';
                            })->implode(' ') !!}
                    </div>
                </div>
            </div>
        </div>
        <div id="video-list">
            <h2 class="breadcrumb">Phim liên quan</h2>
            @foreach ($movie_related as $movie)
                <div id="video-1944" class="video-item">
                    <a title="{{$movie->name}}"
                        href="{{$movie->getUrl()}}">
                        <img class="video-image lazyload" src="{{$movie->getPosterUrl()}}"
    data-original="{{$movie->getPosterUrl()}}"
    alt="{{$movie->name}}"
    style="width: 178px; height: 107px; object-fit: cover; display: block;">
                        <div class="ribbon">{{$movie->language}}</div>
                    </a>
                    <div class="video-name">
                        <a title="{{$movie->name}}"
                            href="{{$movie->getUrl()}}">{{$movie->name}}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        // Function to check if the device is mobile
        function isMobile() {
            return /Mobi|Android/i.test(navigator.userAgent);
        }

        // Select the video player div
        const videoPlayer = document.querySelector('.video-player');

        // Add the appropriate class
        if (isMobile()) {
            videoPlayer.classList.add('mobile');
        } else {
            videoPlayer.classList.add('desktop');
        }
    </script>
@endsection

@push('scripts')
    <script src="/themes/vlxx/static/player/skin/juicycodes.js"></script>
    <link href="/themes/vlxx/static/player/skin/juicycodes.css" rel="stylesheet" type="text/css">
    <script src="/themes/vlxx/static/player/jwplayer.js"></script>


    <script>
        var episode_id = {{ $episode->id }};
        const wrapper = document.getElementById('player-wrapper');
        const vastAds = "{{ Setting::get('jwplayer_advertising_file') }}";

        function chooseStreamingServer(el) {
            const type = el.dataset.type;
            const link = el.dataset.link.replace(/^http:\/\//i, 'https://');
            const id = el.dataset.id;

            const newUrl =
                location.protocol +
                "//" +
                location.host +
                location.pathname.replace(`-${episode_id}`, `-${id}`);

            history.pushState({
                path: newUrl
            }, "", newUrl);
            episode_id = id;

            Array.from(document.getElementsByClassName('streaming-server')).forEach(server => {
                server.classList.remove('bt_active');
            })
            el.classList.add('bt_active')

            renderPlayer(type, link, id);
        }

        function renderPlayer(type, link, id) {
            if (type == 'embed') {
                if (vastAds) {
                    wrapper.innerHTML = `<div id="fake_jwplayer"></div>`;
                    const fake_player = jwplayer("fake_jwplayer");
                    const objSetupFake = {
                        key: "{{ Setting::get('jwplayer_license') }}",
                        aspectratio: "16:9",
                        width: "100%",
                        file: "/themes/vlxx/static/player/1s_blank.mp4",
                        volume: 100,
                        mute: false,
                        autostart: true,
                        advertising: {
                            tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                            client: "vast",
                            vpaidmode: "insecure",
                            skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                            skipmessage: "Bỏ qua sau xx giây",
                            skiptext: "Bỏ qua"
                        }
                    };
                    fake_player.setup(objSetupFake);
                    fake_player.on('complete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe sandbox = "allow-same-origin allow-scripts" width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                    fake_player.on('adSkipped', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe sandbox = "allow-same-origin allow-scripts" width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                    fake_player.on('adComplete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe sandbox = "allow-same-origin allow-scripts" width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                } else {
                    if (wrapper) {
                        wrapper.innerHTML = `<iframe sandbox = "allow-same-origin allow-scripts" width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                    }
                }
                return;
            }

            if (type == 'm3u8' || type == 'mp4') {
                wrapper.innerHTML = `<div id="jwplayer"></div>`;
                const player = jwplayer("jwplayer");
                const objSetup = {
                    key: "{{ Setting::get('jwplayer_license') }}",
                    aspectratio: "16:9",
                    width: "100%",
                    file: link,
                    image: "{{ $currentMovie->getPosterUrl() }}",
                    autostart: true,
                    controls: true,
                    primary: "html5",
                    playbackRateControls: true,
                    playbackRates: [0.5, 0.75, 1, 1.5, 2],
                    // sharing: {
                    //     sites: [
                    //         "reddit",
                    //         "facebook",
                    //         "twitter",
                    //         "googleplus",
                    //         "email",
                    //         "linkedin",
                    //     ],
                    // },
                    volume: 100,
                    mute: false,
                    logo: {
                        file: "{{ Setting::get('jwplayer_logo_file') }}",
                        link: "{{ Setting::get('jwplayer_logo_link') }}",
                        position: "{{ Setting::get('jwplayer_logo_position') }}",
                    },
                    advertising: {
                        tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                        client: "vast",
                        vpaidmode: "insecure",
                        skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                        skipmessage: "Bỏ qua sau xx giây",
                        skiptext: "Bỏ qua",
                        admessage: "Quảng cáo còn xx giây."
                    },
                    tracks: [{
                        "file": "/sub.vtt",
                        "kind": "captions",
                        label: "VN",
                        default: "true"
                    }],
                };

                if (type == 'm3u8') {
                    const segments_in_queue = 50;

                    var engine_config = {
                        debug: !1,
                        segments: {
                            forwardSegmentCount: 50,
                        },
                        loader: {
                            cachedSegmentExpiration: 864e5,
                            cachedSegmentsCount: 1e3,
                            requiredSegmentsPriority: segments_in_queue,
                            httpDownloadMaxPriority: 9,
                            httpDownloadProbability: 0.06,
                            httpDownloadProbabilityInterval: 1e3,
                            httpDownloadProbabilitySkipIfNoPeers: !0,
                            p2pDownloadMaxPriority: 50,
                            httpFailedSegmentTimeout: 500,
                            simultaneousP2PDownloads: 20,
                            simultaneousHttpDownloads: 2,
                            // httpDownloadInitialTimeout: 12e4,
                            // httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpDownloadInitialTimeout: 0,
                            httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpUseRanges: !0,
                            maxBufferLength: 300,
                            // useP2P: false,
                        },
                    };
                    // if (Hls.isSupported() && p2pml.hlsjs.Engine.isSupported()) {
                    //     var engine = new p2pml.hlsjs.Engine(engine_config);
                    //     player.setup(objSetup);
                    //     jwplayer_hls_provider.attach();
                    //     p2pml.hlsjs.initJwPlayer(player, {
                    //         liveSyncDurationCount: segments_in_queue, // To have at least 7 segments in queue
                    //         maxBufferLength: 300,
                    //         loader: engine.createLoaderClass(),
                    //     });
                    // } else {
                    player.setup(objSetup);
                    // }
                } else {
                    player.setup(objSetup);
                }

                player.addButton(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="jw-svg-icon jw-svg-icon-rewind2" viewBox="0 0 240 240" focusable="false"><path d="m 25.993957,57.778 v 125.3 c 0.03604,2.63589 2.164107,4.76396 4.8,4.8 h 62.7 v -19.3 h -48.2 v -96.4 H 160.99396 v 19.3 c 0,5.3 3.6,7.2 8,4.3 l 41.8,-27.9 c 2.93574,-1.480087 4.13843,-5.04363 2.7,-8 -0.57502,-1.174985 -1.52502,-2.124979 -2.7,-2.7 l -41.8,-27.9 c -4.4,-2.9 -8,-1 -8,4.3 v 19.3 H 30.893957 c -2.689569,0.03972 -4.860275,2.210431 -4.9,4.9 z m 163.422413,73.04577 c -3.72072,-6.30626 -10.38421,-10.29683 -17.7,-10.6 -7.31579,0.30317 -13.97928,4.29374 -17.7,10.6 -8.60009,14.23525 -8.60009,32.06475 0,46.3 3.72072,6.30626 10.38421,10.29683 17.7,10.6 7.31579,-0.30317 13.97928,-4.29374 17.7,-10.6 8.60009,-14.23525 8.60009,-32.06475 0,-46.3 z m -17.7,47.2 c -7.8,0 -14.4,-11 -14.4,-24.1 0,-13.1 6.6,-24.1 14.4,-24.1 7.8,0 14.4,11 14.4,24.1 0,13.1 -6.5,24.1 -14.4,24.1 z m -47.77056,9.72863 v -51 l -4.8,4.8 -6.8,-6.8 13,-12.99999 c 3.02543,-3.03598 8.21053,-0.88605 8.2,3.4 v 62.69999 z"></path></svg>',
                    "Forward 10 Seconds", () => player.seek(player.getPosition() + 10), "Forward 10 Seconds");
                player.addButton(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="jw-svg-icon jw-svg-icon-rewind" viewBox="0 0 240 240" focusable="false"><path d="M113.2,131.078a21.589,21.589,0,0,0-17.7-10.6,21.589,21.589,0,0,0-17.7,10.6,44.769,44.769,0,0,0,0,46.3,21.589,21.589,0,0,0,17.7,10.6,21.589,21.589,0,0,0,17.7-10.6,44.769,44.769,0,0,0,0-46.3Zm-17.7,47.2c-7.8,0-14.4-11-14.4-24.1s6.6-24.1,14.4-24.1,14.4,11,14.4,24.1S103.4,178.278,95.5,178.278Zm-43.4,9.7v-51l-4.8,4.8-6.8-6.8,13-13a4.8,4.8,0,0,1,8.2,3.4v62.7l-9.6-.1Zm162-130.2v125.3a4.867,4.867,0,0,1-4.8,4.8H146.6v-19.3h48.2v-96.4H79.1v19.3c0,5.3-3.6,7.2-8,4.3l-41.8-27.9a6.013,6.013,0,0,1-2.7-8,5.887,5.887,0,0,1,2.7-2.7l41.8-27.9c4.4-2.9,8-1,8,4.3v19.3H209.2A4.974,4.974,0,0,1,214.1,57.778Z"></path></svg>',
                    "Rewind 10 Seconds", () => player.seek(player.getPosition() - 10), "Rewind 10 Seconds");

                const resumeData = 'OPCMS-PlayerPosition-' + id;

                player.on('ready', function() {
                    if (typeof(Storage) !== 'undefined') {
                        if (localStorage[resumeData] == '' || localStorage[resumeData] == 'undefined') {
                            console.log("No cookie for position found");
                            var currentPosition = 0;
                        } else {
                            if (localStorage[resumeData] == "null") {
                                localStorage[resumeData] = 0;
                            } else {
                                var currentPosition = localStorage[resumeData];
                            }
                            console.log("Position cookie found: " + localStorage[resumeData]);
                        }
                        player.once('play', function() {
                            console.log('Checking position cookie!');
                            console.log(Math.abs(player.getDuration() - currentPosition));
                            if (currentPosition > 180 && Math.abs(player.getDuration() - currentPosition) >
                                5) {
                                player.seek(currentPosition);
                            }
                        });
                        window.onunload = function() {
                            localStorage[resumeData] = player.getPosition();
                        }
                    } else {
                        console.log('Your browser is too old!');
                    }
                });

                player.on('complete', function() {
                    if (typeof(Storage) !== 'undefined') {
                        localStorage.removeItem(resumeData);
                    } else {
                        console.log('Your browser is too old!');
                    }
                })

                function formatSeconds(seconds) {
                    var date = new Date(1970, 0, 1);
                    date.setSeconds(seconds);
                    return date.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
                }
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const episode = '{{ $episode->id }}';
            let playing = document.querySelector(`[data-id="${episode}"]`);
            if (playing) {
                playing.click();
                return;
            }

            const servers = document.getElementsByClassName('streaming-server');
            if (servers[0]) {
                servers[0].click();
            }
        });
    </script>
@endpush
