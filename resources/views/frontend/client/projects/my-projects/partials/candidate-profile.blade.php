<div class="sticky-top">
    <div class="candidate-info company-info">
        <div class="candidate-detail text-center">
            <div class="candidate-des">
                @php
                    if (auth()->user()->image === null) {
                        $image = config('app.url') . '/dashboard/app-assets/images/avatars/default-user.png';
                    } elseif (auth()->user()->image === 'https://picsum.photos/200/300') {
                        $image = 'https://picsum.photos/200/300';
                    } else {
                        $image = asset('storage') . '/' . auth()->user()->image;
                    }
                @endphp
                <a href="javascript:void(0);">
                    <img alt="" src="{{ $image }}" id="imgProfile">
                </a>
                <div class="upload-link" title="update" data-toggle="tooltip" data-placement="right">
                </div>
            </div>
            <div class="candidate-title">
                <h4 class="m-b5"><a
                        href="javascript:void(0);">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</a>
                </h4>
                <p class="text-center">{{ auth()->user()->email }}</p>
            </div>
            @php
                $average_rating_user = 0;
                if (
                    auth()
                        ->user()
                        ->ratings()
                        ->get()
                        ->count() > 0
                ) {
                    $average_rating_user =
                        auth()
                            ->user()
                            ->ratings()
                            ->get()
                            ->sum('rating') /
                        auth()
                            ->user()
                            ->ratings()
                            ->get()
                            ->count();
                }
            @endphp
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <span class="ratings_user" data-rating-user="{{ $average_rating_user }}"></span>
                    ({{ floor($average_rating_user) }}) |
                    {{ auth()->user()->ratings()->get()->count() }}
                </div>
            </div>
        </div>
        @include('layouts.frontend.app-menu-profile')
    </div>
</div>
