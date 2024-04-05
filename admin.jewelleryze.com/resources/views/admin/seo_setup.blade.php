@extends('admin.master_layout')
@section('title')
<title>{{__('admin.SEO Setup')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.SEO Setup')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.SEO Setup')}}</div>
            </div>
          </div>

          <div class="section-body">
		    <a href="javascript:;" data-toggle="modal" data-target="#createIcon" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
                <div class="col mt-4">
                  <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-3">
                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                    @foreach ($pages as $index => $page)
                                        <li class="nav-item border rounded mb-1">
                                            <a class="nav-link {{ $index == 0  ? 'active' : '' }}" id="error-tab-{{ $page->id }}" data-toggle="tab" href="#errorTab-{{ $page->id }}" role="tab" aria-controls="errorTab-{{ $page->id }}" aria-selected="true">{{ $page->page_name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 col-md-9">
                                <div class="border rounded">
                                    <div class="tab-content no-padding" id="settingsContent">
                                        @foreach ($pages as $index => $page)
                                            <div class="tab-pane fade {{ $index == 0  ? 'show active' : '' }}" id="errorTab-{{ $page->id }}" role="tabpanel" aria-labelledby="error-tab-{{ $page->id }}">
                                                <div class="card m-0">
                                                    <div class="card-body">
                                                        <form action="{{ route('admin.update-seo-setup',$page->id) }}" method="POST">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label for="">{{__('admin.SEO Title')}}</label>
                                                                        <input type="text" name="seo_title" class="form-control" value="{{ $page->seo_title }}">
                                                                    </div>
                                                                </div>



                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label for="">{{__('admin.SEO Description')}}</label>
                                                                        <textarea name="seo_description" id="" cols="30" rows="5" class="form-control text-area-5">{{ $page->seo_description }}</textarea>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>
	  
	        <!--Create Modal -->
      <div class="modal fade" id="createIcon" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                      <div class="modal-header">
                              <h5 class="modal-title">{{__('admin.Add New')}}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                          </div>
                  <div class="modal-body">
                      <div class="container-fluid">
                        <form action="{{ route('admin.seo-page-create') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">{{__('admin.Page Name')}}</label>
                                <input type="text" class="form-control" name="pagename">
                                <input type="hidden" name="column" value="">
                            </div>
                            <div class="form-group">
                                <label for="">{{__('admin.Slug')}}</label>
                                <input type="text" class="form-control" name="pageslug">
                                <input type="hidden" name="column" value="">
                            </div>
                            <div class="form-group">
                                <label for="">{{__('admin.SEO Title')}}</label>
                                <input type="text" class="form-control" name="pagetitle">
                                <input type="hidden" name="column" value="">
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.SEO Description')}}</label>
                                <input type="text" class="form-control" name="pagedesc">
                            </div>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('admin.Save')}}</button>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
@endsection
