<!-- Start footer -->
<footer class="main-footer dt-sl position-relative">
    <div class="back-to-top">
        <a href="#"><span class="icon"><i class="mdi mdi-chevron-up"></i></span> <span>{{ trans('front::messages.index.back-to-top') }}</span></a>
    </div>
    <div class="container main-container">


        <div class="footer-widgets">
            <div class="row">
                @foreach($footer_links as $group)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="widget-menu widget card">
                            <header class="card-header">
                                <h3 class="card-title">{{ option('link_groups_' . $group['key'], $group['name']) }}</h3>
                            </header>
                            <ul class="footer-menu">
                                @foreach($links->where('link_group_id', $group['key']) as $link)
                                    <li>
                                        <a href="{{ $link->link }}">{{ $link->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach

                <div class="col-12 col-md-6 col-lg-3">

                    <div class="symbol footer-logo">

                        @if(option('info_enamad'))
                            {!! option('info_enamad') !!}
                        @endif

                        @if(option('info_samandehi'))
                            {!! option('info_samandehi') !!}
                        @endif

                    </div>
                    <div class="socials">
                        <div class="footer-social">
                            <ul class="text-center">

                              
                                @if(option('social_aparat'))
                                    <li><a href="{{ option('social_aparat') }}" style="    filter: grayscale(1);opacity: 0.8;">
                                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="22px" height="22px"><path fill="#263238" d="M21.881 6.136l-4.315-.974c-3.52-.795-7.017 1.414-7.812 4.934l-.983 4.351C11.603 9.95 16.357 6.788 21.881 6.136zM6.136 26.119l-.974 4.315c-.795 3.52 1.414 7.017 4.934 7.812l4.351.983C9.95 36.396 6.788 31.643 6.136 26.119zM37.904 9.754l-4.351-.983c4.497 2.832 7.659 7.586 8.311 13.109l.974-4.315C43.633 14.047 41.424 10.549 37.904 9.754zM26.119 41.864l4.315.974c3.52.795 7.017-1.414 7.812-4.934l.983-4.351C36.397 38.05 31.643 41.212 26.119 41.864z"/><path fill="#e91e63" d="M24,8C15.163,8,8,15.163,8,24s7.163,16,16,16s16-7.163,16-16S32.837,8,24,8z M20,13 c2.209,0,4,1.791,4,4c0,2.209-1.791,4-4,4s-4-1.791-4-4C16,14.791,17.791,13,20,13z M17,32c-2.209,0-4-1.791-4-4 c0-2.209,1.791-4,4-4s4,1.791,4,4C21,30.209,19.209,32,17,32z M22,24c0-1.105,0.895-2,2-2s2,0.895,2,2c0,1.105-0.895,2-2,2 S22,25.105,22,24z M28,35c-2.209,0-4-1.791-4-4c0-2.209,1.791-4,4-4s4,1.791,4,4C32,33.209,30.209,35,28,35z M31,24 c-2.209,0-4-1.791-4-4c0-2.209,1.791-4,4-4s4,1.791,4,4C35,22.209,33.209,24,31,24z"/></svg>
                                    </a></li>
                                @endif

                                @if(option('social_eita'))
                                    <li><a href="{{ option('social_eita') }}">
                                        <svg width="18px" height="18px" viewBox="0 0 24 24" fill="#666" role="img" xmlns="http://www.w3.org/2000/svg"><path d="M5.968 23.942a6.624 6.624 0 0 1-2.332-.83c-1.62-.929-2.829-2.593-3.217-4.426-.151-.717-.17-1.623-.15-7.207C.288 5.47.274 5.78.56 4.79c.142-.493.537-1.34.823-1.767C2.438 1.453 3.99.445 5.913.08c.384-.073.94-.08 6.056-.08 6.251 0 6.045-.009 7.066.314a6.807 6.807 0 0 1 4.314 4.184c.33.937.346 1.087.369 3.555l.02 2.23-.391.268c-.558.381-1.29 1.06-2.316 2.15-1.182 1.256-2.376 2.42-2.982 2.907-1.309 1.051-2.508 1.651-3.726 1.864-.634.11-1.682.067-2.302-.095-.553-.144-.517-.168-.726.464a6.355 6.355 0 0 0-.318 1.546l-.031.407-.146-.03c-1.215-.241-2.419-1.285-2.884-2.5a3.583 3.583 0 0 1-.26-1.219l-.016-.34-.309-.284c-.644-.59-1.063-1.312-1.195-2.061-.212-1.193.34-2.542 1.538-3.756 1.264-1.283 3.127-2.29 4.953-2.68.658-.14 1.818-.177 2.403-.075 1.138.198 2.067.773 2.645 1.639.182.271.195.31.177.555a.812.812 0 0 1-.183.493c-.465.651-1.848 1.348-3.336 1.68-2.625.585-4.294-.142-4.033-1.759.026-.163.04-.304.031-.313-.032-.032-.293.104-.575.3-.479.334-.903.984-1.05 1.607-.036.156-.05.406-.034.65.02.331.053.454.192.736.092.186.275.45.408.589l.24.251-.096.122a4.845 4.845 0 0 0-.677 1.217 3.635 3.635 0 0 0-.105 1.815c.103.461.421 1.095.739 1.468.242.285.797.764.886.764.024 0 .044-.048.044-.106.001-.23.184-.973.326-1.327.423-1.058 1.351-1.96 2.82-2.74.245-.13.952-.47 1.572-.757 1.36-.63 2.103-1.015 2.511-1.305 1.176-.833 1.903-2.065 2.14-3.625.086-.57.086-1.634 0-2.207-.368-2.438-2.195-4.096-4.818-4.37-2.925-.307-6.648 1.953-8.942 5.427-1.116 1.69-1.87 3.565-2.187 5.443-.123.728-.169 2.08-.093 2.75.193 1.704.822 3.078 1.903 4.156a6.531 6.531 0 0 0 1.87 1.313c2.368 1.13 4.99 1.155 7.295.071.996-.469 1.974-1.196 3.023-2.25 1.02-1.025 1.71-1.88 3.592-4.458 1.04-1.423 1.864-2.368 2.272-2.605l.15-.086-.019 3.091c-.018 2.993-.022 3.107-.123 3.561-.6 2.678-2.54 4.636-5.195 5.242l-.468.107-5.775.01c-4.734.008-5.85-.002-6.19-.056z"/></svg>
                                    </a></li>
                                @endif

                                @if(option('social_sorosh'))
                                    <li><a href="{{ option('social_sorosh') }}">
                                        <img src="{{ asset('back/app-assets/images/icons/soroush.png') }}">
                                    </a></li>
                                @endif
                                

                                @if(option('social_instagram'))
                                    <li><a href="{{ option('social_instagram') }}"><i class="mdi mdi-instagram"></i></a></li>
                                @endif

                                @if(option('social_whatsapp'))
                                    <li><a href="{{ option('social_whatsapp') }}"><i class="mdi mdi-whatsapp"></i></a></li>
                                @endif

                                @if(option('social_telegram'))
                                    <li><a href="{{ option('social_telegram') }}"><i class="mdi mdi-telegram"></i></a></li>
                                @endif

                                @if(option('social_facebook'))
                                    <li><a href="{{ option('social_facebook') }}"><i class="mdi mdi-facebook"></i></a></li>
                                @endif

                                @if(option('social_twitter'))
                                    <li><a href="{{ option('social_twitter') }}"><i class="mdi mdi-twitter"></i></a></li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="copyright">
        <div class="container main-container">
            <p class="text-center">{{ option('info_footer_text') }}</p>
        </div>
    </div>
</footer>
<!-- End footer -->
