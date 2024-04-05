@php
    $setting = App\Models\Setting::first();
@endphp

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">
            <img class="logo" src="{{ asset($setting->logo) }}" alt="logo"/>
        </a>
      </div>

      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('admin.dashboard') }}">{{ $setting->sidebar_sm_header }}</a>
      </div>

      <ul class="sidebar-menu">
          <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.dashboard') }}">
                  <div class="icon">
                    <i class="fas fa-home"></i>
                  </div>
                  <span>{{__('admin.Dashboard')}}</span>
               </a>
          </li>


          <li class="nav-item dropdown {{ Route::is('admin.all-order') || Route::is('admin.order-show') || Route::is('admin.pending-order') || Route::is('admin.pregress-order') || Route::is('admin.delivered-order') ||  Route::is('admin.completed-order') || Route::is('admin.declined-order') || Route::is('admin.cash-on-delivery')  ? 'active' : '' }}">

            <a href="#" class="nav-link has-dropdown">

             <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                              </div>
           <span>{{__('admin.Orders')}}</span></a>
            <ul class="dropdown-menu">

              <li class="{{ Route::is('admin.all-order') || Route::is('admin.order-show') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.all-order') }}">{{__('admin.All Orders')}}</a></li>

              <li class="{{ Route::is('admin.pending-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pending-order') }}">{{__('admin.Pending Orders')}}</a></li>

              <li class="{{ Route::is('admin.pregress-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pregress-order') }}">{{__('admin.Progress Orders')}}</a></li>

              <li class="{{ Route::is('admin.delivered-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.delivered-order') }}">{{__('admin.Delivered Orders')}}</a></li>

              <li class="{{ Route::is('admin.completed-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.completed-order') }}">{{__('admin.Completed Orders')}}</a></li>

              <li class="{{ Route::is('admin.declined-order') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.declined-order') }}">{{__('admin.Declined Orders')}}</a></li>

              <li class="{{ Route::is('admin.cash-on-delivery') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.cash-on-delivery') }}">{{__('admin.Cash On Delivery')}}</a></li>

            </ul>

          </li>
          <li class="nav-item dropdown {{  Route::is('admin.customer-list') || Route::is('admin.customer-show') || Route::is('admin.pending-customer-list') || Route::is('admin.seller-list') || Route::is('admin.seller-show') || Route::is('admin.pending-seller-list') || Route::is('admin.seller-shop-detail') || Route::is('admin.seller-reviews') || Route::is('admin.show-seller-review-details') || Route::is('admin.send-email-to-seller') || Route::is('admin.email-history') || Route::is('admin.product-by-seller') || Route::is('admin.send-email-to-all-seller') || Route::is('admin.send-email-to-all-customer') ? 'active' : '' }}">

                <a href="#" class="nav-link has-dropdown">
                <div class="icon">
                <i class="fas fa-user"></i>
                </div>
                <span>{{__('admin.Users')}}</span></a>

                <ul class="dropdown-menu">

                    <li class="{{ Route::is('admin.customer-list') || Route::is('admin.customer-show') || Route::is('admin.send-email-to-all-customer') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.customer-list') }}">{{__('admin.Customer List')}}</a></li>

                    <li class="{{ Route::is('admin.pending-customer-list') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pending-customer-list') }}">{{__('admin.Pending Customers')}}</a></li>

                    <li class="{{ Route::is('admin.seller-list') || Route::is('admin.seller-show') || Route::is('admin.seller-shop-detail') || Route::is('admin.seller-reviews') || Route::is('admin.show-seller-review-details') || Route::is('admin.send-email-to-seller') || Route::is('admin.email-history') || Route::is('admin.product-by-seller') || Route::is('admin.send-email-to-all-seller') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.seller-list') }}">{{__('admin.Seller List')}}</a></li>

                    <li class="{{ Route::is('admin.pending-seller-list') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pending-seller-list') }}">{{__('admin.Pending Sellers')}}</a></li>

                </ul>

            </li>



          <li class="nav-item dropdown {{ Route::is('admin.product-category.*') || Route::is('admin.product-sub-category.*') || Route::is('admin.product-child-category.*') || Route::is('admin.mega-menu-category.*') || Route::is('admin.mega-menu-sub-category') || Route::is('admin.create-mega-menu-sub-category') || Route::is('admin.edit-mega-menu-sub-category') || Route::is('admin.mega-menu-banner') || Route::is('admin.popular-category') || Route::is('admin.featured-category') ? 'active' : '' }}">

            <p class="s-divide">{{__('admin.Products')}}</p>
            <a href="#" class="nav-link has-dropdown">

             <div class="icon">
                                    <i class="fas fa-th-large"></i>
                                          </div>
            <span>{{__('admin.Manage Categories')}}</span></a>
            <ul class="dropdown-menu">

              <li class="{{ Route::is('admin.product-category.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product-category.index') }}">{{__('admin.Categories')}}</a></li>

              <li class="{{ Route::is('admin.product-sub-category.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product-sub-category.index') }}">{{__('admin.Sub Categories')}}</a></li>

              <li class="{{ Route::is('admin.product-child-category.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product-child-category.index') }}">{{__('admin.Child Categories')}}</a></li>

              <li class="{{ Route::is('admin.mega-menu-category.*') || Route::is('admin.mega-menu-sub-category') || Route::is('admin.create-mega-menu-sub-category') || Route::is('admin.edit-mega-menu-sub-category') || Route::is('admin.mega-menu-banner') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.mega-menu-category.index') }}">{{__('admin.Mega Menu Category')}}</a></li>

              <li class="{{ Route::is('admin.popular-category') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.popular-category') }}">{{__('admin.Popular Category')}}</a></li>

              <li class="{{ Route::is('admin.featured-category') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.featured-category') }}">{{__('admin.Featured Category')}}</a></li>

            </ul>

          </li>





          <li class="nav-item dropdown {{ Route::is('admin.product.*') || Route::is('admin.product-brand.*') || Route::is('admin.product-variant') || Route::is('admin.create-product-variant') || Route::is('admin.edit-product-variant') || Route::is('admin.product-gallery') || Route::is('admin.product-variant-item') || Route::is('admin.create-product-variant-item') || Route::is('admin.edit-product-variant-item') || Route::is('admin.product-review') || Route::is('admin.show-product-review') || Route::is('admin.seller-product') || Route::is('admin.seller-pending-product') || Route::is('admin.wholesale') || Route::is('admin.create-wholesale') || Route::is('admin.edit-wholesale') || Route::is('admin.product-highlight') ||  Route::is('admin.product-report') || Route::is('admin.show-product-report') || Route::is('admin.specification-key.*') || Route::is('admin.stockout-product') || Route::is('admin.product-import') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
            <div class="icon">
            <i class="fas fa-th-large"></i>
            </div>
            <span>{{__('admin.Manage Products')}}</span></a>
            <ul class="dropdown-menu">

            <li class="{{ Route::is('admin.product-brand.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product-brand.index') }}">{{__('admin.Brands')}}</a></li>


            <li class="{{ Route::is('admin.product-import') ? 'active' : '' }}"><a  class="nav-link" href="{{ route('admin.product-import') }}">{{__('admin.Product Import')}}</a></li>


            <li><a class="nav-link" href="{{ route('admin.product.create') }}">{{__('admin.Create Product')}}</a></li>

            <li class="{{ Route::is('admin.product.*') || Route::is('admin.product-variant') || Route::is('admin.create-product-variant') || Route::is('admin.edit-product-variant') || Route::is('admin.product-gallery') || Route::is('admin.product-variant-item') || Route::is('admin.create-product-variant-item') || Route::is('admin.edit-product-variant-item') || Route::is('admin.wholesale') || Route::is('admin.create-wholesale') || Route::is('admin.edit-wholesale') || Route::is('admin.product-highlight') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product.index') }}">{{__('admin.Products')}}</a></li>

            <li class="{{ Route::is('admin.stockout-product') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.stockout-product') }}">{{__('admin.Stock out')}}</a></li>

            <li class="{{ Route::is('admin.seller-product') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.seller-product') }}">{{__('admin.Seller Products')}}</a></li>

            <li class="{{ Route::is('admin.seller-pending-product') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.seller-pending-product') }}">{{__('admin.Seller Pending Products')}}</a></li>

            <li class="{{ Route::is('admin.specification-key.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.specification-key.index') }}">{{__('admin.Specification Key')}}</a></li>

              <li class="{{ Route::is('admin.product-review') || Route::is('admin.show-product-review') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product-review') }}">{{__('admin.Product Reviews')}}</a></li>

              <li class="{{ Route::is('admin.product-report') || Route::is('admin.show-product-report') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.product-report') }}">{{__('admin.Product Report')}}</a></li>
            </ul>

          </li>

          <li class="{{ Route::is('admin.inventory') || Route::is('admin.stock-history') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.inventory') }}">
            <div class="icon">
                <i class="fas fa-th-large"></i>

            </div>
            <span>{{__('admin.Inventory')}}</span></a></li>


          <li class="nav-item dropdown {{ Route::is('admin.flash-sale') || Route::is('admin.currency.*') || Route::is('admin.coupon.*') || Route::is('admin.payment-method') || Route::is('admin.flash-sale-product') ? 'active' : '' }}">
          <p class="s-divide">{{__('admin.Shop Setup')}}</p>
            <a href="#" class="nav-link has-dropdown">
            <div class="icon">
            <i class="fas fa-shopping-cart"></i>
            </div>
            <span>{{__('admin.Ecommerce')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.flash-sale') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.flash-sale') }}">{{__('admin.Flash Sale')}}</a></li>

                <li class="{{ Route::is('admin.flash-sale-product') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.flash-sale-product') }}">{{__('admin.Flash Sale Product')}}</a></li>

                <li class="{{ Route::is('admin.coupon.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.coupon.index') }}">{{__('admin.Coupon')}}</a></li>

                <li class="{{ Route::is('admin.payment-method') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.payment-method') }}">{{__('admin.Payment Method')}}</a></li>
            </ul>

          </li>

          <li class="{{ Route::is('admin.advertisement') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.advertisement') }}">

          <div class="icon">
          <i class="fas fa-ad"></i>
          </div>
           <span>{{__('admin.Advertisement')}}</span></a></li>
            <li class="nav-item dropdown {{ Route::is('admin.country.*') || Route::is('admin.state.*') || Route::is('admin.city.*') || Route::is('admin.country-import-page') || Route::is('admin.state-import-page') || Route::is('admin.city-import-page') || Route::is('admin.shipping-import-page') ? 'active' : '' }}">

                       <a href="#" class="nav-link has-dropdown">
                       <div class="icon">
                        <i class="fas fa-map-marker-alt"></i>
                       </div>
                      <span>{{__('admin.Locations')}}</span></a>

                       <ul class="dropdown-menu">

                           <li class="{{ Route::is('admin.country.*') || Route::is('admin.country-import-page') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.country.index') }}">{{__('admin.Country / Region')}}</a></li>

                           <li class="{{ Route::is('admin.state.*') || Route::is('admin.state-import-page') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.state.index') }}">{{__('admin.State / Province')}}</a></li>

                           <li class="{{ Route::is('admin.city.*') || Route::is('admin.city-import-page') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.city.index') }}">{{__('admin.City / Delivery Area')}}</a></li>


                            <li class="{{ Route::is('admin.shipping.*') || Route::is('admin.shipping-import-page') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.shipping.index') }}">{{__('admin.Shipping Rule')}}</a></li>


                       </ul>

                     </li>

          <li class="nav-item dropdown {{ Route::is('admin.withdraw-method.*') || Route::is('admin.seller-withdraw') || Route::is('admin.pending-seller-withdraw') || Route::is('admin.show-seller-withdraw')  ? 'active' : '' }}">

            <a href="#" class="nav-link has-dropdown">
            <div class="icon">
            <i class="far fa-newspaper"></i>
           </div>
            <span>{{__('admin.Withdraw Payment')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('admin.withdraw-method.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.withdraw-method.index') }}">{{__('admin.Withdraw Method')}}</a></li>

                <li class="{{ Route::is('admin.seller-withdraw') || Route::is('admin.show-seller-withdraw') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.seller-withdraw') }}">{{__('admin.Seller Withdraw')}}</a></li>

                <li class="{{ Route::is('admin.pending-seller-withdraw') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.pending-seller-withdraw') }}">{{__('admin.Pending Seller Withdraw')}}</a></li>
            </ul>

          </li>

          <li class="nav-item dropdown {{ Route::is('admin.service.*') || Route::is('admin.maintainance-mode') || Route::is('admin.announcement') ||  Route::is('admin.slider.*') || Route::is('admin.home-page') || Route::is('admin.banner-image.index') || Route::is('admin.homepage-one-visibility') || Route::is('admin.cart-bottom-banner') || Route::is('admin.shop-page') || Route::is('admin.seo-setup') || Route::is('admin.menu-visibility') || Route::is('admin.product-detail-page') || Route::is('admin.default-avatar') || Route::is('admin.seller-conditions') || Route::is('admin.subscription-banner') || Route::is('admin.testimonial.*') || Route::is('admin.homepage-section-title') ? 'active' : '' }}">
           <p class="s-divide">{{__('admin.Website Settings')}}</p>
            <a href="#" class="nav-link has-dropdown">
            <div class="icon">
            <i class="fas fa-globe"></i>
            </div>
            <span>{{__('admin.Manage Website')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.seo-setup') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.seo-setup') }}">{{__('admin.SEO Setup')}}</a></li>

                <li class="{{ Route::is('admin.slider.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.slider.index') }}">{{__('admin.Slider')}}</a></li>

                <li class="{{ Route::is('admin.shop-page') ? 'active' : '' }} d-none"><a class="nav-link" href="{{ route('admin.shop-page') }}">{{ __('admin.Shop Page') }}</a></li>

                <li class="{{ Route::is('admin.service.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.service.index') }}">{{__('admin.Service')}}</a></li>

                <li class="{{ Route::is('admin.homepage-section-title') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.homepage-section-title') }}">{{__('admin.Homepage Section Title')}}</a></li>
                
                <li class="{{ Route::is('admin.homepage-visibility') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.homepage-visibility') }}">{{__('admin.Visibility')}}</a></li>

                <li class="{{ Route::is('admin.seller-conditions') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.seller-conditions') }}">{{__('admin.Seller Conditions')}}</a></li>

                <li class="{{ Route::is('admin.testimonial.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.testimonial.index') }}">{{__('admin.Testimonial')}}</a></li>

                <li class="{{ Route::is('admin.maintainance-mode') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.maintainance-mode') }}">{{__('admin.Maintainance Mode')}}</a></li>

                <li class="{{ Route::is('admin.announcement') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.announcement') }}">{{__('admin.Announcement')}}</a></li>

                <li class="{{ Route::is('admin.subscription-banner') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.subscription-banner') }}">{{__('admin.Subscription Banner')}}</a></li>

                <li class="{{ Route::is('admin.image-content') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.image-content') }}">{{__('admin.Image Content')}}</a></li>

                <li class="{{ Route::is('admin.default-avatar') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.default-avatar') }}">{{__('admin.Default Avatar')}}</a></li>

            </ul>

          </li>



          <li class="nav-item dropdown {{ Route::is('admin.footer.*') || Route::is('admin.social-link.*') || Route::is('admin.footer-link.*') || Route::is('admin.second-col-footer-link') || Route::is('admin.third-col-footer-link') ? 'active' : '' }}">

            <a href="#" class="nav-link has-dropdown">
            <div class="icon">
            <i class="fas fa-th-large"></i>
            </div>

            <span>{{__('admin.Website Footer')}}</span></a>

            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.footer.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.footer.index') }}">{{__('admin.Footer')}}</a></li>

                <li class="{{ Route::is('admin.social-link.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.social-link.index') }}">{{__('admin.Social Link')}}</a></li>

                <li class="{{ Route::is('admin.footer-link.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.footer-link.index') }}">{{__('admin.First Column Link')}}</a></li>

                <li class="{{ Route::is('admin.second-col-footer-link') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.second-col-footer-link') }}">{{__('admin.Second Column Link')}}</a></li>

                <li class="{{ Route::is('admin.third-col-footer-link') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.third-col-footer-link') }}">{{__('admin.Third Column Link')}}</a></li>

            </ul>

          </li>

          <li class="nav-item dropdown {{ Route::is('admin.about-us.*') || Route::is('admin.custom-page.*') || Route::is('admin.terms-and-condition.*') || Route::is('admin.privacy-policy.*') || Route::is('admin.faq.*') || Route::is('admin.error-page.*') || Route::is('admin.contact-us.*') || Route::is('admin.login-page') ? 'active' : '' }}">

            <a href="#" class="nav-link has-dropdown">

            <div class="icon">
            <i class="fas fa-columns"></i>

            </div>
            <span>{{__('admin.Pages')}}</span></a>
            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.about-us.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.about-us.index') }}">{{__('admin.About Us')}}</a></li>

                <li class="{{ Route::is('admin.contact-us.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.contact-us.index') }}">{{__('admin.Contact Us')}}</a></li>

                <li class="{{ Route::is('admin.custom-page.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.custom-page.index') }}">{{__('admin.Custom Page')}}</a></li>

                <li class="{{ Route::is('admin.terms-and-condition.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.terms-and-condition.index') }}">{{__('admin.Terms And Conditions')}}</a></li>

                <li class="{{ Route::is('admin.privacy-policy.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.privacy-policy.index') }}">{{__('admin.Privacy Policy')}}</a></li>

                <li class="{{ Route::is('admin.faq.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.faq.index') }}">{{__('admin.FAQ')}}</a></li>

                <li class="{{ Route::is('admin.error-page.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.error-page.index') }}">{{__('admin.Error Page')}}</a></li>

                <li class="{{ Route::is('admin.login-page') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.login-page') }}">{{__('admin.Login Page')}}</a></li>
            </ul>

          </li>

          <li class="nav-item dropdown {{ Route::is('admin.blog-category.*') || Route::is('admin.blog.*') || Route::is('admin.popular-blog.*') || Route::is('admin.blog-comment.*') ? 'active' : '' }}">

            <a href="#" class="nav-link has-dropdown">
            <div class="icon">
            <i class="fas fa-th-large"></i>
            </div>
            <span>{{__('admin.Blogs')}}</span></a>

            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.blog-category.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.blog-category.index') }}">{{__('admin.Categories')}}</a></li>

                <li class="{{ Route::is('admin.blog.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.blog.index') }}">{{__('admin.Blogs')}}</a></li>

                <li class="{{ Route::is('admin.popular-blog.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.popular-blog.index') }}">{{__('admin.Popular Blogs')}}</a></li>

                <li class="{{ Route::is('admin.blog-comment.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.blog-comment.index') }}">{{__('admin.Comments')}}</a></li>

            </ul>

          </li>



          <li class="nav-item dropdown {{ Route::is('admin.email-configuration') || Route::is('admin.email-template') || Route::is('admin.edit-email-template') ? 'active' : '' }}">
           <p class="s-divide">{{__('admin.Configuration')}}</p>
            <a href="#" class="nav-link has-dropdown">
            <div class="icon">
            <i class="fas fa-envelope"></i>
            </div>
            <span>{{__('admin.Email Configuration')}}</span></a>

            <ul class="dropdown-menu">

                <li class="{{ Route::is('admin.email-configuration') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.email-configuration') }}">{{__('admin.Setting')}}</a></li>

                <li class="{{ Route::is('admin.email-template') || Route::is('admin.edit-email-template') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.email-template') }}">{{__('admin.Email Template')}}</a></li>

            </ul>

          </li>

          <li class="nav-item dropdown {{ Route::is('admin.sms-notification') || Route::is('admin.sms-template') || Route::is('admin.edit-sms-template') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">

                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>

                <span>{{__('admin.Sms Configuration')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('admin.sms-notification') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.sms-notification') }}">{{__('admin.Setting')}}</a></li>

                <li class="{{ Route::is('admin.sms-template') || Route::is('admin.edit-sms-template') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.sms-template') }}">{{__('admin.Sms Template')}}</a></li>
            </ul>
          </li>

          <li class="{{ Route::is('admin.subscriber') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.subscriber') }}">

                     <div class="icon">
                     <i class="fas fa-fire"></i>
                     </div>
                     <span>{{__('admin.Subscribers')}}</span></a></li>

          <li class="nav-item dropdown {{ Route::is('admin.admin-language') || Route::is('admin.admin-validation-language') || Route::is('admin.website-language') || Route::is('admin.website-validation-language') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
            <div class="icon">
            <i class="fas fa-th-large"></i>
            </div>
            <span>{{__('admin.Language')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('admin.admin-language') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.admin-language') }}">{{__('admin.Admin Language')}}</a></li>

                <li class="{{ Route::is('admin.admin-validation-language') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.admin-validation-language') }}">{{__('admin.Admin Validation')}}</a></li>

                <li class="{{ Route::is('admin.website-language') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.website-language') }}">{{__('admin.Frontend Language')}}</a></li>
                <li class="{{ Route::is('admin.website-validation-language') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.website-validation-language') }}">{{__('admin.Frontend Validation')}}</a></li>
            </ul>
          </li>


          <li class="{{ Route::is('admin.general-setting') ? 'active' : '' }}">
          <p class="s-divide">{{__('admin.Settings')}}</p>

          <a class="nav-link" href="{{ route('admin.general-setting') }}">


           <div class="icon">
           <i class="fas fa-cog"></i>
           </div>
           <span>{{__('admin.Setting')}}</span></a></li>
          @php
              $logedInAdmin = Auth::guard('admin')->user();
          @endphp

          @if ($logedInAdmin->admin_type == 1)

            <li  class="{{ Route::is('admin.clear-database') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.clear-database') }}">
             <div class="icon">
             <i class="fas fa-trash"></i>
             </div>
             <span>{{__('admin.Clear Database')}}</span></a></li>

          @endif



          <li class="{{ Route::is('admin.contact-message') || Route::is('admin.show-contact-message') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.contact-message') }}">
           <div class="icon">
           <i class="fas fa-fa fa-envelope"></i>
           </div>
           <span>{{__('admin.Contact Message')}}</span></a></li>


          @if ($logedInAdmin->admin_type == 1)

            <li class="{{ Route::is('admin.admin.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.admin.index') }}">
             <div class="icon">
             <i class="fas fa-user"></i>
             </div>
             <span>{{__('admin.Admin list')}}</span></a></li>

          @endif

        </ul>
    </aside>

  </div>

