@extends('admin.master_layout')
@section('title')
<title>{{__('admin.City / Delivery Area')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.City / Delivery Area')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.City / Delivery Area')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.city.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>

            <a href="{{ route('admin.city-import-page') }}" class="btn btn-success"><i class="fas fa-plus"></i> {{__('admin.Bulk Upload')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 text-right">
                                <div id="dataTable_filter" class="dataTables_filter">
                                    <form action="{{ route('admin.city.index') }}" method="GET" enctype="multipart/form-data">
                                    @csrf
                                    <label><input type="search" class="form-control form-control-sm" placeholder="Search By City Name" aria-controls="dataTable" name="search" value="{{ $searchTxt }}"></label>&nbsp;
                                    <button class="btn btn-primary">{{__('admin.SearchLabel')}}</button>&nbsp;
                                    <a href="{{ route('admin.city.index') }}" class="btn btn-primary">{{__('admin.ResetLabel')}}</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped" id="dataTable1">
                            <thead>
                                <tr>
                                    <th>{{__('admin.SN')}}</th>
                                    <th>{{__('admin.Id')}}</th>
                                    <th>{{__('admin.City')}}</th>
                                    <th>{{__('admin.State')}}</th>
                                    <th>{{__('admin.Country')}}</th>
                                    <th>{{__('admin.Status')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($cities as $index => $city)
                                    <tr>
                                        <td>{{ (($page)*$perPage)+$index+1 }}</td>
                                        <td>{{ $city->id }}</td>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ $city->countryState->name }}</td>
                                        <td>{{ $city->countryState->country->name }}</td>
                                        <td>
                                            @if($city->status == 1)
                                                <a href="javascript:;" onclick="changeStateStatus({{ $city->id }})">
                                                    <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.InActive')}}" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                                @else
                                                <a href="javascript:;" onclick="changeStateStatus({{ $city->id }})">
                                                    <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.InActive')}}" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                                @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.city.edit',$city->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>


                                            @if ($city->addressCities->count() == 0)
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $city->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @else
                                                <a href="javascript:;" data-toggle="modal" data-target="#canNotDeleteModal" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @endif

                                        </td>

                                    </tr>
                                  @endforeach
                            </tbody>
                        </table>
                        {!! $cities->onEachSide(3)->links() !!}
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                      <div class="modal-body">
                          {{__('admin.You can not delete this city. Because there are one or more users and seller has been created in this city.')}}
                      </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                </div>
            </div>
        </div>
    </div>

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("city/") }}'+"/"+id)
    }
    function changeStateStatus(id){
        var isDemo = "{{ env('APP_MODE') }}"
        if(isDemo == 'DEMO'){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/city-status/')}}"+"/"+id,
            success:function(response){
                toastr.success(response)
            },
            error:function(err){
                console.log(err);

            }
        })
    }
</script>
@endsection