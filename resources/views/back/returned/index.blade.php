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
            <div class="content-body">

                @if($returns->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست مرجوعی  ها</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>ردیف</th>
                                                <th>کاربر مربوطه</th>
                                                <th>شماره سفارش</th>
                                                <th>توضیحات کاربر</th>
                                                <th class="text-center">کد رهگیری پستی</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($returns as $return)
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    
                                                
                                                    <td>{{ $return->user->fullname }}</td>
                                                   
                                                    <td> {{$return->order_id }}</td>
                                                   
                                                    
                                                   <td>{{$return->description}}</td>
                                                   <td>
                                                    @if ($return->trakingCode)
                                                         {{$return->trakingCode}}
                                                    @else
                                                         متاسفانه ثبت نشده است                                                         
                                                    @endif
                                                </td>
                                              
                                                   <td>
                                                 
                                               
                                                    <a href="{{route('admin.return.product.index',['returned'=>$return->id])}}" class="btn btn-warning " >مشاهده کالا های مرجوعی</a>
                                                   </td>

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
                            <h4 class="card-title">لیست تیکت ها</h4>
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
                {{ $returns->links() }}

            </div>
        </div>
    </div>

    {{-- delete return modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19" style="display: none;" aria-hidden="true">
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
                        <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal">خیر</button>
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
