<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\HomePageOneVisibility;
use App\Models\Brand;
use App\Models\Slider;
use App\Models\Category;
use App\Models\MaintainanceText;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\PopularCategory;
use App\Models\Product;
use App\Models\BannerImage;
use App\Models\Service;
use App\Models\Blog;
use App\Models\AboutUs;
use App\Models\ContactPage;
use App\Models\ErrorPage;
use App\Models\PopularPost;
use App\Models\BlogCategory;
use App\Models\BreadcrumbImage;
use App\Models\CustomPagination;
use App\Models\Faq;
use App\Models\CustomPage;
use App\Models\TermsAndCondition;
use App\Models\Vendor;
use App\Models\Subscriber;
use App\Mail\SubscriptionVerification;
use App\Mail\ContactMessageInformation;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\ProductReview;
use App\Models\ProductSpecification;
use App\Models\ProductGallery;
use App\Models\Setting;
use App\Models\ContactMessage;
use App\Models\BlogComment;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\Testimonial;
use App\Models\GoogleRecaptcha;
use App\Models\Order;
use App\Models\ShopPage;
use App\Models\SeoSetting;
use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Rules\Captcha;
use Mail;
use Str;
use Session;
use Cart;
use Carbon\Carbon;
use Route;
use App\Models\FooterSocialLink;
use App\Models\AnnouncementModal;
use App\Models\MegaMenuCategory;
use App\Models\MenuVisibility;
use App\Models\GoogleAnalytic;
use App\Models\FacebookPixel;
use App\Models\TawkChat;
use App\Models\CookieConsent;
use App\Models\FeaturedCategory;
use App\Models\FooterLink;
use App\Models\Footer;
use App\Models\User;
use App\Events\LaravelReact;
use GuzzleHttp\Client;

class HomeController extends Controller
{

    public function websiteSetup()
    {
        $language = include("lang/en/user.php");

        $setting = Setting::select("logo","favicon","enable_user_register",'phone_number_required','default_phone_code',"enable_multivendor","text_direction","timezone","topbar_phone","topbar_email","currency_icon","currency_name","tax_rate","show_product_progressbar","theme_one","theme_two","seller_condition")->first();

        $announcementModal = AnnouncementModal::first();
        $productCategories = Category::with("activeSubCategories.activeChildCategories")->where(["status" => 1])
        ->select("id", "name", "slug", "icon","image")
        ->get();

        $megaMenuCategories = MegaMenuCategory::with("category","subCategories.subCategory")->orderBy("serial", "asc")->where("status", 1)->get();

        $megaMenuBanner = BannerImage::find(23);

        $customPages = CustomPage::where("status", 1)->get();

        $googleAnalytic = GoogleAnalytic::first();

        $facebookPixel = FacebookPixel::first();

        $tawk_setting = TawkChat::first();

        $tawk_setting = (object) [
            "status" => $tawk_setting->status,
            "widget_id" => $tawk_setting->widget_id,
            "property_id" => $tawk_setting->property_id,
        ];

        $maintainance_text = MaintainanceText::first();
        $cookie_consent = CookieConsent::first();
        $flashSale = FlashSale::select("status", "offer", "end_time")->first();
        $flashSaleProducts = FlashSaleProduct::where("status", 1)
            ->select("product_id")
            ->get();

        $flashSaleActive = $flashSale->status == 1 ? true : false;
        $seo_setting = SeoSetting::all();
        $shop_page = ShopPage::first();
        $filter_price_range = $shop_page->filter_price_range;
        $first_col_links = FooterLink::where("column", 1)->get();
        $footer = Footer::first();
        $columnTitle = $footer->first_column;
        $footer_first_col = [
            "col_links" => $first_col_links,
            "columnTitle" => $columnTitle,
        ];

        $footer_first_col = (object) $footer_first_col;
        $second_col_links = FooterLink::where("column", 2)->get();
        $columnTitle = $footer->second_column;
        $footer_second_col = [
            "col_links" => $second_col_links,
            "columnTitle" => $columnTitle,
        ];

        $footer_second_col = (object) $footer_second_col;
        $third_col_links = FooterLink::where("column", 3)->get();
        $columnTitle = $footer->third_column;

        $footer_third_col = [
            "col_links" => $third_col_links,
            "columnTitle" => $columnTitle,
        ];

        $footer_third_col = (object) $footer_third_col;
        $social_links = FooterSocialLink::all();
        $login_page = BannerImage::select("image")
            ->whereId("13")
            ->first();

        $recaptchaSetting = GoogleRecaptcha::first();
        $errorPage = ErrorPage::find(1);
        $image_content = Setting::select(
            "empty_cart",
            "empty_wishlist",
            "change_password_image",
            "become_seller_avatar",
            "become_seller_banner"
        )->first();

        $serviceVisibilty = HomePageOneVisibility::find(2);
        $services = Service::where("status", 1)
            ->get()
            ->take($serviceVisibilty->qty);

        $serviceVisibilty = $serviceVisibilty->status == 1 ? true : false;

        $defaultProfile = BannerImage::whereId("15")
            ->select("image")
            ->first();

        return response()->json([
            "language" => $language,
            "setting" => $setting,
            "defaultProfile" => $defaultProfile,
            "flashSaleActive" => $flashSaleActive,
            "flashSale" => $flashSale,
            "flashSaleProducts" => $flashSaleProducts,
            "announcementModal" => $announcementModal,
            "productCategories" => $productCategories,
            "megaMenuCategories" => $megaMenuCategories,
            "megaMenuBanner" => $megaMenuBanner,
            "customPages" => $customPages,
            "googleAnalytic" => $googleAnalytic,
            "facebookPixel" => $facebookPixel,
            "tawk_setting" => $tawk_setting,
            "maintainance_text" => $maintainance_text,
            "cookie_consent" => $cookie_consent,
            "seo_setting" => $seo_setting,
            "filter_price_range" => $filter_price_range,
            "serviceVisibilty" => $serviceVisibilty,
            "services" => $services,
            "footer_first_col" => $footer_first_col,
            "footer_second_col" => $footer_second_col,
            "footer_third_col" => $footer_third_col,
            "footer" => $footer,
            "social_links" => $social_links,
            "login_page_image" => $login_page,
            "recaptchaSetting" => $recaptchaSetting,
            "errorPage" => $errorPage,
            "image_content" => $image_content,
        ]);

    }



