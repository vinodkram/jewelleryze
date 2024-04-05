@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Advertisement')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Advertisement')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Advertisement')}}</div>
            </div>
          </div>

          <div class="section-body">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4">
                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link active" id="error-tab-1" data-toggle="tab" href="#errorTab-1" role="tab" aria-controls="errorTab-1" aria-selected="true">{{__('Banner One')}} ({{__('Top')}})</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="one-column-banner-tab" data-toggle="tab" href="#oneColumnBanner" role="tab" aria-controls="oneColumnBanner" aria-selected="true">{{__('Banner Two')}} ({{__('Top')}})</a>
                                    </li>


                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="two-column-banner-second" data-toggle="tab" href="#twoColumnBannerSecond" role="tab" aria-controls="twoColumnBannerSecond" aria-selected="true">{{__('Banner Three')}} ({{__('Middle')}})</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="two-column-banner-third" data-toggle="tab" href="#twoColumnBannerThird" role="tab" aria-controls="twoColumnBannerThird" aria-selected="true">{{__('Banner Four')}} ({{__('Middle')}})</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1" style="display:none">
                                        <a class="nav-link" id="shop-page" data-toggle="tab" href="#shopPage" role="tab" aria-controls="shopPage" aria-selected="true">{{__('admin.Homepage Single Banner One')}}</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1" style="display:none">
                                        <a class="nav-link" id="product-details" data-toggle="tab" href="#productDetails" role="tab" aria-controls="productDetails" aria-selected="true">{{__('admin.Homepage Single Banner Two')}}</a>
                                    </li>


                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="campaign-page" data-toggle="tab" href="#campaignPage" role="tab" aria-controls="campaignPage" aria-selected="true">{{__('admin.Shop Page Center Banner')}}</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="shoppage-sidebar-page" data-toggle="tab" href="#shopPageSidebarBanner" role="tab" aria-controls="shopPageSidebarBanner" aria-selected="true">{{__('admin.Shop Page Sidebar Banner')}}</a>
                                    </li>

                                    <li class="nav-item border rounded mb-1">
                                        <a class="nav-link" id="megamanu-banner" data-toggle="tab" href="#megaMenuBanner" role="tab" aria-controls="megaMenuBanner" aria-selected="true">{{__('admin.Mega Menu Banner')}}</a>
                                    </li>




                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 col-md-8">
                                <div class="border rounded">
                                    <div class="tab-content no-padding" id="settingsContent">
                                        <div class="tab-pane fade show active" id="errorTab-1" role="tabpanel" aria-labelledby="error-tab-1">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.slider-banner-one') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Current Banner')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($threeColFirstBanner->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $threeColFirstBanner->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $threeColFirstBanner->title_two }}">
                                                            </div>


                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Category')}}</option>
                                                                    @foreach ($products as $product)
                                                                    <option {{ $product->slug == $threeColFirstBanner->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($subCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->slug == $threeColFirstBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($childCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $threeColFirstBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>



                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $threeColFirstBanner->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $threeColFirstBanner->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="oneColumnBanner" role="tabpanel" aria-labelledby="one-column-banner-tab">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.slider-banner-two') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Current Banner')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($threeColSecondBanner->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>


                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $threeColSecondBanner->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $threeColSecondBanner->title_two }}">
                                                            </div>


                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Category')}}</option>
                                                                    @foreach ($products as $product)
                                                                    <option {{ $product->slug == $threeColSecondBanner->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($subCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->slug == $threeColSecondBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($childCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $threeColSecondBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $threeColSecondBanner->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $threeColSecondBanner->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="tab-pane fade" id="twoColumnBannerSecond" role="tabpanel" aria-labelledby="two-column-banner-second">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.homepage-two-col-first-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($homepageTwoColumnBannerOne->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $homepageTwoColumnBannerOne->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $homepageTwoColumnBannerOne->title_two }}">
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Category')}}</option>
                                                                    @foreach ($products as $product)
                                                                    <option {{ $product->slug == $homepageTwoColumnBannerOne->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($subCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->slug == $homepageTwoColumnBannerOne->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($childCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $homepageTwoColumnBannerOne->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $homepageTwoColumnBannerOne->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $homepageTwoColumnBannerOne->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="twoColumnBannerThird" role="tabpanel" aria-labelledby="two-column-banner-third">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.homepage-two-col-second-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($homepageTwoColumnBannerTwo->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $homepageTwoColumnBannerTwo->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $homepageTwoColumnBannerTwo->title_two }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Category')}}</option>
                                                                    @foreach ($products as $product)
                                                                    <option {{ $product->slug == $homepageTwoColumnBannerTwo->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($subCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->slug == $homepageTwoColumnBannerTwo->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($childCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $homepageTwoColumnBannerTwo->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $homepageTwoColumnBannerTwo->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $homepageTwoColumnBannerTwo->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="shopPage" role="tabpanel" aria-labelledby="shop-page">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.homepage-single-first-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="">{{__('admin.Existing Banner')}}</label>
                                                            <div>
                                                                <img src="{{ asset($homepageSingleBannerOne->image) }}" width="200px" alt="">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="">{{__('admin.New Banner')}}</label>
                                                            <input type="file" class="form-control-file" name="banner_image">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                            <input type="text" name="title_one" class="form-control" value="{{ $homepageSingleBannerOne->title_one }}">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                            <input type="text" name="title_two" class="form-control" value="{{ $homepageSingleBannerOne->title_two }}">
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                            <select name="product_slug" id="" class="form-control select2">
                                                                <option value="">{{__('admin.Select Category')}}</option>
                                                                @foreach ($products as $product)
                                                                <option {{ $product->slug == $homepageSingleBannerOne->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                @endforeach
                                                                @foreach ($subCategories as $product)
                                                                <option {{ $product->c_slug."/".$product->slug == $homepageSingleBannerOne->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                @endforeach
                                                                @foreach ($childCategories as $product)
                                                                <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $homepageSingleBannerOne->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        

                                                        <div class="form-group col-12">
                                                            <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                            <select name="status" class="form-control">
                                                                <option {{ $homepageSingleBannerOne->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                <option {{ $homepageSingleBannerOne->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                            </select>
                                                        </div>

                                                        <button class="btn btn-primary" type="submit">{{__('admin.Update')}}</button>
                                                     </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="productDetails" role="tabpanel" aria-labelledby="product-details">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.homepage-single-second-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Existing Banner')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($homepageSingleBannerTwo->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title" class="form-control" value="{{ $homepageSingleBannerTwo->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Category')}}</option>
                                                                    @foreach ($products as $product)
                                                                    <option {{ $product->slug == $homepageSingleBannerTwo->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($subCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->slug == $homepageSingleBannerTwo->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($childCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $homepageSingleBannerTwo->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                           

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $homepageSingleBannerTwo->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $homepageSingleBannerTwo->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="tab-pane fade" id="campaignPage" role="tabpanel" aria-labelledby="campaign-page">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.shop-page-center-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf

                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($shopPageCenterBanner->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title" class="form-control" value="{{ $shopPageCenterBanner->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Category')}}</option>
                                                                    @foreach ($products as $product)
                                                                    <option {{ $product->slug == $shopPageCenterBanner->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($subCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->slug == $shopPageCenterBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($childCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $shopPageCenterBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.After Product Qty')}} <span class="text-danger">*</span></label>
                                                                <input type="number" name="after_product_qty"  class="form-control" value="{{ $shopPageCenterBanner->after_product_qty }}">
                                                            </div>



                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $shopPageCenterBanner->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $shopPageCenterBanner->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>

                                                        </div>


                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="shopPageSidebarBanner" role="tabpanel" aria-labelledby="shoppage-sidebar-page">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.shop-page-sidebar-banner') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($shopPageSidebarBanner->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $shopPageSidebarBanner->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $shopPageSidebarBanner->title_two }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Category')}}</option>
                                                                    @foreach ($products as $product)
                                                                    <option {{ $product->slug == $shopPageSidebarBanner->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($subCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->slug == $shopPageSidebarBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($childCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $shopPageSidebarBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $shopPageSidebarBanner->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $shopPageSidebarBanner->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>

                                                        </div>


                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="megaMenuBanner" role="tabpanel" aria-labelledby="megamenu-banner">
                                            <div class="card m-0">
                                                <div class="card-body">
                                                    <form action="{{ route('admin.mega-menu-banner-update') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Banner One')}}</label>
                                                                <div>
                                                                    <img src="{{ asset($megaMenuBanner->image) }}" alt="" width="200px">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.New Banner')}}</label>
                                                                <input type="file" name="banner_image" class="form-control-file">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title One')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_one" class="form-control" value="{{ $megaMenuBanner->title_one }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Title Two')}} <span class="text-danger">*</span></label>
                                                                <input type="text" name="title_two" class="form-control" value="{{ $megaMenuBanner->title_two }}">
                                                            </div>

                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Category Link')}}<span class="text-danger">*</span></label>
                                                                <select name="product_slug" id="" class="form-control select2">
                                                                    <option value="">{{__('admin.Select Category')}}</option>
                                                                    @foreach ($products as $product)
                                                                    <option {{ $product->slug == $megaMenuBanner->product_slug ? 'selected' : '' }} value="{{ $product->slug }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($subCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->slug == $megaMenuBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($childCategories as $product)
                                                                    <option {{ $product->c_slug."/".$product->sc_slug."/".$product->slug == $megaMenuBanner->product_slug ? 'selected' : '' }} value="{{ $product->c_slug }}/{{ $product->sc_slug }}/{{ $product->slug }}">{{ $product->c_name }} => {{ $product->sc_name }} => {{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            


                                                            <div class="form-group col-12">
                                                                <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                                <select name="status" class="form-control">
                                                                    <option {{ $megaMenuBanner->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                                    <option {{ $megaMenuBanner->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                                </select>
                                                            </div>

                                                        </div>


                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>











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
@endsection
