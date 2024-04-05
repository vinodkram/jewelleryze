import Link from "next/link";
import SimpleSlider from "../Helpers/SliderCom";
import ShopNowBtn from "../Helpers/Buttons/ShopNowBtn";
import { useRef } from "react";
export default function Banner({ className, sliders = [] }) {
  const sliderRef = useRef(null);
  const settings = {
    infinite: true,
    autoplay: true,
    fade: true,
    arrows: false,
    dots: true,
    responsive: [
      {
        breakpoint: 1025,
        settings: {
          dots: true,
        },
      },
    ],
  };
  return (
    <>
      <div className={`w-full xl:h-[733px] h-[350px] ${className || ""}`}>
        <div className="main-wrapper w-full h-full">
          {/*    slider area*/}
          <div className="hero-slider-wrapper xl:h-full mb-20 xl:mb-0  w-full relative">
            <SimpleSlider settings={settings} selector={sliderRef}>
              {sliders.length > 0 &&
                sliders.map((item, i) => (
                  <div key={i} className="item w-full xl:h-[733px] h-[350px]">
                    <div
                      className="w-full h-full relative"
                      style={{
                        backgroundImage: `url(${
                          process.env.NEXT_PUBLIC_BASE_URL + item.image
                        })`,
                        backgroundRepeat: "no-repeat",
                        backgroundSize: "cover",
                        backgroundPositionX: "80%",
                      }}
                    >
                      <div className="container-x mx-auto flex items-center  h-full">
                        <div className="w-full h-full xl:flex items-center pt-10 xl:pt-0">
                          <div className="md:w-[626px] w-full">
                             <p className="md:text-[34px] text-[20px] font-medium text-qpurple whiteShadow mb-[7px]">
                              {item.title_one}
                            </p>
                            <h1 className="md:text-[66px] text-2xl font-bold text-white shadow md:leading-[80px] leading-[40px] mb-[44px]">
                              {item.title_two}
                            </h1>

                            <Link
                              href={{
                                pathname: "/products/[slug]",
                                query: { slug: item.product_slug },
                              }}
                              // href={{
                              //   pathname: "/single-product",
                              //   query: { slug: item.product_slug },
                              // }}
                              passhref
                            >
                              <a rel="noopener noreferrer">
                                <ShopNowBtn
                                  className="md:w-[160px] w-[145px] h-[52px] bg-qpurple"
                                  textColor="text-white group-hover:text-white"
                                />
                              </a>
                            </Link>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
            </SimpleSlider>
          </div>
        </div>
      </div>
    </>
  );
}