    public function subCategoriesByCategory($id)
    {
        $subCategories = SubCategory::where([
            "category_id" => $id,
            "status" => 1,
        ])->get();

        return response()->json(["subCategories" => $subCategories]);
    }

    public function childCategoriesBySubCategory($id)
    {
        $childCategories = ChildCategory::where([
            "sub_category_id" => $id,
            "status" => 1,
        ])->get();

        return response()->json(["childCategories" => $childCategories]);
    }

    public function categoryList()
    {
        $categories = Category::where("status", 1)->get();
        return response()->json(["categories" => $categories]);
    }

    public function category($id)
    {
        $category = Category::find($id);
        return response()->json(["category" => $category]);
    }

    public function subCategory($id)
    {
        $sub_category = SubCategory::find($id);
        return response()->json(["sub_category" => $sub_category]);
    }


    public function childCategory($id)
    {
        $child_category = ChildCategory::find($id);
        return response()->json(["child_category" => $child_category]);
    }

    public function productByCategory($id)
    {
        $category = Category::find($id);
        $products = Product::with("activeVariants.activeVariantItems")->where(["category_id" => $id, "status" => 1,"approve_by_admin" => 1,
        ])->select("id","name","short_name","slug","thumb_image","qty","sold_qty","price","offer_price","is_undefine","is_featured","new_product","is_top","is_best","category_id","sub_category_id","child_category_id","brand_id")->orderBy("id", "desc")->get();

        return response()->json([
            "category" => $category,
            "products" => $products,
        ]);
    }

