@extends('back.layouts.master')

@section('content')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">مدیریت مرجوعی ها
                                    </li>
                                    <li class="breadcrumb-item active">لیست مرجوعی
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
	        <button type="button" class="close" data-dismiss="alert">×</button>	
             <strong>{{ $message }}</strong>
                </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
	        <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
            </div>
            @endif
            <div class="content-body">

                @if ($item_return->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست مرجوعی ها</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>ردیف</th>

                                                <th>شماره سفارش</th>
                                                <th>نام کالا</th>
                                                <th>تعداد</th>
                                                <th>تعداد تایید اولیه</th>
                                                <th>تعداد تایید</th>
                                                <th>تعداد رد</th>
                                                <th>دلیل</th>
                                                <th>عملیات</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item_return as $item)
                                                <tr>


                                                    <form method="POST" action=" {{route('admin.return.update',['return' => $item->id])}} " >
                                                        @csrf
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $returned->order_id }}</td>
                                                        <td>
                                                            {{ $item->orderitem->product->title }}
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>
                                                            <div class="number-input">
                                                                <button type="button" tstyle-rtl.cssype="button"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown()"></button>
                                                                <input class="quantity" name="confirmation_count"
                                                                    min="0" max="{{ $item->quantity }}"
                                                                    value="{{ $item->ConfirmedCount ? $item->ConfirmedCount : 0 }}"
                                                                    type="number" required>
                                                                <button type="button"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
                                                                    class="plus"></button>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="number-input">
                                                                <button type="button" tstyle-rtl.cssype="button"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown()"></button>
                                                                <input class="quantity" name="accepted_count" min="0"max="{{ $item->quantity }}"value="{{ $item->accepted_count ? $item->accepted_count : 0 }}" type="number" required>
                                                                <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></button>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="number-input">
                                                                <button type="button" tstyle-rtl.cssype="button"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown()"></button>
                                                                <input class="quantity" name="reject_count" min="0"
                                                                    max="{{ $item->quantity }}"
                                                                    value="{{ $item->RejectedCount ? $item->RejectedCount : 0 }}"
                                                                    type="number" required>
                                                                <button type="button"
                                                                    onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
                                                                    class="plus"></button>
                                                            </div>
                                                        </td>
                                                            <td><textarea name="reject_reason" class="form-control" placeholder="دلیل رد و تایید و تاییید اولیه را کامل و با توضیحات به کاربر اعلام فرمایید">{{$item->reject_reason}}</textarea></td>
                                                        <td> <button type="submit" class="btn btn-success">ثبت
                                                                تغییرات</button></td>
                                                    </form>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </section>
                @else
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست مرجوعی ها</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="card-text">
                                    <p>چیزی برای نمایش وجود ندارد!</p>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif


            </div>
        </div>
    </div>

    {{-- delete return modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف صفحه دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form action="#" id="return-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/returned/index.js') }}"></script>
@endpush
