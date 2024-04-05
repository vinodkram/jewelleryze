@extends('admin.master_layout')
@section('title')
<title>{{ $title }}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{ $title }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{ $title }}</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body" style="zoom:90%">
                      <div class="row">
                            <div class="col-sm-12 col-md-12 text-right">
                                <div id="dataTable_filter" class="dataTables_filter">
                                    <form action="{{ route('admin.all-order') }}" method="GET" enctype="multipart/form-data">
                                    @csrf
                                    <label>
                                      <select name="paymentMethod" class="form-control">
                                          <option {{ $paymentMethod == '' ? 'selected' : '' }} value="">{{__('admin.FilterByPaymentMethod')}}</option>
                                          <option {{ $paymentMethod == 'CCAVENUE' ? 'selected="selected"' : '' }} value="CCAVENUE">CCAVENUE</option>
                                          <option {{ $paymentMethod == "Cash on Delivery" ? 'selected="selected"' : '' }} value="Cash on Delivery">Cash on Delivery</option>
                                      </select>
                                    </label>
                                    <label>
                                      <select name="orderStatus" class="form-control">
                                          <option {{ $orderStatus == '' ? 'selected="selected"' : '' }} value="">{{__('admin.FilterByOrder')}}</option>
                                          <option {{ $orderStatus == '0' ? 'selected="selected"' : '' }} value="0">{{__('admin.Pending')}}</option>
                                          <option {{ $orderStatus == '1' ? 'selected="selected"' : '' }} value="1">{{__('admin.Pregress')}}</option>
                                          <option {{ $orderStatus == '2' ? 'selected="selected"' : '' }} value="2">{{__('admin.Delivered')}}</option>
                                          <option {{ $orderStatus == '3' ? 'selected="selected"' : '' }} value="3">{{__('admin.Completed')}}</option>
                                          <option {{ $orderStatus == '4' ? 'selected="selected"' : '' }} value="4">{{__('admin.Declined')}}</option>
                                      </select>
                                    </label>
                                    <label>
                                      <select name="paymentStatus" class="form-control">
                                          <option {{ $paymentStatus == '' ? 'selected="selected"' : '' }} value="">Filter by Payment Status</option>
                                          <option {{ $paymentStatus == '1' ? 'selected="selected"' : '' }} value="1">{{__('admin.success')}}</option>
                                          <option {{ $paymentStatus == '0' ? 'selected="selected"' : '' }} value="0">{{__('admin.Pending')}}</option>
                                      </select>
                                    </label>
                                    <label><input type="search" class="form-control" placeholder="Search By Order Id/Customer Name" aria-controls="dataTable" name="search" value="{{ $searchTxt }}"></label>&nbsp;
                                    <button class="btn btn-primary">{{__('admin.SearchLabel')}}</button>&nbsp;
                                    <a href="{{ route('admin.all-order') }}" class="btn btn-primary">{{__('admin.ResetLabel')}}</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="orderListing">
                            <thead>
                                <tr>
                                    <th width="5%">{{__('admin.SN')}}</th>
                                    <th width="10%">{{__('admin.Customer')}}</th>
                                    <th width="10%">{{__('admin.Order Id')}}</th>
                                    <th width="10%">{{__('admin.Date')}}</th>
                                    <th width="10%">{{__('admin.Quantity')}}</th>
                                    <th width="10%">{{__('admin.Amount')}}</th>
                                    <th width="10%">{{__('admin.Order Status')}}</th>
                                    <th width="10%">{{__('admin.Payment')}}</th>
                                    <th width="15%">{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                    <tr>
                                        <td>{{ (($page)*$perPage)+$index+1 }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->created_at->format('d F, Y') }}</td>
                                        <td>{{ $order->product_qty }}</td>
                                        <td class="text-right">{{ $setting->currency_icon }}{{ round($order->total_amount) }}</td>
                                        <td>
                                            @if ($order->order_status == 1)
                                            <span class="badge badge-success">{{__('admin.Pregress')}} </span>
                                            @elseif ($order->order_status == 2)
                                            <span class="badge badge-success">{{__('admin.Delivered')}} </span>
                                            @elseif ($order->order_status == 3)
                                            <span class="badge badge-success">{{__('admin.Completed')}} </span>
                                            @elseif ($order->order_status == 4)
                                            <span class="badge badge-danger">{{__('admin.Declined')}} </span>
                                            @else
                                            <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->payment_status == 1)
                                            <span class="badge badge-success">{{__('admin.success')}} </span>
                                            @else
                                            <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                            @endif
                                        </td>

                                        <td>

                                        <a href="{{ route('admin.order-show',$order->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $order->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>

                                        <a href="javascript:;" data-toggle="modal" data-target="#orderModalId-{{ $order->id }}" class="btn btn-warning btn-sm"><i class="fas fa-truck" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                  @endforeach
                            </tbody>
                        </table>
                        {!! $orders->onEachSide(3)->links() !!}
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>


      <!-- Modal -->
      @foreach ($orders as $index => $order)
      <div class="modal fade" id="orderModalId-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                      <div class="modal-header">
                              <h5 class="modal-title">{{__('admin.Order Status')}}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                          </div>
                  <div class="modal-body">
                      <div class="container-fluid">
                          <form action="{{ route('admin.update-order-status',$order->id) }}" method="POST">
                            @method('PUT')
                              @csrf
                              <div class="form-group">
                                  <label for="">{{__('admin.Payment')}}</label>
                                  <select name="payment_status" id="" class="form-control">
                                      <option {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                      <option {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Success')}}</option>
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="">{{__('admin.Order')}}</label>
                                  <select name="order_status" id="" class="form-control">
                                    <option {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                    <option {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('admin.In Progress')}}</option>
                                    <option {{ $order->order_status == 2 ? 'selected' : '' }}  value="2">{{__('admin.Delivered')}}</option>
                                    <option {{ $order->order_status == 3 ? 'selected' : '' }} value="3">{{__('admin.Completed')}}</option>
                                    <option {{ $order->order_status == 4 ? 'selected' : '' }} value="4">{{__('admin.Declined')}}</option>
                                  </select>
                              </div>


                      </div>
                  </div>
                 <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                      
                      <button class="btn btn-primary" {{ $order->order_status == 4 ? 'disabled' : '' }} type="submit">{{__('admin.Update Status')}}</button><br><br>
                      
                  </div>
                  <span class="text-center">{{ $order->order_status == 4 ? 'Order has been declined, Status cannot be changed now' : '' }}</span><br>
                </form>
              </div>
          </div>
      </div>

      @endforeach

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("delete-order/") }}'+"/"+id)
    }
</script>
@endsection