    public function index()
    {
        $sliderVisibilty = HomePageOneVisibility::find(1);
        $sliders = Slider::orderBy("serial", "asc")
            ->where(["status" => 1])
            ->get()
            ->take($sliderVisibilty->qty);    
        $sliderVisibilty = $sliderVisibilty->status == 1 ? true : false;

        $threeColFirstBanner = BannerImage::whereId("16")->select("image","id","banner_location","title_one","title_two","product_slug","status")->first();

        $threeColSecondBanner = BannerImage::whereId("17")->select("image","id","banner_location","status","title_one","title_two","product_slug")->first();

        $threeColThirdBanner = BannerImage::whereId("18")
            ->select(
                "image",
                "id",
                "banner_location",
                "status",
                "title_one",
                "title_two",
                "product_slug"
            )
            ->first();

        $popularCategoryVisibilty = HomePageOneVisibility::find(4);
        $popularCategories = PopularCategory::with("category")->get();
        $category_arr = [];

        foreach ($popularCategories as $popularCategory) {
            $category_arr[] = $popularCategory->category_id;
        }

        $setting = Setting::first();
        $popularCategoryProducts = Product::with(
            "activeVariants.activeVariantItems"
        )
            ->select(
                "id",
                "name",
                "short_name",
                "slug",
                "thumb_image",
                "qty",
                "sold_qty",
                "price",
                "offer_price",
                "is_undefine",
                "is_featured",
                "new_product",
                "is_top",
                "is_best",
                "category_id",
                "sub_category_id",
                "child_category_id",
                "brand_id"
            )
            ->whereIn("category_id", $category_arr)
            ->where("status", 1)
            ->where("approve_by_admin", 1)
            ->orderBy("id", "desc")
            ->get()
            ->take($popularCategoryVisibilty->qty);

        $popularCategoryVisibilty = $popularCategoryVisibilty->status == 1 ? true : false;
        $popularCategorySidebarBanner = $setting->popular_category_banner;

        $brandVisibility = HomePageOneVisibility::find(5);

        $brands = Brand::where(["status" => 1])
            ->get()
            ->take($brandVisibility->qty);

        $brandVisibility = $brandVisibility->status == 1 ? true : false;

        $flashSale = FlashSale::first();

        $flashSaleSidebarBanner = BannerImage::select(
            "id",
            "link as play_store",
            "image",
            "banner_location",
            "status",
            "title as app_store"
        )->find(24);

        $topRatedVisibility = HomePageOneVisibility::find(6);
        $topRatedProducts = Product::with("activeVariants.activeVariantItems")
            ->select("id","name","short_name","slug","thumb_image","qty","sold_qty","price","offer_price","is_undefine","is_featured","new_product","is_top","is_best","category_id","sub_category_id","child_category_id","brand_id")
            ->where(["is_top" => 1, "status" => 1, "approve_by_admin" => 1])
            ->orderBy("id", "desc")
            ->get()
            ->take($topRatedVisibility->qty);

        $topRatedVisibility = $topRatedVisibility->status == 1 ? true : false;
        $sellerVisibility = HomePageOneVisibility::find(7);
        $sellers = Vendor::where(["status" => 1])
            ->select("id", "logo", "banner_image", "shop_name", "slug")
            ->get()
            ->take($sellerVisibility->qty);

        $sellerVisibility = $sellerVisibility->status == 1 ? true : false;

        $twoColumnBannerOne = BannerImage::select(
            "id",
            "link",
            "image",
            "banner_location",
            "status",
            "title_one",
            "title_two",
            "badge",
            "product_slug"
        )->find(19);

        $twoColumnBannerTwo = BannerImage::select(
            "id",
            "link",
            "image",
            "banner_location",
            "status",
            "title_one",
            "title_two",
            "badge",
            "product_slug"
        )->find(20);

        $featuredCategorySidebarBanner = $setting->featured_category_banner;

        $featuredProductVisibility = HomePageOneVisibility::find(8);

        $featuredCategories = FeaturedCategory::with("category")->get();

        $category_arr = [];

        foreach ($featuredCategories as $featuredCategory) {
            $category_arr[] = $featuredCategory->category_id;
        }

        $featuredCategoryProducts = Product::with(
            "activeVariants.activeVariantItems"
        )->select("id","name","short_name","slug","thumb_image","qty","sold_qty","price","offer_price","is_undefine","is_featured","new_product","is_top","is_best","category_id","sub_category_id","child_category_id","brand_id"
            )
            ->whereIn("category_id", $category_arr)
            ->where(["status" => 1, "approve_by_admin" => 1])
            ->orderBy("id", "desc")
            ->get()
            ->take($featuredProductVisibility->qty);

        $featuredProductVisibility = $featuredProductVisibility->status == 1 ? true : false;

        $singleBannerOne = BannerImage::select(
            "id",
            "link",
            "image",
            "banner_location",
            "status",
            "title_one",
            "title_two",
            "product_slug"
        )->find(21);

        $newArrivalProductVisibility = HomePageOneVisibility::find(9);
        $newArrivalProducts = Product::with("activeVariants.activeVariantItems")
            ->select(
                "id",
                "name",
                "short_name",
                "slug",
                "thumb_image",
                "qty",
                "sold_qty",
                "price",
                "offer_price",
                "is_undefine",
                "is_featured",
                "new_product",
                "is_top",
                "is_best",
                "category_id",
                "sub_category_id",
                "child_category_id",
                "brand_id"
            )
            ->where([
                "new_product" => 1,
                "status" => 1,
                "approve_by_admin" => 1,
            ])
            ->orderBy("id", "desc")
            ->get()
            ->take($newArrivalProductVisibility->qty);

        $newArrivalProductVisibility = $newArrivalProductVisibility->status == 1 ? true : false;

        $singleBannerTwo = BannerImage::select(
            "id",
            "link",
            "image",
            "banner_location",
            "status",
            "title_one",
            "product_slug"
        )->find(22);

        $bestProductVisibility = HomePageOneVisibility::find(10);

        $bestProducts = Product::with("activeVariants.activeVariantItems")
            ->select(
                "id",
                "name",
                "short_name",
                "slug",
                "thumb_image",
                "qty",
                "sold_qty",
                "price",
                "offer_price",
                "is_undefine",
                "is_featured",
                "new_product",
                "is_top",
                "is_best",
                "category_id",
                "sub_category_id",
                "child_category_id",
                "brand_id"
            )
            ->where(["is_best" => 1, "status" => 1, "approve_by_admin" => 1])
            ->orderBy("id", "desc")
            ->get()
            ->take($bestProductVisibility->qty);

        $bestProductVisibility = $bestProductVisibility->status == 1 ? true : false;

        $subscriptionBanner = BannerImage::select(
            "id",
            "image",
            "banner_location",
            "header",
            "title"
        )->find(27);

        $seoSetting = SeoSetting::find(1);
        $setting = Setting::first();
        $section_title = json_decode($setting->homepage_section_title);
        $homepage_categories = Category::where(["status" => 1])
            ->get()
            ->take(15);

        $homepage_sub_categories = SubCategory::where(["status" => 1])
            ->get()
            ->take(15);

        $flashSale = FlashSale::first();
        $flashSaleProducts = FlashSaleProduct::where("status", 1)->get();
        $product_arr = [];
        foreach ($flashSaleProducts as $flashSaleProduct) {
            $product_arr[] = $flashSaleProduct->product_id;
        }

        $flashsale_products = Product::with("activeVariants.activeVariantItems")
            ->whereIn("id", $product_arr)
            ->orderBy("id", "desc")
            ->where(["status" => 1, "approve_by_admin" => 1])
            ->select(
                "id",
                "name",
                "short_name",
                "slug",
                "thumb_image",
                "qty",
                "sold_qty",
                "price",
                "offer_price",
                "is_undefine",
                "is_featured",
                "new_product",
                "is_top",
                "is_best"
            )
            ->get()
            ->take(10);

        return response()->json([
            "section_title" => $section_title,
            "seoSetting" => $seoSetting,
            "sliderVisibilty" => $sliderVisibilty,
            "sliders" => $sliders,
            "banner_one" => $threeColFirstBanner,
            "banner_two" => $threeColSecondBanner,
            "homepage_categories" => $homepage_categories,
            "homepage_sub_categories" => $homepage_sub_categories,
            "popularCategorySidebarBanner" => $popularCategorySidebarBanner,
            "popularCategoryVisibilty" => $popularCategoryVisibilty,
            "popularCategories" => $popularCategories,
            "popularCategoryProducts" => $popularCategoryProducts,
            "brandVisibility" => $brandVisibility,
            "brands" => $brands,
            "flashSale" => $flashSale,
            "flashSaleSidebarBanner" => $flashSaleSidebarBanner,
            "flashsale_products" => $flashsale_products,
            "topRatedVisibility" => $topRatedVisibility,
            "topRatedProducts" => $topRatedProducts,
            "sellerVisibility" => $sellerVisibility,
            "sellers" => $sellers,
            "banner_three" => $twoColumnBannerOne,
            "banner_four" => $twoColumnBannerTwo,
            "featuredProductVisibility" => $featuredProductVisibility,
            "featuredCategorySidebarBanner" => $featuredCategorySidebarBanner,
            "featuredCategories" => $featuredCategories,
            "featuredCategoryProducts" => $featuredCategoryProducts,
            "singleBannerOne" => $singleBannerOne,
            "newArrivalProductVisibility" => $newArrivalProductVisibility,
            "newArrivalProducts" => $newArrivalProducts,
            "bestProductVisibility" => $bestProductVisibility,
            "singleBannerTwo" => $singleBannerTwo,
            "bestProducts" => $bestProducts,
            "subscriptionBanner" => $subscriptionBanner,
        ]);
    }


