import React from "react";
import Link from "next/link";
import Image from "next/image";
import ShopNowBtn from "../../Helpers/Buttons/ShopNowBtn";

function TwoColumnAds({ bannerOne, bannerTwo }) {
  return (
    <div className="w-full h-full">
      <div className="container-x mx-auto h-full">
        <div
          className={`lg:flex xl:space-x-[30px] md:space-x-5 items-center w-full h-full  overflow-hidden`}
        >
          {bannerOne && (
            
            <div
              className="w-full add_banner bn3 h-[400px] rounded"
              style={{
                backgroundImage: `url(${
                  process.env.NEXT_PUBLIC_BASE_URL + bannerOne.image
                })`,
                backgroundRepeat: `no-repeat`,
                backgroundSize: "cover",
                backgroundPosition: "95%",
              }}
            >
              <div className="px-[40px] pt-[80px]">
                <span className="text-sm text-qblack mb-2 inline-block uppercase font-medium">
                  {bannerOne.title_one}
                </span>
                <h1 className="text-[34px] leading-[38px] font-semibold text-qblack mb-[20px] w-[277px]">
                  {bannerOne.title_two}
                </h1>
                <Link
                  href={{
                    pathname: "/shop/"+bannerOne.product_slug,
                    //query: { category: bannerOne.product_slug },
                  }}
                  // href={{
                  //   pathname: "/products",
                  //   query: { category: bannerOne.product_slug },
                  // }}
                  passHref
                >
                  <a rel="noopener noreferrer">
                    <ShopNowBtn
                      className="w-[128px] h-[40px] bg-qyellow"
                      textColor="text-qblack group-hover:text-white"
                    />
                  </a>
                </Link>
              </div>
            </div>
          )}
          {bannerTwo && (
            <div
            className="w-full add_banner bn4 h-[400px] rounded"
            style={{
              backgroundImage: `url(${
                process.env.NEXT_PUBLIC_BASE_URL + bannerTwo.image
              })`,
              backgroundRepeat: `no-repeat`,
              backgroundSize: "cover",
              backgroundPosition: "95%",
            }}
          >
            <div className="px-[40px] pt-[80px]">
              <span className="text-sm text-qblack mb-2 inline-block uppercase font-medium">
                {bannerTwo.title_one}
              </span>
              <h1 className="text-[34px] leading-[38px] font-semibold text-qblack mb-[20px] w-[277px]">
                {bannerTwo.title_two}
              </h1>
              <Link
                href={{
                  pathname: "/shop/"+bannerTwo.product_slug,
                  //query: { category: bannerTwo.product_slug },
                }}
                // href={{
                //   pathname: "/products",
                //   query: { category: bannerTwo.product_slug },
                // }}
                passHref
              >
                <a rel="noopener noreferrer">
                  <ShopNowBtn
                    className="w-[128px] h-[40px] bg-qyellow"
                    textColor="text-qblack group-hover:text-white"
                  />
                </a>
              </Link>
            </div>
          </div>
          )}
        </div>
      </div>
    </div>
  );
}
          

export default TwoColumnAds;
