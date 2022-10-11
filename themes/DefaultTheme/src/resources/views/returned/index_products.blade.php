@extends('front::user.layouts.master')

@section('user-content')
    <!-- Start Content -->
    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">

        @if($item_return->count())

            <div class="row">
                <div class="col-12">
                    <div
                            class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                        <h2>همه مرجوعی های شماره سفارش {{$returned->order_id}}</h2>
                    </div>
                    <div class="dt-sl">
                        <div class="table-responsive">
                            <table class="table table-order">
                                <thead>
                                <tr>
                                    <th>#</th>
                                  
                                 
                                    <th>شماره سفارش</th>
                                    <th>نام کالا</th>
                                    <th>وضعیت</th>
                                    <td> تعداد مرجوعی شده</td>
                                    <th>دلیل</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item_return as $item)
                                        <tr>
                                            <td>{{ $loop->iteration}}</td>
                                            <td>{{$returned->order_id}}</td>
                                            <td>
                                               {{$item->orderitem->product->title}}
                                            </td>
                                            <td>
                                                <span style="color:#34f144 " >تعداد تایید اولیه</span> <span> {{$item->ConfirmedCount}}</span> 
                                                , <span style="color:#096811 ">تعداد تایید نهایی</span> <span> {{$item->accepted_count}}</span>
                                                , <span style="color:#c51e1e ">تعداد رد شده</span> <span> {{$item->RejectedCount}}</span>
                                            </td>
                                           
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{$item->reject_reason}}</td>
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
                        <p>مرجوعی برای سفارش های شما ثبت نشده است</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
             {{-- {{ $returns->links('front::components.paginate') }}  --}}
        </div>

    </div>
    <!-- End Content -->
@endsection