    public function aboutUs()
    {
        $aboutUs = AboutUs::first();
        $seoSetting = SeoSetting::find(2);
        $testimonials = Testimonial::where(["status" => 1])->get();
        $services = Service::where("status", 1)->get();
        $blogs = PopularPost::with("blog.activeComments")
            ->where("status", 1)
            ->orderBy("id", "desc")
            ->get()
            ->take(10);

        return response()->json([
            "aboutUs" => $aboutUs,
            "seoSetting" => $seoSetting,
            "testimonials" => $testimonials,
            "services" => $services,
            "blogs" => $blogs,
        ]);
    }

    public function contactUs()
    {
        $contact = ContactPage::first();
        $recaptchaSetting = GoogleRecaptcha::first();
        $seoSetting = SeoSetting::find(3);
        return response()->json([
            "contact" => $contact,
            "recaptchaSetting" => $recaptchaSetting,
            "seoSetting" => $seoSetting,
        ]);
    }

    public function sendContactMessage(Request $request)
    {
        $rules = [
            "name" => "required",
            "email" => "required",
            "subject" => "required",
            "message" => "required",
            "g-recaptcha-response" => new Captcha(),
        ];
        $this->validate($request, $rules);
        $setting = Setting::first();
        if ($setting->enable_save_contact_message == 1) {
            $contact = new ContactMessage();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->subject = $request->subject;
            $contact->phone = $request->phone;
            $contact->message = $request->message;
            $contact->save();
        }
        MailHelper::setMailConfig();
        $template = EmailTemplate::where("id", 2)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace("{{name}}", $request->name, $message);
        $message = str_replace("{{email}}", $request->email, $message);
        $message = str_replace("{{phone}}", $request->phone, $message);
        $message = str_replace("{{subject}}", $request->subject, $message);
        $message = str_replace("{{message}}", $request->message, $message);
        Mail::to($setting->contact_email)->send(
            new ContactMessageInformation($message, $subject)
        );

        $notification = trans("Message send successfully");
        return response()->json(["notification" => $notification]);
    }


    public function blog(Request $request)
    {
        $paginateQty = CustomPagination::whereId("1")->first()->qty;

        $blogs = Blog::with("activeComments")
            ->orderBy("id", "desc")
            ->where(["status" => 1]);

        if ($request->search) {
            $blogs = $blogs->where(
                "title",
                "LIKE",
                "%" . $request->search . "%"
            );
        }

        if ($request->category) {
            $category = BlogCategory::where(
                "slug",
                $request->category
            )->first();

            $blogs = $blogs->where("blog_category_id", $category->id);
        }

        $blogs = $blogs->paginate($paginateQty);

        $seoSetting = SeoSetting::find(6);

        return response()->json([
            "blogs" => $blogs,
            "seoSetting" => $seoSetting,
        ]);
    }

    public function blogDetail($slug)
    {
        $blog = Blog::where(["status" => 1, "slug" => $slug])->first();

        $blog->views += 1;
        $blog->save();

        $popularPosts = PopularPost::with("blog")
            ->where(["status" => 1])
            ->get();

        $categories = BlogCategory::where(["status" => 1])->get();

        $recaptchaSetting = GoogleRecaptcha::first();

        $paginateQty = CustomPagination::whereId("4")->first()->qty;

        $activeComments = BlogComment::where("blog_id", $blog->id)
            ->orderBy("id", "desc")
            ->paginate($paginateQty);

        return response()->json([
            "blog" => $blog,
            "popularPosts" => $popularPosts,
            "categories" => $categories,
            "recaptchaSetting" => $recaptchaSetting,
            "activeComments" => $activeComments,
        ]);
    }


    public function blogComment(Request $request)
    {
        $rules = [
            "name" => "required",
            "email" => "required",
            "comment" => "required",
            "blog_id" => "required",
            "g-recaptcha-response" => new Captcha(),
        ];

        $this->validate($request, $rules);

        $comment = new BlogComment();

        $comment->blog_id = $request->blog_id;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->save();

        $notification = trans("Blog comment submited successfully");
        return response()->json(["message" => $notification], 200);
    }



    public function faq()
    {
        $faqs = FAQ::orderBy("id", "desc")
            ->where("status", 1)
            ->get();

        return response()->json(["faqs" => $faqs]);
    }

    public function trackOrderResponse($id)
    {
        if (!$id) {
            $message = trans("Order id is required");
            return response()->json(["status" => 0, "message" => $message]);
        }

        $order = Order::where("order_id", $id)->first();
        if ($order) {
            return response()->json(["order" => $order]);
        } else {
            $message = trans("Order not found");
            return response()->json(["status" => 0, "message" => $message]);
        }

    }


    public function allCustomPage()
    {
        $pages = CustomPage::where(["status" => 1])->get();
        return response()->json(["pages" => $pages]);
    }

    public function customPage($slug)
    {
        $page = CustomPage::where(["slug" => $slug, "status" => 1])->first();
        return response()->json(["page" => $page]);
    }

    public function termsAndCondition()
    {
        $terms_conditions = TermsAndCondition::select(
            "terms_and_condition"
        )->first();

        return response()->json(["terms_conditions" => $terms_conditions]);
    }

    public function sellerTemsCondition()
    {
        $seller_tems_conditions = Setting::select("seller_condition")->first();
        return response()->json([
            "seller_tems_conditions" => $seller_tems_conditions,
        ]);
    }

