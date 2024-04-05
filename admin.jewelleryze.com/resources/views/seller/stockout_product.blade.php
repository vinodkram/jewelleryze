@extends('seller.master_layout')
@section('title')
<title>{{__('admin.Stock out products')}}</title>
@endsection
@section('seller-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Stock out products')}}</h1>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th width="5%">{{__('admin.SN')}}</th>
                                    <th width="30%">{{__('admin.Name')}}</th>
                                    <th width="10%">{{__('admin.Price')}}</th>
                                    <th width="15%">{{__('admin.Photo')}}</th>
                                    <th width="15%">{{__('admin.Type')}}</th>
                                    <th width="15%">{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td><a target="_blank" href="{{ $frontend_view.$product->slug }}">{{ $product->short_name }}</a></td>
                                        <td>{{ $setting->currency_icon }}{{ $product->price }}</td>
                                        <td> <img class="rounded-circle" src="{{ asset($product->thumb_image) }}" alt="" width="80px"></td>
                                        <td>
                                            @if ($product->new_product == 1)
                                            <span class="badge badge-primary p-1">{{__('admin.New')}}</span>
                                            @endif

                                            @if ($product->is_featured == 1)
                                            <span class="badge badge-success p-1">{{__('admin.Featured')}}</span>
                                            @endif

                                            @if ($product->is_top == 1)
                                            <span class="badge badge-warning p-1">{{__('admin.Top')}}</span>
                                            @endif

                                            @if ($product->is_best == 1)
                                            <span class="badge badge-danger p-1">{{__('admin.Best')}}</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('seller.product.edit',$product->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>

                                            <div class="dropdown d-inline">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                                </button>

                                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -131px, 0px);">
                                                <a class="dropdown-item has-icon" href="{{ route('seller.product-gallery',$product->id) }}"><i class="far fa-image"></i> {{__('admin.Image Gallery')}}</a>

                                                <a class="dropdown-item has-icon" href="{{ route('seller.product-variant',$product->id) }}"><i class="fas fa-cog"></i> {{__('admin.Product Variant')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

@endsection
