@extends('front::user.layouts.master')

@section('user-content')
    <!-- Start Content -->
    <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12">
      
        @if ($order->items->count())
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-sm-title title-wide mb-1 no-after-title-wide dt-sl mb-2 px-res-1">
                        <h2>{{ trans('front::messages.profile.all-orders') }}</h2>
                    </div>
                    <div class="dt-sl">
                        <form action="{{ route('front.returned.store', ['order' => $order->id]) }}" method="post">
                            @csrf
                            
                            <div class="table-responsive">
                                <table class="table table-order">
                                    <thead>
                                        <tr>
                                            <th>#</th>
    
                                            <th>ردیف</th>
                                            <th>نام کالا</th>
                                            <th>شماره سفارش</th>
                                            <th>قیمت</th>
                                            <th>تاریخ</th>
                                            <th>تعداد مرجوعی</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        @foreach ($order->items as $item)
                                            
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="return_items[{{ $item->id }}][checked]" value="{{ $item->id }}">
                                                    <input type="hidden" name="return_items[{{ $item->id }}][item_id]" value="{{ $item->id }}">
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-info">{{ $item->product->title }}</td>
                                                <td>{{ $item->order_id }}</td>
                                                <td>{{ trans('front::messages.currency.prefix') }}{{ number_format($item->price) }}
                                                    {{ trans('front::messages.currency.suffix') }}</td>
                                                <td>{{ jdate($item->created_at)->format('%d %B %Y') }}</td>
    
                                                <td>
                                                    <div class="number-input">
                                                        <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()"></button>
                                                        <input class="quantity" name="return_items[{{ $item->id }}][quantity]" min="1" max="{{$item->quantity}}" value="1" type="number" required>
                                                        <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                    </tbody>
                                    
                                </table>
    
                            </div>
                            <div>
                                <label>دلیل شما برای مرجوعی</label>
                                <textarea name="description" class="col-md-7 form-control"></textarea>
                                <button type="submit" class="btn btn-success mt-2"> ثبت مرجوعی</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="page dt-sl dt-sn pt-3">
                        <p>{{ trans('front::messages.profile.there-nothing-show') }}</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <!-- End Content -->
@endsection