    public function privacyPolicy()
    {
        $privacyPolicy = TermsAndCondition::select("privacy_policy")->first();
        return response()->json(["privacyPolicy" => $privacyPolicy]);
    }

    public function seller()
    {
        $sellers = Vendor::orderBy("id", "desc")
            ->where("status", 1)
            ->select(
                "id",
                "banner_image",
                "shop_name",
                "slug",
                "open_at",
                "closed_at",
                "address",
                "email",
                "logo",
                "phone"
            )
            ->paginate(20);

        $seoSetting = SeoSetting::find(5);

        return response()->json([
            "sellers" => $sellers,
            "seoSetting" => $seoSetting,
        ]);
    }




    public function sellerDetail(Request $request, $shop_name)
    {
        $slug = $shop_name;

        $seller = Vendor::where(["status" => 1, "slug" => $slug])
            ->select(
                "id",
                "banner_image",
                "shop_name",
                "slug",
                "open_at",
                "closed_at",
                "address",
                "email",
                "phone",
                "seo_title",
                "seo_description",
                "logo"
            )
            ->first();

        if (!$seller) {
            return response()->json(["message" => "Seller not found"], 403);
        }

        $searchCategoryArr = [];
        $searchBrandArr = [];
        $categories = Category::with("activeSubCategories.activeChildCategories")
            ->where(["status" => 1])
            ->select("id", "name", "slug", "icon")
            ->get();

        $brands = Brand::where(["status" => 1])
            ->select("id", "name", "slug")
            ->get();

        $activeVariants = ProductVariant::with("activeVariantItems")
            ->select("name", "id")
            ->groupBy("name")
            ->get();

        $paginateQty = CustomPagination::whereId("2")->first()->qty;

        $products = Product::with("activeVariants.activeVariantItems")
            ->orderBy("id", "desc")
            ->where([
                "status" => 1,
                "vendor_id" => $seller->id,
                "approve_by_admin" => 1,
            ]);

        if ($request->category) {
            $category = Category::where("slug", $request->category)->first();
            $products = $products->where("category_id", $category->id);
            $searchCategoryArr[] = $category->id;
        }

        if ($request->sub_category) {
            $sub_category = SubCategory::where(
                "slug",
                $request->sub_category
            )->first();
            $products = $products->where("sub_category_id", $sub_category->id);
            $searchCategoryArr[] = $sub_category->category_id;
        }

        if ($request->child_category) {
            $child_category = ChildCategory::where(
                "slug",
                $request->child_category
            )->first();
            $products = $products->where(
                "child_category_id",
                $child_category->id
            );
            $searchCategoryArr[] = $child_category->category_id;
        }

        if ($request->brand) {
            $brand = Brand::where("slug", $request->brand)->first();
            $products = $products->where("brand_id", $brand->id);
            $searchBrandArr[] = $brand->id;
        }

        if ($request->search) {
            $products = $products->where(
                "name",
                "LIKE",
                "%" . $request->search . "%"
            );
        }

        $paginateQty = CustomPagination::whereId("2")->first()->qty;

        $products = $products->select(
            "id",
            "name",
            "short_name",
            "slug",
            "thumb_image",
            "qty",
            "sold_qty",
            "price",
            "offer_price",
            "is_undefine",
            "is_featured",
            "new_product",
            "is_top",
            "is_best",
            "category_id",
            "sub_category_id",
            "child_category_id",
            "brand_id"
        );

        $products = $products->paginate($paginateQty);
        $products = $products->appends($request->all());

        $sellerReviewQty = ProductReview::where("status", 1)
            ->where("product_vendor_id", $seller->id)
            ->count();

        $sellerTotalReview = ProductReview::where("status", 1)
            ->where("product_vendor_id", $seller->id)
            ->sum("rating");

        $shopPageCenterBanner = BannerImage::select(
            "link",
            "image",
            "banner_location",
            "status",
            "after_product_qty",
            "title_one",
            "product_slug"
        )->find(25);

        $shopPageSidebarBanner = BannerImage::select(
            "link",
            "image",
            "banner_location",
            "status",
            "title_one",
            "title_two",
            "product_slug"
        )->find(26);

        return response()->json([
            "seller" => $seller,
            "sellerReviewQty" => $sellerReviewQty,
            "sellerTotalReview" => $sellerTotalReview,
            "searchCategoryArr" => $searchCategoryArr,
            "searchBrandArr" => $searchBrandArr,
            "categories" => $categories,
            "brands" => $brands,
            "activeVariants" => $activeVariants,
            "products" => $products,
            "shopPageCenterBanner" => $shopPageCenterBanner,
            "shopPageSidebarBanner" => $shopPageSidebarBanner,
        ]);


    }



    public function variantItemsByVariant($name)
    {
        $variantItemsForSearch = ProductVariantItem::with("product", "variant")
            ->groupBy("name")
            ->select("name", "id")
            ->where("product_variant_name", $name)
            ->get();

        return response()->json([
            "variantItemsForSearch" => $variantItemsForSearch,
        ]);
    }

