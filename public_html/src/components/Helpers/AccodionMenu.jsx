import { useEffect, useState } from "react";
import Link from "next/link";
import Image from "next/image";

export default function Accodion({ init, slug, image, title, des }) {
  const [collaps, setCollaps] = useState(init || true);
  const [collapsub, setCollapsub] = useState(init || true);
  const [subCatHeight, setHeight] = useState(null);
  const handler = () => {
    setCollaps(!collaps);
  };
  const subhandler = () => {
    setCollapsub(!collapsub);
  };

  useEffect(() => {
    let categorySelector = document.querySelector(".category-dropdown");
    setHeight(categorySelector.offsetHeight);
  }, [collapsub]);
  return (
    <div
      style={{ boxShadow: "rgba(0, 0, 0, 0.05) 0px 15px 64px" }}
      className={`accordion-item rounded w-full bg-white overflow-hidden ${
        collaps ? "bg-qpurple" : "bg-white"
      } 
      ${collapsub ? "bg-qpurple" : "bg-white"}`}
    >
      <button
        onClick={handler}
        type="button"
        className="w-full abc-abcc bg-qpurplelow h-[50px] text-qblack flex justify-between items-center px-5"
      >
        <Link
          href={{
            pathname: "/shop/[category]",
            query: { category: slug },
          }}
          passHref
        >
          <a rel="noopener noreferrer">
            <div className=" flex justify-between items-center px-2 h-10 cursor-pointer">
              <div className="flex items-center space-x-6">
                <span className="icon pt-2">
                  {/*<FontAwesomeCom*/}
                  {/*  className="w-4 h-4"*/}
                  {/*  icon={item.icon}*/}
                  {/*/>*/}
                  <Image
                    width="45px"
                    height="45px"
                    objectFit="scale-down"
                    src={process.env.NEXT_PUBLIC_BASE_URL + image}
                    alt=""
                  />
                </span>
                <span className="name text-sm font-400">{title}</span>
              </div>
            </div>
          </a>
        </Link>
        <span className="text-[#9A9A9A]">
          {collaps ? (
            <svg
              width="21"
              height="20"
              viewBox="0 0 21 20"
              className="fill-current text-qpurple"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M20.9974 10.2344C20.9974 10.0781 20.9974 9.92188 20.9974 9.76562C20.987 9.75 20.9766 9.72917 20.9714 9.71354C20.7109 8.71354 20.1068 8.24479 19.0755 8.24479C17.0495 8.24479 15.0287 8.24479 13.0026 8.24479C12.9245 8.24479 12.8516 8.24479 12.7526 8.24479C12.7526 8.15104 12.7526 8.07292 12.7526 7.99479C12.7526 5.93229 12.7526 3.86979 12.7526 1.80208C12.7526 1.01042 12.2995 0.354168 11.5807 0.104168C11.4662 0.0625019 11.3464 0.036459 11.2266 0C11.0703 0 10.9141 0 10.7578 0C10.7474 0.00520897 10.737 0.015625 10.7266 0.020834C9.70052 0.291668 9.23698 0.885418 9.23698 1.94271C9.23698 3.95833 9.23698 5.97917 9.23698 7.99479C9.23698 8.07292 9.23698 8.14583 9.23698 8.24479C9.14323 8.24479 9.06511 8.24479 8.98698 8.24479C6.92448 8.24479 4.86198 8.24479 2.79427 8.24479C1.90365 8.24479 1.22656 8.79167 1.02865 9.65625C1.01823 9.69271 1.0026 9.72917 0.992188 9.76562C0.992188 9.92188 0.992188 10.0781 0.992188 10.2344C1.0026 10.2604 1.01302 10.2812 1.02344 10.3073C1.26823 11.2708 1.89323 11.7604 2.89323 11.7604C4.92969 11.7604 6.96615 11.7604 8.9974 11.7604C9.07032 11.7604 9.14844 11.7604 9.23698 11.7604C9.23698 11.8646 9.23698 11.9427 9.23698 12.0156C9.23698 14.0781 9.23698 16.1406 9.23698 18.1979C9.23698 18.901 9.54427 19.4375 10.1589 19.7813C10.3411 19.8854 10.5599 19.9271 10.7578 20C10.9141 20 11.0703 20 11.2266 20C11.237 19.9948 11.2474 19.9844 11.2578 19.9792C12.2787 19.7188 12.7474 19.1146 12.7474 18.0573C12.7474 16.0417 12.7474 14.0208 12.7474 12.0052C12.7474 11.9271 12.7474 11.8542 12.7474 11.7552C12.8412 11.7552 12.9193 11.7552 12.9974 11.7552C15.0599 11.7552 17.1224 11.7552 19.1901 11.7552C19.8307 11.7552 20.3464 11.5 20.6901 10.9635C20.8359 10.75 20.8984 10.4792 20.9974 10.2344Z" />
            </svg>
          ) : (
            <svg
              width="20"
              height="4"
              viewBox="0 0 20 4"
              className="fill-current text-qpurple"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M19.9798 2.27979C19.8586 2.56995 19.7575 2.90155 19.5959 3.17098C19.212 3.73057 18.6665 3.97927 17.9998 4C17.9593 4 17.9189 4 17.8785 4C12.6051 4 7.33175 4 2.05836 4C1.51284 4 1.02793 3.85492 0.603634 3.48187C-0.204548 2.71503 -0.204548 1.26425 0.623839 0.518135C1.02793 0.165803 1.47243 0 1.99775 0C7.33175 0 12.6455 0 17.9795 0C18.9898 0 19.7373 0.621762 19.9596 1.63731C19.9596 1.65803 19.9798 1.67876 20 1.69948C19.9798 1.88601 19.9798 2.07254 19.9798 2.27979Z"
              />
            </svg>
          )}
        </span>
      </button>
      {!collaps && (
        <div className="border-t border-qborder">
          <div data-aos="fade-up">
            <div className="sm:text-[15px] text-xs">
              <ul className="">
                {des.map((subItem) => (
                  <li key={subItem.id} className="category-item">
                    <button
                      onClick={subhandler}
                      type="button"
                      className="w-full h-10 accordion-abc-menu bg-qpurplelow text-qblack flex justify-between items-center px-5"
                    >
                      <Link
                        href={{
                          pathname: "/shop/[category]/[sub_category]",
                          query: { category: slug, sub_category: subItem.slug },
                        }}
                        passHref
                      >
                        <a rel="noopener noreferrer">
                          <div className=" flex justify-between items-center px-5 h-10 transition-all duration-300 ease-in-out cursor-pointer">
                            <div>
                              <span className="text-sm font-400">
                                {subItem.name}
                              </span>
                            </div>
                          </div>
                        </a>
                      </Link>
                      <div>
                        <span className="icon-arrow">
                          <svg
                            width="6"
                            height="9"
                            viewBox="0 0 6 9"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            className="fill-current"
                          >
                            <rect
                              x="1.49805"
                              y="0.818359"
                              width="5.78538"
                              height="1.28564"
                              transform="rotate(45 1.49805 0.818359)"
                            />
                            <rect
                              x="5.58984"
                              y="4.90918"
                              width="5.78538"
                              height="1.28564"
                              transform="rotate(135 5.58984 4.90918)"
                            />
                          </svg>
                        </span>
                      </div>
                    </button>
                    {!collapsub && (
                      <div
                        className="category-dropdown sub-category-lvl-two absolute left-[270px] z-10 w-[270px]"
                        style={{ height: `${subCatHeight}px` }}
                      >
                        <ul className="">
                          {subItem.active_child_categories.length > 0 &&
                            subItem.active_child_categories.map(
                              (subsubItem) => (
                                <li
                                  key={subsubItem.id}
                                  className="category-item"
                                >
                                  <Link
                                    href={{
                                      pathname:
                                        "/shop/[category]/[sub_category]/[child_category]",
                                      query: {
                                        category: slug,
                                        sub_category: subItem.slug,
                                        child_category: subsubItem.slug,
                                      },
                                    }}
                                    passHref
                                  >
                                    <a rel="noopener noreferrer">
                                      <div className=" flex justify-between items-center px-5 h-10 transition-all duration-300 ease-in-out cursor-pointer">
                                        <div className="flex items-center space-x-6">
                                          <span className="text-sm font-400 capitalize ">
                                            {subsubItem.name}
                                          </span>
                                        </div>
                                      </div>
                                    </a>
                                  </Link>
                                </li>
                              )
                            )}
                        </ul>
                      </div>
                    )}
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
