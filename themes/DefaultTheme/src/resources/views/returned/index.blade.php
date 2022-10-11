@extends('front::user.layouts.master')

@section('user-content')
    <!-- Start Content -->
    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
        {{-- @dd($returns->orders()) --}}
        @if ($returns->count())
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                        <h2>همه مرجوعی ها</h2>
                    </div>
                    <div class="dt-sl">
                        <div class="table-responsive">
                            <table class="table table-order">
                                <thead>
                                    <tr>
                                        <th>#</th>


                                        <th>شماره سفارش</th>
                                        <th>توضیحات شما </th>
                                        <th>تاریخ</th>
                                        <td>مشاهده</td>
                                        <td>ثبت کد پیگیری پستی</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($returns as $return)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $return->order_id }}</td>
                                            <td>
                                                {{ $return->description }}
                                            </td>
                                            <td>{{ jdate($return->created_at)->format('%d %B %Y') }}</td>
                                            @if ($return->status == 'pending')
                                                <td>
                                                    <p class="text-warning">در حال بررسی</p>
                                                </td>
                                            @elseif ($return->status == 'accepted')
                                                <td>
                                                    <p class="text-success">مورد قبول</p>
                                                </td>
                                            @elseif ($return->status == 'rejected')
                                                <td>
                                                    <p class="text-danger">رد شد</p>
                                                </td>
                                            @elseif($return->status == 'confirmation')
                                                <td>
                                                    <p style="color: rgb(7, 204, 0)">تایید اولیه</p>
                                                </td>
                                            @endif
                                            @if ($return->status == 'pending')
                                                <td>
                                                    <p class="text-warning">در حال بررسی</p>
                                                </td>
                                            @elseif($return->status == 'confirmation')
                                                <td>
                                                    <p style="color: rgb(7, 204, 0)">تایید اولیه</p>
                                                </td>
                                            @elseif ($return->status == 'accepted')
                                                <td>
                                                    <p class="text-success">همکاران ما با شما تماس گرفته و محصول مورد نظر را
                                                        از طریق پست به نشانی ما ارسال فرمایید</p>
                                                </td>
                                            @elseif ($return->status == 'rejected')
                                                <td>
                                                    <p class="text-danger">{{ $return->reject_reason }}</p>
                                                </td>
                                            @endif

                                            <td>
                                                <div>

                                                    <div>

                                                        <a href="{{ route('front.returned.index.product', ['returned' => $return->id]) }}"
                                                            class="btn btn-success">مشاهده کالا هایی در این سفارش مرجوعی
                                                            کرده اید </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                    @if ($return->TrackingCode)
                                                        <p>{{ $return->TrackingCode }}</p>
                                                    @else
                                                        <form action="{{route('front.returned.traking',['return'=>$return->id])}}" method="POST">
                                                            @csrf

                                                            <input class="form-control" type="text" name="traking"
                                                                placeholder="لطفا بعد از تایید اولیه کالا محصول را به دفتر پست به شماره پستی ما ارسال نمایید">

                                                            <button type="submit" class="btn btn-success mt-2">ثبت کد پیگیری پستی</button>
                                                        </form>
                                                    @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="page dt-sl dt-sn pt-3">
                        <p>مرجوعی موجود نیست</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
            {{ $returns->links('front::components.paginate') }}
        </div>

    </div>
    <!-- End Content -->
@endsection