    public function product(Request $request)
    {
        $searchCategoryArr = [];
        $searchBrandArr = [];

        $categories = Category::with("activeSubCategories.activeChildCategories")
            ->where(["status" => 1])
            ->select("id", "name", "slug", "icon")
            ->get();

        $brands = Brand::where(["status" => 1])
            ->select("id", "name", "slug")
            ->get();

        $activeVariants = ProductVariant::with("activeVariantItems")
            ->select("name", "id")
            ->groupBy("name")
            ->get();

        $paginateQty = CustomPagination::whereId("2")->first()->qty;

        $products = Product::with("activeVariants.activeVariantItems")
            ->orderBy("products.id", "desc")
            ->where(["products.status" => 1, "approve_by_admin" => 1]);

        $products->leftJoin('categories', 'categories.id', '=', 'products.category_id');
            
        if ($request->category) {
            $category = Category::where("slug", $request->category)->first();
            $products = $products->where("category_id", $category->id);
            $searchCategoryArr[] = $category->id;
        }

        if ($request->sub_category) {
            $sub_category = SubCategory::where("slug", $request->sub_category)->first();
            $products = $products->where("sub_category_id", $sub_category->id);
            $searchCategoryArr[] = $sub_category->category_id;
        }

        if ($request->child_category) {
            $child_category = ChildCategory::where("slug", $request->child_category)->first();
            $products = $products->where("child_category_id", $child_category->id);
            $searchCategoryArr[] = $child_category->category_id;
        }

        $popularCategoryArr = [];

        if ($request->highlight) {
            if ($request->highlight == "popular_category") {
                $pop_categories = PopularCategory::all();
                foreach ($pop_categories as $pop_category) {
                    $popularCategoryArr[] = $pop_category->category_id;
                }
                $products = $products->whereIn("products.category_id", $popularCategoryArr);
            }
        }


        if ($request->highlight == "top_product") {
            $products = $products->where("products.is_top", 1);
        }

        if ($request->highlight == "new_arrival") {
            $products = $products->where("products.new_product", 1);
        }

        if ($request->highlight == "featured_product") {
            $products = $products->where("products.is_featured", 1);
        }

        if ($request->highlight == "best_product") {
            $products = $products->where("products.is_best", 1);
        }

        if ($request->brand) {
            $brand = Brand::where("slug", $request->brand)->first();
            $products = $products->where("brand_id", $brand->id);
            $searchBrandArr[] = $brand->id;
        }

        if ($request->search) {
            $searchTxt = $request->search;
            $products = $products->where(function($query)  use ($searchTxt) {
                $query->where('products.sku','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('products.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('products.short_name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('products.long_description','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('products.slug','LIKE',"%" . $searchTxt . "%");
            });


        }

        $products = $products->select(
            "products.id",
            "products.name",
            "products.short_name",
            "products.slug",
            "thumb_image",
            "qty",
            "sold_qty",
            "price",
            "offer_price",
            "is_undefine",
            "is_featured",
            "new_product",
            "is_top",
            "is_best",
            "category_id",
            "sub_category_id",
            "child_category_id",
            "brand_id",
            "categories.name as category_name",
            "categories.slug as category_slug"
        );

        $products = $products->paginate($paginateQty);
        $products = $products->appends($request->all());
        $seoSetting = SeoSetting::find(9);

        $shopPageCenterBanner = BannerImage::select(
            "link",
            "image",
            "banner_location",
            "status",
            "after_product_qty",
            "title_one",
            "product_slug"
        )->find(25);

        $shopPageSidebarBanner = BannerImage::select(
            "link",
            "image",
            "banner_location",
            "status",
            "title_one",
            "title_two",
            "product_slug"
        )->find(26);


        return response()->json([
            "searchCategoryArr" => $searchCategoryArr,
            "searchBrandArr" => $searchBrandArr,
            "categories" => $categories,
            "brands" => $brands,
            "activeVariants" => $activeVariants,
            "products" => $products,
            "seoSetting" => $seoSetting,
            "shopPageCenterBanner" => $shopPageCenterBanner,
            "shopPageSidebarBanner" => $shopPageSidebarBanner,
        ]);

    }



    public function searchProduct(Request $request)
    {

        $paginateQty = CustomPagination::whereId("2")->first()->qty;
        if ($request->variantItems) {
            $products = Product::with("activeVariants.activeVariantItems")
                ->whereHas("variantItems", function ($query) use ($request) {
                    $sortArr = [];
                    if ($request->variantItems) {
                        foreach ($request->variantItems as $variantItem) {
                            $sortArr[] = $variantItem;
                        }
                        $query->whereIn("name", $sortArr);
                    }
                })
                ->where("products.status", 1)
                ->where("approve_by_admin", 1);
        } else {
            $products = Product::with("activeVariants.activeVariantItems")
                ->where("products.status", 1)
                ->where("approve_by_admin", 1);
        }
        $products->leftJoin('categories', 'categories.id', '=', 'products.category_id');

        if ($request->shorting_id) {
            if ($request->shorting_id == 1) {
                $products = $products->orderBy("products.id", "desc");
            } elseif ($request->shorting_id == 2) {
                $products = $products->orderBy("products.price", "asc");
            } elseif ($request->shorting_id == 3) {
                $products = $products->orderBy("products.price", "desc");
            }
        } else {
            $products = $products->orderBy("products.id", "desc");
        }

        /*if ($request->sub_category && $request->category) {
            $sub_category = SubCategory::where(
                "slug",
                $request->sub_category
            )->first();
            $products = $products->where("sub_category_id", $sub_category->id)->where("category_id", $sub_category->category_id);
        }
        else */
        if ($request->category) {
            $category = Category::where("slug", $request->category)->first();
            $products = $products->where("category_id", $category->id);
        }
        /*else */
        if ($request->sub_category) {
            $sub_category = SubCategory::where(
                "slug",
                $request->sub_category
            )->first();
            // $products = $products->where("sub_category_ids", $sub_category->id)->where("category_id", $sub_category->category_id);
            $products = $products->where("sub_category_id", $sub_category->id);
        }



        if ($request->child_category) {
            $child_category = ChildCategory::where(
                "slug",
                $request->child_category
            )->first();

            $products = $products->where(
                "child_category_id",
                $child_category->id
            );
        }

        if ($request->brand) {
            $brand = Brand::where("slug", $request->brand)->first();
            $products = $products->where("brand_id", $brand->id);
        }

        $brandSortArr = [];

        if ($request->brands) {
            foreach ($request->brands as $brand) {
                $brandSortArr[] = $brand;
            }

            $products = $products->whereIn("brand_id", $brandSortArr);

        }

        $categorySortArr = [];
        if ($request->categories) {
            foreach ($request->categories as $brand) {
                $categorySortArr[] = $brand;
            }

            $products = $products->whereIn("category_id", $categorySortArr);
        }

        $popularCategoryArr = [];

        if ($request->highlight) {
            if ($request->highlight == "popular_category") {
                $pop_categories = PopularCategory::all();
                foreach ($pop_categories as $pop_category) {
                    $popularCategoryArr[] = $pop_category->category_id;
                }

                $products = $products->whereIn(
                    "category_id",
                    $popularCategoryArr
                );
            }

            if ($request->highlight == "top_product") {
                $products = $products->where("is_top", 1);
            }

            if ($request->highlight == "new_arrival") {
                $products = $products->where("new_product", 1);
            }

            if ($request->highlight == "featured_product") {
                $products = $products->where("is_featured", 1);
            }

            if ($request->highlight == "best_product") {
                $products = $products->where("is_best", 1);
            }
        }

        if ($request->min_price) {
            if ($request->min_price > 0) {

                $products = $products->where(function ($query) use ($request) {
                   // $query->where('price', '>=', $request->min_price)->orWhere('offer_price', '>=', $request->min_price);
                   $query->where('offer_price', '>=', $request->min_price);
                });
                // $products = $products->where(
                //     "price",
                //     ">=",
                //     $request->min_price
                // )->orWhere(
                //     "offer_prices",
                //     ">=",
                //     $request->min_price
                // );
            }
        }

        

        if ($request->max_price) {
            if ($request->max_price > 0) {

                $products = $products->where(function ($query) use ($request) {
                   // $query->where('price', '<=', $request->max_price)->orWhere('offer_price', '<=', $request->max_price);
                   $query->where('offer_price', '<=', $request->max_price);
                });

                // $products = $products->where(
                //     "price",
                //     "<=",
                //     $request->max_price
                // )->orWhere(
                //     "offer_price",
                //     ">=",
                //     $request->max_price
                // );
            }
        }

        if ($request->shop_name) {
            $slug = $request->shop_name;
            $seller = Vendor::where(["slug" => $slug])->first();
            $products = $products->where("vendor_id", $seller->id);
        }

        if ($request->search && $request->search != 'undefined') {
            $searchTxt = $request->search;
            $products = $products->where(function($query)  use ($searchTxt) {
                $query->where('products.sku','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('products.name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('products.short_name','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('products.long_description','LIKE',"%" . $searchTxt . "%")
                    ->orWhere('products.slug','LIKE',"%" . $searchTxt . "%");
            });


        }
        $products = $products->select(
            "products.id",
            "products.name",
            "products.short_name",
            "products.slug",
            "thumb_image",
            "qty",
            "sold_qty",
            "price",
            "offer_price",
            "is_undefine",
            "is_featured",
            "new_product",
            "is_top",
            "is_best",
            "category_id",
            "sub_category_id",
            "child_category_id",
            "brand_id",
            "categories.name as category_name",
            "categories.slug as category_slug"
        );
        // dd($products->toSql());
        $products = $products->paginate($paginateQty);
        $products = $products->appends($request->all());

        return response()->json(["products" => $products]);
    }

    public function productDetail($slug)
    {
        $product = Product::with(
            "category",
            "brand",
            "activeVariants.activeVariantItems",
            "avgReview",
        )
        ->where(["status" => 1, "slug" => $slug])
        ->first();

        if (!$product) {
            $notification = trans("Something went wrong");
            return response()->json(["message" => $notification], 403);
        }

        $paginateQty = CustomPagination::whereId("5")->first()->qty;
        $productReviews = ProductReview::with("user")
            ->where(["status" => 1, "product_id" => $product->id])
            ->get()
            ->take(10);

        $totalProductReviewQty = ProductReview::where([
            "status" => 1,
            "product_id" => $product->id,
        ])->count();

        $totalReview = ProductReview::where([
            "status" => 1,
            "product_id" => $product->id,
        ])->sum("rating");

        $recaptchaSetting = GoogleRecaptcha::first();

        $relatedProducts = Product::with("activeVariants.activeVariantItems")
            ->where([
                "category_id" => $product->category_id,
                "status" => 1,
                "approve_by_admin" => 1,
            ])
            ->where("id", "!=", $product->id)
            ->get()
            ->take(10);

        $defaultProfile = BannerImage::whereId("15")
            ->select("image")
            ->first();

        $specifications = ProductSpecification::with("key")->where("product_id", $product->id)
            ->get();

        //$specifications = ProductSpecification::with("key")->where("product_id", $product->id)->join('product_specification_keys', 'product_specification_keys.id', '=', 'product_specifications.product_specification_key_id')->get();    

        $gellery = ProductGallery::where("product_id", $product->id)->get();

        $is_seller_product = $product->vendor_id == 0 ? false : true;

        $this_seller_products = [];

        if ($is_seller_product) {
            $this_seller_products = Product::with(
                "activeVariants.activeVariantItems"
            )
            ->where([
                "vendor_id" => $product->vendor_id,
                "status" => 1,
                "approve_by_admin" => 1,
            ])
            ->where("id", "!=", $product->id)
            ->get()
            ->take(10);
        }

        $seller = Vendor::with("user")
            ->where("id", $product->vendor_id)
            ->first();

        $sellerTotalProducts = 0;

        if ($is_seller_product) {
            $sellerTotalProducts = Product::with(
                "activeVariants.activeVariantItems"
            )
            ->where([
                "status" => 1,
                "vendor_id" => $product->vendor_id,
                "approve_by_admin" => 1,
            ])
            ->count();
        }

        $sellerReviewQty = 0;
        if ($is_seller_product) {
            $sellerReviewQty = ProductReview::where([
                "status" => 1,
                "product_vendor_id" => $product->vendor_id,
            ])->count();
        }

        $sellerTotalReview = 0;
        if ($is_seller_product) {
            $sellerTotalReview = ProductReview::where([
                "status" => 1,
                "product_vendor_id" => $product->vendor_id,
            ])->sum("rating");
        }

        $tagArray = json_decode($product->tags);
        $tags = "";

        if ($product->tags) {
            foreach ($tagArray as $index => $tag) {
                $tags .= $tag->value . ",";
            }
        }

        return response()->json([
            "product" => $product,
            "gellery" => $gellery,
            "tags" => $tags,
            "totalProductReviewQty" => $totalProductReviewQty,
            "totalReview" => $totalReview,
            "productReviews" => $productReviews,
            "specifications" => $specifications,
            "recaptchaSetting" => $recaptchaSetting,
            "relatedProducts" => $relatedProducts,
            "defaultProfile" => $defaultProfile,
            "is_seller_product" => $is_seller_product,
            "seller" => $seller,
            "sellerTotalProducts" => $sellerTotalProducts,
            "this_seller_products" => $this_seller_products,
            "sellerReviewQty" => $sellerReviewQty,
            "sellerTotalReview" => $sellerTotalReview,
        ]);
    }

    public function productReviewList($id)
    {
        $reviews = ProductReview::with("user")
            ->where(["product_id" => $id, "status" => 1])
            ->paginate(10);

        return response()->json(["reviews" => $reviews]);
    }

    public function addToCompare($id)
    {
        $compare_array = [];
        foreach (Cart::instance("compare")->content() as $content) {
            $compare_array[] = $content->id;
        }

        if (3 <= Cart::instance("compare")->count()) {
            $notification = trans("Already 3 items added");
            return response()->json([
                "status" => 0,
                "message" => $notification,
            ]);
        }

        if (in_array($id, $compare_array)) {
            $notification = trans("Already added this item");
            return response()->json([
                "status" => 0,
                "message" => $notification,
            ]);

        } else {
            $product = Product::with("tax")->find($id);
            $data = [];
            $data["id"] = $id;
            $data["name"] = "abc";
            $data["qty"] = 1;
            $data["price"] = 1;
            $data["weight"] = 1;
            $data["options"]["product"] = $product;
            Cart::instance("compare")->add($data);
            $notification = trans("Item added successfully");
            return response()->json([
                "status" => 1,
                "message" => $notification,
            ]);
        }
    }

    public function compare()
    {
        $banner = BreadcrumbImage::where(["id" => 6])->first();
        $compare_contents = Cart::instance("compare")->content();
        $currencySetting = Setting::first();

        return view("compare", compact("banner", "compare_contents", "currencySetting"));

    }

    public function removeCompare($id)
    {
        Cart::instance("compare")->remove($id);
        $notification = trans("Item remmoved successfully");

        $notification = ["messege" => $notification, "alert-type" => "success"];

        return redirect()
            ->back()
            ->with($notification);
    }

    public function flashSale()
    {
        $flashSale = FlashSale::first();
        $flashSaleProducts = FlashSaleProduct::where("status", 1)->get();
        $product_arr = [];
        foreach ($flashSaleProducts as $flashSaleProduct) {
            $product_arr[] = $flashSaleProduct->product_id;
        }

        $paginateQty = CustomPagination::whereId("2")->first()->qty;
        $products = Product::with("activeVariants.activeVariantItems")
            ->whereIn("id", $product_arr)
            ->orderBy("id", "desc")
            ->where(["status" => 1, "approve_by_admin" => 1])
            ->select(
                "id",
                "name",
                "short_name",
                "slug",
                "thumb_image",
                "qty",
                "sold_qty",
                "price",
                "offer_price",
                "is_undefine",
                "is_featured",
                "new_product",
                "is_top",
                "is_best"
            )
            ->paginate($paginateQty);

        $seoSetting = SeoSetting::find(8);

        return response()->json([
            "flashSale" => $flashSale,
            "products" => $products,
            "seoSetting" => $seoSetting,
        ]);
    }

    public function subscribeRequest(Request $request)
    {
        if ($request->email != null) {
            $isExist = Subscriber::where("email", $request->email)->count();
            if ($isExist == 0) {
                $subscriber = new Subscriber();
                $subscriber->email = $request->email;
                $subscriber->verified_token = random_int(100000, 999999);
                $subscriber->save();

                MailHelper::setMailConfig();
                $template = EmailTemplate::where("id", 3)->first();
                $message = $template->description;
                $subject = $template->subject;

                Mail::to($subscriber->email)->send(
                    new SubscriptionVerification(
                        $subscriber,
                        $message,
                        $subject
                    )
                );

                return response()->json([
                    "message" => trans(
                        "Subscription successfully, please verified your email"
                    ),
                ]);

            } else {
                return response()->json(
                    ["message" => trans("Email already exist"), 403],
                    403
                );
            }
        } else {
            return response()->json(
                ["message" => trans("Email Field is required")],
                403
            );
        }
    }

    public function subscriberVerifcation(Request $request, $token)
    {
        $subscriber = Subscriber::where([
            "verified_token" => $token,
            "email" => $request->email,
        ])->first();

        if ($subscriber) {
            $subscriber->verified_token = null;
            $subscriber->is_verified = 1;
            $subscriber->save();

            $setting = Setting::first();
            $frontend_url = $setting->frontend_url;

            return redirect($frontend_url);
        } else {
            $setting = Setting::first();
            $frontend_url = $setting->frontend_url;

            return redirect($frontend_url);

        }
    }
}

